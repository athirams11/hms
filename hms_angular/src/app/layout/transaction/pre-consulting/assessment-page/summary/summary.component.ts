import { Component, OnInit,Input, Output, OnChanges, SimpleChanges } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { NursingAssesmentService } from './../../../../../shared/services'
import { AppSettings } from './../../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import { AssessmentPageComponent } from '../assessment-page.component';
import { formatTime, formatDateTime, formatDate } from './../../../../../shared/class/Utils';
@Component({
  selector: 'app-summary',
  templateUrl: './summary.component.html',
  styleUrls: ['./summary.component.scss']
})
export class SummaryComponent implements OnInit, OnChanges {
 
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() vital_values:any = [];
  @Input() blood_sugar:any = [];
  private notifier: NotifierService;
  public user_rights : any ={};
  public loading = false;
  public user_data : any ={};
  public vital_params : any = [];
  //public vital_values : any = [];
  public param_values : any = [];
  public vital_form_values : any = [];
  public dateVal = new Date();
  public assessment_entry_id : number = 0;
  constructor(public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,notifierService: NotifierService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameters();
    //this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    //this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameters();
    this.user_data = JSON.parse(localStorage.getItem('user'));
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
  public getAssesmentParameters()
  {
      var postData = {
        test_methode : AppSettings.NURSING_ASSESMENT
      }
      this.loading = true;
      this.rest2.getAssesmentParameters(postData).subscribe((result) => {
        if(result.status == "Success")
        {
          this.loading = false;
        //console.log(result.data);
        this.vital_params = result.data;
        //this.router.navigate(['transaction/pre-consulting']);
          //window.location.reload();
        }
        else
        {
          
          this.loading = false;
        }
      }, (err) => {
        console.log(err);
      });
  }
}
