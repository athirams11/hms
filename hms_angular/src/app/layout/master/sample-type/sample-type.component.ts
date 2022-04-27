import { Component, OnInit, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
@Component({
  selector: 'app-sample-type',
  templateUrl: './sample-type.component.html',
  styleUrls: ['./sample-type.component.scss']
})
export class SampleTypeComponent implements OnInit {

  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  public p = 50;
  public now = new Date();
  public date: any;
  public collection: any = '';
  page = 1;
  public appdata = {
   type: '',
   type_status: 1,
   search: '',
   type_id: ''
  };
  user_rights: any;
  user: any;
  notifier: NotifierService;
  public type_list: any = [];
  start: number;
  limit: number;
  public get_type: any = [];
  public Type_id: '';
  public status: string;
  constructor(private loaderService: LoaderService , public notifierService: NotifierService, public rest: ConsultingService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getType();
    this.formatDateTime ();
  }
  public selectStatus(val) {
    this.appdata.type_status = val;
  }
  public savetype() {
    if (this. appdata.type === '') {
      this.notifier.notify( 'error', 'Please Enter Sample Type!' );
    } 
    else {
    const postData = {
      type_id: this.appdata.type_id,
      user_id: this.user.user_id,
      type: this.appdata.type,
      type_status : this.appdata.type_status,
      };
      this.loaderService.display(true);
      this.rest.saveType(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify( 'success' , 'Sample Type saved successfully..!' );
          this.getType();
          this.clearForm();
        } else {
          this.loaderService.display(false);
          this.notifier.notify( 'error', ' Failed' );
        }
      });
    }

  }
  public getType(page= 0) {
    this.status = '';
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      type_id: '',
      start: this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
   this.rest.getTypelist(post2Data).subscribe((result: {}) => {
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.type_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
  }

  public editType(data) {
    this.status = '';
    this.Type_id = data.SAMPLE_TYPE_ID;
    const post2Data = {
      type_id: data.SAMPLE_TYPE_ID

    };

    this.loaderService.display(true);
    this.rest.getType(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
       this.loaderService.display(false);
       this.get_type = result['data'];
       this.appdata.type_id = this.get_type.SAMPLE_TYPE_ID;
       this.appdata.type = this.get_type.TYPE_NAME;
       this.appdata.type_status = this.get_type.STATUS;
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
      type_id: '',
      start: this.start,
      limit : this.limit,
      search_text: this.appdata.search
    };
    this.loaderService.display(true);
   this.rest.getTypelist(post2Data).subscribe((result: {}) => {
     this.status = result['status'];
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.type_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
}
  public clearForm() {
    this.appdata = {
      type: '',
      type_status: 1,
      search: '',
      type_id: ''
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
