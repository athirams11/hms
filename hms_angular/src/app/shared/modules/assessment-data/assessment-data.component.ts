import { Component, OnInit,Input, Output, SimpleChanges, OnChanges } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { NursingAssesmentService } from '../../services'
import { AppSettings } from '../../../app.settings';
import { NotifierService } from 'angular-notifier';

@Component({
  selector: 'app-assessment-data',
  templateUrl: './assessment-data.component.html',
  styleUrls: ['./assessment-data.component.scss']
})
export class AssessmentDataComponent implements OnInit,OnChanges {

  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() selected_visit: any = [];

  private notifier: NotifierService;
  public user_rights : any ={};
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
    mode_arrival : "None",
    mode_arrival_other_value : '',
    accompanied_by : "None",
    accompanied_other_value : '',
    patient_waiting_time_informed : '',
    expected_waiting_time : ''
  }
  
  public accompanied_by_list :any = [];
  public mode_of_arrivel_list :any = [];
  constructor(public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,notifierService: NotifierService) {
    this.notifier = notifierService;
    this.user_data = JSON.parse(localStorage.getItem('user'));
    
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getNotesParameters();
    this.editNotesAssesmentValues();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getNotesParameters();
    this.editNotesAssesmentValues();
  }
  public changeArrivalMode(value)
  {
    this.notesData.mode_arrival = value;
  }
  public changeAccompaniedBy(value)
  {
    this.notesData.accompanied_by = value;
  }
  public saveNursingNotes()
  {

   // console.log("user"+this.user_data);
    this.notesData.user_id = this.user_data.user_id;

    this.notesData.assessment_id = this.assessment_id;

    this.notesData.assessment_notes_id = this.assessment_notes_id;

    this.rest2.saveAssesmentNotes(this.notesData).subscribe((result) => {
      if(result.status == "Success")
      {
        this.notifier.notify( 'success', result.msg );  
        this.assessment_notes_id = result.data_id;
        this.notesData.assessment_notes_id = result.data_id;
      }
      else{
        this.notifier.notify( 'error', result.msg );
      }
      
    }, (err) => {
      //console.log(err);
      this.notifier.notify( 'error', err );
    });
  }
  public editNotesAssesmentValues()
  {
    
    var postData = {
      assessment_id : this.assessment_id
    } ;
    
    this.rest2.editNotesAssesmentValues(postData).subscribe((result) => {
      if(result.status == "Success")
      {
        var response = result.data;
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
          expected_waiting_time : response.EXPECTED_WAITING_TIME
        }
        if(response.MODE_ARRIVAL == 'Other')
        {
          this.notesData.mode_arrival_other_value = response.MODE_ARRIVAL_OTHER;
        }
        else{
          this.notesData.mode_arrival_other_value = response.MODE_ARRIVAL;
        }
        if(response.ACCOMPANIED_BY == 'Other')
        {
          this.notesData.accompanied_other_value = response.ACCOMPANIED_BY_OTHER;
        }
        else{
          this.notesData.accompanied_other_value = response.ACCOMPANIED_BY;
        }
      }
      
    }, (err) => {
      console.log(err);
    });
  }
  public getNotesParameters()
  {
    this.rest2.getNotesParameters(null).subscribe((result) => {
      if(result.status == "Success")
      {
        this.accompanied_by_list = result.data.accompanied_by;
        this.mode_of_arrivel_list = result.data.mode_of_arrivel;

        // console.log(this.accompanied_by_list);
        // console.log(this.mode_of_arrivel_list);

      }
      
    }, (err) => {
      console.log(err);
    });
  }
}

