import { Component, OnInit, Input, SimpleChanges, OnChanges, Output, EventEmitter } from '@angular/core';
import {DatePipe} from '@angular/common';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { Router } from '@angular/router';
import { NursingAssesmentService } from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../../../shared/class/Utils';
import { NotifierService } from 'angular-notifier';
import { ConsultingPageComponent } from '../consulting-page.component';
import { AppSettings } from 'src/app/app.settings';
import { NgForm } from '@angular/forms';
import { LoaderService } from '../../../../../shared';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';

@Component({
  selector: 'app-allergies',
  templateUrl: './allergies.component.html',
  styleUrls: ['./allergies.component.scss']
})
export class AllergiesComponent implements OnInit,OnChanges {
  @Input() patient_id: number = 0;
  @Input() selected_visit: any = [];
  todaysDate = new Date();
  @Input() values:ConsultingPageComponent;
  @Input() save_notify: number ;
  @Output() saveNotifys = new EventEmitter();
  private notifier: NotifierService;
  public user_rights : any ={};
  public user_data : any ={};
  public data : any = [];
  public allergy_data : any = [];
  public other_allergy_data : any = [""];
  public loading = false;
  public drug_allergy_data : any = [""];
  public patient_drug_allergies_generic_name: any = {};
  public patient_drug_allergies_brand_name: any = {};
  public patient_other_allergies_detail_id:any = {};
  public patient_other_allergies_item: any = {};
  public patient_allergies_id: number  = 0;
  public patient_allergies_details_id: number = 0;
  public addDrugAllergies: any = {};
  public patient_no_known_allergies: number = 0;
  current_date : any;
  get_status: number;
  constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,notifierService: NotifierService) {
  this.notifier = notifierService;
   }
   public now = new Date();
   public form_data = {
    patient_allergies_id: 0,
    patient_id: 0,
    assessment_id:0,
    patient_no_known_allergies:0,
    patient_drug_allergies_generic_name:[""],
    patient_drug_allergies_brand_name:[""],
    patient_other_allergies_detail_id:[""],
    patient_other_allergies_item:[""],
    client_date:'',
    user_id:0,
    date: new Date(),
    timezone : Moment.tz.guess()
   }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.todaysDate = defaultDateTime();
    this.form_data.date = defaultDateTime();
    this.form_data.user_id = this.user_data.user_id;
    this.form_data.timezone = Moment.tz.guess()
    this.listAllergiesOther();
    this.getPatientAllergies();
    this.formatClientDateTime ();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.listAllergiesOther();
    this.getPatientAllergies();
  }
  public getEvent()
  {
    // console.log(formatDate(this.todaysDate))
    // console.log(formatDate(this.selected_visit.CREATED_TIME))
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      this.save_notify = 1
      this.saveNotifys.emit(this.save_notify)
        // if(this.form_data.patient_no_known_allergies == 0 && this.form_data.patient_drug_allergies_brand_name.length == 0 && this.form_data.patient_drug_allergies_generic_name.length == 0 && this.form_data.patient_other_allergies_detail_id.length == 0 && this.form_data.patient_other_allergies_item.length == 0)
        // {
        //   this.save_notify = 0
        //   this.saveNotify.emit(this.save_notify)
        // }
        // if(this.form_data.patient_no_known_allergies ==0 && this.drug_allergy_data.length == 0 && this.other_allergy_data.length == 0)
        // {
        //   this.save_notify = 0
        //   this.saveNotify.emit(this.save_notify)
        // }
        // if(this.form_data.patient_no_known_allergies == 0)
        // {
        //   let flag = 0
        //   if(this.form_data.patient_drug_allergies_brand_name.length >0)
        //     for(let drg of this.form_data.patient_drug_allergies_brand_name) {
              
        //       if(drg !="") {
        //         flag = 1;
        //       }
        //     }
        //   if(this.form_data.patient_drug_allergies_generic_name.length >0)
        //     for(let drg of this.form_data.patient_drug_allergies_generic_name) {
              
        //       if(drg !="") {
        //         flag = 1
        //       }
        //     }
        //   if(this.form_data.patient_other_allergies_detail_id.length >0)
        //     for(let drg of this.form_data.patient_other_allergies_detail_id) {
              
        //       if(drg !="") {
        //         flag = 1;
        //       }
        //     }
        //   if(this.form_data.patient_other_allergies_item.length >0)
        //     for(let drg of this.form_data.patient_other_allergies_item) {
              
        //       if(drg !="") {
        //         flag = 1
        //       }
        //     }
        //   if(flag == 1) {
        //     this.save_notify = 1
        //     this.saveNotify.emit(this.save_notify)
        //   }
        //   else {
        //     this.save_notify = 0
        //     this.saveNotify.emit(this.save_notify)
        //   }
        // }
        // if(this.form_data.patient_no_known_allergies == 1)
        // {
        //   this.save_notify = 1
        //   this.saveNotify.emit(this.save_notify)
        // }
      } 
    else {
      this.save_notify = 0
      this.saveNotifys.emit(this.save_notify)
    }
    
  }
  public listAllergiesOther()
  {
    var postData = {
      allergies_other_id : AppSettings.NURSING_ASSESMENT
    }
    this.loading = true;
    this.rest2.listAllergiesOther(postData).subscribe((result) => {
      if(result["status"] === "Success")
      {
        this.loading= false;
        this.data = result["data"];
      }
      else
      {
        this.data = [];
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
  public saveDrugAllergies(form: NgForm)
  {
    if( this.form_data.patient_no_known_allergies == 0)
    {
      let flag = 0
      for(let drg of this.form_data.patient_drug_allergies_generic_name) {
        if(drg !="") {
          flag = 1;
        }
      }
      for(let drg of this.form_data.patient_drug_allergies_generic_name) {
        if(drg !="") {
          flag = 1
        }
      }

      if(flag == 0) {
        this.notifier.notify( 'error', 'Please enter any allergy details OR Check no Known allergies!' );
        return;
      }
    }

    this.form_data.date = defaultDateTime();
    this.form_data.patient_id = this.patient_id;
    this.form_data.patient_allergies_id = this.patient_allergies_id;
    this.loaderService.display(true);
      this.rest2.savePatientAllergies(this.form_data).subscribe((result) => {
        this.loaderService.display(false);
        this.save_notify = 2
        this.saveNotifys.emit(this.save_notify)
      if(result['status'] == 'Success') {
        this.notifier.notify( 'success', "Allergy details saved successfully..!" );
        this.patient_allergies_id = result.data_id;
        this.form_data.patient_allergies_id = result.data_id;
        this.getPatientAllergies();
      } else {

          this.notifier.notify( 'error', result.msg );
        }
    });

}
  public getPatientAllergies() {
    const postData = {
      patient_id : this.patient_id,
      patient_allergies_id : this.form_data.patient_allergies_id,
    };
    this.patient_allergies_id = 0
    this.drug_allergy_data = ["0"]
    this.other_allergy_data = ["0"]
    this.form_data = {
      patient_allergies_id: 0,
      patient_id: 0,
      assessment_id:0,
      patient_no_known_allergies:0,
      patient_drug_allergies_generic_name:[""],
      patient_drug_allergies_brand_name:[""],
      patient_other_allergies_detail_id:[""],
      patient_other_allergies_item:[""],
      client_date:'',
      user_id:0,
      date: new Date(),
      timezone : Moment.tz.guess()
    }
    this.loaderService.display(true);
    this.rest2.getPatientAllergies(postData).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.get_status = 1
        this.patient_allergies_id = result.data.PATIENT_ALLERGIES_ID;
        this.form_data.patient_id = this.patient_id;
        this.form_data.patient_no_known_allergies = result.data.NO_KNOWN_ALLERGIES;
        let i = 0;
        for (const val of result.data.DRUG_ALLERGIES) {
          this.drug_allergy_data[i] = val.BRAND_NAME;
          this.form_data.patient_drug_allergies_brand_name[i] = val.BRAND_NAME;
          this.form_data.patient_drug_allergies_generic_name[i] = val.GENERIC_NAME;
          i = i + 1;
        }
        let j = 0;
        for (const val of result.data.OTHER_ALLERGIES) {
          this.other_allergy_data[j] = j;
          this.form_data.patient_other_allergies_detail_id[j] = val.ALLERGIES_OTHER_ID;
          this.form_data.patient_other_allergies_item[j] = val.ALLERGIES_ITEM;
          j = j + 1;
        }
        // console.log(this.other_allergy_data)
      } else {
          this.patient_allergies_id = 0
          this.drug_allergy_data = ["0"]
          this.other_allergy_data = ["0"]
          this.form_data = {
            patient_allergies_id: 0,
            patient_id: 0,
            assessment_id:0,
            patient_no_known_allergies:0,
            patient_drug_allergies_generic_name:[""],
            patient_drug_allergies_brand_name:[""],
            patient_other_allergies_detail_id:[""],
            patient_other_allergies_item:[""],
            client_date:'',
            user_id:0,
            date: new Date(),
            timezone : Moment.tz.guess()
           }
      }
      }, (err) => {
       // console.log(err);
      });
  }
  public addDrugrow(index) {
    this.drug_allergy_data[index + 1] = '';
    this.form_data.patient_drug_allergies_brand_name[index + 1] = '';
    this.form_data.patient_drug_allergies_generic_name[index + 1] = '';
  }
  public addOtherrow(index) {
    this.other_allergy_data[index + 1] = '';
    this.form_data.patient_other_allergies_detail_id[index + 1] = '';
    this.form_data.patient_other_allergies_item[index + 1] = '';
  }
  public deleteDrugrow(index) {
    this.drug_allergy_data.splice(index, 1);
    this.form_data.patient_drug_allergies_brand_name.splice(index, 1);
    this.form_data.patient_drug_allergies_generic_name.splice(index, 1);
    this.getEvent()
  }
  public deleteOtherrow(index) {
    this.other_allergy_data.splice(index, 1);
    this.form_data.patient_other_allergies_detail_id.splice(index, 1);
    this.form_data.patient_other_allergies_item.splice(index, 1);
    this.getEvent()
  }
  public changeKnownAllergies(checked) {
    if (this.form_data.patient_no_known_allergies == 1) {
      this.form_data.patient_no_known_allergies = 0;
      this.form_data.patient_drug_allergies_generic_name=[""];
      this.form_data.patient_drug_allergies_brand_name=[""];
      this.form_data.patient_other_allergies_detail_id=[""];
      this.form_data.patient_other_allergies_item=[""]
      this.drug_allergy_data = []
      this.drug_allergy_data = ["0"]
      this.other_allergy_data = []
      this.other_allergy_data = ["0"]
    } else {
      this.form_data.patient_no_known_allergies = 1;
      this.form_data.patient_drug_allergies_generic_name=[""];
      this.form_data.patient_drug_allergies_brand_name=[""];
      this.form_data.patient_other_allergies_detail_id=[""];
      this.form_data.patient_other_allergies_item=[""]
      this.drug_allergy_data = []
      this.drug_allergy_data = ["0"]
      this.other_allergy_data = []
      this.other_allergy_data = ["0"]

    }
  }
  public formatClientDateTime() {
    if (this.now ) {
      this.form_data.client_date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
}

