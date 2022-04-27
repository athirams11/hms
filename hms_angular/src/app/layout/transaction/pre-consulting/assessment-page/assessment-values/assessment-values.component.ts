import { Component, OnInit, Input,EventEmitter, Output, OnChanges, SimpleChanges } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { NursingAssesmentService } from './../../../../../shared/services';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../../../shared/class/Utils';
import { AppSettings } from './../../../../../app.settings';
import { NotifierService } from 'angular-notifier';
@Component({
  selector: 'app-assessment-values',
  templateUrl: './assessment-values.component.html',
  styleUrls: ['./assessment-values.component.scss']
})
export class AssessmentValuesComponent implements OnInit, OnChanges {

  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() selected_visit: any = [];
  @Output() onEvent = new EventEmitter();
  @Output() onEvents = new EventEmitter();
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  todaysDate = new Date();
  private notifier: NotifierService;
  public user_rights: any ={};
  public loading = false;
  public user_data : any ={};
  public assessment_notes_id  =0;
  public notesData = {
    user_id : 0,
    assessment_id : 0,
    assessment_notes_id : 0,
    next_of_kin : '',
    next_of_kin_mobile : '',
    chief_complaints : '',
    nursing_notes : '',
    past_history : '',
    family_history : '',
    mode_arrival : 'None',
    mode_arrival_other_value : '',
    accompanied_by : 'None',
    accompanied_other_value : '',
    patient_waiting_time_informed : '',
    expected_waiting_time : '',
    client_date : new Date(),
    date : new Date(),
    timeZone: Moment.tz.guess(),
  };
  current_date :any;
  public accompanied_by_list: any = [];
  public mode_of_arrivel_list: any = [];
  public copy_assessment_data: any = {};
  vital_values: any;
  blood_sugar: any;
  constructor(public datepipe: DatePipe, private router: Router, public rest2: NursingAssesmentService, notifierService: NotifierService) {
    this.notifier = notifierService;
    
   }

