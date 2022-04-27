import { Component, OnInit,EventEmitter, Input ,Output, OnChanges, SimpleChanges} from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { NursingAssesmentService } from './../../../../../shared/services'
import { AppSettings } from './../../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../../../shared';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../../../shared/class/Utils';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
@Component({
  selector: 'app-vitals',
  templateUrl: './vitals.component.html',
  styleUrls: ['./vitals.component.scss']
})
export class VitalsComponent implements OnInit, OnChanges {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() vital_values: any = [];
  @Input() blood_sugar: any = [];
  @Output() onEvent = new EventEmitter();
  @Output() onEvents = new EventEmitter();
  @Input() selected_visit: any = [];
  todaysDate = new Date();
  @Input() save_notify: number ;
  @Output() saveNotifys = new EventEmitter();
  private notifier: NotifierService;
  public user_rights : any ={};
  public user_data: any ={};
  public loading = false;
  public vital_params : any = [];
  public settings = AppSettings;
  // public vital_values : any = [];
  public vital_form_values : any = [];
  public dateVal = new Date();
  public assessment_entry_id : number = 0;
  current_date :any;
  blood_sugar_report: string;
  blood_sugar_report_id = 0;
  constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,notifierService: NotifierService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameters();
    this.getBloodSugarReport();
    //this.values.getAssesmentParameterValues();  
    // this.getAssesmentParameterValues();
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.dateVal = defaultDateTime();
    this.todaysDate = defaultDateTime();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameters();
    this.getBloodSugarReport();
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.dateVal = defaultDateTime();
    this.todaysDate = defaultDateTime();
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

  public saveVitals()
  {
    var form_data = this.vital_form_values;
    if(this.vital_form_values.length == 0)
    {
      // this.failed_message = "Please enter atleast one parameter value";
      this.notifier.notify( 'error', 'Please enter atleast one parameter value!' );
      return;
    }
      let postData = {
        client_date  : new Date(),
        user_id : this.user_data.user_id,
        assessment_id : this.assessment_id,
        assessment_entry_id : this.assessment_entry_id,
        test_methode : AppSettings.NURSING_ASSESMENT,
        vitals_details : this.vital_form_values,
        date:defaultDateTime(),
        timeZone: Moment.tz.guess()
    }
    this.loaderService.display(true);

    this.rest2.saveAssesmentParameters(postData).subscribe((result) => {
      if(result.status == 'Success') {
        this.loaderService.display(false);
        this.dateVal = defaultDateTime();
        this.assessment_entry_id = 0;
        //this.getAssesmentParameters();
         this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
        this.onEvents.emit();
        this.vital_form_values = [];
        //console.log("vital_form_values=="+ this.vital_form_values);
      }
      this.loaderService.display(false);
    }, (err) => {
      console.log(err);
    });
  }
  public getAssesmentParameters() {
      const postData = {
      test_methode : AppSettings.NURSING_ASSESMENT
      };
     // this.loaderService.display(true);
      this.rest2.getAssesmentParameters(postData).subscribe((result) => {
        if(result.status == "Success") {
         // this.loaderService.display(false);
        // console.log(result.data);
        this.vital_params = result.data;
        //console.log("vitalparam"+this.vital_params);
        // this.router.navigate(['transaction/pre-consulting']);
          // window.location.reload();
        } else {
          this.loaderService.display(false);
        }
      }, (err) => {
        console.log(err);
      });

  }
  
  public calculateBMI(height,weight ) {
    weight= +weight;
    height= +height;
    if(weight&&height) {
      this.vital_form_values[this.settings.BMI]=(weight/(height*height)*10000).toFixed(2);
    } else {
      this.vital_form_values[this.settings.BMI]='';
    }
    if(weight&&height) {
      this.vital_form_values[this.settings.BSA]=Math.sqrt((weight*height)/3600).toFixed(2);
    } else {
      this.vital_form_values[this.settings.BSA]='';
    }
  }
  public getAssesmentParameterValues(patient_id=0,assessment_id=0) {
    // this.patient_id = patient_id;
    // this.assessment_id = assessment_id;
      const postData = {
        patient_id : this.patient_id,
        assessment_id : this.assessment_id
      }
      this.loaderService.display(true);
      this.rest2.getAssesmentParameterValues(postData).subscribe((result) => {
        if(result.status == "Success") {
          this.loaderService.display(false);
       // console.log(result.data);
        this.vital_values = result.data;
        // this.router.navigate(['transaction/pre-consulting']);
          // window.location.reload();
        } else {
          this.loaderService.display(false);
          this.vital_values = result.data;
        }
      }, (err) => {
        console.log(err);
      });

  }
  public editAssesmentValues(assesment_vitals_id,vitalsForm: NgForm) {
    //console.log(assesment_vitals_id);
    const postData = {
      entry_id : assesment_vitals_id,
      entry_type : AppSettings.NURSING_ASSESMENT
    }
    this.loaderService.display(true);
    this.rest2.editAssesmentValues(postData).subscribe((result) => {
      if(result.status == 'Success') {

        this.loaderService.display(false);
      const array_val = [];
      const value_id = 1;
      for (const value of result.data.parameters) {
        this.vital_form_values[value.TEST_PARAMETER_ID] = value.PARAMETER_VALUE;
      }
      this.dateVal = result.data.DATE_TIME;
      this.assessment_entry_id = result.data.NURSING_ASSESSMENT_ENTRY_ID;

      } else {
        this.loaderService.display(false);
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getEvent()
  {
    this.save_notify = 1
    this.saveNotifys.emit(this.save_notify)
  }
  public saveBloodSugarReport()
  {
    if(this.blood_sugar_report == '' || this.blood_sugar_report == null)
    {
      this.notifier.notify( 'error', 'Please enter blood sugar report!' );
      return;
    }
      let postData = {
        blood_sugar_report_id : this.blood_sugar_report_id,
        client_date  : new Date(),
        user_id : this.user_data.user_id,
        assessment_id : this.assessment_id,
        patient_id : this.patient_id,
        blood_sugar_report : this.blood_sugar_report,
        date:defaultDateTime(),
        timeZone: Moment.tz.guess()
    }
    this.loaderService.display(true);
    this.rest2.saveBloodSugarReport(postData).subscribe((result) => {
      this.save_notify = 2
      this.saveNotifys.emit(this.save_notify)
      this.loaderService.display(false);
      if(result['status'] == 'Success') {
        this.notifier.notify('success',result.msg)
        this.blood_sugar_report_id = result.data_id
        this.getBloodSugarReport();
        this.onEvents.emit();
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getBloodSugarReport() {
    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id,
      blood_sugar_report_id : this.blood_sugar_report_id
    }
    this.loaderService.display(true);
    this.rest2.getBloodSugarReport(postData).subscribe((result) => {
      this.loaderService.display(false);
      if(result.status == "Success") {
        this.blood_sugar = result.data
        this.blood_sugar_report = result.data.BLOOD_SUGAR_REPORT
        this.blood_sugar_report_id = result.data.BLOOD_SUGAR_REPORT_ID
      }
       else {
        this.blood_sugar = []
        this.blood_sugar_report = ''
        this.blood_sugar_report_id = 0
      }
    }, (err) => {
      console.log(err);
    });

  }
}
