import { Component, OnInit, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
import { formatTime, formatDateTime, formatDate,defaultDateTime } from '../../../shared/class/Utils';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
@Component({
  selector: 'app-sample-collection',
  templateUrl: './sample-collection.component.html',
  styleUrls: ['./sample-collection.component.scss']
})
export class SampleCollectionComponent implements OnInit {

  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  public p = 50;
  public now = new Date();
  public date: any;
  public collection: any = '';
  public types: any = [];
  page = 1;
  public appdata = {
    mrno: '',
    collection_type: 1,
   search: '',
   collection_id: '',
   sel_pay_type: '',
   collected_date:'',
   patient_no:'',
   doctor:'',
   sample_type:'',
   test:'',
   status:'',
   remarks:''
  };
  user_rights: any;
  user: any;
  notifier: NotifierService;
  public collection_list: any = [];
  start: number;
  limit: number;
  public p_no: any=[];
  searching = false;
  searchFailed = false;
  public patient_list : any = [0];
  public get_collection: any = [];
  public dr_list : any = [];
  public type_list : any = []; 
  public test_list : any = [];
  public collected_date: any;
  public Collection_id: '';
  public p_number = ''; 
  public status: string;
  constructor(private loaderService: LoaderService , public notifierService: NotifierService, public rest: ConsultingService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getCollection();
    this.formatDateTime ();
    this.getDropdowns();
    this.getDropdownss();
    this.getdroptest();
    this. appdata.collected_date = moment(new Date()).format('YYYY-MM-DD')
    
  }
  public selectStatus(val) {
    this.appdata.collection_type = val;
  }
  public format_date(time)
  {
    return formatDate(time)
  }

  public edit(data) {
    console.log(data);
    this.appdata.mrno = data.MR_NO;
    this.appdata.collection_id = data.SAMPLE_ID;
    let val=[{"OP_REGISTRATION_NUMBER" : data.OP_REGISTRATION_NUMBER,"NAME" : data.FIRSTNAME}];
    this.p_no = data;
    this.p_number = data.OP_REGISTRATION_ID;
    this.appdata.status = data.CURRENT_STATUS;
    this.appdata.remarks = data.RESULT_REMARKS;
    this.appdata.doctor = data.DOCTOR_ID;
    this.lab();
   // this.appdata.doctor = data.DOCTOR_ID;
   this.appdata.sample_type = data.SAMPLE_TYPE_ID;
   this.appdata.test = data.TEST_ID;
    this.appdata.collected_date = moment(data.SAMPLE_COLLECTED_DATE).format('YYYY-MM-DD');
  }

  public savecollection() {
    if (this. appdata.mrno === '') {
      this.notifier.notify( 'error', 'Please Enter MR.No!' );
    } 
     else if (this. appdata.collected_date === '') {
      this.notifier.notify( 'error', 'Please Enter Collected Date!' );
    }  else if (this.p_number === '') {
      this.notifier.notify( 'error', 'Please Select Patient Number !' );
    }  else if (this. appdata.doctor === '') {
      this.notifier.notify( 'error', 'Please Select Doctor!' );
    }  else if (this. appdata.sample_type === '') {
      this.notifier.notify( 'error', 'Please Select Sample Type!' );
    }  else if (this. appdata.test === '') {
      this.notifier.notify( 'error', 'Please Select Test Name!' );
    } 
    // else if (this. appdata.status === '') {
    //   this.notifier.notify( 'error', 'Please Select Status!' );
    // } 
    else {
    const postData = {
      collection_id: this.appdata.collection_id,
      user_id: this.user.user_id,
      mrno: this.appdata.mrno,
     // sel_pay_type: this.appdata.sel_pay_type,
      collected_date: moment(this.appdata.collected_date).format('YYYY-MM-DD'),
      p_number: this.p_number,
      doctor: this.appdata.doctor,
      sample_type: this.appdata.sample_type,
      test: this.appdata.test,
     // status: this.appdata.status,
      //collection_type : this.appdata.collection_type,
      remarks : this.appdata.remarks,
      };
      this.loaderService.display(true);
      this.rest.saveCollection(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify( 'success' , result['msg'] );
          this.getCollection();
          this.clearForm();
        } else {
          this.loaderService.display(false);
          this.notifier.notify( 'error',  result['msg'] );
        }
      });
    }

  }

  public clearForm() {
    this.appdata = {
      mrno: '',
      collection_type: 1,
     search: '',
     collection_id: '',
     sel_pay_type: '',
     collected_date:'',
     patient_no:'',
     doctor:'',
     sample_type:'',
     test:'',
     status:'',
     remarks:''
    };
     this.p_number='';
     this.p_no='';
     this. appdata.collected_date = moment(new Date()).format('YYYY-MM-DD')
     this.getdroptest();
  }

  public getToday() 
  {
    return moment(new Date()).format('YYYY-MM-DD')
 }

