import { Component, OnInit, Input, SimpleChanges, OnChanges, Output, EventEmitter } from '@angular/core';
import { ConsultingService, LoaderService } from './../../../../../shared/services';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import { DatePipe } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import { NgForm } from '@angular/forms';
import * as moment from 'moment';
@Component({
  selector: 'app-immunization',
  templateUrl: './immunization.component.html',
  styleUrls: ['./immunization.component.scss']
})
export class ImmunizationComponent implements OnInit, OnChanges {
  notifier: any;
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() selected_visit: any = [];
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  todaysDate = defaultDateTime();
  public loading = false;
  public data: any = [];
  public showMainContent = true;
  vaccine: any;
  public user_rights: any = {};
  public user_data: any = {};
  consultation_id: number;
  patient_immunization_id: number;
  patient_type: any;
  vaccine_list: any;
  user_id = 0;
  public immunization_ids:  any = {};
  public selectedButton;
  public visible = true;
  public vaccines_list: any;
  public now = new Date();
  immunization_id: any;
  vaccines: any;
  vacciness: any;
  options: any;
  optionss: any;
  vaccines_lists: any;
  public vaccine_optional = 0;
  constructor(public datepipe: DatePipe, private loaderService : LoaderService,private router: Router, public rest2: ConsultingService, notifierService: NotifierService) {
    this.notifier = notifierService;
    this.user_data = JSON.parse(localStorage.getItem('user'));
     }
     public immunization_data = {
      consultation_id : 0,
      patient_immunization_id : 0,
      immunization_id : 0,
      doctor_id : 0,
      user_id : 0,
      patient_id : 1,
      assessment_id : 2,
      immunization_ids : [],
      vaccine_optional : 1,
      client_date: new Date(),
      date: defaultDateTime(),
      timeZone: getTimeZone()
    };
  ngOnInit() {
    this. listImmunization();
    this. getPatientImmunization();
  }
  ngOnChanges(changes: SimpleChanges) {
    this. listImmunization();
    this. getPatientImmunization();
  }
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatDate (date) {
    return  formatDate(date);
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  public getEvent()
  {
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      this.save_notify = 1
      this.saveNotify.emit(this.save_notify)
      // if(this.immunization_data.immunization_ids.length != 0)
      // {
      //   this.save_notify = 1
      //   this.saveNotify.emit(this.save_notify)
      // }
      // else
      // {
      //   this.save_notify = 0
      //   this.saveNotify.emit(this.save_notify)
      // }
    }
    else {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
    }
  }
  public savePatientImmunization() {
    this.immunization_data.patient_id = this.patient_id;
    this.immunization_data.assessment_id = this.assessment_id;
    this.immunization_data.user_id = this.user_data.user_id;
    this.immunization_data.consultation_id = this.consultation_id;
    this.immunization_data.patient_immunization_id = this.patient_immunization_id;
    this.immunization_data.client_date = this.formatDateTime(new Date());
    this.immunization_data.date = defaultDateTime();
    this.immunization_data.timeZone = getTimeZone();
    this.loaderService.display(true);
    
    this.rest2.savePatientImmunization(this.immunization_data).subscribe((result) => {
      this.loaderService.display(false);
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if (result.status == 'Success') {
        this.notifier.notify( 'success', 'Immunization details saved successfully!' );
        this.patient_immunization_id = result.data_id;
        this.immunization_data.patient_immunization_id = result.data_id;
        this.getPatientImmunization();
      } else {
      
        this.notifier.notify( 'error', "Failed" );
      }
      }, (err) => {
      //  console.log(err);
      });
    }
  public listImmunization() {
    const postData = {
      patient_immunization_id : 3
    };
    this.loaderService.display(true);
    this.rest2.listImmunization(postData).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] == "Success") {
       
        this.immunization_data.vaccine_optional = result.data.VACCINE_OPTIONAL;
        this.vaccine_list = result['data'];
        // console.log("data="+JSON.stringify(result));
        this.vaccines = this.vaccine_list.filter(s => s.PATIENT_TYPE_ID == 22 && s.VACCINE_OPTIONAL == 0);
        this.vacciness = this.vaccine_list.filter(s => s.PATIENT_TYPE_ID == 21 && s.VACCINE_OPTIONAL == 0);
        this.options = this.vaccine_list.filter(s => s.PATIENT_TYPE_ID == 22 );
        this.optionss = this.vaccine_list.filter(s => s.PATIENT_TYPE_ID == 21 );
      if (result.data.VACCINE_OPTIONAL == 1) {
        this.vaccines_list = this.options;
        this.vaccines_lists = this.optionss;
      } else {
        this.vaccines_list = this.vaccines;
        this.vaccines_lists = this.vacciness;
      }
      } else {
        this.vaccine = [];
      }
      }, (err) => {
      //  console.log(err);
      });
  }
  public getPatientImmunization() {
  const postData = {
    patient_id : this.patient_id,
  };
  this.loaderService.display(true);
  this.rest2.getPatientImmunization(postData).subscribe((result) => {
    this.loaderService.display(false);
    if (result.status == 'Success') {
     
     // console.log(result.data);
      this.immunization_data.patient_id = this.patient_id;
      this.immunization_data.vaccine_optional = parseInt(result.data.VACCINE_OPTIONAL);
      if (result.data.VACCINE_OPTIONAL == 1) {
        this.vaccines_list = this.options;
        this.vaccines_lists = this.optionss;
      } else {
        this.vaccines_list = this.vaccines;
        this.vaccines_lists = this.vacciness;
      }
      const i = 0;
      const patient_vaccine = [];
      for (const val of result.data.IMMUNIZATION_DETAILS) {
        patient_vaccine.push(val.IMMUNIZATION_ID);
      }
      this.immunization_data.immunization_ids = patient_vaccine;
     // console.log(this.immunization_data.immunization_ids)

    } else {
     
    }
    }, (err) => {
    //  console.log(err);
    });
  }
  public giveVaccine(checked) {
    if (this.immunization_data.immunization_ids.includes(checked)) {
      const index: number = this.immunization_data.immunization_ids.indexOf(checked);
      if (index !== -1) {
        this.immunization_data.immunization_ids.splice(index, 1);
      }
    } else {
      this.immunization_data.immunization_ids.push(checked);
    }
  }
  public isexistVaccin(IMMUNIZATION_ID): boolean {
    if (this.immunization_data.immunization_ids.includes(IMMUNIZATION_ID)) {
      return true;
    } else {
      return false;
    }
  }
  public giveoption() {
    if (this.immunization_data.vaccine_optional == 0) {
      this.immunization_data.vaccine_optional = 1;
      this.vaccines_list = this.options;
      this.vaccines_lists = this.optionss;
    } else {
      this.immunization_data.vaccine_optional = 0;
      this.vaccines_list = this.vaccines;
      this.vaccines_lists = this.vacciness;
    }
  }
}
