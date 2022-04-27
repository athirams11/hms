import { Component, OnInit, Input, Output } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
@Component({
  selector: 'app-ins-payer',
  templateUrl: './ins-payer.component.html',
  styleUrls: ['./ins-payer.component.scss','../master.component.scss']
})
export class InsPayerComponent implements OnInit {
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  public now = new Date();
  public date: any;
  public appdata = {
   ins_payer: '',
   ins_payer_id: 0,
   ins_classification: '',
   ins_link_id: '',
   ins_status: 1,
   search: ''
  };
  user_rights: any;
  user: any;
  public status: string;
  notifier: NotifierService;
  public p = 50;
  public collection: any = '';
  public Inspayer_list: any = [];
  public get_payer: any = [];
  page = 1;
  start: number;
  limit: number;
  constructor(private loaderService: LoaderService , public notifierService: NotifierService, public rest: ConsultingService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getInspayer();
    this.formatDateTime ();
  }
  public selectStatus(val) {
    this.appdata.ins_status = val;
  }
  public save_inspayer() {
    if (this. appdata.ins_payer === '') {
      this.notifier.notify( 'error', 'Please Enter Insurance payer Name!' );
    } else if (this. appdata.ins_classification === '') {
      this.notifier.notify( 'error', 'Please Enter Classification!' );
    } else if (this. appdata.ins_link_id === '') {
      this.notifier.notify( 'error', 'Please Enter Link id' );
    }
    else {
      const postData = {
        user_id: this.user.user_id,
        nsurance_payers_eclaim_link_id: this.appdata.ins_link_id,
        insurance_payers_name: this.appdata.ins_payer,
        insurance_payers_classification: this.appdata.ins_classification,
        insurance_payers_status : this.appdata.ins_status,
        insurance_payers_eclaim_link_id : this.appdata.ins_link_id,
        insurance_payers_id : this.appdata.ins_payer_id,
        client_date: this.date
        };
        this.loaderService.display(true);
        this.rest.saveInsurancepayer(postData).subscribe((result) => {
          if (result['status'] === 'Success') {
            this.loaderService.display(false);
            this.notifier.notify( 'success' , 'Insurance payer details saved successfully..!' );
            // this.getTpa();
            this.clearForm();
          } else {
            this.loaderService.display(false);
            this.notifier.notify( 'error', ' Failed' );
          }
       });
    }
  }

  public getInspayer(page= 0) {
    this.status = '';
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const postData = {

      start: this.start,
      limit : this.limit,

    };
    this.loaderService.display(true);
   this.rest.getInspayerlist(postData).subscribe((result: {}) => {

     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.Inspayer_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
  }

  public getSearchlist(page= 0) {
    const limit = 100;
      this.start = 0;
      this.limit = limit;
      const postData = {
        start: this.start,
        limit : this.limit,
        search_text: this.appdata.search
      };
      this.loaderService.display(true);
     this.rest.getInspayerlist(postData).subscribe((result: {}) => {
      this.status = result['status'];
       if (result['status'] === 'Success') {
        this.loaderService.display(false);
          this.Inspayer_list = result['data'];
          // console.log('this.Inspayer_list  ' + this.Inspayer_list);

          // this.collection = result['total_count'];
       }
       this.loaderService.display(false);
     });
  }

  public editInspayer(data) {
    this.status = '';
    this.appdata.search = '';
    const post2Data = {
      insurance_payers_id: data.INSURANCE_PAYERS_ID

    };

    this.loaderService.display(true);
    this.rest.getInscompany(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
       this.loaderService.display(false);
       this.get_payer = result['data'];
       this.appdata.ins_payer_id = this.get_payer.INSURANCE_PAYERS_ID;
       this.appdata.ins_payer = this.get_payer.INSURANCE_PAYERS_NAME;
       this.appdata.ins_classification = this.get_payer.INSURANCE_PAYERS_CLASSIFICATION;
       this.appdata.ins_status = this.get_payer.INSURANCE_PAYERS_STATUS;
       this.appdata.ins_link_id = this.get_payer.INSURANCE_PAYERS_ECLAIM_LINK_ID;
      }
      this.loaderService.display(false);
    });

    window.scrollTo(0, 0);
  }

  public clearForm() {
    this.appdata = {
      ins_payer: '',
      ins_payer_id: 0,
      ins_classification: '',
      ins_link_id: '',
      ins_status: 1,
      search: ''
     };
  }
  public clear_search() {
  if (this.appdata.search !== '') {
  this.clearForm();
  this.status = '';
}
}
public formatDateTime () {
    if (this.now ) {
      this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
}