  ngOnInit() {
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.notesData.date = defaultDateTime();
    this.notesData.user_id = this.user_data.user_id;
    //this.formatDateTime(this.notesData.client_date);
    this.todaysDate = defaultDateTime();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getNotesParameters();
    this.editNotesAssesmentValues();
    this.getBloodSugarReport();
    this.getAssesmentParameterValues();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.notesData.date = defaultDateTime();
    this.notesData.user_id = this.user_data.user_id;
    //this.formatDateTime(this.notesData.client_date);
    this.todaysDate = defaultDateTime();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getNotesParameters();
    this.editNotesAssesmentValues();
    this.getBloodSugarReport();
    this.getAssesmentParameterValues();
  }
  public changeArrivalMode(value) {
    this.notesData.mode_arrival = value;
  }
  public changeAccompaniedBy(value) {
    this.notesData.accompanied_by = value;
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
  public assessment(val)
  {
    if(val == 1)
    {
      this.copy_assessment_data = {
        next_of_kin : this.notesData.next_of_kin,
        next_of_kin_mobile : this.notesData.next_of_kin_mobile,
        chief_complaints : this.notesData.chief_complaints,
        nursing_notes : this.notesData.nursing_notes,
        past_history : this.notesData.past_history,
        family_history : this.notesData.family_history,
        mode_arrival : this.notesData.mode_arrival,
        mode_arrival_other_value : this.notesData.mode_arrival_other_value,
        accompanied_by : this.notesData.accompanied_by,
        accompanied_other_value : this.notesData.accompanied_other_value,
        patient_waiting_time_informed : this.notesData.patient_waiting_time_informed,
        expected_waiting_time : this.notesData.expected_waiting_time,
      }
      if(this.notesData)
      {
        this.notifier.notify("info","Assessment data copied")
      }
      
    }
    else
    {
      this.notesData = this.copy_assessment_data
      if(this.notesData == this.copy_assessment_data)
      {
        this.notifier.notify("info","Assessment data added")
      }
    }
    
  }
  public isNotEmptyObject(obj) {
   // console.log(Object.keys(obj).length)
    return Object.keys(obj).length;
  }
  public saveNursingNotes() {
    if (this.notesData.nursing_notes === '' || this.notesData.nursing_notes == null) {
      this.notifier.notify( 'error', 'Please enter the Nursing notes !' );
      return false;
    }
    //console.log('user' + this.user_data);
    this.notesData.user_id = this.user_data.user_id;

    this.notesData.assessment_id = this.assessment_id;

    this.notesData.assessment_notes_id = this.assessment_notes_id;
    this.notesData.date = defaultDateTime();
    this.notesData.timeZone = Moment.tz.guess();
    //this.loading = true;
    this.rest2.saveAssesmentNotes(this.notesData).subscribe((result) => {
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if (result.status === 'Success') {
       // this.loading = false;
        this.notifier.notify( 'success',"Assessment details saved successfully..!" );
        this.assessment_notes_id = result.data_id;
        this.notesData.assessment_notes_id = result.data_id;
        this.onEvent.emit();
      } else {
       // this.loading = false;
        this.notifier.notify( 'error', result.msg );
      }

    }, (err) => {
      // console.log(err);
      this.notifier.notify( 'error', err );
    });
  }
  public getEvent()
  {
    this.save_notify = 1
    this.saveNotify.emit(this.save_notify)
  }
  public editNotesAssesmentValues() {

    const postData = {
      assessment_id : this.assessment_id
    } ;
   // this.loading = true;
    this.rest2.editNotesAssesmentValues(postData).subscribe((result) => {
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if (result.status === 'Success') {
       // this.loading = false;
        const response = result.data;
        this.assessment_notes_id = response.NURSING_ASSESSMENT_NOTES_ID;
        this.notesData = {
          user_id : this.user_data.user_id,
          assessment_id : this.assessment_id,
          assessment_notes_id : response.NURSING_ASSESSMENT_NOTES_ID,
          next_of_kin : response.NEXT_OF_KIN,
          next_of_kin_mobile : response.NEXT_OF_KIN_MOBILE,
          chief_complaints : response.CHIEF_COMPLAINTS,
          nursing_notes : response.NURSING_NOTES,
          past_history : response.PAST_HISTORY,
          family_history : response.FAMILY_HISTORY,
          mode_arrival : response.MODE_ARRIVAL,
          mode_arrival_other_value : '',
          accompanied_by : response.ACCOMPANIED_BY,
          accompanied_other_value : '',
          patient_waiting_time_informed : response.PATIENT_WAITING_TIME_INFORMED,
          expected_waiting_time : response.EXPECTED_WAITING_TIME,
          client_date: this.notesData.client_date,
          date : defaultDateTime(),
          timeZone: Moment.tz.guess(),
        };
        if (response.MODE_ARRIVAL === 'Other') {
          this.notesData.mode_arrival_other_value = response.MODE_ARRIVAL_OTHER;
        }
        if (response.ACCOMPANIED_BY === 'Other') {
          this.notesData.accompanied_other_value = response.ACCOMPANIED_BY_OTHER;
        }
      } else {
        this.assessment_notes_id = 0
        this.notesData = {
          user_id : 0,
          assessment_id : 0,
          assessment_notes_id : 0,
          next_of_kin : '',
          next_of_kin_mobile : '',
          chief_complaints : '',
          nursing_notes : '',
          past_history : '',
          family_history : '',
          mode_arrival : 'None',
          mode_arrival_other_value : '',
          accompanied_by : 'None',
          accompanied_other_value : '',
          patient_waiting_time_informed : '',
          expected_waiting_time : '',
          client_date: this.notesData.client_date,
          date : defaultDateTime(),
          timeZone: Moment.tz.guess(),
        };
      }

    }, (err) => {
      console.log(err);
    });
   // console.log(this.notesData.nursing_notes)
  }

  public getNotesParameters() {
   // this.loading = true;
    this.rest2.getNotesParameters(null).subscribe((result) => {
      if (result.status === 'Success') {
        //this.loading = false;
        this.accompanied_by_list = result.data.accompanied_by;
        this.mode_of_arrivel_list = result.data.mode_of_arrivel;

        //console.log(this.accompanied_by_list);
       // console.log(this.mode_of_arrivel_list);

      }

    }, (err) => {
      console.log(err);
    });
  }
   public OnApplyNotify(event)
  {
    //console.log('event',event)
    this.save_notify = event
    this.saveNotify.emit(this.save_notify)
  }
  public getBloodSugarReport() {
    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id
    }
    this.rest2.getBloodSugarReport(postData).subscribe((result) => {
      if(result.status == "Success") 
      {
        this.blood_sugar = result.data
        this.onEvent.emit();
      } 
      else 
      {
        this.blood_sugar = result.data;
        this.onEvent.emit();
      }
    }, (err) => {
      console.log(err);
    });

  }
  public getAssesmentParameterValues(patient_id=0,assessment_id=0)
  {
      var postData = {
        patient_id : this.patient_id,
        assessment_id : this.assessment_id
      }
      this.rest2.getAssesmentParameterValues(postData).subscribe((result) => {
        if(result.status == "Success")
        {
          this.vital_values = result.data;
          this.onEvent.emit();
        }
        else
        {
          this.vital_values = result.data;
          this.onEvent.emit();
        }
      }, (err) => {
        console.log(err);
      }); 
  }
}
