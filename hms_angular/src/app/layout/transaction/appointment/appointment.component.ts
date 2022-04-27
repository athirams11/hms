import { Component, OnInit, ViewChild, ViewContainerRef } from '@angular/core';
import { Router } from '@angular/router';
import {DatePipe} from '@angular/common';
import {NgbModal, ModalDismissReasons, NgbModalRef, NgbModalOptions} from '@ng-bootstrap/ng-bootstrap';
import {NgbTabset} from "@ng-bootstrap/ng-bootstrap";
import { routerTransition } from '../../../router.animations';
import { AppointmentService } from '../../../shared'
import * as moment from 'moment';
import Mmoment from 'moment-timezone';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { formatTime, formatDateTime, formatDate,defaultDateTime } from '../../../shared/class/Utils';
import { interval, observable, Subscription } from 'rxjs';
@Component({
  selector: 'app-appointment',
  templateUrl: './appointment.component.html',
  styleUrls: ['./appointment.component.scss'],
  animations: [routerTransition()]
})
export class AppointmentComponent implements OnInit {
  @ViewChild('app_tabs') app_tabs;
  public subscription: Subscription;
  source = interval(5000);
  private notifier: NotifierService;
  @ViewChild('appointment_form') af: any;
  pat_list : any = [];
  time: any;
  private modalRef: NgbModalRef;
  public user_rights : any ={};
  public  appData =  {
    dateVal: this.formatDates(new Date()),
    sel_doc: "",
    sel_schedule:"",
    gender: 1,
    sel_slot: "",
    p_name: "",
    p_number: "",
    p_id: "",
    ph_no: "",
    email : "",
    sel_time: "",
    start_time : "",
    end_time : "",
    sel_end_time : "",
    age: 0,
    app_type:1,
    user_id: 1,
    app_id:0,
    time_slots : [],
    timeZone:""
  }
  public slotdata = {
    check : [],
    startDate : [],
    endDate : []
  }
  filter = "";
  filter_ap_list = "";
  sel_dr = "";
  sel_dr_date = new Date();
  dateVal = new Date();
  appointmentDate = new Date();
  public dr_list : any = [];
  public date_schedule_list : any = [];
  public dr_schedule_list_date : any = [];
  public dr_schedule_list_by_date: any= [];
  public slot_list : any = [];
  public appoinment_list : any = [];
  public appointmentStatus: any = [];
  public sel_doc : any = "";
  public sel_slot : any = "";
  public success_message="";
  public failed_message="";
  public f=0;
  todaysDate = this.formatDates(new Date())
  closeResult: string;
  doctor_id: any = '';
  avg_time: any;
 public slot_array : any = []
 public array: any = [];
 public alreadyBookedSlot: any = [];
 public total_time_slot = '';
 public current_seconds: any;
 public notavailableSlot: any = [];
  availableSlot: any;
  public getToday = moment(new Date()).format('YYYY-MM-DD')
  flag: number;
  slot_start_time: any;
  slot_end_time: any;
  slots: any[];
  select_time_slot: any[];
  
