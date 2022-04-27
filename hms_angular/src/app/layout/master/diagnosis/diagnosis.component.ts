import { Component, OnInit,ViewChild, Input, Output, EventEmitter } from '@angular/core';
import { routerTransition } from '../../../router.animations';
import { NotifierService } from 'angular-notifier';
import { NgModule } from '@angular/core';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
import { Router } from '@angular/router';
import { LoaderService } from '../../../shared';
import { DoctorsService, ConsultingService } from '../../../shared/services';
import { from, Observable, of } from 'rxjs';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
@Component({
  selector: 'app-diagnosis',
  templateUrl: './diagnosis.component.html',
  styleUrls: ['./diagnosis.component.scss','../master.component.scss'],
  animations: [routerTransition()]
})
export class DiagnosisComponent implements OnInit {
 constructor(private loaderService:LoaderService ,public rest:DoctorsService,public rest2: ConsultingService,notifierService: NotifierService) {
  this.notifier = notifierService;
}
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() vital_values: any = [];
  @Input() id: string;
  @Input() maxSize: number;
  @Output() pageChange: EventEmitter<number>;
  private notifier: NotifierService
  public collectionSize = '';
  public user_rights: any= [];
  public diagnosis_list : any = [];
  public diagnosis_id:'';
  public user_data: any = {};
  public show: boolean;
  public loading = false;
  public now = new Date();
  public date:any;
  start: number;
  limit: number;
  public p = 50;
  public collection:any= '';
  page = 1;
  index: number;
  public diagnosis_data: any = {
    diagnosis_name : '',
    diagnosis_order : '',
    diagnosis_description : '',
    diagnosis_code : '',
    user_id :'',
    search_diagnosis:''
 };
  status: any;
  model: any;
  searching = false;
  searchFailed = false;
  ngOnInit() {
    this.getDiagnosisList();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.diagnosis_data.user_id = this.user_data.user_id;
    this.formatDateTime ();
  }
  public save_diagnosis() {
    if(this.diagnosis_data.diagnosis_name === '') {
      this.notifier.notify( 'error', 'Please Enter Diagnosis Name!' );
    } else if (this.diagnosis_data.diagnosis_code === '') {
      this.notifier.notify( 'error', 'Please Enter Diagnosis code!' );
    } else if (!(this.diagnosis_data.diagnosis_description)) {
      this.notifier.notify( 'error', 'Please Enter the Diagnosis Description!' );
    } else if (!(this.diagnosis_data.diagnosis_order)) {
      this.notifier.notify( 'error', 'Please Enter Diagnosis order!' );
    } else {
      const postData = {
        diagnosis_id: this.diagnosis_id,
        diagnosis_name: this.diagnosis_data.diagnosis_name,
        diagnosis_description: this.diagnosis_data.diagnosis_description,
        diagnosis_order: this.diagnosis_data.diagnosis_order,
        diagnosis_code: this.diagnosis_data.diagnosis_code,
        client_date: this.date
      };
      this.loaderService.display(true);
      this.rest.save_diagnosis(postData).subscribe((result) => {
        window.scrollTo(0, 0);
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify( 'success', "Diagnosis details saved successfully..!" );
          this.getDiagnosisList();
          this.clearDiagnosis();
        } else {
          this.notifier.notify( 'error', result.msg );
          this.loaderService.display(false);
        }
      });
    }
  }
  public editDiagnosis(diagno_data) {
    this.diagnosis_data.diagnosis_name = diagno_data.NAME;
    this.diagnosis_data.diagnosis_code = diagno_data.CODE;
    this.diagnosis_data.diagnosis_description = diagno_data.DESCRIPTION;
    this.diagnosis_data.diagnosis_order = diagno_data.LIST_ORDER;
    this.diagnosis_id = diagno_data.DIAGNOSIS_ID;
    window.scrollTo(0, 0);
  }
  // public getDiagnosis() {
  //   const post2Data = {
  //     diagnosis_id: ''
  //   };
  //   this.loaderService.display(true);
  //   this.rest.getDiagnosisList(post2Data).subscribe((result: {}) => {
  //    if (result['status'] === 'Success') {
  //       this.diagnosis_list = result['data'];
  //       this.loaderService.display(false);
  //    }
  //    this.loaderService.display(false);
  //  });
  // }
  public clearDiagnosis() {
    this.diagnosis_data = {
      diagnosis_name : '',
      diagnosis_order : '',
      diagnosis_description : '',
      diagnosis_code : '',
      user_id : '',
      search_diagnosis : ''
   };
  }
  getDiagnosisList(page = 0) {
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const postData = {
      start : this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
    // window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest2.listDiagnosis(postData).subscribe((result: {}) => {
     if (result['status'] === 'Success') {
        this.diagnosis_list = result['data'];
        this.collection = result['total_count'];

        this.loaderService.display(false);
     } else {
        this.loaderService.display(false);
        this.collection = 0;
     }

   });
  }
  getsearchlist(_page = 0) {

    const limit = 100;
        this.start = 0;
        this.limit = limit;
    const postData = {
      start : this.start,
      limit : this.limit,
      search_text : this.diagnosis_data.search_diagnosis,
      diagnosis_id : '0'
    };
    this.loaderService.display(true);
    window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest2.listDiagnosis(postData).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.status = result['status'];
          this.diagnosis_list = result['data'];
          this.collection = result['total_count'];
          const i = this.diagnosis_list.length;
          this.index = i + 5;
          this.loaderService.display(false);
      } else {
          this.loaderService.display(false);
          this.collection = 0;
          this.status = result['status'];
     }
    });
  }

  public formatDateTime () {
    if (this.now ) {
      this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
  getlength($event){
    if (this.diagnosis_data.search_diagnosis.length > 2) {
      this.getsearchlist($event);
    }
  }
  public clear_search() {
    if (this.diagnosis_data.search_diagnosis !== '') {
    this.clearDiagnosis();
    this.getDiagnosisList();
    }
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
