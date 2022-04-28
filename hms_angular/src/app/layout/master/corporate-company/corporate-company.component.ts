import { Component, OnInit, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService, ReportService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import { DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';

@Component({
  selector: 'app-corporate-company',
  templateUrl: './corporate-company.component.html',
  styleUrls: ['./corporate-company.component.scss']
})
export class CorporateCompanyComponent implements OnInit {


  public p = 50;
  public now = new Date();
  public date: any;
  public collection: any = '';
  page = 1;
  user_rights: any;
  user: any;
  public company_data = {
    company_name: '',
    company_id: '',
    company_address: '',
    company_code: '',
    search_text: '',
    user_id: "",
    company_status: 1,
    client_date: new Date(),
  };

  notifier: NotifierService;
  public company_list: any = [];
  start: number;
  limit: number;
  public companydata: any = [];
  public Tpa_id: '';
  public status: string;
  constructor(private loaderService: LoaderService, public notifierService: NotifierService, public rest: ReportService) {
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    // this.listCorporateCompany();

  }
  public selectStatus(val) {
    this.company_data.company_status = val;
  }
  public saveCorporateCompany() {
    if (this.company_data.company_name === '') {
      this.notifier.notify('warning', 'Please enter company name!');
    }
    // else if (this. company_data.company_address === '') {
    //   this.notifier.notify( 'warning', 'Please Enter Classification!' );
    // } else if (this. company_data.company_code === '') {
    //   this.notifier.notify( 'warning', 'Please Link id' );
    // }
    else {
      this.formatDateTime(this.date);
      this.company_data.user_id = this.user.user_id;
      this.company_data.client_date = this.date
      this.loaderService.display(true);
      this.rest.saveCorporateCompany(this.company_data).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify('success', result.msg);
          // this.listCorporateCompany();
          this.clearForm();
        } else {
          this.loaderService.display(false);
          this.notifier.notify('error', ' Failed');
        }
      });
    }

  }
  public listCorporateCompany(page = 0) {
    this.status = '';
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      company_id: '',
      start: this.start,
      limit: this.limit,
    };
    this.loaderService.display(true);
    this.rest.listCorporateCompany(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.company_list = result['data'];
        this.collection = result['total_count'];
      }
      this.loaderService.display(false);
    });
  }

  public getCorporateCompany(data) {

    const post2Data = {
      company_id: data.CORPORATE_COMPANY_ID

    };

    this.loaderService.display(true);
    this.rest.getCorporateCompany(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.companydata = result['data'];
        this.company_data.company_code = this.companydata.CORPORATE_COMPANY_CODE;
        this.company_data.company_name = this.companydata.CORPORATE_COMPANY_NAME;
        this.company_data.company_id = this.companydata.CORPORATE_COMPANY_ID;
        this.company_data.company_address = this.companydata.CORPORATE_COMPANY_ADDRESS;
        this.company_data.company_status = this.companydata.CORPORATE_STATUS;
      }
      this.loaderService.display(false);
    });

    window.scrollTo(0, 0);
  }

  public getSearchlist(page = 0) {
    const limit = 100;
    this.start = 0;
    this.limit = limit;
    const post2Data = {
      company_id: '',
      start: this.start,
      limit: this.limit,
      search_text: this.company_data.search_text
    };
    this.loaderService.display(true);
    this.rest.listCorporateCompany(post2Data).subscribe((result: {}) => {
      this.status = result['status'];
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.company_list = result['data'];
        this.collection = result['total_count'];
      }
      this.loaderService.display(false);
    });
  }
  public clearForm() {
    this.company_data = {
      company_name: '',
      company_id: '',
      company_address: '',
      company_code: '',
      search_text: '',
      user_id: "",
      company_status: 1,
      client_date: new Date(),
    };
  }
  public clear_search() {
    if (this.company_data.search_text !== '') {
      this.clearForm();
      this.status = '';
      // this.getCPTlist();
    }
  }
  public formatDateTime(data) {
    if (this.now) {
      this.date = moment(data, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
}