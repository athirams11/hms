import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { AppSettings } from 'src/app/app.settings';
import { DatePipe } from '@angular/common';
import { LoaderService, ReportService, ExcelService } from 'src/app/shared';
import { Router } from '@angular/router';
import moment from 'moment-timezone';
import * as XLSX from 'xlsx';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from 'src/app/shared/class/Utils';
import { ExportAsService, ExportAsConfig, SupportedExtensions } from 'ngx-export-as';
import { NgbModalOptions, NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
const EXCEL_EXTENSION = '.xlsx';
@Component({
  selector: 'app-cash-report',
  templateUrl: './cash-report.component.html',
  styleUrls: ['./cash-report.component.scss']
})
export class CashReportComponent implements OnInit {
  @ViewChild('cash_table') table: ElementRef;
  private notifier: NotifierService;
  public user_rights: any = {};
  public user_data: any = {};
  public todate = new Date();
  public fromdate = new Date();
  cashlist: any = [];
  doctor: any;
  grand_total: number = 0;
  search_text = ''
  public index: number;
  public start: any;
  public limit: any;
  public search: any;
  public i = 0;
  p: number = 50;
  public collection: number = 0;
  page = 1;
  public collectionSize = '';
  public pageSize = 10;
  status: any;
  public institution = JSON.parse(localStorage.getItem('institution'));
  public logo_path = JSON.parse(localStorage.getItem('logo_path'));
  config: ExportAsConfig = {
    type: 'pdf',
    elementIdOrContent: 'report',
    options: {
      jsPDF: {
        orientation: 'portrait'
      },
      pdfCallbackFn: this.pdfCallbackFn // to add header and footer
    }
  };
  tpa_receiver: any;
  tpa_options: any;
  tpa_data: any;
  tpa_id: number = 0;
  doctor_options: any;
  doctor_data: any;
  doctor_id: number = 0;
  cashier: any;
  cashier_options: any;
  cashier_data: any;
  cashier_id: number = 0;
  corporate_company: any;
  company_options: any;
  company_data: any;
  company_id: number = 0;
  discount_total: number;
  net_total: number;
  pay_type = 0
  status_result = 1;
  patient_type = 1;
  closeResult: string;
  public patient_details: any;
  public invoice_list: any;
  public invoice: any;
  co_ins: any;
  ins_data: any;
  corporate_data: any;
  public gender: any = ['Female', 'Male'];
  public gender_sur: any = ['Ms.', 'Mr.'];
  constructor(private exportAsService: ExportAsService, private modalService: NgbModal, private loaderService: LoaderService, private router: Router, public rest: ReportService, notifierService: NotifierService) {
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.fromdate = defaultDateTime();
    this.todate = defaultDateTime();
    this.listCashReport();
    // this.listTPA();
    this.listDoctor();
    this.listCashier();
    // this.listCorporateCompany();
  }
  public selectType(val) {
    this.pay_type = val
    this.company_data = []
    this.company_id = 0
    this.tpa_data = []
    this.tpa_id = 0
    this.status_result = 0
  }
  public getToday(): string {
    return new Date().toISOString().split('T')[0]
  }
  public formatTime(time) {
    return formatTime(time);
  }
  public formatDate(date) {
    return formatDate(date);
  }
  public formatDateTime(data) {
    return formatDateTime(data);
  }
  exportAsString(type: SupportedExtensions, opt?: string) {
    this.config.elementIdOrContent = '<div> test string </div>';
    this.exportAs(type, opt);
    setTimeout(() => {
      this.config.elementIdOrContent = 'mytable';
    }, 1000);
  }

  exportAs(type: SupportedExtensions, opt?: string) {
    this.config.type = type;
    if (opt) {
      this.config.options.jsPDF.orientation = opt;
    }
    this.exportAsService.save(this.config, 'myFile').subscribe(() => {
      // save started
    });
  }

  pdfCallbackFn(pdf: any) {
    // example to add page number as footer to every page of pdf
    const noOfPages = pdf.internal.getNumberOfPages();
    // for (let i = 1; i <= noOfPages; i++) {
    //   pdf.setPage(i);
    // }
    pdf.text(' <div class="header col-md-12">sfdf<div class="col-md-12 text-center">', pdf.internal.pageSize.getWidth() - 400, pdf.internal.pageSize.getHeight() - 30);


  }
  // pdf.text(' <div class="header col-md-12"><div class="col-md-12 text-center">'+
  //     '<h5 class="name"><b>'+this.institution["INSTITUTION_NAME"]+'</b></h5>'+
  //     '<div style="line-height: 0.5em;"><label>'+this.institution.INSTITUTION_ADDRESS+'</label></div>'+
  //     '<div style="line-height: 0.5em;"><label>'+this.institution.INSTITUTION_CITY+','+this.institution.INSTITUTION_COUNTRY_NAME+',&nbsp;</label></div>'+
  //     '<div style="line-height: 0.5em;"><label>Ph :&nbsp;'+this.institution.INSTITUTION_PHONE_NO+'</label></div>'+
  //     '<div style="line-height: 0.5em;"><label>Email :&nbsp;'+this.institution.INSTITUTION_EMAIL+'</label></div>'+
  //     '</div></div>  <div>&nbsp;</div><div class="clearfix"></div>'+
  //     '<h5 class="text-center">CASH REPORT</h5><br><div>&nbsp;</div>')
  public listCashReport(page = 0) {
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const postData = {
      todate: this.todate,
      fromdate: this.fromdate,
      timeZone: moment.tz.guess(),
      doctor_id: this.doctor_id,
      tpa_id: this.tpa_id,
      cashier_id: this.cashier_id,
      company_id: this.company_id,
      start: this.start,
      limit: this.limit,
      pay_type: this.pay_type

    };
    this.loaderService.display(true);
    this.rest.listCashReport(postData).subscribe((result) => {
      this.loaderService.display(false);
      if (result["status"] == "Success") {
        this.status_result = 1;
        this.cashlist = result["data"];
        var sum = 0;
        var net = 0;
        var disc = 0;
        for (let list of this.cashlist) {
          list["PATIENT_AMOUNT"] = Math.round(list["PATIENT_AMOUNT"])
          list["PAID_BY_PATIENT"] = Math.round(list["PAID_BY_PATIENT"])
          // list["PATIENT_DISCOUNT"] = Math.round(list["PATIENT_DISCOUNT"])
          net = net + parseFloat(list.PATIENT_AMOUNT)
          sum = sum + parseFloat(list.PAID_BY_PATIENT)
          disc = disc + parseFloat(list.PATIENT_DISCOUNT)
        }
        this.grand_total = Math.round(sum)
        this.net_total = Math.round(net)
        this.discount_total = Math.round(disc)
        this.collection = result["total_count"];
        const i = this.cashlist.length;
        this.index = i + 5;
      }
      else {
        this.cashlist = [];
        this.collection = 0;
        this.grand_total = 0;
      }
    }, (err) => {
      console.log(err);
    });
  }
  public cash_ExportToExcel() {
    const ws: XLSX.WorkSheet = XLSX.utils.table_to_sheet(this.table.nativeElement);
    const wb: XLSX.WorkBook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

    /* save to file */
    XLSX.writeFile(wb, 'CashReport_' + new Date().getTime() + EXCEL_EXTENSION);

  }
  public cash_clear_search() {
    this.search_text = '';
    this.listCashReport()
  }
  cash_getSearchlist(page = 0) {
    if (this.search_text.length > 2) {
      const limit = 100;
      this.start = 0;
      this.limit = limit;
      const postData = {
        start: this.start,
        limit: this.limit,
        todate: this.todate,
        fromdate: this.fromdate,
        timeZone: moment.tz.guess(),
        doctor_id: this.doctor_id,
        tpa_id: this.tpa_id,
        cashier_id: this.cashier_id,
        search_text: this.search_text,
        pay_type: this.pay_type
      };
      this.loaderService.display(true);
      // window.scrollTo(0, 0);
      this.rest.listCashReport(postData).subscribe((result) => {
        this.loaderService.display(false);
        if (result['status'] === 'Success') {
          this.patient_type = this.pay_type;
          this.cashlist = result["data"];
          var sum = 0;
          var net = 0;
          var disc = 0;
          for (let list of this.cashlist) {
            list["PATIENT_AMOUNT"] = Math.round(list["PATIENT_AMOUNT"])
            list["PAID_BY_PATIENT"] = Math.round(list["PAID_BY_PATIENT"])
            list["PATIENT_DISCOUNT"] = Math.round(list["PATIENT_DISCOUNT"])
            net = net + parseFloat(list.PATIENT_AMOUNT)
            sum = sum + parseFloat(list.PAID_BY_PATIENT)
            disc = disc + parseFloat(list.PATIENT_DISCOUNT)
          }
          this.grand_total = Math.round(sum)
          this.net_total = Math.round(net)
          this.discount_total = Math.round(disc)
          this.collection = result["total_count"];
          const i = this.cashlist.length;
          this.index = i + 5;
          this.loaderService.display(false);
          this.status = result['status'];
          this.listTPA();
          this.listDoctor();
          this.listCashier();
          this.listCorporateCompany();
        } else {
          this.loaderService.display(false);
          this.collection = 0;
          this.status = result['status'];
          this.grand_total = 0;
        }
      });
    }
  }
  public listTPA() {
    this.loaderService.display(true);
    this.rest.getTPA().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        if (data['tpa_receiver']['status'] === 'Success') {
          this.tpa_receiver = data['tpa_receiver']['data'];
          this.tpa_options = this.tpa_receiver;
          for (let index = 0; index < this.tpa_options.length; index++) {
            if (this.tpa_options[index].TPA_ID === this.tpa_id) {
              this.tpa_data = this.tpa_options[index];
            }
          }
        }
      }
    });
  }
  public setTPA() {

    for (let index = 0; index < this.tpa_receiver.length; index++) {
      if (this.tpa_receiver[index].TPA_ID == this.tpa_id) {
        this.tpa_data = this.tpa_receiver[index];
        break;
      }
    }
  }
  getTpa() {
    if (this.tpa_data) {
      this.tpa_id = this.tpa_data.TPA_ID;
    }
    else {
      this.tpa_id = 0;
    }
  }

  public listDoctor() {
    const postData = {
    };
    this.loaderService.display(true);
    this.rest.listDoctorList(postData).subscribe((response) => {
      this.loaderService.display(false);
      if (response['status'] === 'Success') {
        this.doctor = response['data'];
        this.doctor_options = this.doctor;
        for (let index = 0; index < this.doctor_options.length; index++) {
          if (this.doctor_options[index].DOCTORS_ID === this.doctor_id) {
            this.doctor_data = this.doctor_options[index];
          }
        }
      }
    });
  }
  public setDoctor() {

    for (let index = 0; index < this.doctor.length; index++) {
      if (this.doctor[index].DOCTORS_ID == this.doctor_id) {
        this.doctor_data = this.doctor[index];
        break;
      }
    }
  }
  getDoctor() {
    if (this.doctor_data) {
      this.doctor_id = this.doctor_data.DOCTORS_ID;
    }
    else {
      this.doctor_id = 0;
    }
  }
  listCashier() {
    this.loaderService.display(true);
    this.rest.getUserList().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        this.cashier = data['data'];
        this.cashier_options = this.cashier;
        for (let index = 0; index < this.cashier_options.length; index++) {
          if (this.cashier_options[index].USER_SPK === this.cashier_id) {
            this.cashier_data = this.cashier_options[index];
          }
        }
      }

    });
  }
  public setCashier() {

    for (let index = 0; index < this.cashier.length; index++) {
      if (this.cashier[index].USER_SPK == this.cashier_id) {
        this.cashier_data = this.cashier[index];
        break;
      }
    }
  }
  getCashier() {
    if (this.cashier_data) {
      this.cashier_id = this.cashier_data.USER_SPK;
    }
    else {
      this.cashier_id = 0;
    }
  }
  public listCorporateCompany() {
    const post2Data = {
      company_status: 1,
    };
    this.loaderService.display(true);
    this.rest.listCorporateCompany(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.corporate_company = result['data'];
        this.company_options = this.corporate_company;
        for (let index = 0; index < this.company_options.length; index++) {
          if (this.company_options[index].CORPORATE_COMPANY_ID == this.company_id) {
            this.company_data = this.company_options[index];
          }
        }
      }
    });
  }
  public setcompanyDropdown() {
    for (let index = 0; index < this.company_options.length; index++) {
      if (this.company_options[index].CORPORATE_COMPANY_ID == this.company_id) {
        this.company_data = this.company_options[index];
        break;
      }
    }
  }
  getcompany() {
    if (this.company_data) {
      this.company_id = this.company_data.CORPORATE_COMPANY_ID;
    }
    else {
      this.company_id = 0;
    }
  }
  doctor_config = {
    displayKey: "DOCTORS_NAME",
    search: true,
    height: '250px',
    placeholder: 'Select Doctor',
    limitTo: 100,
    moreText: '.........',
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'DOCTORS_NAME'
  }
  tpa_config = {
    displayKey: "TPA",
    search: true,
    height: '250px',
    placeholder: 'Select TPA / Receiver',
    limitTo: 100,
    moreText: '.........',
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'TPA'
  }
  cashier_config = {
    displayKey: "FIRSTNAME",
    search: true,
    height: '250px',
    placeholder: 'Select Cashier',
    limitTo: 100,
    moreText: '.........',
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'FIRSTNAME'
  }
  config_company = {
    displayKey: "CORPORATE_COMPANY_NAME",
    search: true,
    height: '250px',
    placeholder: 'Select Corporate Company',
    limitTo: 100,
    moreText: '.........',
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'CORPORATE_COMPANY_NAME'
  }
  private open(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop: 'static',
      keyboard: true,
      ariaLabelledBy: 'modal-basic-title',
      size: 'lg',
      centered: false
    };

    this.modalService.open(content, ngbModalOptions).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
  }
  public cash_getBill(bill_id, content) {
    const post2Data = {
      billing_id: bill_id,
    };
    this.loaderService.display(true);
    this.rest.getBill(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.invoice = result["data"]
        this.patient_details = this.invoice.pateint_details.data;
        this.co_ins = this.invoice.pateint_details.data.co_ins;
        this.ins_data = this.invoice.pateint_details.data.ins_data;
        this.corporate_data = this.invoice.pateint_details.data.corporate_data;
        this.invoice_list = this.invoice.bill_details
        this.open(content)
      }
    });
  }
}

