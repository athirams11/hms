import { Component, OnInit, Input, SimpleChanges, OnChanges, ɵConsole, Output, EventEmitter } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { ConsultingService, DoctorsService } from './../../../../../shared/services';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import { NotifierService } from 'angular-notifier';
import { NgForm } from '@angular/forms';
import { LoaderService } from '../../../../../shared';
import { Observable , of} from 'rxjs';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import * as moment from 'moment';
@Component({
  selector: 'app-diagnosis',
  templateUrl: './diagnosis.component.html',
  styleUrls: ['./diagnosis.component.scss']
})

export class DiagnosisComponent implements OnInit, OnChanges {
  get_status: number;
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() selected_visit: any = [];
  todaysDate = defaultDateTime();
  @Input() visit_list: any = [];
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  public loading = false;
  public level_list: any = [];
  public type_list: any = [];
  public user_rights: any = {};
  public user_data: any = {};
  public now = defaultDateTime();
  notifier: NotifierService;
  diagnosis_id: number;
  consultation_id: number;
  public diagnosis_id_arr: any = {};
  public diagnosis_id_array: any = {};
  public code: any = [];
  public diagnosis_level_arr: any = {};
  public diagnosis_type_arr: any = {};
  public patient_diagnosis_id = 0;
  data: any = [];
  dropdownSettings = {};
  dropdown_type_Settings = {};
  public diagnosis_list: any = [];
  item: any;
  textField: any;
  public previous_diagnosis: any;
  public patient_other_diagnosis: string;
  public num_rows: any = ['0'];
  public diagnosis_date: any;
  public config = {};
  public diagnosis_data_arr: any = [];
  public get_diagnosis_data = {
    diagnosis_name_data: [],
    diagnosis_name: [],
    code : [],
    patient_other_diagnosis : '',
    diagnosis_id_arr: [],
    diagnosis_level_arr: [],
    diagnosis_type_arr: [],
  }
  start: number;
  limit: number;
  public p = 10;
  public collection:any= '';
  page :number = 1;
  public diagnosis_data = {
    diagnosis_name_data: [],
    diagnosis_name: [],
    code : [],
    user_id : 0,
    patient_diagnosis_id: 0,
    patient_id: 0,
    assessment_id : 0,
    consultation_id: 0,
    patient_other_diagnosis : '',
    diagnosis_id_arr: [],
    diagnosis_level_arr: [],
    diagnosis_type_arr: [],
    date: defaultDateTime(),
    client_date: new Date(),
    timeZone : getTimeZone()
    };
  model: any;
  searching = false;
  searchFailed = false;
  index: any;
  constructor(private loaderService: LoaderService, public datepipe: DatePipe, private router: Router, public rest2: ConsultingService, public rest: DoctorsService, notifierService: NotifierService) {
    this.notifier = notifierService;
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.get_type();
    this.get_level();
    this.getPatientDiagnosis();
    this.getPreviousDiagnosis();
    this.formatDiagnosisDateTime ();
   }
   ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.get_type();
    this.get_level();
    this.getPatientDiagnosis();
    this.getPreviousDiagnosis();
  }
  public getEvent()
  {
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      this.save_notify = 1
      this.saveNotify.emit(this.save_notify)
        // let flag = 0
        // if(this.diagnosis_data.diagnosis_id_arr.length == 0)
        // {
        //   this.save_notify = 0
        //   this.saveNotify.emit(this.save_notify)
        // }
        // else
        // {
        //   if(this.diagnosis_data.diagnosis_id_arr.length > 0)
        //   { 
        //     for(let drg of this.diagnosis_data.diagnosis_id_arr) {
        //       if(drg !="" || drg !=null) {
        //         flag = 1;
        //       }
        //     }
        //   }
        //   if(this.diagnosis_data.diagnosis_level_arr.length > 0)
        //   { 
        //     for(let drg of this.diagnosis_data.diagnosis_level_arr) {
        //       if(drg !="" || drg !=null) {
        //         flag = 1;
        //       }
        //     }
        //   }
        //   if(this.diagnosis_data.diagnosis_type_arr.length > 0)
        //   {
        //     for(let drg of this.diagnosis_data.diagnosis_type_arr) {
        //       if(drg !="" || drg !=null) {
        //         flag = 1;
        //       }
        //     }
        //   } 
        // } 
        // if(this.diagnosis_data.patient_other_diagnosis && this.diagnosis_data.patient_other_diagnosis != ''){
        //   flag = 1  
        // }
        // if(flag == 1) {
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
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatDate (date) {
    return  formatDate(date);
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  public formatDiagnosisDateTime () {
    this.diagnosis_data.date = defaultDateTime();
    // if (this.now ) {
    //   this.diagnosis_data.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    // }
  }

   public set_item($event, i) {
    const item = $event.item;
    this.diagnosis_data.diagnosis_id_arr[i] = item.DIAGNOSIS_ID;
    this.getEvent()
    this.getDiagnosis(item, i);
   }
   public getDiagnosis(data, i) {

     const postData = {
      diagnosis_id : data.DIAGNOSIS_ID

     };
     this.loaderService.display(true);
     this.rest2.getDiagnosis(postData).subscribe((result: {}) => {
      if (result['status'] == 'Success') {
        const dailist_data = result['data'];
        this.diagnosis_data.diagnosis_name[i] = dailist_data.NAME;
        this.diagnosis_data.code[i] = dailist_data.CODE;
        this.addrow(i)
        this.loaderService.display(false);
       } else {
          this.loaderService.display(false);
       }
    });
   }
  public get_level() {
    const postData = {
      master_id : 4
    };
    // this.loading = true;
    this.loaderService.display(true);
    this.rest2.get_master_data(postData).subscribe((result) => {
      if (result['status'] == 'Success') {
        // this.loading = false;
        this.loaderService.display(false);
        this.level_list = result['master_list'];
      } else {
        this.loaderService.display(false);
        this.level_list = [];
      }
    }, (err) => {
     // console.log(err);
    });
  }
  public get_type() {
    const postData = {
      master_id : 9
    };
    // this.loading = true;
    this.loaderService.display(true);
    this.rest2.get_master_data(postData).subscribe((result) => {
      if (result['status'] == 'Success') {
        // this.loading = false;
        this.loaderService.display(false);
        this.type_list = result['master_list'];
      } else {
        this.loaderService.display(false);
        this.type_list = [];
      }
    }, (err) => {
     // console.log(err);
    });
  }
  public saveDaignosis() {
    this.diagnosis_data.patient_id = this.patient_id;
    this.diagnosis_data.assessment_id = this.assessment_id;
    this.diagnosis_data.user_id = this.user_data.user_id;
    this.diagnosis_data.patient_diagnosis_id = this.patient_diagnosis_id;
    var rel = 0
    this.diagnosis_data.diagnosis_id_arr.forEach((item, index) => {
        if (this.diagnosis_data.diagnosis_level_arr[index] == '' || this.diagnosis_data.diagnosis_level_arr[index] == null) {
          rel = 1;
        }
        if (this.diagnosis_data.diagnosis_type_arr[index] == '' || this.diagnosis_data.diagnosis_type_arr[index] == null) {
           rel = 2;
         }  
    });
    if(rel == 1)
    {
      this.notifier.notify( 'error', 'Please select diagnosis level' );
      return true;
    }
    if(rel == 2)
    {
      this.notifier.notify( 'error', 'Please select diagnosis type' );
      return true;
    }
      let flag = false;
      for (const dg of this.diagnosis_data.diagnosis_level_arr) {
        if (dg == 15 && flag == true) {
            this.notifier.notify( 'error', 'There should be only one principal diagnosis');
            return false;
        }
        if (dg == 15 && flag == false) {
            flag = true;
        }
        if (flag == false) {
           this.notifier.notify( 'error', 'Could not select secondary more than one principal');
           return false;
        }
      }
    // this.loading = true;
    this.loaderService.display(true);
    this.rest2.savePatientDiagnosis(this.diagnosis_data).subscribe((result) => {
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if (result['status'] == 'Success') {
        this.loaderService.display(false);
        this.notifier.notify( 'success', "Patient diagnosis details saved successfully..!" );
        this.patient_diagnosis_id = result.data_id;
        this.diagnosis_data.patient_diagnosis_id = result.data_id;
        this.getPatientDiagnosis();
      } else {
        // this.loading = false;
        this.loaderService.display(false);
        this.notifier.notify( 'error', result.msg );
      }
    });
  }
    public getPatientDiagnosis() {
    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id,
    };
    this.loaderService.display(true);
    this.rest2.getPatientDiagnosis(postData).subscribe((result) => {
    if (result.status == 'Success') {
      this.get_status = 1
      this.loaderService.display(false);
      this.patient_diagnosis_id = result.data.PATIENT_DIAGNOSIS_ID;
      this.diagnosis_data.patient_other_diagnosis = result.data.PATIENT_OTHER_DIAGNOSIS;
      this.get_diagnosis_data.patient_other_diagnosis = result.data.PATIENT_OTHER_DIAGNOSIS;
      let i = 0;
     this.num_rows = ['0'];
     if(result.data.PATIENT_DIAGNOSIS_DETAILS.length > 0)
     {
      var num_rows = [];
        for (const val of result.data.PATIENT_DIAGNOSIS_DETAILS) {
          const temp = {
              'DIAGNOSIS_ID': val.DIAGNOSIS_ID,
              'NAME': val.DIAGNOSIS_NAME,
              'CODE': val.DIAGNOSIS_CODE
              };
          num_rows[i] = i;
          num_rows.push(i);
          this.diagnosis_data.diagnosis_name_data[i] = temp;
          this.diagnosis_data.diagnosis_id_arr[i] =  val.DIAGNOSIS_ID;
          this.diagnosis_data.diagnosis_name[i] = val.DIAGNOSIS_NAME;
          this.diagnosis_data.code[i] = val.DIAGNOSIS_CODE;
          this.diagnosis_data.diagnosis_level_arr[i] = val.DIAGNOSIS_LEVEL_ID;
          this.diagnosis_data.diagnosis_type_arr[i] = val.DIAGNOSIS_TYPE_ID;
          this.get_diagnosis_data.diagnosis_name_data[i] = temp;
          this.get_diagnosis_data.diagnosis_id_arr[i] =  val.DIAGNOSIS_ID;
          this.get_diagnosis_data.diagnosis_name[i] = val.DIAGNOSIS_NAME;
          this.get_diagnosis_data.code[i] = val.DIAGNOSIS_CODE;
          this.get_diagnosis_data.diagnosis_level_arr[i] = val.DIAGNOSIS_LEVEL_ID;
          this.get_diagnosis_data.diagnosis_type_arr[i] = val.DIAGNOSIS_TYPE_ID;
          i = i + 1;
        }
      //this.diagnosis_data_arr = diagnosis_data_arr
        //this.diagnosis_id_array = diagnosis_id_array;
        this.num_rows = num_rows;
      }
      } else {
        this.loaderService.display(false);
        this.num_rows = ['0'];
        this.patient_diagnosis_id = 0;
        this.diagnosis_data.patient_other_diagnosis = '';
        this.get_diagnosis_data.patient_other_diagnosis = '';
        // this.diagnosis_id_array = [];
        // this.diagnosis_data_arr = [];
        this.diagnosis_data = {
          diagnosis_name : [],
          diagnosis_name_data: [],
          code : [],
          user_id : 0,
          patient_diagnosis_id: 0,
          patient_id: 0,
          assessment_id : 0,
          consultation_id: 0,
          patient_other_diagnosis : this.patient_other_diagnosis,
          diagnosis_id_arr: [],
          diagnosis_level_arr: [],
          diagnosis_type_arr: [],
          date: defaultDateTime(),
          client_date: new Date(),
          timeZone : getTimeZone()
          };
      }
      }, (err) => {
      //  console.log(err);
      });
    }
    public getPreviousDiagnosis(page = 0) {
      const limit = 10;
      const starting = page * limit;
      this.start = starting;
      this.limit = limit;
      const postData = {
        patient_id : this.patient_id,
        assessment_id : this.assessment_id,
        start : this.start,
        limit : this.limit,
      };
    this.loaderService.display(true);
    this.rest2.getPreviousDiagnosis(postData).subscribe((result) => {
    if (result.status === 'Success') {
      this.loaderService.display(false);
      this.diagnosis_date = result.data.DIAGNOSIS_DATE;
      this.previous_diagnosis = result.data;
      this.collection = result['total_count'];
      const i = this.previous_diagnosis.length;
      this.index = i + 5;
    } else {
      this.previous_diagnosis = [];
      this.loaderService.display(false);
    }
    }, (err) => {
    //  console.log(err);
    });
  }
  public addrow(index) {
    this.num_rows[index + 1] = '';
    this.diagnosis_data.diagnosis_level_arr[index + 1] = '';
    this.diagnosis_data.diagnosis_type_arr[index + 1] = '';

  }
  public deleterow(index) {
    this.num_rows.splice(index, 1);
    this.diagnosis_data.diagnosis_name_data.splice(index, 1);
    this.diagnosis_data.diagnosis_name.splice(index, 1);
    this.diagnosis_data.code.splice(index, 1);
    this.diagnosis_data.diagnosis_level_arr.splice(index, 1);
    this.diagnosis_data.diagnosis_type_arr.splice(index, 1);
    this.diagnosis_data.diagnosis_id_arr.splice(index, 1);
    this.getEvent()
  }

  diagnosis_search = (text$: Observable<string>) =>
  text$.pipe(
  debounceTime(500),
    distinctUntilChanged(),

    tap(() => this.searching = true),
    switchMap(term =>
    this.rest2.diagnosis_search(term).pipe(
      tap(() => this.searchFailed = false),
      catchError(() => {
        this.searchFailed = true;
        return of(['']);
      })
      )
  ),
  tap(() => this.searching = false)
  )
  formatter = (x: {NAME: String, DIAGNOSIS_ID: Number, CODE: String }) => x.NAME;
}
