import { Component, OnInit, ViewChild, Input, Output, EventEmitter } from '@angular/core';
import { routerTransition } from '../../../router.animations';
import { NotifierService } from 'angular-notifier';
 import { NgModule } from '@angular/core';
import {DatePipe, CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { DoctorsService, DrConsultationService, ConsultingService } from '../../../shared/services';
import { HttpClient } from '@angular/common/http';
import { HttpErrorResponse } from '@angular/common/http';
import { LoaderService } from '../../../shared';
import { Observable, of } from 'rxjs';
import * as moment from 'moment';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
@Component({
  selector: 'app-current-procedural-terminology',
  templateUrl: './current-procedural-terminology.component.html',
  styleUrls: ['./current-procedural-terminology.component.scss','../master.component.scss'],
})
export class CurrentProceduralTerminologyComponent implements OnInit {
  discount_sites: any;

 constructor(private loaderService: LoaderService , 
  private httpService: HttpClient, 
  public rest: DoctorsService, 
  private rest2: DrConsultationService, 
  notifierService: NotifierService, 
  private rest3: ConsultingService) 
  {
    this.notifier = notifierService;
  }
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() vital_values: any = [];
  @Input() id: string;
  @Input() maxSize: number;
  @Output() pageChange: EventEmitter<number>;
  public collectionSize = '';
  public pageSize = 100;
  public  user_rights: any = {};
  private notifier: NotifierService;
  public cpt_list: any = [];
  public dental_procedure: any = [];
  public cpt_groups: any = [];
  public diagnosis_id: '';
  public user_data: any = {};
  public cptlist_data: any = [];
  public current_procedural_code_id = 0;
  public loading = false;
  public cpt_code_id: any;
  public index: number;
  public start: any;
  public limit: any;
  public search: any;
  public status: any;
  public i = 0;
  public now = new Date();
  public date: any;
  public cpt_group: any = {};
  file: File;
  fileName: any;
  showselect :boolean = false;
  public splitted: any = [];
  public  myfile: any;
  public cpt_data: any = {
    cpt_group: '',
    cpt_code : '',
    allias_name : '',
    procedure_name : '',
    procedure_description: '',
    excel : '',
    cpt_rate: '',
    search_cpt: '',
    cpt_type:'',
    dental_procedure : 0,
    discount_site : 0
  };
  p = 50;
  public collection: number = 0;
  page = 1;
  model: any;
  searching = false;
  searchFailed = false;
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getDropdowns();
  }
  save_cpt () {
     if (this.cpt_data.excel !== '') {
      this.saveExel();
    } 

    else if (!(this.cpt_data.cpt_type)) {
      this.notifier.notify( 'error', 'Please select CPT type!' );
    } 
    else if (this.cpt_data.cpt_code === '') {
     this.notifier.notify( 'error', 'Please enter CPT code!' );
    } 
    else if (this.cpt_data.allias_name === '') {
      this.notifier.notify( 'error', 'Please enter allias name!' );
    } 
    else if (!(this.cpt_data.procedure_name)) {
      this.notifier.notify( 'error', 'Please enter procedure name!' );
    } 
    else if (this.cpt_data.cpt_type == 37 && this.cpt_data.dental_procedure == 0) {
      this.notifier.notify( 'error', 'Please select dental procedure!' );
    } 
    else if (this.cpt_data.cpt_group === '') {
       this.notifier.notify( 'error', 'Please select CPT group!' );
    } 
    else if (!(this.cpt_data.cpt_rate)) {
      this.notifier.notify( 'error', 'Please enter Rate!' );
    } 
    else if (!(this.cpt_data.procedure_description)) {
      this.notifier.notify( 'error', 'Please enter description!' );
    }
    else if(this.showselect && this.cpt_data.discount_site == 0)
    {
      this.notifier.notify( 'error', 'Please select discount site!' );
    }
    else {
      if(!this.showselect)
      {
        this.cpt_data.discount_site = 0
      }
      const postData = {
        current_procedural_code_group : this.cpt_data.cpt_group,
        current_procedural_code_id : this.current_procedural_code_id,
        current_procedural_code: this.cpt_data.cpt_code,
        current_procedural_code_name: this.cpt_data.allias_name,
        current_procedural_code_alias_name: this.cpt_data.procedure_name,
        current_procedural_code_description: this.cpt_data.procedure_description,
        current_procedural_code_type:this.cpt_data.cpt_type,
        current_procedural_code_rate : this.cpt_data.cpt_rate,
        current_dental_procedure : this.cpt_data.dental_procedure,
        current_procedure_discount_site : this.cpt_data.discount_site,
        client_date: this.formatDateTime(this.date)
      };
      this.loaderService.display(true);
      this.rest.saveCPT(postData).subscribe((result) => {
        window.scrollTo(0, 0);
        if (result['status'] === 'Success') {
          this.loaderService.display(false);

          this.getCPTlist();
          this.notifier.notify( 'success', 'CPT details saved successfully...!' );

          this.clearCPT();

        } else {
          this.notifier.notify( 'error', ' Failed' );
          this.loaderService.display(false);
        }
      });
    }
  }

  getCPTlist(page = 0) {
    const limit = 50;
    this.status = '';
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const postData = {
      start : this.start,
      limit : this.limit,
      current_procedural_code_id : '0',
      search_text : this.cpt_data.search_cpt
    };
    this.loaderService.display(true);
    this.rest.getCPTlist(postData).subscribe((result: {}) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.cptlist_data = result['data'];
        this.collection = result['total_count'];
        const i = this.cptlist_data.length;
        this.index = i + 5;
        this.loaderService.display(false);
      } else {
          this.loaderService.display(false);
          this.collection = 0;
      }
    });
  }

  getCPTsearchlist(page = 0) {
    const limit = 100;
    this.start = 0;
    this.limit = limit;
    const postData = {
      start : this.start,
      limit : this.limit,
      current_procedural_code_id : '0',
      search_text : this.cpt_data.search_cpt,
    };
    this.loaderService.display(true);
    this.rest.getCPTlist(postData).subscribe((result: {}) => {
      this.status = result['status'];
      if (result['status'] === 'Success') {
          this.cptlist_data = result['data'];
          this.collection = result['total_count'];
          const i = this.cptlist_data.length;
          this.index = i + 5;
          this.loaderService.display(false);
      } 
      else {
        this.loaderService.display(false);
        this.collection = 0;
      }
    });
  }


  public editCpt(data, i) {
    this.status = '';
    const postData = {
      current_procedural_code_id : data.CURRENT_PROCEDURAL_CODE_ID
    };
    this.loaderService.display(true);
    window.scrollTo(0, 0);
    this.rest.getCPT(postData).subscribe((result: {}) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        const cptlist_data = result['data'];
        this.cpt_data.cpt_code = cptlist_data.PROCEDURE_CODE;
        this.cpt_data.cpt_type = cptlist_data.CPT_CATEGORY_CODE;
        this.cpt_data.dental_procedure = cptlist_data.DENTAL_PROCEDURE_ID;
        this.cpt_data.allias_name = cptlist_data.PROCEDURE_CODE_NAME;
        this.cpt_data.procedure_name = cptlist_data.PROCEDURE_CODE_ALIAS_NAME;
        this.cpt_data.procedure_description = cptlist_data.PROCEDURE_CODE_DESCRIPTION;
        this.current_procedural_code_id = cptlist_data.CURRENT_PROCEDURAL_CODE_ID;
        this.cpt_data.cpt_rate = cptlist_data.CPT_RATE;
        this.cpt_data.cpt_group = cptlist_data.CPT_GROUP_ID;
        if(cptlist_data.DISCOUNT_SITE_ID > 0)
        {
          this.showselect = true
        }
        this.cpt_data.discount_site = cptlist_data.DISCOUNT_SITE_ID;
        
      } 
      else {
      }

    });
  }
  public getDropdowns() {
    this.loaderService.display(true);   
    this.rest.getcpt_group().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        this.getCPTlist();
        if (data['CPT_CATEGORYS']['status'] === 'Success') {
          this.cpt_list = data['CPT_CATEGORYS']['data'];
        }
        if (data['CPT_GROUP']['status'] === 'Success') {
          this.cpt_groups = data['CPT_GROUP']['data'];
        }
        if (data['DENTAL_PROCEDURE']['status'] === 'Success') {
          this.dental_procedure = data['DENTAL_PROCEDURE']['data'];
        }
        if (data['DISCOUNT_SITE']['status'] === 'Success') {
          this.discount_sites = data['DISCOUNT_SITE']['data'];
        }
        
      } 
      else {
        this.getCPTlist();
      }
    });
  }
  exportAsXLSX($event: any): void {
    const file = $event.target.files[0];
    this.fileName = file.name;
    if (this.validateFile(this.fileName)) {
      this.readThis($event.target);
      this.notifier.notify( 'success', 'Click save to upload the document' );

    } 
    else {
    this.notifier.notify( 'error', 'Documet is not in the expected format' );
    }
  }

  public saveExel() 
  {
    this.index = this.index + 1;
    this.splitted = this.myfile.split(',');
    const postData = {
      assessment_id: this.assessment_id,
      patient_id: this.patient_id,
      base64_file_str : this.splitted[1],
      file_name : this.fileName,
      refer_id : this.index,
      module_id : 43,
      client_date: this.date
    };
    this.rest.saveDocuments(postData).subscribe((result) => {
      window.scrollTo(0, 0);
      if (result['status'] === 'Success') 
      {
        this.notifier.notify( 'success', result.response );
        this.clearCPT();
      } 
      else {
        this.notifier.notify( 'error', ' Failed' );
      }
    });
  }

  validateFile(name: String) 
  {
    const ext = name.substring(name.lastIndexOf('.') + 1);
    if (ext === 'xls') {
      return true;
    } else if (ext === 'xlsx') {
      return true;
    } else if (ext === 'xlsm') {
      return true;
    } else if (ext === 'xlsb') {
      return true;
    } else if (ext === 'xltx') {
      return true;
    } else if (ext === 'xltm') {
      return true;
    } else if (ext === 'xlt') {
      return true;
    } else if (ext === 'xml') {
      return true;
    } else if (ext === 'xlam') {
      return true;
    } else if (ext === 'xla') {
      return true;
    } else if (ext === 'xlw') {
      return true;
    } else if (ext === 'xlr') {
      return true;
    } else {
      return false;
    }
  }

  readThis(inputValue: any): void {
    const file: File = inputValue.files[0];
    const myReader: FileReader = new FileReader();
    myReader.onloadend = (e) => {
      this.myfile = myReader.result;
    };
    myReader.readAsDataURL(file);
  }

  public clearCPT() {
    this.cpt_data = {
      cpt_code : '',
      allias_name : '',
      procedure_name : '',
      procedure_description : '',
      cpt_group : '',
      cpt_rate: '',
      excel : '',
      search_cpt: '',
      cpt_type:'',
      cpt_code_id:'',
      dental_procedure : 0,
      discount_site : 0
    };
    this.current_procedural_code_id = 0;
    this.showselect = false
  }

 cptsearch = (text$: Observable<string>) =>
 text$.pipe(
   debounceTime(500),
    distinctUntilChanged(), tap(() => this.searching = true),
    switchMap(term =>
     this.rest3.master_cptsearch(term).pipe(
       tap(() => this.searchFailed = false),catchError(() => {
         this.searchFailed = true;
         return of(['']);
       }) )
    ),
    tap(() => this.searching = false)
  )
  formatter = (x: {PROCEDURE_CODE_NAME: String, CURRENT_PROCEDURAL_CODE_ID: Number, PROCEDURE_CODE_CATEGORY: Number, PROCEDURE_CODE: String }) => x.PROCEDURE_CODE_NAME;

  public set_item($event) {
    const item = $event.item;
    this.cptlist_data = [];
    this.cptlist_data.push(item);
  }
  public clear_search() 
  { 
    if (this.cpt_data.search_cpt !== '') {
      this.clearCPT();
      this.getCPTlist(1);
      this.status = '';
    }
  }
  public formatDateTime (data) {
    if (this.now ) {
      this.date =  moment(data, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
  getlength($event){
    if (this.cpt_data.search_cpt.length >2) {
      this.getCPTsearchlist($event);
    }
  }
  public validateNumber(event) {
    const keyCode = event.keyCode;  
    // console.log(keyCode)  
    const excludedKeys = [8, 37, 39, 46];   
     if (!((keyCode > 48 && keyCode < 57) ||
      (keyCode >= 96 && keyCode <= 105 ) ||  ( keyCode == 37  ) || 
      ( keyCode == 110  ) || ( keyCode == 190  ) ||  (excludedKeys.includes(keyCode)))) {
      event.preventDefault();
    }
  }
}

