import { Component, OnInit, Input, ViewChild } from '@angular/core';
import { LoaderService, DoctorsService, OpVisitService, NursingAssesmentService, ConsultingService } from 'src/app/shared';
import { NgbModal, NgbProgressbar } from '@ng-bootstrap/ng-bootstrap';
import { NotifierService } from 'angular-notifier';
import { DatePipe } from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { AppSettings } from 'src/app/app.settings';
import { BillingService } from 'src/app/shared/services/billing.service';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from '../../../../shared/class/Utils';
import { ModalDismissReasons, NgbModalOptions } from '@ng-bootstrap/ng-bootstrap';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import Moment from 'moment-timezone';
@Component({
  selector: 'app-patient-bill',
  templateUrl: './patient-bill.component.html',
  styleUrls: ['./patient-bill.component.scss']
})
export class PatientBillComponent implements OnInit {
  @ViewChild('bill_tabs') bill_tabs;
  //@ViewChild('appointment_form') af: any;
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() selected_visit: any = [];
  @Input() assessment_list: any = [];
  todaysDate = defaultDateTime();
  public gender: any = ["Female", "Male"]
  public gender_sur: any = ["Ms.", "Mr."]
  navigationSubscription: any;
  public notifier: NotifierService;
  user_id: number = 0;
  public patient_details: any = [];
  public cpt_rate: any = [];
  public user_rights: any = {};
  public user_data: any = {};
  public loading = false;
  public payment_type: any = '';
  public dateVal = defaultDateTime();
  public invoice_list: any = [];
  public insurance_tpa_id: number = 0;
  public insurance_network_id: number = 0;
  public cpt_code_ids: any = {};
  public lab_details_ids: any = {};
  public price: any = {};
  public co_payment: any = {};
  public isClickedOnce = false;
  public co_payment_type: any = {};
  public patient_pay: any = {};
  public total_rate: any = {};
  public qty: any = [];
  public co_ins: any = [];
  public ins_data: any = [];
  public table_invoice_list: any = [];
  public claim_list: any = [];
  public cash_list: any = [];
  public bill_total = 0;
  public bill_id: any = {};
  public gross_total = 0;
  public patient_payable_total = 0;
  public net_total = 0;
  public billing_id: number = 0;
  public patient_pay_tot: number = 0;
  invoice_number: any;
  public assesment_list: any = {};
  status: any;
  lab_status: any;
  generate_status: number = 0;
  public prior_authorization: any = {};
  public insurance_status: number = 1;
  public checked: any = {};
  public patient_total: any = {};
  public payment_mode = 0;
  public institution = JSON.parse(localStorage.getItem('institution'));
  public logo_path = JSON.parse(localStorage.getItem('logo_path'));
  investigation: any;
  logo: any;
  closeResult: string;
  public claim_gross_total: number = 0;
  public claim_net_total: number = 0;
  public claim_bill_total: number = 0;
  public claim_patient_payable_total: number = 0;
  public cash_gross_total: number = 0;
  public cash_net_total: number = 0;
  public cash_bill_total: number = 0;
  public cash_patient_payable_total: number = 0;
  public claim_invoice_number: any;
  public claim_billing_id: number = 0;
  public claim_generate_status = 0;
  public cash_invoice_number: any;
  public cash_billing_id: number = 0;
  public cash_generate_status = 0;
  public claim_status: any;
  public cash_status: any;
  public claim_bill_id: any = [];
  public cash_bill_id: any = [];
  patient_type_detail_id: number = 0;
  confirm_text: any;
  bill_type: any;
  list_index: any;
  list_data: any;
  bill_details: any = [];
  pending_amount = 0
  public invoice = {
    claim_invoice: {},
    cash_invoice: {},
  }
  public patient_pay_payment = 0;
  public balance_payment = 0;
  cash_cash_lab_details_ids: any;
  cash_cpt_code_ids: any;
  cash_price: any;
  cash_co_payment: any;
  cash_co_payment_type: any;
  cash_patient_pay: any;
  cash_total_rate: any;
  cash_checked: any;
  cash_patient_total: any;
  cash_lab_details_ids: any;
  cash_payment_mode = 0;
  claim_payment_mode = 0;
  searching = false;
  searchFailed = false;
  public cpt_add_data = {
    patient_id: 0,
    assessment_id: 0,
    lab_investigation_id: 0,
    lab_investigation_details_id: 0,
    description: '',
    cpt_data: [],
    alliasname: "",
    cptname: "",
    cptcode: "",
    quantity: "1",
    rate: 0,
    change_of_future: "",
    remarks: "",
    priority: 0,
    billedamount: 0,
    un_billedamount: 0,
    instruction: '',
    current_procedural_code_id: 0,
    cptcode_id: 0,
    user_id: 0,
    client_date: new Date(),
    date: defaultDateTime(),
    timeZone: ''
  };
  sum: number;
  public priority_list: any = [];
  lab_investigation_id: any;
  remove_data: any;
  success: number = 0;

