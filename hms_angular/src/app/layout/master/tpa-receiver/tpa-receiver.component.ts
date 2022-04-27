import { Component, OnInit, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
@Component({
  selector: 'app-tpa-receiver',
  templateUrl: './tpa-receiver.component.html',
  styleUrls: ['./tpa-receiver.component.scss','../master.component.scss']
})
export class TpaReceiverComponent implements OnInit {

  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  public p = 50;
  public now = new Date();
  public date: any;
  public collection: any = '';
  page = 1;
  public appdata = {
   tpa_receiver: '',
   tpa_receiver_id: '',
   tpa_classification: '',
   tpa_link_id: '',
   tpa_status: 1,
   search: '',
   tpa_id: ''
  };
  user_rights: any;
  user: any;
  notifier: NotifierService;
  public tpa_list: any = [];
  start: number;
  limit: number;
  public get_tpa: any = [];
  public Tpa_id: '';
  public status: string;
  constructor(private loaderService: LoaderService , public notifierService: NotifierService, public rest: ConsultingService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getTpa();
    this.formatDateTime ();
  }
  public selectStatus(val) {
    this.appdata.tpa_status = val;
  }
  public savetpa() {
    if (this. appdata.tpa_receiver === '') {
      this.notifier.notify( 'error', 'Please Enter TPA receiver Name!' );
    } else if (this. appdata.tpa_classification === '') {
      this.notifier.notify( 'error', 'Please Enter Classification!' );
    } else if (this. appdata.tpa_link_id === '') {
      this.notifier.notify( 'error', 'Please Enter Link id' );
    } 
    else {
    const postData = {
      tpa_id: this.appdata.tpa_id,
      user_id: this.user.user_id,
      tpa_eclaim_link_id: this.appdata.tpa_link_id,
      tpa_name: this.appdata.tpa_receiver,
      tpa_classification: this.appdata.tpa_classification,
      tpa_status : this.appdata.tpa_status,
      client_date: this.date
      };
      this.loaderService.display(true);
      this.rest.saveTpa(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify( 'success' , 'TPA receiver details saved successfully..!' );
          this.getTpa();
          this.clearForm();
        } else {
          this.loaderService.display(false);
          this.notifier.notify( 'error', ' Failed' );
        }
      });
    }

  }
  public getTpa(page= 0) {
    this.status = '';
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      tpa_id: '',
      start: this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
   this.rest.getTpalist(post2Data).subscribe((result: {}) => {
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.tpa_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
  }

  public editTpa(data) {
    this.status = '';
    this.Tpa_id = data.TPA_ID;
    const post2Data = {
      tpa_id: data.TPA_ID

    };

    this.loaderService.display(true);
    this.rest.getTpa(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
       this.loaderService.display(false);
       this.get_tpa = result['data'];
       this.appdata.tpa_id = this.get_tpa.TPA_ID;
       this.appdata.tpa_link_id = this.get_tpa.TPA_ECLAIM_LINK_ID;
       this.appdata.tpa_receiver = this.get_tpa.TPA_NAME;
       this.appdata.tpa_receiver_id = this.get_tpa.TPA_ID;
       this.appdata.tpa_classification = this.get_tpa.TPA_CLASSIFICAION;
       this.appdata.tpa_status = this.get_tpa.TPA_STATUS;
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
      tpa_id: '',
      start: this.start,
      limit : this.limit,
      search_text: this.appdata.search
    };
    this.loaderService.display(true);
   this.rest.getTpalist(post2Data).subscribe((result: {}) => {
     this.status = result['status'];
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.tpa_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
}
  public clearForm() {
    this.appdata = {
      tpa_receiver: '',
      tpa_receiver_id: '',
      tpa_classification: '',
      tpa_link_id: '',
      tpa_status: 1,
      search: '',
      tpa_id: ''
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