  constructor(private loaderService : LoaderService, private modalService: NgbModal,public rest:AppointmentService, public datepipe: DatePipe, private router: Router,notifierService: NotifierService) { 
    // this.appData.dateVal = defaultDateTime();
    this.dateVal = defaultDateTime();
    this.todaysDate = this.formatDates(new Date())
    this.filter = this.datepipe.transform(this.dateVal, 'dd/MM/yyyy');
    this.filter_ap_list = this.datepipe.transform(this.dateVal, 'dd/MM/yyyy');
    this.notifier = notifierService;
  }
  
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    // this.appData.dateVal = defaultDateTime();
    this.getDropdowns();
    this.getScheduleBydate();
    this.getAppointmentsByDate();
    this.getDoctersByDate();
    this.time = Mmoment.tz.guess();
    this.appData.timeZone=this.time;
    this.subscription = this.source.subscribe(x => this.getAppointmentsByDate(1));
  }
  
  ngOnDestroy() {
    // avoid memory leaks here by cleaning up after ourselves. If we  
    // don't then we will continue to run our initialiseInvites()   
    // method on every navigationEnd event.
    if (this.subscription) {  
       this.subscription.unsubscribe();
    }
  }
  public format_date(time)
  {
    return formatDate(time)
  }
  public getOnlyToday()
  {
    //console.log(moment(this.appData.dateVal).format('YYYY-MM-DD'))
      if(moment(this.appData.dateVal).format('YYYY-MM-DD') < this.getToday)
      {
        this.appData.dateVal = ""
        return  
      }
  }
  public getMaxDate() 
  {
    return moment(new Date()).format('YYYY-MM-DD')
  }
  open(content) {
   
    if(this.appData.ph_no != "" && this.appData.ph_no != null)
    {
      var sendJson = {
        ph_no : this.appData.ph_no
      }
      this.loaderService.display(true)
      this.rest.getPatientsByPhoneNo(sendJson).subscribe((result) => {
        this.loaderService.display(false)
        if(result.status == "Success")
        {
          this.pat_list = result.data;
          if(this.pat_list.length > 0)
          {
            this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title', centered: true }).result.then((result) => {
              this.closeResult = `Closed with: ${result}`;
            }, (reason) => {
              this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
            });
            
          }
        }
        else
        {
          this.pat_list = [];
          
        }
      }, (err) => {
        // console.log(err);
      });
      
    }
  }
  public slot(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true,
      ariaLabelledBy: 'modal-basic-title',
      size: 'sm'
    };
    this.modalRef = this.modalService.open(content,ngbModalOptions);
    this.modalRef.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatTimes (time)
  {
    var retVal = "";
    if(time != "")
    {
      retVal =  moment(time, "HH:mm a").format("hh:mm a");
    }
    return  retVal;
  }
  // public formatDate (date) {
  //   return  formatDate(date);
  // }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return  `with: ${reason}`;
    }
  }
  public selectPatient(patient)
  {
    this.appData.p_name = patient.name;
    this.appData.gender = patient.gender;
    this.appData.age = patient.age;
    this.appData.email = patient.email;
    this.appData.p_number = patient.OP_no
    this.appData.p_id = patient.reg_id
    this.appData.ph_no = patient.phone_no
    
  }
  public formatDate (date)
  {
    var retVal = "";
    if(date != "")
    {
      var Wday: string[] = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];  
      const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      var d  =new Date(date)
      var TodayDay = Wday[d.getDay()];  
      
      retVal =  this.datepipe.transform(d, 'dd/MM/yyyy')
      var a = retVal.split('/'); 
      var retVal = a[0]+'/'+monthNames[d.getMonth()]+'/'+a[2]
    }
    return  retVal;
  }
  public formatDates(date)
  {
    var retVal = "";
    if(date != "")
    {
      var d  =new Date(date)
      retVal =  this.datepipe.transform(d, 'yyyy-MM-dd')
    }
    return  retVal;
  }
  public drSelected(dr)
  {
    if(dr)
    {
      // console.log(this.dr_schedule_list_by_date.DOCTORS[dr])
      var sel_doc = this.dr_schedule_list_by_date.DOCTORS[dr];
      this.slot_list = sel_doc.slotList;
      this.appData.sel_slot = "";
      this.sel_slot = "";
      this.avg_time = sel_doc.avg_time
      this.appData.sel_schedule = sel_doc.schedule_id;
      this.appData.sel_doc = sel_doc.id;
      this.appData.sel_time = ''
      this.appData.time_slots = []
    }
  }
  public slotSelected(slot)
  {
   
    if(slot)
    {
      var sel_slot = this.slot_list[slot]
      this.appData.sel_slot = sel_slot.SCHEDULE_NO;
      // this.appData.sel_time = moment(sel_slot.START_AT, "HH:mm.ss").format("HH:mm");
      this.appData.start_time =sel_slot.START_AT
      this.appData.end_time =sel_slot.END_AT
      this.slot_start_time = sel_slot.START_AT
      this.slot_end_time = sel_slot.END_AT
      this.appData.sel_time = ''
      this.appData.sel_end_time = ''
      this.appData.time_slots = []
    }
      
  }
  selectSlot(time)
  {
    this.appData.sel_time = moment(time, ["HH:mm a"]).format("hh:mm a");
    // if(this.appData.sel_time)
    // {
    //   if(this.modalRef)
    //     this.modalRef.close();
    // }
   
  }
  selectTimeSlot(data,i)
  {
  //  console.log(this.appData.time_slots)
    if(!this.appData.time_slots[i])
    {
      this.appData.time_slots[i] = data.start_time
      
    }
    else
    {
      if(this.appData.time_slots[i] === "")
        this.appData.time_slots[i] = data.start_time;
      else
        this.appData.time_slots[i] = ""
    }
    // console.log(this.appData.time_slots)
    const array = []
    var arrays = []
    this.appData.time_slots.forEach((element,i) => {
    //  console.log(this.appData.time_slots[i])
      if(this.appData.time_slots[i])
      {
        var time = this.timeStringToFloat(moment(this.appData.time_slots[i], ["h:mm a"]).format("HH:mm:ss"))
        var tym = {
          time : time,
          time_slot : this.appData.time_slots[i],
          id : i
        }
        array.push(tym)
        
      }
    });
    arrays = array;
    
    var min = Math.min.apply(null,(arrays.map(function(obj) { return obj.time; })))
    const index = arrays.findIndex(slot=> slot.time === min);
    if(index > -1)
    {
      this.appData.sel_time = arrays[index].time_slot
      this.appData.start_time = arrays[index].time_slot
    }
    
    var max = Math.max.apply(null,(arrays.map(function(obj) { return obj.time; })))
    const indexs = arrays.findIndex(slot=> slot.time === max);
    if(indexs > -1)
    { 
      var slotStartTime : any
      slotStartTime = moment(arrays[indexs].time_slot, ["h:mm a"]).format("HH:mm:ss").split(':'); 
      var start_seconds = (+slotStartTime[0]) * 60 * 60 + (+slotStartTime[1]) * 60 + (+slotStartTime[2]); 
      var avg_time = this.avg_time * 60 ; 
      var startTime = start_seconds +  avg_time
      var starth = Math.floor(startTime / 3600);
      var startm = Math.floor(startTime % 3600 / 60);
      this.appData.end_time  = this.formatTimes(starth+":"+startm)
      this.appData.sel_end_time  = this.formatTimes(starth+":"+startm)
    }
    // console.log(max)
    // console.log(min)
    this.checkSlotsareNearBy(arrays)
  }
  public timeStringToFloat(time) {
    var hoursMinutes = time.split(/[.:]/);
    var hours = parseInt(hoursMinutes[0], 10);
    var minutes = hoursMinutes[1] ? parseInt(hoursMinutes[1], 10) : 0;
    return hours + minutes / 60;
  }
  public checkSlotsareNearBy(array)
  {
    // console.log("array")
    // console.log(array)
    var min = Math.min.apply(null,(array.map(function(obj) { return obj.time; })))
    const inde = array.findIndex(slot=> slot.time === min);
   
    var max = Math.max.apply(null,(array.map(function(obj) { return obj.time; })))
    const indexs = array.findIndex(slot=> slot.time === max);

    var time = moment.utc(moment(array[indexs].time_slot, "HH:mm:ss").diff(moment(array[inde].time_slot, "HH:mm:ss"))).format("HH:mm:ss")
    
     var a = time.split(':'); 
     var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
     var avg = this.avg_time * 60 ; 
     var slot_block = seconds/avg;
    //  console.log("slot_block"+slot_block)
    //  console.log("time"+time)
    //  console.log("this.avg_time"+this.avg_time)
      var slots =[]
     for(var i = 0;i <=slot_block;i++)
     {
        var slotStartTime : any
        slotStartTime =  moment(array[inde].time_slot, ["h:mm a"]).format("HH:mm:ss").split(':'); 
        var start_seconds = (+slotStartTime[0]) * 60 * 60 + (+slotStartTime[1]) * 60 + (+slotStartTime[2]); 
        var avg_time = this.avg_time * 60 ; 
        var startTime = start_seconds +  (i*avg_time)
        var starth = Math.floor(startTime / 3600);
        var startm = Math.floor(startTime % 3600 / 60);
       // console.log(starth+":"+startm)
        slots[i] =  this.formatTimes(starth+":"+startm)
     }
     this.flag = 0
    //   !slots.some(function(item) {
    //     if(this.appData.time_slots.indexOf(item) === -1)
    //     {
    //       this.flag = 1
    //     }
    // });
    var select_time_slot = []
    this.appData.time_slots.forEach((element,index) => {
      if(this.appData.time_slots[index] != "")
      {
        select_time_slot.push(element)
      }
    });
    this.select_time_slot = select_time_slot
    this.slots = slots
    this.slots.forEach(element => {
      if(this.select_time_slot.indexOf(element) === -1)
      {
        this.flag = 1
      }
      // console.log("select_time_slot.indexOf(element)")
      //   console.log(select_time_slot.indexOf(element))
    });
    
    // console.log("select_time_slot")
    // console.log(select_time_slot)
    // console.log("slots")
    // console.log(slots)
        
    //  for(var j = 0; j <slots.length; j++)
    //  {
    //     const index = this.appData.time_slots.findIndex(slot=> slot === slots[j]);
    //     console.log(index)
    //     console.log("this.appData.time_slots")
    //     console.log(this.appData.time_slots)
    //     console.log(slots[j])
    //     if(index == -1)
    //     {
    //       this.flag = 1
    //     }
    //  }
     if(this.flag == 1)
     {
      this.notifier.notify("info","Please choose near by time slots")
     }
   // console.log(slots)
   
  }
  public time_convert(num)
  { 
    var hours = Math.floor(num / 60);  
    var minutes = num % 60;
    return hours + ":" + minutes;         
  }

  getDay(date)
  {
    var TodayDay = "";
    if(date != "")
    {
      var Wday: string[] = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];  
      var d  =new Date(date)
      var TodayDay = Wday[d.getDay()];  
    }
    return TodayDay;
  }
  checkAvailableSlots()
  {
    const postData = {
      doctor_id : this.appData.sel_doc,
      schedule_id : this.appData.sel_schedule,
      slot : this.appData.sel_slot,
      slots : this.array,
      date : this.appData.dateVal,
    }
    this.loaderService.display(true)
    this.rest.checkAvailableSlots(postData).subscribe((result) => {
      this.loaderService.display(false)
      if(result["status"] == "Success")
      {
        this.alreadyBookedSlot = result["data"]
        var i =0
        for(let data of this.slot_array)
        {
          for(let book of this.alreadyBookedSlot)
          {
            if(this.appData.app_id != book.APPOINTMENT_ID)
            {
              if(this.formatTimes(data.start_time) == this.formatTimes(book.APPOINTMENT_TIME))
              {
                this.slot_array[i].booked = 1
                this.slot_array[i].patientName = book.PATIENT_NAME
                this.slot_array[i].phone_no = book.PATIENT_PHONE_NO
                this.getAllbookedslot(book.APPOINTMENT_TIME,book.APPOINTMENT_END_TIME,book)
              }
              if(this.formatTimes(data.end_time) == this.formatTimes(book.APPOINTMENT_END_TIME))
              {
                this.slot_array[i].booked = 1
                this.slot_array[i].patientName = book.PATIENT_NAME
                this.slot_array[i].phone_no = book.PATIENT_PHONE_NO
              }
            }
            else
            {
              this.getAllbookedslot(book.APPOINTMENT_TIME,book.APPOINTMENT_END_TIME,book)
            }
            
          }
          i=i+1
        }
      }
      else
      {
        this.alreadyBookedSlot = []
      }
    });
  }
  public count() {
    let counter = 0;
    for (let i = 0; i < this.slot_array.length; i++) {
      if(this.format_date(this.todaysDate) == this.format_date(this.appData.dateVal))
      {
        if(this.formatTimeslot(this.slot_array[i].start_time) > this.formatTimeslot(this.current_seconds)  && this.slot_array[i].booked === 0)
        {
          counter++;
        }
      }
      else
      {
        if(this.slot_array[i].booked === 0)
        {
          counter++;
        }
      }
    }
    return counter; 
  }
  public getAllbookedslot(start,end,val)
  {
    var data = []
    if(start != '' && end != "")
    {
      var time : any;
      time = moment.utc(moment(end, "HH:mm:ss").diff(moment(start, "HH:mm:ss"))).format("HH:mm:ss")
     // console.log(time)
      var a = time.split(':'); 
      var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
      var avg = this.avg_time * 60 ; 
      var slot_block = seconds/avg;
      var array = []
      for(let p = 0; p < slot_block; p++)
      {
        var times = {
          start_time : "",
          end_time : "",
          booked : 0,
          patientName : "",
          phone_no : ""
        }
       
        var slotStartTime : any
        slotStartTime = start.split(':'); 
        var start_seconds = (+slotStartTime[0]) * 60 * 60 + (+slotStartTime[1]) * 60 + (+slotStartTime[2]); 
        var avg_time = this.avg_time * 60 ; 
        var startTime = start_seconds +  (p*avg_time)
        var starth = Math.floor(startTime / 3600);
        var startm = Math.floor(startTime % 3600 / 60);
        times.start_time =  this.formatTimes(starth+":"+startm)
        array[p] = this.formatTimes(times.start_time)
        // slotlist[i] =  moment(times.start_time, "HH:mm.ss").format("HH:mm:ss");
        var end : any
        end = startTime +  avg_time
        var endh = Math.floor(end / 3600);
        var endm = Math.floor(end % 3600 / 60);
        times.end_time =  this.formatTimes(endh+":"+endm)
        data = array
        var timeslot = []
        // this.appData.time_slots = array
        this.slot_array.forEach((element,index)=> {
          data.forEach((item)=> {
            //  console.log("item"+item)
            //  console.log("itemS"+element.start_time)
            if(this.formatTimes(item) === this.formatTimes(element.start_time))
            {
             
                timeslot[index] = this.formatTimes(item)
               // this.appData.time_slots[index] = ''
            }
          });
        });
        //  console.log(this.appData.time_slots)
        //  console.log(this.slot_array)
      }
      var i=0;
      var j=0
      
    
      if(this.appData.app_id != val.APPOINTMENT_ID)
      {
        for(let tym of timeslot)
        {
          if(this.formatTimes(tym) === this.formatTimes(this.slot_array[i].start_time))
          {
            if(this.slot_array[i])
              this.slot_array[i].booked = 1
          }
          i=i+1
        }
      }
      if(this.appData.app_id == val.APPOINTMENT_ID)
      {
        for(let tym of timeslot)
        {
          if(this.formatTimes(tym) === this.formatTimes(this.slot_array[i].start_time))
          {
            if(this.slot_array[i])
              this.slot_array[i].booked = 0
          }
          i=i+1
        }
        this.appData.time_slots = []
        this.appData.time_slots = timeslot
      }
      //  console.log(this.appData.time_slots) 
    }
  }
  public slotAddAverageTime(time)
  {
    var slotStartTime : any
    slotStartTime = time.split(':'); 
    var start_seconds = (+slotStartTime[0]) * 60 * 60 + (+slotStartTime[1]) * 60 + (+slotStartTime[2]); 
    var avg_time = this.avg_time * 60 ; 
    var startTime = start_seconds +  avg_time
    var starth = Math.floor(startTime / 3600);
    var startm = Math.floor(startTime % 3600 / 60);
    return this.formatTimes(starth+":"+startm)
      // moment(times, ["h:mm a"]).format("HH:mm:ss");
  }
  close()
  {
    var rel = 0
    this.appData.time_slots.forEach((item,i) => {
      // console.log(this.appData.time_slots[i])
      if(this.appData.time_slots[i] != '')
      {
        rel = 1
      }
    });
    if(this.flag != 1)
    {
      this.modalRef.close()

    }
    else
    {
      this.notifier.notify("info","Please choose near by time slots")
    }

    if(rel == 0)
    {
      this.appData.sel_time = ''
      this.appData.sel_end_time = ''
    }
      // if(this.flag == 1)
      // {
      //   this.notifier.notify("info","Please choose near by time slots")
      // }
      // if(rel == 1)
      // {
      //   this.notifier.notify("info","Please choose a time slot")
      // }
  }
  getslot(content:any = "")
  {
    this.slot_array = []
    // this.appData.time_slots = []
    var d = new Date(); // for now
    // d.getHours(); // => 9
    // d.getMinutes(); // =>  30
    // d.getSeconds(); // => 51
    var current_seconds = (+d.getHours()) * 60 * 60 + (d.getMinutes()) * 60 + (+d.getSeconds()); 
    this.current_seconds = this.formatTimes(d.getHours()+":"+d.getMinutes()+":"+d.getSeconds())
    this.total_time_slot = ''
    if(this.appData.sel_slot != '' && this.appData.dateVal != '')
    {
      var time : any;
      time = moment.utc(moment(this.slot_end_time, "HH:mm:ss").diff(moment(this.slot_start_time, "HH:mm:ss"))).format("HH:mm:ss")
      // console.log("this.slot_start_time"+this.slot_start_time)
      // console.log("this.appData.end_time"+this.slot_end_time)
      // console.log(time)
      var a = time.split(':'); 
      var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
      var avg = this.avg_time * 60 ; 
      var slot_block = seconds/avg;
      this.slot_array = []
      var slotlist = []
      for(let i = 0; i < slot_block; i++)
      {
        var times = {
          start_time : "",
          end_time : "",
          booked : 0,
          patientName : "",
          phone_no : ""
        }
       
        var slotStartTime : any
        slotStartTime = this.slot_start_time.split(':'); 
        var start_seconds = (+slotStartTime[0]) * 60 * 60 + (+slotStartTime[1]) * 60 + (+slotStartTime[2]); 
        var avg_time = this.avg_time * 60 ; 
        var startTime = start_seconds +  (i*avg_time)
        var starth = Math.floor(startTime / 3600);
        var startm = Math.floor(startTime % 3600 / 60);
        times.start_time =  this.formatTimes(starth+":"+startm)
        slotlist[i] = moment(times.start_time, ["h:mm a"]).format("HH:mm:ss");
        var end = startTime +  avg_time
        var endh = Math.floor(end / 3600);
        var endm = Math.floor(end % 3600 / 60);
        times.end_time =  this.formatTimes(endh+":"+endm)
        this.slot_array.push(times)
        this.array = slotlist
      }
      if(this.slot_array.length > 0)
      {
        this.total_time_slot = ''
        this.checkAvailableSlots()
        if(content != '')
          this.slot(content)
      }
    }
  }
  
  public formatTimeslot(time)
  {
    return moment(time, ["h:mm a"]).format("HH:m:ss");
  }
  public filterByDate()
  {
    this.filter = this.datepipe.transform(this.dateVal, 'dd/MM/yyyy');
    // this.filter_ap_list = this.datepipe.transform(this.dateVal, 'dd/MM/yyyy');
    this.getScheduleBydate();
  }
  public filterByDateforlist()
  {
    // this.filter = this.datepipe.transform(this.appointmentDate, 'dd/MM/yyyy');
    this.filter_ap_list = this.datepipe.transform(this.appointmentDate, 'dd/MM/yyyy');
    this.getAppointmentsByDate();
  }
  
  public filterByDr()
  {
    if (this.sel_dr != "")
    {
      this.filter = "Dr. "+this.dr_list.find(x=>x.DOCTORS_SCHEDULE_ID == this.sel_dr).DOCTORS_NAME;
      this.getDrSchduleForWeek();
      // this.doctor_id = this.sel_dr
      // this.getAppointmentsByDate();
    }
    
  }
  public filterByDrNext()
  {
    if (this.sel_dr != "")
    {
      this.sel_dr_date = new Date(this.dr_schedule_list_date.to);
      this.sel_dr_date.setDate( this.sel_dr_date.getDate() + 1 );
      this.filter = "Dr. "+this.dr_list.find(x=>x.DOCTORS_SCHEDULE_ID == this.sel_dr).DOCTORS_NAME;
      this.getDrSchduleForWeek();
      
    }
    
  }
  public filterByDrPrev()
  {
    if (this.sel_dr != "")
    {
      this.sel_dr_date = new Date(this.dr_schedule_list_date.from);
      this.sel_dr_date.setDate( this.sel_dr_date.getDate() - 1 );
      this.filter = "Dr. "+this.dr_list.find(x=>x.DOCTORS_SCHEDULE_ID == this.sel_dr).DOCTORS_NAME;
      this.getDrSchduleForWeek();
    }
    
  }
  public filterByDrforList()
  {
    if(!this.appointmentDate)
    {
      this.notifier.notify("warning","  Please select a date")
    }
    else
    {
      this.getAppointmentsByDate();
    }
  }
  public addAppointment() {
    if(this.appData.dateVal == null)
    {
      this.notifier.notify( 'error', 'Please select correct gender !' );  
       return false;
    }
    else if(this.appData.sel_doc == '' || this.appData.sel_doc == null)
    {
      this.notifier.notify( 'error', 'Please select doctor !' );  
       return false;
    }
    else if(this.appData.sel_slot == '' || this.appData.sel_slot == null)
    {
      this.notifier.notify( 'error', 'Please select slot !' );  
       return false;
    }
    else if(this.appData.sel_time == '' || this.appData.sel_time == null)
    {
      this.notifier.notify( 'error', 'Please select time !' );  
      return false;
    }
    else if(this.appData.p_name == '' || this.appData.p_name == null)
    {
      this.notifier.notify( 'error', 'Please enter patient name !' );  
       return false;
    }
    else if(this.appData.ph_no == '' || this.appData.ph_no == null)
    {
      this.notifier.notify( 'error', 'Please enter phone number !' );  
       return false;
    }
    // else if(this.appData.age == null || this.appData.age == 0 )
    // {
    //   this.notifier.notify( 'error', 'Please enter age !' );  
    //   return false;
    // }
    else{
      this.appData.sel_time = moment(this.appData.sel_time, ["h:mm a"]).format("HH:mm:ss");
      this.appData.end_time = moment(this.appData.end_time, ["h:mm a"]).format("HH:mm:ss");
      this.appData.sel_end_time  = moment(this.appData.sel_end_time, ["h:mm a"]).format("HH:mm:ss");
      this.appData.dateVal = this.formatDates(this.appData.dateVal)
      this.loaderService.display(true)
      this.rest.addNewAppointMent(this.appData).subscribe((result) => {
        this.loaderService.display(false)
        if(result["status"] == "Success")
        {
          this.notifier.notify( 'success','Appointment details saved successfully..!' );
          if(this.app_tabs)
          {
            this.app_tabs.select('app_list_tab')
          }
          this.refreshForm()
              this.appData =  {
                dateVal: this.formatDates(new Date()),
                sel_doc: "",
                sel_schedule:"",
                gender: 1,
                sel_slot: "",
                p_name: "",
                p_number: "",
                p_id: "",
                ph_no: "",
                email : "",
                sel_time: "",
                start_time : "",
                end_time : "",
                sel_end_time : "",
                age: 0,
                app_type:1,
                user_id: 1,
                app_id:0,
                time_slots : [],
                timeZone:this.time
              }
              this.filter = "";
              this.sel_dr = "";
              this.dateVal = defaultDateTime(),
              this.getDropdowns();
              this.getScheduleBydate();
              this.getAppointmentsByDate();
        }
        else
        {
          this.notifier.notify( 'error','Failed to save !', result.response );
        }
      }, (err) => {
        // console.log(err);
      });
    }
  }
  public editAppointment(appointment)
  {
    //this.flag = 0
    this.total_time_slot = ''
    this.appData =  {
      dateVal: appointment.APPOINTMENT_DATE,
      sel_doc: appointment.DOCTOR_ID,
      sel_schedule:appointment.SCHEDULE_ID,
      gender: appointment.APP_GENDER,
      sel_slot: appointment.SLOT_NO,
      p_name: appointment.PATIENT_NAME,
      p_number: appointment.PATIENT_NO,
      p_id: appointment.PATIENT_ID,
      ph_no: appointment.PATIENT_PHONE_NO,
      email : appointment.PATIENT_EMAIL,
      sel_time: moment(appointment.APPOINTMENT_TIME, "HH:mm.ss").format("HH:mm"),
      age: appointment.APP_AGE,
      app_type:1,
      user_id: 1,
      start_time : appointment.APPOINTMENT_TIME,
      end_time : appointment.APPOINTMENT_END_TIME,
      sel_end_time : appointment.APPOINTMENT_END_TIME,
      app_id:appointment.APPOINTMENT_ID,
      time_slots : [],
      timeZone:this.time,
    
    };

    this.avg_time = appointment.AVG_CONS_TIME
    // this.getAllbookedslot(appointment.APPOINTMENT_TIME,appointment.APPOINTMENT_END_TIME,appointment)
    this.sel_doc = this.dr_schedule_list_by_date.DOCTORS.findIndex(d => d.schedule_id == appointment.SCHEDULE_ID);
    var sel_doc = this.date_schedule_list.DOCTORS.filter(d => d.schedule_id == appointment.SCHEDULE_ID)
    this.slot_list = sel_doc[0].slotList;
    //this.avg_time = sel_doc[0].avg_time  
    // this.sel_slot = this.slot_list.findIndex(d => d.SCHEDULE_NO == appointment.SLOT_NO);
    
    
    for (let index in this.slot_list) {
      if(this.slot_list[index].SCHEDULE_NO == appointment.SLOT_NO)
      {
        this.sel_slot = index;
        this.slotSelected(this.sel_slot)
      }
    }
    this.appData.sel_time = moment(appointment.APPOINTMENT_TIME, ["HH:mm a"]).format("hh:mm a");
    this.appData.sel_end_time = moment(appointment.APPOINTMENT_END_TIME, ["HH:mm a"]).format("hh:mm a");
    // this.getslot()
    // this.sel_doc = appointment.DOCTOR_ID;
    if(this.app_tabs)
    {
      this.app_tabs.select('app_new_tab')
    }
  }
  public refreshForm()
  {
    this.doctor_id = ""
    this.sel_dr = ""
    this.appData =  {
      dateVal: this.formatDates(new Date()),
      sel_doc: "",
      sel_schedule:"",
      gender: 1,
      sel_slot: "",
      p_name: "",
      p_number: "",
      p_id: "",
      ph_no: "",
      email : "",
      sel_time: "",
      start_time : "",
      end_time : "",
      sel_end_time : "",
      age: 0,
      app_type:1,
      user_id: 1,
      time_slots : [],
      app_id:0,
      timeZone:this.time
    };
    
    this.slot_list = [];
    this.sel_doc = "";
    this.sel_slot = "";
  }
  public getAppointmentsByDate(f=0)
  {
    
    var sendJson = {
      dateVal : this.formatDateTime(this.appointmentDate),
      timeZone: Mmoment.tz.guess(),
      doctor_id : this.doctor_id
    }
    if (f=0) {
      this.loaderService.display(true)
    }
    this.rest.getAppointmentsByDate(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if(result.status == "Success")
      {
        this.appoinment_list = result.data;
        var i=0
        for(let appointment of this.appoinment_list)
        {
          if(appointment.APPOINTMENT_STATUS == '2')
          appointment["ACTION"] = "Cancelled"
          if(appointment.PATIENT_VISIT_LIST_ID != null && appointment.NURSING_ASSESSMENT_ID == null)
          appointment["ACTION"] = "Arrived"
          if(appointment.NURSING_ASSESSMENT_ID != null && appointment.STAT != 1)
          appointment["ACTION"] = "Nursing Assessment Started"
          if(appointment.NURSING_ASSESSMENT_ID != null && appointment.STAT == 1 && appointment.BILL_STATUS == null)
          appointment["ACTION"] = "Nursing Assessment Completed"
          if(appointment.BILL_STATUS == 1)
          appointment["ACTION"] = "Billing Completed"
          this.appointmentStatus[i] = appointment.APPOINTMENT_STATUS
          i=i+1
        }
      }
      else
      {
        this.appoinment_list = [];
      }
    }, (err) => {
      // console.log(err);
    });
  }
  public getAppointmentsByDoctor(f=0)
  {
    var sendJson = {
      dateVal : this.formatDateTime(this.dateVal),
      timeZone: Mmoment.tz.guess(),
      doctor_id : this.sel_dr
    }
    if (f=0) {
      this.loaderService.display(true)
    }
    this.rest.getAppointmentsByDoctor(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if(result.status == "Success")
      {
        this.appoinment_list = result.data;
        var i =0
        for(let appointment of this.appoinment_list)
        {
          if(appointment.APPOINTMENT_STATUS == '2')
          appointment["ACTION"] = "Cancelled"
          if(appointment.PATIENT_VISIT_LIST_ID != null && appointment.NURSING_ASSESSMENT_ID == null)
          appointment["ACTION"] = "Arrived"
          if(appointment.NURSING_ASSESSMENT_ID != null && appointment.STAT != 1)
          appointment["ACTION"] = "Nursing Assessment Started"
          if(appointment.NURSING_ASSESSMENT_ID != null && appointment.STAT == 1 && appointment.BILL_STATUS == null)
          appointment["ACTION"] = "Nursing Assessment Completed"
          if(appointment.BILL_STATUS == 1)
          appointment["ACTION"] = "Billing Completed"
          this.appointmentStatus[i] = appointment.APPOINTMENT_STATUS
          i=i+1
        }
        
      }
      else
      {
        this.appoinment_list = [];
      }
    }, (err) => {
      // console.log(err);
    });
  }
  public getScheduleBydate()
  {
    var sendJson = {
      dateVal : this.formatDateTime(this.dateVal),
      timeZone: Mmoment.tz.guess()
    }
    this.loaderService.display(true)
    this.rest.getDrSchduleByDate(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if(result.status == "Success")
      {
        this.date_schedule_list = result.data;
        //console.log("date_schedule_list"+this.date_schedule_list)
        this.dr_schedule_list_date = [];
      }
      else
      {
        this.date_schedule_list = [];
        this.dr_schedule_list_date = [];
      }
    }, (err) => {
      // console.log(err);
    });
  }
  public getDrSchduleForWeek()
  {
    var sendJson = {
      dateVal : this.sel_dr_date,
      sel_dr: this.sel_dr,
      timeZone: Mmoment.tz.guess()
    }
    this.loaderService.display(true)
    this.rest.getDrSchduleForWeek(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if(result.status == "Success")
      {
       
        this.dr_schedule_list_date = result.data;
        this.date_schedule_list = [];
      }
      else
      {
        this.dr_schedule_list_date = [];
        this.date_schedule_list = [];
      }
    }, (err) => {
      // console.log(err);
    });
  }
  public getDoctersByDate()
  {
    var sendJson = {
      dateVal :  this.formatDateTime (this.appData.dateVal),
      timeZone: Mmoment.tz.guess()
    }
    this.loaderService.display(true)
    this.rest.getDoctersByDate(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if(result.status == "Success")
      {
       
        this.dr_schedule_list_by_date = result.data;
        this.doctor_id = ""
        this.sel_dr = ""
        this.appData.sel_doc = ""
        this.appData.sel_schedule = ""
        this.appData.sel_slot = ""
        this.appData.sel_doc = ""
        this.appData.sel_time = ""
        this.appData.sel_time = ""
        this.appData.start_time = ""
        this.appData.end_time = ""
        this.appData.sel_end_time = ""
        this.appData.time_slots = []
        this.slot_list = [];
        this.sel_doc = "";
        this.sel_slot = "";
      }
      else
      {
        this.dr_schedule_list_by_date = [];
      }
    }, (err) => {
      // console.log(err);
    });
  }
  public getDropdowns() {
    this.dr_list = [];
    this.loaderService.display(true)
    this.rest.getDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false)
      if(data['status'] == 'Success')
      {
        if(data['doctors_list']["status"] == 'Success')
        {
          this.dr_list = data['doctors_list']['data'];
        }
      }
      
    });
  }
  public getPatientDetails()
  {
    
    if(this.appData.p_number.length == 10)
    {
      var sendJson = {
        op_number : this.appData.p_number
      }
      this.loaderService.display(true)
      this.rest.getPatientDetails(sendJson).subscribe((result) => {
        this.loaderService.display(false)
        if(result.status == "Success")
        {
          var patient_details = result.data;
          this.appData.gender = patient_details.patient_data.GENDER;
          this.appData.ph_no = patient_details.patient_data.MOBILE_NO;
          this.appData.email = patient_details.patient_data.EMAIL_ID;
          this.appData.age = patient_details.patient_data.AGE;
          this.appData.p_id = patient_details.patient_data.OP_REGISTRATION_ID;
          this.appData.p_name = patient_details.patient_data.FIRST_NAME + ' ' + patient_details.patient_data.MIDDLE_NAME + ' ' + patient_details.patient_data.LAST_NAME;
          
        }
        else
        {
          this.appData.gender = 0;
          this.appData.ph_no = "";
          this.appData.email = "";
          this.appData.age = 0;
          this.appData.p_name = "";
        }
      }, (err) => {
        // console.log(err);
      });
    }
    
  }
}