  constructor(private loaderService: LoaderService, public Doc: DoctorsService, private modalService: NgbModal, public bill: BillingService, public nas: NursingAssesmentService, notifierService: NotifierService, public rest2: ConsultingService, public datepipe: DatePipe, private router: Router) {
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.getBillByAssessment();
    this.get_priority();
  }
  public get_priority() {
    const postData = {
      master_id: 11
    };
    this.rest2.get_priority(postData).subscribe((result) => {
      if (result['status'] === 'Success') {
        this.priority_list = result['master_list'];
      } else {
        this.priority_list = [];
      }
    }, (err) => {
      //  console.log(err);
    });
  }
  public set_item($event) {
    const item = $event.item;
    this.cpt_add_data.current_procedural_code_id = item.CURRENT_PROCEDURAL_CODE_ID;
    this.getCpt(item);
  }
  public getCpt(data) {

    const postData = {
      current_procedural_code_id: data.CURRENT_PROCEDURAL_CODE_ID
    };
    this.loaderService.display(true);

    this.loaderService.display(true);
    this.Doc.getCPT(postData).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        const cptlist_data = result['data'];
        this.cpt_add_data.alliasname = cptlist_data.PROCEDURE_CODE_ALIAS_NAME;
        this.cpt_add_data.cptcode = cptlist_data.PROCEDURE_CODE;
        this.cpt_add_data.description = cptlist_data.PROCEDURE_CODE_DESCRIPTION;
        this.cpt_add_data.cptcode_id = cptlist_data.CURRENT_PROCEDURAL_CODE_ID;
        this.cpt_add_data.rate = cptlist_data.CPT_RATE;

        this.loaderService.display(false);
      } else {
        this.loaderService.display(false);
      }

    });

  }
  public removeCPT(data) {
    this.remove_data = data
  }
  public deleteInvestigationByCashier() {
    const postData = {
      lab_investigation_details_id: this.remove_data.LAB_INVESTIGATION_DETAILS_ID,
      lab_investigation_id: this.cpt_add_data.lab_investigation_id,
      rate: this.remove_data.RATE
    };

    this.loaderService.display(true);
    this.bill.deleteInvestigationByCashier(postData).subscribe((result) => {
      if (result.status === 'Success') {
        this.loaderService.display(false);
        this.notifier.notify("success", result.msg)
        this.get_investigation();
        // this.cpt_add_data.billedamount = 0
        // this.calculateBillamount()
      }
      else {
        this.notifier.notify("error", result.msg)
      }
    });
  }
  public saveInvestigationByCashier() {
    //  console.log(this.cpt_add_data.current_procedural_code_id)
    if (this.cpt_add_data.current_procedural_code_id == 0 || this.cpt_add_data.current_procedural_code_id < 0 || this.cpt_add_data.current_procedural_code_id == null) {
      this.notifier.notify('error', 'Please select a CPT!');
      return;
    }
    this.get_investigation();
    //  this.cpt_add_data.lab_investigation_id = this.lab_investigation_id
    this.cpt_add_data.patient_id = this.patient_id,
      this.cpt_add_data.assessment_id = this.assessment_id,
      this.cpt_add_data.user_id = this.user_data.user_id
    this.cpt_add_data.client_date = new Date()
    this.cpt_add_data.date = defaultDateTime()
    this.cpt_add_data.timeZone = Moment.tz.guess()
    this.loaderService.display(true);
    this.bill.saveInvestigationByCashier(this.cpt_add_data).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status == "Success") {
        this.notifier.notify('success', result.msg)
        this.get_investigation();
        this.cpt_add_data.alliasname = ''
        this.cpt_add_data.cptcode = ''
        this.cpt_add_data.description = ''
        this.cpt_add_data.cptcode_id = 0
        this.cpt_add_data.rate = 0
        this.cpt_add_data.description = ''
        this.cpt_add_data.priority = 0
        this.cpt_add_data.remarks = ''
        this.cpt_add_data.quantity = '1'
        this.cpt_add_data.change_of_future = ''
        this.cpt_add_data.lab_investigation_id = result.data_id
        const temp = {
          'PROCEDURE_CODE': '',
          'PROCEDURE_CODE_NAME': '',
          'CURRENT_PROCEDURAL_CODE_ID': '',
          'PROCEDURE_CODE_CATEGORY': ''
        };
        this.cpt_add_data.cpt_data[0] = temp;
        this.cpt_add_data.lab_investigation_details_id = 0

      }
      else {
        this.notifier.notify('error', result.msg)
      }
      //  this.loaderService.display(false);
    }, (err) => {
      //console.log(err);
    });
  }
  public editCPT(invoice) {
    for (let data of this.invoice_list) {
      if (data.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID) {
        this.cpt_add_data.alliasname = data.PROCEDURE_CODE_ALIAS_NAME;
        this.cpt_add_data.cptcode = data.PROCEDURE_CODE;
        this.cpt_add_data.description = data.DESCRIPTION;
        this.cpt_add_data.cptcode_id = data.CURRENT_PROCEDURAL_CODE_ID;
        this.cpt_add_data.current_procedural_code_id = data.CURRENT_PROCEDURAL_CODE_ID
        this.cpt_add_data.rate = data.RATE;
        this.cpt_add_data.priority = data.LAB_PRIORITY_ID
        this.cpt_add_data.remarks = data.REMARKS
        this.cpt_add_data.quantity = data.QUANTITY
        this.cpt_add_data.change_of_future = data.CHANGE_TO_FUTURE
        this.cpt_add_data.lab_investigation_details_id = data.LAB_INVESTIGATION_DETAILS_ID
        // this.cpt_add_data.lab_investigation_id = data.LAB_INVESTIGATION_DETAILS_ID
        const temp = {
          'PROCEDURE_CODE': data.PROCEDURE_CODE,
          'PROCEDURE_CODE_NAME': data.PROCEDURE_CODE_ALIAS_NAME,
          'CURRENT_PROCEDURAL_CODE_ID': '',
          'PROCEDURE_CODE_CATEGORY': ''
        };
        this.cpt_add_data.cpt_data[0] = data;
      }
    }

  }
  public addToClaim(i, invoice) {

    if (this.claim_list.length == 0) {
      this.claim_gross_total = 0
      this.claim_net_total = 0
      this.claim_patient_payable_total = 0
      this.claim_bill_total = 0
    }
    if (invoice.TOTAL > 0) {
      if (this.claim_list.length > 0) {
        for (let data of this.claim_list) {
          if (data.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID) {
            return this.notifier.notify('error', "Already added to credit invoice")
          }
        }
      }
      if (this.cash_list.length > 0) {
        for (let cash of this.cash_list) {
          if (cash.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID) {
            return this.notifier.notify('error', " Already added to cash invoice.")
          }
        }
      }
      if (this.claim_list.push(invoice)) {
        this.notifier.notify('success', "Added to Credit / Claim invoice")
        this.checkstatusInvoice()
        this.getTotal();
        this.bill_type = 1;
        // this.table_invoice_list[i].BILL_TYPE = 1;
      }

      this.claim_gross_total = this.claim_gross_total + invoice.TOTAL + invoice.TOTAL_PATIENT_PAYABLE
      this.claim_net_total = this.claim_net_total + invoice.TOTAL
      this.claim_patient_payable_total = this.claim_patient_payable_total + invoice.TOTAL_PATIENT_PAYABLE
      this.claim_bill_total = this.claim_bill_total + invoice.TOTAL
      // console.log(this.claim_gross_total)
      // console.log(this.claim_net_total)
      // console.log(this.claim_patient_payable_total)
      // console.log(this.claim_bill_total)
    }
    else {
      this.notifier.notify("error", "Insurance amount is not avilable for this CPT.")
    }

  }
  public addToCash(i, invoice) {
    if (this.cash_list.length == 0) {
      this.cash_gross_total = 0
      this.cash_net_total = 0
      this.cash_patient_payable_total = this.pending_amount
      this.cash_bill_total = 0
    }
    if (this.cash_list.length > 0) {
      for (let data of this.cash_list) {
        if (data.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID) {
          return this.notifier.notify('error', "Already added to Cash invoice")
        }
      }
    }
    if (this.claim_list.length > 0) {
      for (let data of this.claim_list) {
        if (data.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID) {
          return this.notifier.notify('error', "Already added to Credit / Claim invoice")
        }
      }
    }
    invoice.TOTAL_PATIENT_PAYABLE = invoice.TOTAL_PATIENT_PAYABLE + invoice.TOTAL
    invoice.TOTAL = 0.00
    if (this.cash_list.push(invoice)) {
      this.notifier.notify('success', "Added to Cash invoice")
      this.checkstatusInvoice()
      this.getTotal();
      this.bill_type = 0;
      // this.table_invoice_list[i].BILL_TYPE = 0;
    }
    //  console.log(this.cash_gross_total)
    this.cash_gross_total = this.cash_gross_total + invoice.TOTAL_PATIENT_PAYABLE
    this.cash_net_total = this.cash_net_total + invoice.TOTAL
    this.cash_patient_payable_total = this.cash_patient_payable_total + invoice.TOTAL_PATIENT_PAYABLE
    this.cash_bill_total = this.cash_bill_total + invoice.TOTAL
    this.patient_pay_payment = this.cash_gross_total
    //  console.log(invoice)
    //  console.log(this.cash_list)
  }
  public remove(i, data) {
    this.list_index = i
    this.list_data = data
  }
  public removeToClaim() {
    this.claim_list.splice(this.list_index, 1);
    this.claim_gross_total = this.claim_gross_total - parseFloat(this.list_data.TOTAL) - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.claim_net_total = this.claim_net_total - parseFloat(this.list_data.TOTAL)
    this.claim_patient_payable_total = this.claim_patient_payable_total - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.claim_bill_total = this.claim_bill_total - parseFloat(this.list_data.TOTAL)
    this.list_index = ''
    this.list_data = ''
    this.checkstatusInvoice()
  }
  public removeToCash() {
    this.cash_list.splice(this.list_index, 1);
    this.cash_gross_total = this.cash_gross_total - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.cash_net_total = this.cash_net_total - parseFloat(this.list_data.TOTAL)
    this.cash_patient_payable_total = this.cash_patient_payable_total - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.cash_bill_total = this.cash_bill_total - parseFloat(this.list_data.TOTAL)
    this.list_index = ''
    this.list_data = ''
    this.patient_pay_payment = this.cash_gross_total
    if (this.cash_list.length == 0) {
      this.patient_pay_payment = 0;
    }
    this.checkstatusInvoice()
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
  public get_investigation() {
    var postData = {
      patient_id: this.patient_id,
      assessment_id: this.assessment_id,
    }
    this.loaderService.display(true);
    this.bill.get_lab_investigation(postData).subscribe((result) => {
      if (result.status == "Success") {
        this.loaderService.display(false);
        this.investigation = result.data
        this.cpt_add_data.lab_investigation_id = result.data.LAB_INVESTIGATION_ID
        this.invoice_list = result.data.LAB_INVESTIGATION_DETAILS
        var i = 0
        for (var data of result.data.LAB_INVESTIGATION_DETAILS) {
          this.cpt_code_ids[i] = data.CURRENT_PROCEDURAL_CODE_ID
          i = i + 1;
        }
        this.cpt_code_ids = this.cpt_code_ids;
        var rel = 0;
        if (this.bill_details.length > 0) {
          for (let bill of this.bill_details) {
            if (bill.GENERATED == 1) {
              this.patient_details = bill.pateint_details.data;
              this.co_ins = bill.pateint_details.data.co_ins;
              this.ins_data = bill.pateint_details.data.ins_data;
              this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA
              this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK
              this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE
              // this.getPatientCptRate()
              rel = 1;
            }
          }
        }
        //  console.log(this.payment_type)
        if (rel == 0) {
          this.getPatientDetails();
        }
        if (rel == 1) {
          this.getPatientCptRate()
        }
        this.lab_status = result.status
      }
      else {
        this.loaderService.display(false);
        this.lab_status = result.status
      }
    }, (err) => {
      console.log(err);
    });
  }

  public getPatientDetails() {
    var sendJson = {
      patient_id: this.patient_id
    }
    this.loaderService.display(true)
    this.bill.getPatientDetails(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if (result.status == "Success") {
        this.patient_details = result.data;
        this.co_ins = result.data.co_ins;
        this.ins_data = result.data.ins_data;
        this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA
        this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK
        this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE
        this.getPatientCptRate()
      }
      else {
        this.patient_details = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getPatientCptRate() {
    var sendJson = {
      insurance_tpa_id: this.insurance_tpa_id,
      insurance_network_id: this.insurance_network_id,
      cpt_code_ids: this.cpt_code_ids
    }
    this.loaderService.display(true)
    this.bill.getPatientCptRate(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if (result.status == "Success") {
        this.cpt_rate = result.data;
        this.getTotal();
      }
      else {
        this.getTotal();
        this.cpt_rate = [];
      }
    }, (err) => {
      console.log(err);
    });
  }

  public getTotal() {

    var i = 0;
    var table_invoice_list = [];
    var bill_total = 0;
    var gross_total = 0;
    var patient_payable_total: number = 0;
    var net_total = 0;
    for (let invoice of this.invoice_list) {
      var rate = this.find_rate(invoice);
      var patient_payable = this.find_patient_payable(invoice, rate);
      var patient_pay_tot = 0;
      var item_total = rate * invoice.QUANTITY;
      var total = item_total - patient_payable;
      var in_status = 1;
      // var prior_auth = '';
      // if(invoice.PRIOR_AUTHORIZATION == null)
      // {
      //   prior_auth = '';
      // }
      // else
      // {
      //   prior_auth = invoice.PRIOR_AUTHORIZATION;
      // }
      if (invoice.INSURANCE_STATUS == 0) {
        patient_pay_tot = item_total;
        total = 0;
        in_status = 0;
      }
      else {
        patient_pay_tot = parseFloat(patient_payable);
        in_status = 1
      }
      bill_total = bill_total + total;
      gross_total = gross_total + item_total;
      patient_payable_total = patient_payable_total + patient_pay_tot;
      net_total = gross_total - patient_payable_total;

      table_invoice_list.push({
        LAB_INVESTIGATION_DETAILS_ID: invoice.LAB_INVESTIGATION_DETAILS_ID,
        PROCEDURE_CODE_NAME: invoice.PROCEDURE_CODE_NAME,
        PROCEDURE_CODE: invoice.PROCEDURE_CODE,
        QUANTITY: invoice.QUANTITY,
        CURRENT_PROCEDURAL_CODE_ID: invoice.CURRENT_PROCEDURAL_CODE_ID,
        //  PRIOR_AUTHORIZATION:prior_auth,
        RATE: rate,
        CO_PAYMENT: this.find_co_payment(invoice),
        CO_PAYMENT_TYPE: this.find_co_payment_type(invoice),
        PATIENT_PAYABLE: patient_payable,
        TOTAL: total,
        TOTAL_PATIENT_PAYABLE: patient_pay_tot,
        INSURANCE_STATUS: in_status,
        BILL_TYPE: this.bill_type,
        USER_ID: invoice.USER_ID
      }
      );
    }
    // console.log("table_invoice_list"+JSON.stringify(table_invoice_list));
    this.table_invoice_list = table_invoice_list;

    this.bill_total = bill_total;
    this.gross_total = gross_total;
    this.patient_payable_total = patient_payable_total + this.pending_amount;
    // alert(patient_payable_total + this.pending_amount)
    this.net_total = net_total;
    //this.patient_pay_tot = patient_pay_tot;
    // var i=0;
    // for (var data of this.table_invoice_list) 
    // {
    //   this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
    //   this.cpt_code_ids[i] = data.PROCEDURE_CODE
    //   this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
    //   this.price[i] = data.RATE
    //   this.co_payment[i] = data.CO_PAYMENT
    //   this.co_payment_type[i] = data.CO_PAYMENT_TYPE
    //   this.patient_pay[i] = data.PATIENT_PAYABLE
    //   this.total_rate[i] = data.TOTAL
    //   this.checked[i] = data.INSURANCE_STATUS
    //   this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
    //   i = i+1; 
    // }
    //  console.log("this.patient_total "+JSON.stringify(this.patient_total));
    this.checkstatusInvoice()
  }
  public selectCoinPaymentType(val) {
    this.payment_mode = val;
    //console.log("this.payment_mode  "+this.payment_mode);
  }
  changeCheckbox(checked, i) {
    if (checked != 0) {
      this.invoice_list[i].INSURANCE_STATUS = 0;
      this.getTotal();
    }
    else {
      this.invoice_list[i].INSURANCE_STATUS = 1;
      this.getTotal();
    }
  }
  public find_co_payment(invoice) {
    if (this.patient_details.patient_data.OP_REGISTRATION_TYPE != 1) {
      return 0;
    }
    if (invoice.PROCEDURE_CODE_CATEGORY == 33) {
      return this.patient_details.ins_data.OP_INS_DEDUCTIBLE;
    }
    if (this.cpt_rate.length <= 0) {
      //console.log("invoice");
      return 0;
    }
    if (this.patient_details.ins_data.OP_INS_IS_ALL == 1) {
      return this.patient_details.ins_data.OP_INS_ALL_VALUE;
    }
    else if (this.co_ins.length > 0) {
      for (let co of this.co_ins) {
        if (invoice.PROCEDURE_CODE_CATEGORY == co.COIN_ID) {
          return co.COIN_VALUE;
        }
      }
      return "0";
    }
    else {
      return "0";
    }
  }
  public find_co_payment_type(invoice) {
    if (this.patient_details.patient_data.OP_REGISTRATION_TYPE != 1) {
      return "%";
    }
    if (invoice.PROCEDURE_CODE_CATEGORY == 33) {
      return this.patient_details.ins_data.OP_INS_DEDUCT_TYPE;
    }
    if (this.cpt_rate.length <= 0) {
      return "%";
    }

    if (this.patient_details.ins_data.OP_INS_IS_ALL == 1) {
      return this.patient_details.ins_data.OP_INS_ALL_TYPE;
    }
    else if (this.co_ins.length > 0) {
      for (let co of this.co_ins) {
        if (invoice.PROCEDURE_CODE_CATEGORY == co.COIN_ID) {
          return co.COIN_VALUE_TYPE;
        }
      }
      return "";
    }
    else {
      return "";
    }
  }
  public find_patient_payable(invoice, rate) {
    if (this.patient_details.patient_data.OP_REGISTRATION_TYPE != 1) {
      return (parseFloat(rate) * invoice.QUANTITY).toFixed(2)
    }
    if (this.cpt_rate.length <= 0) {
      return (parseFloat(rate) * invoice.QUANTITY).toFixed(2)
    }
    if (this.cpt_rate.length > 0) {
      var data = 0;
      for (let val of this.cpt_rate) {
        if (invoice.CURRENT_PROCEDURAL_CODE_ID == val.cpt_code_id && val.rate == 0) {
          data = data + (parseFloat(rate) * invoice.QUANTITY)
        }
      }
      if (data > 0)
        return data;
    }
    if (invoice.PROCEDURE_CODE_CATEGORY == 33) {
      if (this.patient_details.ins_data.OP_INS_DEDUCT_TYPE == 'AED') {
        return (this.patient_details.ins_data.OP_INS_DEDUCTIBLE)
      }
      else if (this.patient_details.ins_data.OP_INS_DEDUCT_TYPE == '%') {
        return (parseFloat(rate) * invoice.QUANTITY * (this.patient_details.ins_data.OP_INS_DEDUCTIBLE / 100)).toFixed(2);
      }
      return 0;
    }
    else if (this.patient_details.ins_data.OP_INS_IS_ALL == 1) {
      if (this.patient_details.ins_data.OP_INS_ALL_TYPE == 'AED') {
        return this.patient_details.ins_data.OP_INS_ALL_VALUE;
      }
      else if (this.patient_details.ins_data.OP_INS_ALL_TYPE == '%') {
        return (parseFloat(rate) * invoice.QUANTITY * (this.patient_details.ins_data.OP_INS_ALL_VALUE / 100)).toFixed(2);
      }
      return 0;
    }
    else if (this.co_ins.length > 0) {
      for (let co of this.co_ins) {
        if (invoice.PROCEDURE_CODE_CATEGORY == co.COIN_ID) {
          if (co.COIN_VALUE_TYPE == 'AED') {
            return co.COIN_VALUE
          }
          else if (co.COIN_VALUE_TYPE == '%') {
            return (parseFloat(rate) * invoice.QUANTITY * (co.COIN_VALUE / 100)).toFixed(2)
          }
          return 0;
        }
      }
      return 0;
    }
    else {
      return 0;
    }
  }
  public find_rate(invoice) {

    if (this.cpt_rate.length > 0) {
      for (let rate of this.cpt_rate) {

        if (invoice.CURRENT_PROCEDURAL_CODE_ID == rate.cpt_code_id && rate.rate > 0) {
          return rate.rate
        }
      }
    }
    return invoice.RATE
  }
  public getBillByAssessment() {
    var sendJson = {
      assessment_id: this.assessment_id
    }
    this.bill.getBillByAssessment(sendJson).subscribe((result) => {
      this.loaderService.display(false)

      if (result.status == "Success") {
        this.bill_details = result.data
        for (let bill of result.data) {
          this.invoice_number = bill.BILLING_INVOICE_NUMBER
          this.bill_id = bill
          this.billing_id = bill.BILLING_ID
          this.pending_amount = bill.PENDING_PAYMENT
          if (bill.BILL_TYPE == 1) {
            this.claim_invoice_number = bill.BILLING_INVOICE_NUMBER
            this.claim_billing_id = bill.BILLING_ID
            this.claim_bill_id = bill;
            this.claim_generate_status = bill.GENERATED;
            if (bill.GENERATED == 1) {
              this.generate_status = 1;
            }
            this.claim_list = bill.bill_details;
            this.claim_gross_total = parseFloat(bill.BILLED_AMOUNT);
            this.claim_net_total = parseFloat(bill.INSURED_AMOUNT);
            this.claim_bill_total = parseFloat(bill.INSURED_AMOUNT);
            this.claim_patient_payable_total = parseFloat(bill.PAID_BY_PATIENT);
            this.payment_mode = bill.PAYMENT_MODE
            this.claim_payment_mode = bill.PAYMENT_MODE
            var i = 0
            for (let data of this.claim_list) {
              data["RATE"] = data.TOTAL_AMOUNT
              data["CO_PAYMENT"] = data.COINS
              data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
              data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
              this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
              i = i + 1;
            }
            // this.pending_amount=parseFloat(bill.PENDING_PAYMENT);
          }
          if (bill.BILL_TYPE == 0) {
            this.cash_invoice_number = bill.BILLING_INVOICE_NUMBER
            this.cash_billing_id = bill.BILLING_ID
            this.cash_generate_status = bill.GENERATED;
            if (bill.GENERATED == 1) {
              this.generate_status = 1;
            }
            this.cash_bill_id = bill;
            this.cash_list = bill.bill_details;
            this.cash_gross_total = parseFloat(bill.BILLED_AMOUNT);
            this.cash_net_total = parseFloat(bill.INSURED_AMOUNT);
            this.cash_bill_total = parseFloat(bill.INSURED_AMOUNT);
            this.cash_patient_payable_total = parseFloat(bill.PATIENT_PAYABLE);
            this.payment_mode = bill.PAYMENT_MODE
            this.cash_payment_mode = bill.PAYMENT_MODE
            this.patient_pay_payment = bill.PAID_BY_PATIENT
            this.pending_amount = parseFloat(bill.PENDING_PAYMENT);
            var i = 0
            for (let data of this.cash_list) {
              data["RATE"] = data.TOTAL_AMOUNT
              data["CO_PAYMENT"] = data.COINS
              data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
              data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
              // this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
              i = i + 1;
            }
          }

        }
        this.get_investigation();
        // if(result.data.GENERATED == 1)
        // {
        //   this.getPatient()
        //   this.gross_total = result.data.BILLED_AMOUNT
        //  //  console.log(this.gross_total);
        //   this.patient_payable_total = result.data.PAID_BY_PATIENT
        //   this.net_total = result.data.INSURED_AMOUNT
        //   this.bill_total = result.data.INSURED_AMOUNT
        //   this.table_invoice_list = result.data.bill_details;
        //   for(let data of this.table_invoice_list)
        //   {
        //     data["RATE"] = data.TOTAL_AMOUNT
        //     data["CO_PAYMENT"] = data.COINS
        //     data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
        //     data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
        //   }
        // }
        // else
        // {
        // }


      }
      else {
        this.get_investigation();
        this.getPendingAmount();
        this.bill_id = result.status;
      }
    }, (err) => {
      console.log(err);
    });
  }

  // public pendingcalculation()
  // {
  //   this.balance_payment = this.cash_patient_payable_total-this.patient_pay_payment;
  //   console.log(this.balance_payment);
  // }

  public getPendingAmount() {
    var sendJson = {
      patient_id: this.patient_id,
      assessment_id: this.assessment_id
    }
    this.bill.getPendingAmount(sendJson).subscribe((result) => {
      this.loaderService.display(false)

      if (result.status == "Success") {
        this.pending_amount = parseFloat(result.data);
        var total = this.patient_payable_total;
        this.patient_payable_total = total + this.pending_amount;
        var cash_total = this.cash_patient_payable_total;
        //console.log(cash_total);
        this.cash_patient_payable_total = cash_total + this.pending_amount;
      }
      else {

      }
    }, (err) => {
      console.log(err);
    });
  }
  public getPatient() {
    var sendJson = {
      patient_id: this.patient_id
    }
    this.loaderService.display(true)
    this.bill.getPatientDetails(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if (result.status == "Success") {
        this.patient_details = result.data;
        this.co_ins = result.data.co_ins;
        this.ins_data = result.data.ins_data;
        this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA
        this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK
        this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE
      }
      else {
        this.patient_details = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  public generatePatientBill() {
    this.data()
    this.loaderService.display(true)
    this.bill.generatePatientBill(this.invoice).subscribe((result) => {
      this.loaderService.display(false)
      if (result.status == "Success") {
        // console.log(result)
        this.notifier.notify('success', result.message);
        // this.claim_billing_id = result.claim_billing_id
        // this.cash_billing_id = result.cash_billing_id
        this.getBillByAssessment();
      }
      else {
        this.notifier.notify('error', result.message);
      }
    }, (err) => {
      console.log(err);
    });

  }
  public checkstatusInvoice() {
    for (let data of this.table_invoice_list) {
      data["BILL_TYPE"] = 2;
      if (this.cash_list.length > 0) {
        for (let cash of this.cash_list) {
          if (cash.LAB_INVESTIGATION_DETAILS_ID == data.LAB_INVESTIGATION_DETAILS_ID) {
            data["BILL_TYPE"] = 0
          }
        }
      }
      if (this.claim_list.length > 0) {
        for (let claim of this.claim_list) {
          if (data.LAB_INVESTIGATION_DETAILS_ID == claim.LAB_INVESTIGATION_DETAILS_ID) {
            data["BILL_TYPE"] = 1
          }
        }
      }
    }
    // console.log(this.table_invoice_list)
  }
  public savePatientBill() {
    if (this.cash_list.length > 0 && this.patient_pay_payment == 0 || this.patient_pay_payment < 0 || this.patient_pay_payment == null) {
      this.notifier.notify("error", "Please enter paid amount")
    }
    else {
      if (this.cash_list.length > 0 && this.cash_patient_payable_total < this.patient_pay_payment) {
        this.notifier.notify("error", "Paid amount is greater than patient payable ")
      }
      else {
        if (this.cash_list.length == 0) {
          this.patient_pay_payment = 0
        }
        this.data();
        this.loaderService.display(true)
        // console.log(this.invoice)
        this.bill.savePatientBill(this.invoice).subscribe((result) => {
          this.loaderService.display(false)
          if (result.status == "Success") {
            this.notifier.notify('success', result.message);
            // this.claim_billing_id = result.claim_billing_id
            // this.cash_billing_id = result.cash_billing_id
            this.getBillByAssessment();
          }
          else {
            this.notifier.notify('error', result.message);
          }
        }, (err) => {
          console.log(err);
        });
      }
    }
  }
  public getPostData(value: any) {
    this.cpt_code_ids = [];
    this.lab_details_ids = [];
    this.price = []
    this.co_payment = []
    this.co_payment_type = []
    this.patient_pay = []
    this.total_rate = []
    this.checked = []
    this.patient_total = []
    if (this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1) {
      if (this.patient_details.ins_data)
        this.patient_type_detail_id = this.patient_details.ins_data.OP_INS_DETAILS_ID
    }
    else if (this.patient_details.patient_data.OP_REGISTRATION_TYPE == 3) {
      if (this.patient_details.corporate_data)
        this.patient_type_detail_id = this.patient_details.corporate_data.OP_CORPORATE_DETAILS_ID
    }
    if (value == 1) {
      var i = 0;
      for (var data of this.claim_list) {
        this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
        this.cpt_code_ids[i] = data.PROCEDURE_CODE
        this.price[i] = data.RATE
        this.co_payment[i] = data.CO_PAYMENT
        this.co_payment_type[i] = data.CO_PAYMENT_TYPE
        this.patient_pay[i] = data.PATIENT_PAYABLE
        this.total_rate[i] = data.TOTAL
        this.checked[i] = data.INSURANCE_STATUS
        this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
        i = i + 1;
      }

      return {
        lab_details_ids: this.lab_details_ids,
        billing_id: this.claim_billing_id,
        patient_id: this.patient_id,
        assessment_id: this.assessment_id,
        payment_type: this.payment_type,
        cpt_code_ids: this.cpt_code_ids,
        price: this.price,
        co_payment: this.co_payment,
        co_payment_type: this.co_payment_type,
        patient_pay: this.patient_pay,
        billing_date: this.dateVal,
        billed_amount: this.claim_gross_total,
        paid_by_patient: this.claim_patient_payable_total,
        insured_amount: this.claim_net_total,
        user_id: this.user_data.user_id,
        total: this.total_rate,
        prior_authorization: this.prior_authorization,
        patient_payable_total: this.patient_total,
        insurance_payable_total: this.total_rate,
        payment_mode: this.payment_mode,
        date: defaultDateTime(),
        timeZone: getTimeZone(),
        bill_type: 1,
        patient_type: this.patient_details.patient_data.OP_REGISTRATION_TYPE,
        patient_type_detail_id: this.patient_type_detail_id,

      }
    }
    if (value == 0) {

      var i = 0;
      for (var data of this.cash_list) {
        this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
        this.cpt_code_ids[i] = data.PROCEDURE_CODE
        this.price[i] = data.RATE
        this.co_payment[i] = data.CO_PAYMENT
        this.co_payment_type[i] = data.CO_PAYMENT_TYPE
        this.patient_pay[i] = data.PATIENT_PAYABLE
        this.total_rate[i] = data.TOTAL
        this.checked[i] = data.INSURANCE_STATUS
        this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
        i = i + 1;
      }
      return {
        lab_details_ids: this.lab_details_ids,
        billing_id: this.cash_billing_id,
        patient_id: this.patient_id,
        assessment_id: this.assessment_id,
        payment_type: this.payment_type,
        cpt_code_ids: this.cpt_code_ids,
        price: this.price,
        co_payment: this.co_payment,
        co_payment_type: this.co_payment_type,
        patient_pay: this.patient_pay,
        billing_date: this.dateVal,
        billed_amount: this.cash_gross_total,
        paid_by_patient: this.patient_pay_payment,
        insured_amount: this.cash_net_total,
        user_id: this.user_data.user_id,
        total: this.total_rate,
        prior_authorization: this.prior_authorization,
        patient_payable_total: this.patient_total,
        insurance_payable_total: this.total_rate,
        insurance_status: this.checked,
        payment_mode: this.payment_mode,
        date: defaultDateTime(),
        timeZone: getTimeZone(),
        bill_type: 0,
        patient_type: this.patient_details.patient_data.OP_REGISTRATION_TYPE,
        patient_type_detail_id: this.patient_type_detail_id,
        patient_payable: this.cash_patient_payable_total,
        balance_payment: this.cash_patient_payable_total - this.patient_pay_payment,
        pending_amount: this.pending_amount
      }
    }
  }
  public data() {
    this.cpt_code_ids = [];
    this.lab_details_ids = [];
    this.price = []
    this.co_payment = []
    this.co_payment_type = []
    this.patient_pay = []
    this.total_rate = []
    this.checked = []
    this.patient_total = []
    if (this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1) {
      if (this.patient_details.ins_data)
        this.patient_type_detail_id = this.patient_details.ins_data.OP_INS_DETAILS_ID
    }
    else if (this.patient_details.patient_data.OP_REGISTRATION_TYPE == 3) {
      if (this.patient_details.corporate_data)
        this.patient_type_detail_id = this.patient_details.corporate_data.OP_CORPORATE_DETAILS_ID
    }

    var i = 0;
    if (this.claim_list.length > 0) {
      for (var data of this.claim_list) {
        this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
        this.cpt_code_ids[i] = data.PROCEDURE_CODE
        this.price[i] = data.RATE
        this.co_payment[i] = data.CO_PAYMENT
        this.co_payment_type[i] = data.CO_PAYMENT_TYPE
        this.patient_pay[i] = data.PATIENT_PAYABLE
        this.total_rate[i] = data.TOTAL
        this.checked[i] = data.INSURANCE_STATUS
        this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
        i = i + 1;
      }
    }


    this.invoice.claim_invoice = {
      lab_details_ids: this.lab_details_ids,
      billing_id: this.claim_billing_id,
      patient_id: this.patient_id,
      assessment_id: this.assessment_id,
      payment_type: this.payment_type,
      cpt_code_ids: this.cpt_code_ids,
      price: this.price,
      co_payment: this.co_payment,
      co_payment_type: this.co_payment_type,
      patient_pay: this.patient_pay,
      billing_date: this.dateVal,
      billed_amount: this.claim_gross_total,
      paid_by_patient: this.claim_patient_payable_total,
      insured_amount: this.claim_net_total,
      user_id: this.user_data.user_id,
      total: this.total_rate,
      prior_authorization: this.prior_authorization,
      patient_payable_total: this.patient_total,
      insurance_payable_total: this.total_rate,
      payment_mode: this.payment_mode,
      date: defaultDateTime(),
      timeZone: getTimeZone(),
      bill_type: 1,
      patient_type: this.patient_details.patient_data.OP_REGISTRATION_TYPE,
      patient_type_detail_id: this.patient_type_detail_id,
    }
    this.cash_cpt_code_ids = [];
    this.cash_lab_details_ids = [];
    this.cash_price = []
    this.cash_co_payment = []
    this.cash_co_payment_type = []
    this.cash_patient_pay = []
    this.cash_total_rate = []
    this.cash_checked = []
    this.cash_patient_total = []
    if (this.cash_list.length > 0) {
      var i = 0;
      for (var data of this.cash_list) {
        this.cash_lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
        this.cash_cpt_code_ids[i] = data.PROCEDURE_CODE
        // this.cash_prior_authorization[i] = data.PRIOR_AUTHORIZATION
        this.cash_price[i] = data.RATE
        this.cash_co_payment[i] = data.CO_PAYMENT
        this.cash_co_payment_type[i] = data.CO_PAYMENT_TYPE
        this.cash_patient_pay[i] = data.PATIENT_PAYABLE
        this.cash_total_rate[i] = data.TOTAL
        this.cash_checked[i] = data.INSURANCE_STATUS
        this.cash_patient_total[i] = data.TOTAL_PATIENT_PAYABLE
        i = i + 1;
      }
    }

    this.invoice.cash_invoice = {
      lab_details_ids: this.cash_lab_details_ids,
      billing_id: this.cash_billing_id,
      patient_id: this.patient_id,
      assessment_id: this.assessment_id,
      payment_type: this.payment_type,
      cpt_code_ids: this.cash_cpt_code_ids,
      price: this.cash_price,
      co_payment: this.cash_co_payment,
      co_payment_type: this.cash_co_payment_type,
      patient_pay: this.cash_patient_pay,
      billing_date: this.dateVal,
      billed_amount: this.cash_gross_total,
      paid_by_patient: this.patient_pay_payment,
      insured_amount: this.cash_net_total,
      patient_payable: this.cash_patient_payable_total,
      user_id: this.user_data.user_id,
      total: this.cash_total_rate,
      prior_authorization: this.prior_authorization,
      patient_payable_total: this.cash_patient_total,
      insurance_payable_total: this.cash_total_rate,
      insurance_status: this.cash_checked,
      payment_mode: this.payment_mode,
      date: defaultDateTime(),
      timeZone: getTimeZone(),
      bill_type: 0,
      patient_type: this.patient_details.patient_data.OP_REGISTRATION_TYPE,
      patient_type_detail_id: this.patient_type_detail_id,
      balance_payment: this.cash_patient_payable_total - this.patient_pay_payment,
      pending_amount: this.pending_amount

    }

  }

  private confirms(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop: 'static',
      keyboard: true,
      ariaLabelledBy: 'modal-basic-title',
      size: 'sm',
      centered: false
    };

    this.modalService.open(content, ngbModalOptions).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private open(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop: 'static',
      keyboard: true,
      ariaLabelledBy: 'modal-basic-title',
      windowClass: 'col-md-12',
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
  public changeRate(cash, i) {
    this.cash_list[i].TOTAL_PATIENT_PAYABLE = cash.RATE * cash.QUANTITY
    this.cash_list[i].PATIENT_PAYABLE = cash.TOTAL_PATIENT_PAYABLE
    this.cash_gross_total = 0
    this.cash_net_total = 0
    this.cash_patient_payable_total = 0
    this.cash_bill_total = 0
    for (let invoice of this.cash_list) {
      this.cash_gross_total = this.cash_gross_total + parseFloat(invoice.TOTAL_PATIENT_PAYABLE)
      this.cash_net_total = this.cash_net_total + parseFloat(invoice.TOTAL)
      this.cash_patient_payable_total = this.cash_patient_payable_total + parseFloat(invoice.TOTAL_PATIENT_PAYABLE)
      this.cash_bill_total = this.cash_bill_total + parseFloat(invoice.TOTAL)
    }

  }
  public validateNumber(event) {
    const keyCode = event.keyCode;
    //  console.log(keyCode)  
    const excludedKeys = [9, 8, 37, 39, 46];
    if (!((keyCode > 48 && keyCode < 57) ||
      (keyCode >= 96 && keyCode <= 105) || (keyCode == 48) || (keyCode == 37) ||
      (keyCode == 110) || (keyCode == 190) || (excludedKeys.includes(keyCode)))) {
      event.preventDefault();
    }
  }
  // public save()
  // {
  //   if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1){
  //       this.saveclaimPatientBill();
  //       this.savecashPatientBill();
  //      // this.notifier.notify( 'success', "Bill details saved successfully..!" ); 
  //       this.getBillByAssessment();
  //   }
  //   else
  //   {
  //     if(this.cash_list.length > 0){
  //       this.savecashPatientBill();
  //      // this.notifier.notify( 'success', "Bill details saved successfully..!" ); 
  //       this.getBillByAssessment();
  //     }
  //     else
  //     {
  //       this.notifier.notify( 'warning', "Please add atleast one item of any invoice..!" ); 
  //     }
  //   }
  // }
  public generate() {
    if (this.cash_list.length > 0 && this.patient_pay_payment == 0 || this.patient_pay_payment < 0 || this.patient_pay_payment == null) {
      this.notifier.notify("error", "Please enter paid amount")
    }
    else {
      if (this.cash_list.length > 0 && this.cash_patient_payable_total < this.patient_pay_payment) {
        this.notifier.notify("error", "Paid amount is greater than patient payable ")
      }
      else {
        if (this.cash_list.length == 0) {
          this.patient_pay_payment = 0
        }
        if (this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1) {
          if (this.claim_billing_id > 0 && this.cash_billing_id > 0) {
            // console.log(this.claim_billing_id)
            this.generateclaimPatientBill();
            this.generatecashPatientBill();
            this.getBillByAssessment();
          }
          if (this.claim_billing_id > 0 && this.cash_billing_id == 0) {
            // console.log(this.claim_billing_id)
            this.generateclaimPatientBill();
            this.getBillByAssessment();
          }
          if (this.cash_billing_id > 0 && this.claim_billing_id == 0) {
            this.generatecashPatientBill();
            this.getBillByAssessment();
          }
          if (this.success == 1) {
            this.notifier.notify('success', "The invoice finalized successsfully...!");
          }

        }
        else {
          if (this.cash_list.length > 0) {
            this.generatePatientcashBill();
          }
          else {
            this.notifier.notify('warning', "Please add atleast one item of any invoice..!");
          }
        }
        this.getBillByAssessment();
      }
    }
  }

  public generatecashPatientBill() {
    var sendJson = this.getPostData(0)
    this.bill.generatePatientBill(sendJson).subscribe((result) => {
      this.loaderService.display(false)

      if (result.status == "Success") {
        this.success = 1
        //this.notifier.notify( 'success', "The invoice finalized successsfully...!" ); 
        this.getBillByAssessment();

      }
      else {
        this.success = 0
        this.notifier.notify('error', result.message);
      }
    }, (err) => {
      console.log(err);
    });

  }
  public generatePatientcashBill() {
    var sendJson = this.getPostData(0)
    this.bill.generatePatientBill(sendJson).subscribe((result) => {
      this.loaderService.display(false)

      if (result.status == "Success") {
        this.notifier.notify('success', "The invoice finalized successsfully...!");
        this.getBillByAssessment();

      }
      else {

        this.notifier.notify('error', result.message);
      }
    }, (err) => {
      console.log(err);
    });

  }
  public generateclaimPatientBill() {
    var sendJson = this.getPostData(1)
    this.bill.generatePatientBill(sendJson).subscribe((result) => {
      this.loaderService.display(false)

      if (result.status == "Success") {
        this.success = 1
      }
      else {
        this.success = 0
        this.notifier.notify('error', result.message);
      }
    }, (err) => {
      console.log(err);
    });
    // this.generatecashPatientBill();
  }
  cptsearch = (text$: Observable<string>) =>
    text$.pipe(
      debounceTime(500),
      distinctUntilChanged(),

      tap(() => this.searching = true),
      switchMap(term =>
        this.rest2.cptsearch(term).pipe(

          tap(() => this.searchFailed = false),

          catchError(() => {
            this.searchFailed = true;
            return of(['']);
          })

        )

      ),
      tap(() => this.searching = false)

    )
  formatter = (x: { PROCEDURE_CODE_NAME: String, CURRENT_PROCEDURAL_CODE_ID: Number, PROCEDURE_CODE_CATEGORY: Number, PROCEDURE_CODE: String }) => x.PROCEDURE_CODE_NAME;
}