public getdroptest() {
  const myDate = new Date();
  this.loaderService.display(true);
  this.rest.getdroptest().subscribe((data: {}) => {
    this.loaderService.display(false);
    if (data['status'] === 'Success') {
      // if (data['test_list']['status'] === 'Success') {
      //   this.test_list = data['test_list']['data'];
      // }
      if (data['type_list']['status'] === 'Success') {
        this.type_list = data['type_list']['data'];
      }
      //if(this.appdata.mrno == ''){
        if (data['mr_no']['status'] === 'Success') {
            this.appdata.mrno = data['mr_no']['data'];
        }
      //}
     
    }
  });
}
public set_item($event) {
  console.log($event);
  const item = $event.item;
  this.p_no = item;
  this.p_number = item.OP_REGISTRATION_ID;
  this.lab();
 }
// public getno($event) {
//   this.p_no = $event;
//  }

 public lab(){
  this.test_list = [];
  const post2Data = {
    collected_date: this.appdata.collected_date,
      p_number: this.p_number,
      doctor: this.appdata.doctor,
  };
  this.loaderService.display(true);
 this.rest.getlab(post2Data).subscribe((result: {}) => {
   if (result['status'] === 'Success') {
    this.loaderService.display(false);
    this.test_list = result['data'];
   }
   this.loaderService.display(false);
 });
 }


cptsearch = (text$: Observable<string>) =>
text$.pipe(
 debounceTime(500),
  distinctUntilChanged(),

  tap(() => this.searching = true),
  switchMap(term =>
   this.rest.getPatientList(term).pipe(

     tap(() => this.searchFailed = false),

     catchError(() => {
       this.searchFailed = true;
       return of(['']);
     })

     )

 ),
 tap(() => this.searching = false)

)
formatter = (x: {NAME: String, OP_REGISTRATION_NUMBER: String}) => x.OP_REGISTRATION_NUMBER;

 public getDropdowns() {
  const myDate = new Date();
  this.loaderService.display(true);
  this.rest.getOpDropdowns().subscribe((data: {}) => {
    this.loaderService.display(false);
    if (data['status'] === 'Success') {
      if (data['type']['status'] === 'Success') {
        this.types = data['type']['data'];
      }
     
    }
  });
}
public getDropdownss() {
  const myDate = new Date();
  this.loaderService.display(true);
  this.rest.getDropdowns().subscribe((data: {}) => {
    this.loaderService.display(false);
    if (data['status'] === 'Success') {
      if(data['doctors_list']["status"] == 'Success')
        {
          this.dr_list = data['doctors_list']['data'];
        }
    }
  });
}

  public getCollection(page= 0) {
    this.status = '';
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      collection_id: '',
      start: this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
   this.rest.getCollectionlist(post2Data).subscribe((result: {}) => {
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.collection_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
  }

  // public editCollection(data) {
  //   this.status = '';
  //   this.Collection_id = data.TEST_ID;
  //   const post2Data = {
  //     collection_id: data.TEST_ID

  //   };

  //   this.loaderService.display(true);
  //   this.rest.getCollection(post2Data).subscribe((result: {}) => {
  //     if (result['status'] === 'Success') {
  //      this.loaderService.display(false);
  //      this.get_collection = result['data'];
  //      this.appdata.collection_id = this.get_collection.SAMPLE_ID;
  //      this.appdata.mrno = this.get_collection.MR_NO;
  //      this.appdata.collection_type = this.get_collection.COLLECTION_TYPE;
  //     }
  //     this.loaderService.display(false);
  //   });

  //   window.scrollTo(0, 0);
  // }

public getSearchlist(page= 0) {
  const limit = 100;
    this.start = 0;
    this.limit = limit;
    const post2Data = {
      collection_id: '',
      start: this.start,
      limit : this.limit,
      search_text: this.appdata.search
    };
    this.loaderService.display(true);
   this.rest.getCollectionlist(post2Data).subscribe((result: {}) => {
     this.status = result['status'];
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.collection_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
}

  
  public clear_search() {
  if (this.appdata.search !== '') {
  this.clearForm();
  this.status = '';
  // this.getCPTlist();
}
}
public formatDateTime () {
    if (this.now ) {
      this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
}
