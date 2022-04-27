import { Component, OnInit,NgModule,Input,EventEmitter,Output, SimpleChanges, OnChanges } from '@angular/core';
import {DatePipe, CommonModule } from '@angular/common';
import { ConsultingService,NursingAssesmentService} from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../../../shared/class/Utils';
import { AppSettings } from './../../../../../app.settings';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import { NotifierService } from 'angular-notifier';
import { FormGroup, FormControl } from '@angular/forms';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { LoaderService } from '../../../../../shared';
@Component({
  selector: 'app-vital-signals',
  templateUrl: './vital-signals.component.html',
  styleUrls: ['./vital-signals.component.scss']
})
// @NgModule({
//   imports: [CommonModule]
// })
export class VitalSignalsComponent implements OnInit,OnChanges {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() vital_values: any = [];
  @Output() onEvent = new EventEmitter();
  @Input() selected_visit: any = [];
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  @Output() saveNotifys = new EventEmitter();
  todaysDate = new Date();
  private notifier: NotifierService;
  public user_rights : any ={};
  public user_data: any ={};
  public vital_params : any = [];
  public settings = AppSettings;
  // public vital_values : any = [];
  
  public vital_form_values : any = [];
  public dateVal = new Date();
  public assessment_entry_id : number = 0;
  public now = new Date(); 
  public date:any;
  current_date : any;
  blood_sugar_report: string;
  blood_sugar_report_id = 0;
  get_status: number = 0;
  get_blood_sugar_report: string;
  constructor(public datepipe: DatePipe,private loaderService : LoaderService,private router: Router, public rest2:NursingAssesmentService,notifierService: NotifierService) {
    this.notifier = notifierService;
   }

  ngOnInit() {

    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.getAssesmentParameters();
    this.getAssesmentParameterValues();
    this.getBloodSugarReport();
    //this.values.getAssesmentParameterValues();  
    // this.getAssesmentParameterValues();
    this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  ngOnChanges(changes: SimpleChanges) {
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameters();
    this.getAssesmentParameterValues();
    this.getBloodSugarReport();
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
  public getEvent()
  {
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      if(this.vital_form_values.length != 0)
      {
        var flag = 0
        for(let data of this.vital_form_values)
        {
          if(data != undefined && data !="" && data !=null)
          {
            flag = 1
          }
        }
        if(flag == 1)
        {
          this.save_notify = 1
          this.saveNotify.emit(this.save_notify)
          this.saveNotifys.emit(this.save_notify)
        }
        else
        {
          this.save_notify = 0
          this.saveNotify.emit(this.save_notify)
          this.saveNotifys.emit(this.save_notify)
        }
        
      }
    }
    else {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
      this.saveNotifys.emit(this.save_notify)

    }
    
  }
  public getEvents()
  {
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      if(this.get_status == 1)
      {
          if(this.get_blood_sugar_report != this.blood_sugar_report)
          {
            this.save_notify = 1
            this.saveNotify.emit(this.save_notify)
            this.saveNotifys.emit(this.save_notify)

          }
          else
          {
            this.save_notify = 0
            this.saveNotify.emit(this.save_notify)            
            this.saveNotifys.emit(this.save_notify)

          }
      }
      else
      {
        if(this.blood_sugar_report.length != 0)
        {
          this.save_notify = 1
          this.saveNotify.emit(this.save_notify)            
          this.saveNotifys.emit(this.save_notify)
        }
        else
        {
          this.save_notify = 0
          this.saveNotify.emit(this.save_notify)            
          this.saveNotifys.emit(this.save_notify)
        }
      }

        
    }
    else {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)            
      this.saveNotifys.emit(this.save_notify)
    }
    
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
      date  : defaultDateTime(),
      user_id : this.user_data.user_id,
      assessment_id : this.assessment_id,
      assessment_entry_id : this.assessment_entry_id,
      test_methode : AppSettings.NURSING_ASSESMENT,
      vitals_details : this.vital_form_values,
      timeZone: Moment.tz.guess()
      
    };

    this.loaderService.display(true);
    this.rest2.saveAssesmentParameters(postData).subscribe((result) => {
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)            
      this.saveNotifys.emit(this.save_notify)
      if(result.status == "Success")
      {
        this.loaderService.display(false);
        this.dateVal = defaultDateTime();
        this.assessment_entry_id = 0;
        // this.getAssesmentParameter();
        this.getAssesmentParameterValues();
        // this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
        this.onEvent.emit();
        this.vital_form_values = [];
        //console.log("vital_form_values=="+this.vital_form_values);
      }
      this.loaderService.display(false);
    }, (err) => {
      //console.log(err);
    });
  }
  public getAssesmentParameters()
  {
      let postData = {
      test_methode : AppSettings.NURSING_ASSESMENT
      }

      this.loaderService.display(true);
      this.rest2.getAssesmentParameters(postData).subscribe((result) => {
        if(result.status == "Success")
        {
          this.loaderService.display(false);
          this.vital_params = result.data;
          //console.log("vitalparam" +this.vital_params);
        // this.router.navigate(['transaction/pre-consulting']);
          // window.location.reload();
        } else {

          this.loaderService.display(false);
        }
      }, (err) => {
       // console.log(err);
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
        if(result.status == 'Success') {
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
       // console.log(err);
      });
      this.loaderService.display(false);
  }
  public editAssesmentValues(assesment_vitals_id,vitalsForm: NgForm) {
    if(this.vital_values.CREATED_BY = this.user_data.user_id) {
      //console.log(this.vital_values.CREATED_BY);
      //console.log("user_id "+this.user_data.user_id);

    // console.log(assesment_vitals_id);
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
      this.getEvent()
      this.dateVal = result.data.DATE_TIME;
      this.assessment_entry_id = result.data.NURSING_ASSESSMENT_ENTRY_ID;
      this.loaderService.display(false);
      } else {
        this.loaderService.display(false);
        this.vital_form_values = []
      }
    }, (err) => {
     // console.log(err);
    });
  }
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
      this.saveNotify.emit(this.save_notify)            
      this.saveNotifys.emit(this.save_notify)
      this.loaderService.display(false);
      if(result['status'] == 'Success') {
        this.notifier.notify('success',result.msg)
        this.blood_sugar_report_id = result.data_id
        this.getBloodSugarReport();
        this.onEvent.emit();
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
        this.get_status = 1;
        this.blood_sugar_report = result.data.BLOOD_SUGAR_REPORT
        this.get_blood_sugar_report = result.data.BLOOD_SUGAR_REPORT
        this.blood_sugar_report_id = result.data.BLOOD_SUGAR_REPORT_ID
      } else 
      {
        this.blood_sugar_report = ''
        this.blood_sugar_report_id = 0  
      }
    }, (err) => {
      console.log(err);
    });

  }
}
