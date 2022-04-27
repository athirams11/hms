import { Component, OnInit, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
@Component({
  selector: 'app-lab-test',
  templateUrl: './lab-test.component.html',
  styleUrls: ['./lab-test.component.scss']
})
export class LabTestComponent implements OnInit {

  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  public p = 50;
  public now = new Date();
  public date: any;
  public collection: any = '';
  page = 1;
  public appdata = {
    test: '',
    test_status: 1,
   search: '',
   test_id: ''
  };
  user_rights: any;
  user: any;
  notifier: NotifierService;
  public test_list: any = [];
  start: number;
  limit: number;
  public get_test: any = [];
  public Test_id: '';
  public status: string;
  constructor(private loaderService: LoaderService , public notifierService: NotifierService, public rest: ConsultingService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getTest();
    this.formatDateTime ();
  }
  public selectStatus(val) {
    this.appdata.test_status = val;
  }
  public savetest() {
    if (this. appdata.test === '') {
      this.notifier.notify( 'error', 'Please Enter Test Name!' );
    } 
    else {
    const postData = {
      test_id: this.appdata.test_id,
      user_id: this.user.user_id,
      test: this.appdata.test,
      test_status : this.appdata.test_status,
      };
      this.loaderService.display(true);
      this.rest.saveTest(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify( 'success' , 'Lab Test saved successfully..!' );
          this.getTest();
          this.clearForm();
        } else {
          this.loaderService.display(false);
          this.notifier.notify( 'error', ' Failed' );
        }
      });
    }

  }
  public getTest(page= 0) {
    this.status = '';
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      test_id: '',
      start: this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
   this.rest.getTestlist(post2Data).subscribe((result: {}) => {
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.test_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
  }

  public editTest(data) {
    this.status = '';
    this.Test_id = data.TEST_ID;
    const post2Data = {
      test_id: data.TEST_ID

    };

    this.loaderService.display(true);
    this.rest.getTest(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
       this.loaderService.display(false);
       this.get_test = result['data'];
       this.appdata.test_id = this.get_test.TEST_ID;
       this.appdata.test = this.get_test.TEST_NAME;
       this.appdata.test_status = this.get_test.STATUS;
      }
      this.loaderService.display(false);
    });

    window.scrollTo(0, 0);
  }

public getSearchlist(page= 0) {
  const limit = 100;
    this.start = 0;
    this.limit = limit;
    const post2Data = {
      test_id: '',
      start: this.start,
      limit : this.limit,
      search_text: this.appdata.search
    };
    this.loaderService.display(true);
   this.rest.getTestlist(post2Data).subscribe((result: {}) => {
     this.status = result['status'];
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.test_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
}
  public clearForm() {
    this.appdata = {
      test: '',
      test_status: 1,
      search: '',
      test_id: ''
     };
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
