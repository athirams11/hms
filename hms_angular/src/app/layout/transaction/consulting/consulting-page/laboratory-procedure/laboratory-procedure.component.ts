import { Component, OnInit, Input, SimpleChanges, OnChanges, NgModule } from '@angular/core';
import { ConsultingService, NursingAssesmentService, DoctorsService} from './../../../../../shared/services';
import { AppSettings } from './../../../../../app.settings';
import {DatePipe, CommonModule, JsonPipe } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import { FormGroup, FormControl } from '@angular/forms';
import * as moment from 'moment';
import { NgxLoadingComponent }  from 'ngx-loading';
import { LoaderService } from '../../../../../shared';
import { formatTime, formatDateTime, formatDate } from './../../../../../shared/class/Utils';
import { SelectDropDownModule } from 'ngx-select-dropdown';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import { when } from 'q';
@Component({
  selector: 'app-laboratory-procedure',
  templateUrl: './laboratory-procedure.component.html',
  styleUrls: ['./laboratory-procedure.component.scss']
})
export class LaboratoryProcedureComponent implements OnInit {
  public laboratory: any = 
    [{
      laboratory_cptname:'',
      laboratory_cptcode: '',
      result_data:{
      result_name : [''],
      result_value: [''],
      result_unit: [''],
      }
    }]
  ;
  public result_data_arr :any =[''];
  public laboratory_data : any = [''];
  // public laboratory={
  //   laboratory_cptname:'',
  //   laboratory_cptcode: ''
  // }
  public laboratory_cptname:'';
  public laboratory_cptcode: '';
  public user_id : '';
  public table_data:any=[''];
  public table={
    // table_id:[],
    result_name : [''],
    result_value: [''],
    result_unit: [''],
    };
    i=0;
  // public result_data = {
  //   result_name : [''],
  //   result_value: [''],
  //   result_unit: [''],
  //   table:[]
  // };
  // public table:any=[];
  public index;
  public result_index;
  searchFailed = false;
  public notifier: NotifierService;
  public td:any=[];
  constructor(public dropdown: SelectDropDownModule, private loaderService: LoaderService, public datepipe: DatePipe, private router: Router, public rest2: ConsultingService, public rest: NursingAssesmentService, notifierService: NotifierService, public rest1: DoctorsService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.laboratory_data =this.laboratory;
   }
  
  public addCptrow(index) {
    this.laboratory[index + 1] ='';
    //console.log(" i "+index);
    this.laboratory.result_data[index+1]='';
    // this.laboratory.laboratory_cptname[index+1]='';
    // this.laboratory.laboratory_cptcode[index+1]='';
    
  //   this.table_data = this.table;
  //   var laboratory_data = [];
  //   console.log("this.table_data  "+JSON.stringify(this.table_data)); 
  //   for(let data in this.table) {
  //   console.log("this.table_data  "+JSON.stringify(this.table_data)); 
  //   this.laboratory_data_arr[index + 1] = '';
  //   // this.result_data_arr[index + 1] = '';
  //   // this.table[index + 1] = '';
  //   // this.laboratory_data[this.index+index +1]='';    
  //   laboratory_data.push( {
  //     RESULT_NAME:this.table_data.result_name,
  //     RESULT_VALUE:this.table_data.result_value,
  //     RESULT_UNIT:this.table_data.result_unit,
  //     CPT_NAME:this.laboratory_cptname,
  //     CPT_CODE:this.laboratory_cptcode,
  //   });
  //   this.laboratory_data=laboratory_data;
  // //    this.i+1;
  // //  console.log("i  "+this.i);
  //  console.log("1111111111");
   
  // }
}
  public deleteCptrow(index) {
    this.laboratory. splice(index, 1);
    // this.laboratory_data.laboratory_cptcode.splice(index, 1);
    // this.laboratory_data.laboratory_cptname.splice(index, 1);
    // this.laboratory_data.table.table_id.splice(index, 1);
    // this.result_data_arr.splice(index, 1);
  }
 
  public addResultrow(index,i,table) {
    this.index = index;
      this.result_data_arr[index + 1] = '';
      this.table[index + 1] = '';
      // this.laboratory_data.table.table_id[i+1]=i;
      this.result_index = index;
      this.table.result_name[index+ 1] = '';
      this.table.result_value[index+ 1] = '';
      this.table.result_unit[index+1] = '';
//       console.log("laboratory_data.table  "+ JSON.stringify(this.laboratory_data.table));
}
  public deleteResultrow(index,i) {
    this.result_data_arr. splice(index, 1);
    this.table.result_name.splice(index, 1);
    this.table.result_value. splice(index, 1);
    this.table.result_unit. splice(index, 1);
    // this.laboratory_data.table.result_name.splice(index, 1);
  }
}
