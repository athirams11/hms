import { Component, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../../../shared/class/Utils';
import { AppointmentService, LoaderService } from 'src/app/shared';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import Mmoment from 'moment-timezone';
import * as moment from 'moment';

@Component({
  selector: 'app-dr-appointment',
  templateUrl: './dr-appointment.component.html',
  styleUrls: ['./dr-appointment.component.scss']
})
export class DrAppointmentComponent implements OnInit {
  appointmentDate = new Date();
  filter_ap_list = "";
  dateVal: any;
  todaysDate = this.formatDates(new Date())
  filter = "";
  sel_dr = "";
  sel_dr_date = new Date();
  notifier: NotifierService;
  doctor_id: any = '';
  department_id: any = '';
  dr_list: any = [];
  user_rights: any;
  date_schedule_list: any;
  dr_schedule_list_date: any[];
  slot_array: any[];
  current_seconds: any;
  total_time_slot: string;
  array: any[];
  alreadyBookedSlot: any;
  public getToday = moment(new Date()).format('YYYY-MM-DD')
  specialized_in: any;

  constructor(private loaderService: LoaderService,
    private modalService: NgbModal,
    public rest: AppointmentService,
    public datepipe: DatePipe,
    private router: Router,
    notifierService: NotifierService) {
    this.dateVal = defaultDateTime();
    this.todaysDate = this.formatDates(new Date())
    // this.filter = this.datepipe.transform(this.dateVal, 'dd/MM/yyyy');
    this.filter_ap_list = this.datepipe.transform(this.dateVal, 'dd/MM/yyyy');
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    // this.appData.dateVal = defaultDateTime();
    this.getDropdowns();
    this.getScheduleBydate()
  }
  public formatTimeslot(time) {
    return moment(time, ["h:mm a"]).format("HH:m:ss");
  }
  public getDropdowns() {
    this.dr_list = [];
    this.loaderService.display(true)
    this.rest.getDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false)
      if (data['status'] == 'Success') {
        if (data['doctors_list']["status"] == 'Success') {
          this.dr_list = data['doctors_list']['data'];
        }
        if (data['specialized_in']['status'] === 'Success') {
          this.specialized_in = data['specialized_in']['data'];
        }
      }

    });
  }
  public formatDates(date) {
    var retVal = "";
    if (date != "") {
      var d = new Date(date)
      retVal = this.datepipe.transform(d, 'yyyy-MM-dd')
    }
    return retVal;
  }

  public formatDateTime(data) {
    return formatDateTime(data);
  }
  public getOnlyToday() {
    //console.log(moment(this.appData.dateVal).format('YYYY-MM-DD'))
    if (moment(this.dateVal).format('YYYY-MM-DD') < this.getToday) {
      this.dateVal = ""
      return
    }
  }
  public getMaxDate() {
    return moment(new Date()).format('YYYY-MM-DD')
  }

  public getScheduleBydate() {
    var sendJson = {
      dateVal: this.formatDateTime(this.dateVal),
      timeZone: Mmoment.tz.guess(),
      department_id : this.department_id,
      doctor_id : this.doctor_id,
    }
    this.loaderService.display(true)
    this.rest.getDrSchduleByDate(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if (result.status == "Success") {
        this.filter = this.format_date(this.dateVal)
        this.date_schedule_list = result.data.DOCTORS;
        this.date_schedule_list.forEach((element, i) => {
          element.slotList.forEach((val, j) => {
            console.log(val)
            this.getslot(element, val, j, i)
          });
        });
        console.log("date_schedule_list")
        console.log(this.date_schedule_list)

        this.dr_schedule_list_date = [];
      }
      else {
        this.date_schedule_list = [];
        this.dr_schedule_list_date = [];
      }
    }, (err) => {
      // console.log(err);
    });
  }
  public formatTimes(time) {
    var retVal = "";
    if (time != "") {
      retVal = moment(time, "HH:mm a").format("hh:mm a");
    }
    return retVal;
  }
  getslot(doctor, data, slotin, din) {
    console.log(doctor)
    this.slot_array = []
    var d = new Date(); // for now
    var current_seconds = (+d.getHours()) * 60 * 60 + (d.getMinutes()) * 60 + (+d.getSeconds());
    this.current_seconds = this.formatTimes(d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds())
    this.total_time_slot = ''
    // if(this.appData.sel_slot != '' && this.appData.dateVal != '')
    // {

    var time: any;
    time = moment.utc(moment(data.END_AT, "HH:mm:ss").diff(moment(data.START_AT, "HH:mm:ss"))).format("HH:mm:ss")

    var a = time.split(':');
    var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
    var avg = doctor.avg_time * 60;
    var slot_block = seconds / avg;
    this.slot_array = []
    var slotlist = []
    for (let i = 0; i < slot_block; i++) {
      var times = {
        start_time: "",
        end_time: "",
        booked: 0,
        patientName: "",
        phone_no: ""
      }

      var slotStartTime: any
      slotStartTime = data.START_AT.split(':');
      var start_seconds = (+slotStartTime[0]) * 60 * 60 + (+slotStartTime[1]) * 60 + (+slotStartTime[2]);
      var avg_time = doctor.avg_time * 60;
      var startTime = start_seconds + (i * avg_time)
      var starth = Math.floor(startTime / 3600);
      var startm = Math.floor(startTime % 3600 / 60);
      times.start_time = this.formatTimes(starth + ":" + startm)
      slotlist[i] = moment(times.start_time, ["h:mm a"]).format("HH:mm:ss");
      var end = startTime + avg_time
      var endh = Math.floor(end / 3600);
      var endm = Math.floor(end % 3600 / 60);
      times.end_time = this.formatTimes(endh + ":" + endm)
      this.slot_array.push(times)
      this.array = slotlist

    }
    if (this.slot_array.length > 0) {
      this.total_time_slot = ''
      // this.checkAvailableSlots(doctor, data, slotin, din)
      this.getAllbookedslot(data.START_AT, data.END_AT, data, doctor, slotin, din)
    }
    // }
  }
  checkAvailableSlots(doctor, data, sin, din) {
    const postData = {
      doctor_id: doctor.id,
      schedule_id: doctor.schedule_id,
      slot: data.SCHEDULE_NO,
      slots: this.array,
      date: this.dateVal,
    }
    this.loaderService.display(true)
    this.rest.checkAvailableSlots(postData).subscribe((result) => {
      this.loaderService.display(false)
      if (result["status"] == "Success") {
        this.alreadyBookedSlot = result["data"]
        var i = 0
        if (this.alreadyBookedSlot.length > 0) {
          for (let data of this.slot_array) {
            for (let book of this.alreadyBookedSlot) {
              this.getAllbookedslot(book.APPOINTMENT_TIME, book.APPOINTMENT_END_TIME, book, doctor, sin, din)
            }
            i = i + 1
          }
        }
        else {
          this.getAllbookedslot(data.START_AT, data.END_AT, data, doctor, sin, din)

        }

      }
      else {
        this.alreadyBookedSlot = []
      }
    });
  }
  public getAllbookedslot(start, end, val, doctor, sin, din) {
    var data = []
    if (start != '' && end != "") {
      var time: any;
      time = moment.utc(moment(end, "HH:mm:ss").diff(moment(start, "HH:mm:ss"))).format("HH:mm:ss")
      // console.log(time)
      var a = time.split(':');
      var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
      var avg = doctor.avg_time * 60;
      var slot_block = seconds / avg;
      var array = []
      for (let p = 0; p < slot_block; p++) {
        var times = {
          start_time: "",
          end_time: "",
          booked: 0,
          patientName: "",
          phone_no: ""
        }

        var slotStartTime: any
        slotStartTime = start.split(':');
        var start_seconds = (+slotStartTime[0]) * 60 * 60 + (+slotStartTime[1]) * 60 + (+slotStartTime[2]);
        var avg_time = doctor.avg_time * 60;
        var startTime = start_seconds + (p * avg_time)
        var starth = Math.floor(startTime / 3600);
        var startm = Math.floor(startTime % 3600 / 60);
        times.start_time = this.formatTimes(starth + ":" + startm)
        array[p] = this.formatTimes(times.start_time)
        // slotlist[i] =  moment(times.start_time, "HH:mm.ss").format("HH:mm:ss");
        var end: any
        end = startTime + avg_time
        var endh = Math.floor(end / 3600);
        var endm = Math.floor(end % 3600 / 60);
        times.end_time = this.formatTimes(endh + ":" + endm)
        data = array
        var timeslot = []
        // this.appData.time_slots = array
        this.slot_array.forEach((element, index) => {
          data.forEach((item) => {
            //  console.log("item"+item)
            //  console.log("itemS"+element.start_time)
            if (this.formatTimes(item) === this.formatTimes(element.start_time)) {

              timeslot[index] = this.formatTimes(item)
              // this.appData.time_slots[index] = ''
            }
          });
        });
        //  console.log(this.appData.time_slots)
        //  console.log(this.slot_array)
        this.date_schedule_list[din].slotList[sin].SLOTS = this.slot_array
      }

    }
    //  console.log(this.appData.time_slots) 
  }
  public format_date(time) {
    return formatDate(time)
  }
}
