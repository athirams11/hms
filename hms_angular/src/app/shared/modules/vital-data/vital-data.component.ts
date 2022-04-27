import { Component, OnInit,Input, Output, SimpleChanges, OnChanges } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { NursingAssesmentService } from '../../services'
import { formatTime, formatDateTime, formatDate } from './../../class/Utils';
import { AppSettings } from '../../../app.settings';
import { NotifierService } from 'angular-notifier';

@Component({
  selector: 'app-vital-data',
  templateUrl: './vital-data.component.html',
  styleUrls: ['./vital-data.component.scss']
})
export class VitalDataComponent implements OnInit ,OnChanges{
 
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() vital_values: any = [];
  @Input() blood_sugar: any = [];
  private notifier: NotifierService;
  public user_rights : any ={};
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
    //this.getAssesmentParameters();
    //this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    //this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    //this.getAssesmentParameters();
     //console.log(this.blood_sugar)
    this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  // public formatTime (time)
  // {
  //   var retVal = "";
  //   if(time != "")
  //   {
  //     retVal =  moment(time, "HH:mm.ss").format("hh:mm a");
  //   }
  //   return  retVal;
  // }
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
      this.rest2.getAssesmentParameters(postData).subscribe((result) => {
        if(result.status == "Success")
        {
    
        //console.log(result.data);
        this.vital_params = result.data;
        //this.router.navigate(['transaction/pre-consulting']);
          //window.location.reload();
        }
        else
        {

        }
      }, (err) => {
        console.log(err);
      });
  }
  
}
