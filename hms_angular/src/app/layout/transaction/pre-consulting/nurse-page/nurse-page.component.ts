import { Component, OnInit, Input, SimpleChanges, OnChanges, Output, EventEmitter } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import * as moment from 'moment';
import { NursingAssesmentService } from '../../../../shared/services'
import { formatTime, formatDateTime, formatDate } from '../../../../shared/class/Utils';
import { AppSettings } from '../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../../shared';
@Component({
  selector: 'app-nurse-page',
  templateUrl: './nurse-page.component.html',
  styleUrls: ['./nurse-page.component.scss']
})
export class NursePageComponent implements OnInit, OnChanges {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() selected_visit: any = [];
  @Output() finishAssessment = new EventEmitter();
  @Output() showDiscount = new EventEmitter();
  public settings = AppSettings;
  public assessment_menu_list: any = [];
  private notifier: NotifierService;
  public user_rights : any ={};
  public loading=false;
  public vital_params : any = [];
  public vital_values : any = [];
  public blood_sugar : any = [];
  public vital_form_values : any = [];
  public dateVal = new Date();
  public assessment_entry_id : number = 0;
  public selectedUserTab : any = {};
   tabs = [
     {
       name: 'Treatement Summary',
       key: 1,
       active: true
     },
      {
      name: 'Assessment Summary',
      key: 2,
      active: false
    },
    {
      name: 'Assesssments',
      key: 3,
      active: false
    },
    {
      name: 'Vitals',
      key: 4,
      active: false
    },
    {
      name: 'Documents',
      key: 5,
      active: false
    }
   ];
  finishactive: number;
  constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,notifierService: NotifierService) { 
    this.notifier = notifierService;
  }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    
    this.getAssessmentMenus();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id); 
    this.getBloodSugarReport(); 
  }
  ngOnChanges(changes: SimpleChanges) {
    // changes.prop contains the old and the new value...
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    
    //this.getAssessmentMenus();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id); 
    this.getBloodSugarReport(); 
  }
  public getBloodSugarReport() {
    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id
    }
    //this.loaderService.display(true);
    this.rest2.getBloodSugarReport(postData).subscribe((result) => {
      //this.loaderService.display(false);
      if(result.status == "Success") 
      {
        this.blood_sugar = result.data
      } 
      else 
      {
        this.blood_sugar = result.data;
      }
    }, (err) => {
      console.log(err);
    });

  }
  public tabChange(selectedTab) {
    //console.log('### tab change');
    this.selectedUserTab = selectedTab;
    for (let tab of this.assessment_menu_list) {
        if (tab.MODULE_ID === selectedTab.MODULE_ID) {
          tab.active = true;
        } else {
          tab.active = false;
        }
    }
  } 
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatDate (date) {
    return formatDate(date);
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
 
  public getAssessmentMenus()
  {
      var postData = {
        master_id : AppSettings.NURSING_ASSESMENT_MENU
      }
      // this.loading=true;
     // this.loaderService.display(true);
      this.rest2.getAssessmentMenus(postData).subscribe((result) => {
        if(result["status"] == "Success")
        {
          // this.loading=false;
         // this.loaderService.display(false);
          this.assessment_menu_list = result["assessment_menu_list"];
         //console.log(this.assessment_menu_list);
          this.selectedUserTab = this.assessment_menu_list["2"];
          
        }
        else
        {
          this.assessment_menu_list = [];
        }
      }, (err) => {
        console.log(err);
      }); 
  }
  public getAssesmentParameterValues(patient_id=0,assessment_id=0)
  {
    //this.patient_id = patient_id;
    //this.assessment_id = assessment_id;
      var postData = {
        patient_id : this.patient_id,
        assessment_id : this.assessment_id
      }
      // this.loading=true;
     // this.loaderService.display(true);
      this.rest2.getAssesmentParameterValues(postData).subscribe((result) => {
        if(result.status == "Success")
        {
          // this.loading= false;
          //this.loaderService.display(true);
       // console.log(result.data);
        this.vital_values = result.data;
        //this.router.navigate(['transaction/pre-consulting']);
          //window.location.reload();
        }
        else
        {
          // this.loading = false;
          //this.loaderService.display(false);
          this.vital_values = result.data;
        }
      }, (err) => {
        console.log(err);
      }); 
  }
  public OnFinish()
  {
    // this.selectedUserTab = {}
    // this.finishactive = 1
    this.finishAssessment.emit(1)
  }
  public ShowDiscount()
  {
    this.showDiscount.emit(1)
  }
}
