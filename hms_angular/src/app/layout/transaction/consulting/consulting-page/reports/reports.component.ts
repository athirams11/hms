import { Component, OnInit, Input, SimpleChanges, OnChanges } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { NursingAssesmentService, ConsultingService } from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate } from './../../../../../shared/class/Utils';
import { NotifierService } from 'angular-notifier';
import { ConsultingPageComponent } from '../consulting-page.component';
import { NgForm } from '@angular/forms';
import * as moment from 'moment';
import { AppSettings } from 'src/app/app.settings';
import { LoaderService } from '../../../../../shared';
@Component({
  selector: 'app-reports',
  templateUrl: './reports.component.html',
  styleUrls: ['./reports.component.scss']
})
export class ReportsComponent implements OnInit ,OnChanges{
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() vital_values: any = [];
  public loading = false;
  notifier: NotifierService;
  user_rights: any;
  user_data: any;
  reports_notes_id: number =0;
  consultation_id: number = 0;
  investigation_requested: string;
  public vital_params : any = [];
  public now = new Date();
  public param_values : any = [];
  public vital_form_values : any = [];
  constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,public rest:ConsultingService,notifierService: NotifierService) {
    this.notifier = notifierService;
  }
  public report_data = {
    reports_notes_id: 0,
    patient_id : 0,
    user_id: 0,
    consultation_id: 0,
    assessment_id: 0,
    investigation_requested: this.investigation_requested,
    date:''
  }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.getReportsAndNotes();
    this.getAssesmentParameters();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    this.formatDateReportTime ();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.getReportsAndNotes();
    this.getAssesmentParameters();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id);

  }
  public saveReportsAndNotes(form: NgForm)
  { 
    //console.log("data FORM : "+this.form_data);
    this.report_data.patient_id = this.patient_id;
    this.report_data.assessment_id =this.assessment_id
    this.report_data.reports_notes_id = this.reports_notes_id;

    // this.loading = true;
    this.loaderService.display(true);
    this.rest.saveReportsAndNotes(this.report_data).subscribe((result) => {
      if(result["status"] == "Success")
      {
        // this.loading = false;
        this.loaderService.display(false);
        //console.log("data="+result["data"]);
        this.notifier.notify( 'success', result.msg );
        this.reports_notes_id=result.data_id;
        this.report_data.reports_notes_id=result.data_id;
      }
      else
        {
          // this.loading = false;
          this.loaderService.display(false);
          this.notifier.notify( 'error', result.msg );

        }
      })
  }
  public getReportsAndNotes()
  {
    var postData = {
      patient_id :this.patient_id,
      assessment_id : this.assessment_id,
      }
      // this.loading = true;
      this.loaderService.display(true);
    this.rest.getReportsAndNotes(postData).subscribe((result) => {
      if(result.status == "Success")
      {
        // this.loading = false;
        this.loaderService.display(false);
      //console.log(result.data);
      this.reports_notes_id = result.data.REPORTS_NOTES_ID,
      this.report_data.investigation_requested = result.data.INVESTIGATION_REQUESTED;
      this.report_data.patient_id = this.patient_id;
      this.report_data.assessment_id = this.assessment_id;
      //this.form_data=result.data;
      }
      else
      {
        // this.loading = false;
        this.loaderService.display(false);
      }
      }, (err) => {
      //  console.log(err);
      });
    }

  public deleteValue()
  {
    this.report_data.investigation_requested = ''

  }
  public getAssesmentParameters()
  {
      let postData = {
      test_methode : AppSettings.NURSING_ASSESMENT
      }
      // this.loading = true;
      this.loaderService.display(true);
      this.rest2.getAssesmentParameters(postData).subscribe((result) => {
        if(result.status == 'Success')
        {
          // this.loading = false;
          this.loaderService.display(false);
        this.vital_params = result.data;

        // this.router.navigate(['transaction/pre-consulting']);
          // window.location.reload();
        } else {
          // this.loading = false;
          this.loaderService.display(false);
        }
      }, (err) => {
      //  console.log(err);
      });

  }
  public getAssesmentParameterValues(patient_id=0,assessment_id=0) {
    // this.patient_id = patient_id;
    // this.assessment_id = assessment_id;
      let postData = {
        patient_id : this.patient_id,
        assessment_id : this.assessment_id
      }
      // this.loading = true;
      this.loaderService.display(true);
      this.rest2.getAssesmentParameterValues(postData).subscribe((result) => {
        if(result.status == 'Success') {
          // this.loading = false;
          this.loaderService.display(false);
       // console.log(result.data);
        this.vital_values = result.data;
        // this.router.navigate(['transaction/pre-consulting']);
          // window.location.reload();
        } else {
          this.loaderService.display(false);
          this.vital_values = result.data;
          // this.loading = false;
        }
      }, (err) => {
      //  console.log(err);
      });

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
  public formatDateReportTime () {
    if (this.now ) {
      this.report_data.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  
  }
}
