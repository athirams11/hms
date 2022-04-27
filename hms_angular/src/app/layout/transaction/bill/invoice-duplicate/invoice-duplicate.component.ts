import { Component, OnInit, Input, ViewChild } from '@angular/core';
import { LoaderService, DoctorsService, OpVisitService, NursingAssesmentService, ConsultingService, BillService } from 'src/app/shared';
import { NgbModal, NgbProgressbar } from '@ng-bootstrap/ng-bootstrap';
import { NotifierService } from 'angular-notifier';
import { DatePipe } from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { AppSettings } from 'src/app/app.settings';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from '../../../../shared/class/Utils';
import { ModalDismissReasons, NgbModalOptions } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-invoice-duplicate',
  templateUrl: './invoice-duplicate.component.html',
  styleUrls: ['./invoice-duplicate.component.scss']
})
export class InvoiceDuplicateComponent implements OnInit {
  @ViewChild('bill_tabs') bill_tabs;
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() selected_visit: any = [];
  @Input() assessment_list: any = [];
  public gender : any = [ "Female","Male"]
  public gender_sur : any = [ "Ms.","Mr."]  
  navigationSubscription: any;
  public notifier: NotifierService;
  user_id: number = 0;
  public patient_details : any = [];
  public cpt_rate : any = [];
  public user_rights : any ={};
  public user_data : any ={};
  public loading = false;
  public payment_type : any = '';
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
  public qty : any = [];
  public co_ins : any = [];
  public ins_data : any = [];
  public table_invoice_list : any = [];
  public claim_list : any = [];
  public cash_list : any = [];
  public bill_total = 0;
  public bill_id : any ={};
  public gross_total = 0;
  public patient_payable_total = 0;
  public net_total = 0; 
  public billing_id :number = 0;
  public patient_pay_tot: number = 0;
  public patienttotal = 0;
  public cash_full_list: any = [];
  public credit_full_list: any = [];
  invoice_number: any;
  public assesment_list: any = {};
  status: any;
  lab_status: any;
  generate_status : any;
  public prior_authorization: any = {};
  public insurance_status: number = 1;
  public checked: any = {};
  public patient_total: any = {};
  public payment_mode = 0;
  public institution = JSON.parse(localStorage.getItem('institution'));
  public logo_path = JSON.parse(localStorage.getItem('logo_path'));
  investigation: any;
  logo: any;
  public invoice_data = {
    claim_percentage :"0",
    claim_discount : "0",
    claim_discount_type:  1,
    claim_amount: "0",
    cash_percentage :"0",
    cash_discount : "0",
    cash_discount_type:  1,
    cash_amount: "0",
  }
  public invoice = {
    claim_invoice:{},
    cash_invoice: {},
  }
  
  closeResult: string;
  public claim_gross_total:number = 0;
  public claim_net_total:number = 0;
  public claim_bill_total:number = 0;
  public claim_patient_payable_total:number = 0;
  public cash_gross_total:number = 0;
  public cash_net_total:number = 0;
  public cash_bill_total:number = 0;
  public cash_patient_payable_total:number = 0;
  public claim_invoice_number: any;
  public claim_billing_id: any;
  public claim_generate_status: number = 0;
  public cash_invoice_number: any;
  public cash_billing_id: any;
  public cash_generate_status: number = 0;
  public claim_status: any;
  public cash_status: any;
  public claim_bill_id: any ={};
  public cash_bill_id: any ={};
  patient_type_detail_id: number = 0;
  confirm_text: any;
  bill_type: any;
  list_index: any;
  list_data: any;
  bill_details: any;
  claim_patienttotal = 0;
  cash_patienttotal = 0;
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
  claim_payment_mode = 0;
  cash_payment_mode = 0;
  cash_department_id = 0;
  claim_department_id = 0;
  constructor(private loaderService : LoaderService,
    public Doc:DoctorsService,
    private modalService: NgbModal,
    public bill:BillService,
    public nas: NursingAssesmentService,
    notifierService: NotifierService,
    public rest2: ConsultingService,
     public datepipe: DatePipe,
     private router: Router) 
  {
    this.notifier = notifierService;
   }

   ngOnInit() {
    // this.logo = this.logo_path+this.institution.INSTITUTION_LOGO
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    //console.log("ud"+JSON.stringify(this.institution));
    this.getBillByAssessment();
  }
  public convertParseFloat(number)
  {
    return parseFloat(number)
  }
  private open(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true,
      ariaLabelledBy: 'modal-basic-title',
      windowClass: 'col-md-12',
      size: 'lg',
      centered : false
    };
   
    this.modalService.open(content, ngbModalOptions).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  public addToClaim(i,invoice) {
    
    if(this.claim_list.length == 0)
    {
      this.claim_gross_total = 0
      this.claim_net_total = 0
      this.claim_patient_payable_total = 0
      this.claim_bill_total = 0
      this.claim_patienttotal = 0
    }
    if(this.claim_department_id==1)
      {
        return this.notifier.notify('error',"This item can't add to Credit invoice")
      }
    if(invoice.TOTAL > 0)
    {
      if(this.claim_list.length > 0)
      {
        for(let data of this.claim_list)
        {
          if(data.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID)
          {
            return this.notifier.notify('error',"Already added to credit invoice")
          }
        }
      }
      if(this.cash_list.length > 0)
      {
        for(let cash of this.cash_list)
        {
          if(cash.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID)
          {
            return this.notifier.notify('error'," Already added to cash invoice.")
          }
        }
      }
      if(this.claim_list.push(invoice))
      {
        this.notifier.notify('success',"Added to Credit / Claim invoice")
        this.checkstatusInvoice()
        this.getTotal();
        this.bill_type = 1;
        // this.table_invoice_list[i].BILL_TYPE = 1;
      }

      this.claim_gross_total = this.claim_gross_total + invoice.TOTAL + invoice.TOTAL_PATIENT_PAYABLE
      this.claim_net_total = this.claim_net_total + invoice.TOTAL
      this.claim_patient_payable_total = this.claim_patient_payable_total + invoice.TOTAL_PATIENT_PAYABLE
      this.claim_bill_total = this.claim_bill_total + invoice.TOTAL
      this.invoice_data.claim_discount = invoice.PATIENT_DISCOUNT 
      this.invoice_data.claim_amount = invoice.PATIENT_DISCOUNT 
      this.invoice_data.claim_discount_type = 2 
      if(invoice.PATIENT_DISCOUNT > 0 )
      this.claim_patienttotal = this.claim_patienttotal + invoice.TOTAL_PATIENT_PAYABLE - parseFloat(invoice.PATIENT_DISCOUNT)
      else
      this.claim_patienttotal = this.claim_patienttotal + invoice.TOTAL_PATIENT_PAYABLE 
      // console.log( this.claim_patienttotal)
      // console.log(this.claim_net_total)
      // console.log(this.claim_patient_payable_total)
      // console.log(this.claim_bill_total)
     this.getclaimAmount()
    }
    else
    {
      this.notifier.notify("error","Insurance amount is not avilable for this CPT.")
    }
   
  }
  public addToCash(i,invoice) {
    if(this.cash_list.length == 0)
    {
      this.cash_gross_total = 0
      this.cash_net_total = 0
      this.cash_patient_payable_total = 0
      this.cash_bill_total = 0
      this.cash_patienttotal = 0
    }
      if(this.cash_department_id==1)
      {
        return this.notifier.notify('error',"This item can't add to Cash invoice")
      }
      if(this.cash_list.length > 0)
      {
        for(let data of this.cash_list)
        {
          if(data.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID)
          {
            return this.notifier.notify('error',"Already added to Cash invoice")
          }
        }
      }
      if(this.claim_list.length > 0)
      {
        for(let data of this.claim_list)
        {
          if(data.LAB_INVESTIGATION_DETAILS_ID == invoice.LAB_INVESTIGATION_DETAILS_ID)
          {
            return this.notifier.notify('error',"Already added to Credit / Claim invoice")
          }
        }
      }
      invoice.TOTAL_PATIENT_PAYABLE = invoice.TOTAL_PATIENT_PAYABLE + invoice.TOTAL
      invoice.TOTAL = 0.00
      if(this.cash_list.push(invoice))
      {
        this.notifier.notify('success',"Added to Cash invoice")
        this.checkstatusInvoice()
        this.getTotal();
        this.bill_type = 0;
        // this.table_invoice_list[i].BILL_TYPE = 0;
      }
      // console.log(this.cash_gross_total)
      this.cash_gross_total = this.cash_gross_total + invoice.TOTAL_PATIENT_PAYABLE
      this.cash_net_total = this.cash_net_total + invoice.TOTAL
      this.cash_patient_payable_total = this.cash_patient_payable_total + invoice.TOTAL_PATIENT_PAYABLE
      this.cash_bill_total = this.cash_bill_total + invoice.TOTAL
      this.invoice_data.cash_discount = invoice.PATIENT_DISCOUNT 
      this.invoice_data.cash_amount = invoice.PATIENT_DISCOUNT 
      this.invoice_data.cash_discount_type = 2 
      this.cash_patienttotal = this.cash_patienttotal + invoice.TOTAL_PATIENT_PAYABLE 
      this.getcashAmount()
   //this.getdiscAmount()
  //  console.log(invoice)
  //  console.log(this.cash_list)
  }
  public remove(i,data)
  {
    this.list_index = i
    this.list_data = data
  }
  public removeToClaim() {
    this.claim_list.splice(this.list_index, 1);
    this.claim_gross_total = this.claim_gross_total - parseFloat(this.list_data.TOTAL) - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.claim_net_total = this.claim_net_total - parseFloat(this.list_data.TOTAL)
    this.claim_patient_payable_total = this.claim_patient_payable_total - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.claim_bill_total = this.claim_bill_total - parseFloat(this.list_data.TOTAL)
    this.claim_patienttotal = this.claim_patienttotal - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.getclaimAmount()
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
    this.cash_patienttotal = this.cash_patienttotal - parseFloat(this.list_data.TOTAL_PATIENT_PAYABLE)
    this.getcashAmount()
    this.list_index = ''
    this.list_data = ''
    this.checkstatusInvoice()
  }
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatDate (date) {
    return  formatDate(date);
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  public get_investigation()
  {
  var postData = {
    patient_id : this.patient_id,
    assessment_id : this.assessment_id,
      department_id: this.user_data.department
  }
  this.loaderService.display(true);
  this.bill.getLabInvestigation(postData).subscribe((result) => {
    if(result.status == "Success")
    {
      this.loaderService.display(false);
      this.investigation = result.data   
      this.invoice_list = result.data.LAB_INVESTIGATION_DETAILS   
      var i = 0
      for (var data of result.data.LAB_INVESTIGATION_DETAILS) {
        this.cpt_code_ids[i] = data.CURRENT_PROCEDURAL_CODE_ID
        i = i+1;
      }
      this.cpt_code_ids = this.cpt_code_ids;
      var rel = 0;
      if(this.bill_details && this.bill_details.length > 0)
      {
        for(let bill of this.bill_details)
        {
          if(bill.GENERATED == 1)
          {
            this.patient_details = bill.pateint_details.data;
            this.co_ins = bill.pateint_details.data.co_ins;
            this.ins_data =  bill.pateint_details.data.ins_data;
            this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA
            this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK
            this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE
            // this.getPatientCptRate()
            rel = 1;
          }
        }
      }
     // console.log(this.payment_type)
      if(rel == 0){
        this.getPatientDetails();
      }
      if(rel == 1)
      {
        this.getPatientCptRate()
      }
  }
    else
    {
      this.loaderService.display(false);
      this.lab_status=result.status
    }
    }, (err) => {
      console.log(err);
    });
  } 
  public getcashAmount()
  {
    // if(this.invoice_data.percentage && this.patient_payable_total)
    //   {
    //     var amount = (this.patient_payable_total * parseFloat(this.invoice_data.percentage)/100).toFixed(2);
    //     this.invoice_data.discount = amount;
    //     if(parseFloat(this.invoice_data.discount) > this.patient_payable_total  )
    //     {
    //       this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
    //       this.invoice_data.discount = "0"
    //       return false;
    //     }
    //     else{

    //       this.patienttotal =  Math.round(this.patient_payable_total - parseFloat(amount));
    //     }
    //   }
    
    if(this.cash_list.length > 0){
      if(this.invoice_data.cash_amount && this.invoice_data.cash_discount_type == 1 && this.cash_patient_payable_total)
      {
        var cash_amount = (this.cash_patient_payable_total * parseFloat(this.invoice_data.cash_amount)/100).toFixed(2);
        this.invoice_data.cash_discount = cash_amount;

      
        if(parseFloat(cash_amount) > this.cash_patient_payable_total  )
        {
          this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
          // this.invoice_data.cash_discount = "0"
          return false;
        }
        else{

          this.cash_patienttotal =  Math.round(this.cash_patient_payable_total - parseFloat(cash_amount));
        }
      }
      if((this.invoice_data.cash_amount) && this.invoice_data.cash_discount_type == 2 && this.cash_patient_payable_total)
      {
        this.invoice_data.cash_discount = this.invoice_data.cash_amount;
        if(parseFloat(this.invoice_data.cash_amount) > this.cash_patient_payable_total  )
        {
        
          this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
          // this.invoice_data.cash_discount = "0"
          return false;
        }
        else{

          this.cash_patienttotal =  Math.round(this.cash_patient_payable_total - parseFloat(this.invoice_data.cash_discount));
        }
      }
    }
  }
  public getclaimAmount()
  {
    if(this.claim_list.length > 0){
      if((this.invoice_data.claim_amount) && this.invoice_data.claim_discount_type==1 && this.claim_patient_payable_total)
      {
        var claim_amount = (this.claim_patient_payable_total * parseFloat(this.invoice_data.claim_amount)/100).toFixed(2);
        this.invoice_data.claim_discount = claim_amount;
        if(parseFloat(this.invoice_data.claim_discount) > this.claim_patient_payable_total  )
        {
          this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
          // this.invoice_data.claim_discount = "0"
          return false;
        }
        else{

          this.claim_patienttotal =  Math.round(this.claim_patient_payable_total - parseFloat(claim_amount));
        }
      }
      if((this.invoice_data.claim_amount) && this.invoice_data.claim_discount_type == 2 && this.patient_payable_total)
      {
        this.invoice_data.claim_discount = this.invoice_data.claim_amount;
        if(parseFloat(this.invoice_data.claim_discount) > this.claim_patient_payable_total  )
        {
          this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
          // this.invoice_data.claim_discount = "0"
          return false;
        }
        else{

          this.claim_patienttotal =  Math.round(this.claim_patient_payable_total - parseFloat(this.invoice_data.claim_discount));
        }
      }
    }
  }
  // public getpercentage()
  // {
  //   if(parseFloat(this.invoice_data.discount) && this.patient_payable_total)
  //     {
  //       var per = (parseFloat(this.invoice_data.discount) / this.patient_payable_total ) * 100;
  //       this.invoice_data.percentage = per.toFixed(2);
  //     }
  // }
  // public getdiscAmount()
  // {
  //   if(this.claim_list.length > 0)
  //   { 
  //     if(parseFloat(this.invoice_data.claim_discount) && this.claim_patient_payable_total)
  //     {
  //       if(parseFloat(this.invoice_data.claim_discount) > this.claim_patient_payable_total)
  //       {
  //         this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
  //         return false;
  //       }
  //       else{
  //         this.claim_patienttotal =  Math.round(this.claim_patient_payable_total - parseFloat(this.invoice_data.claim_discount));
  //       }
  //     }
  //   }
  //   if(this.cash_list.length > 0)
  //   { 
  //     if(parseFloat(this.invoice_data.cash_discount) && this.cash_patient_payable_total)
  //     {
  //       if(parseFloat(this.invoice_data.cash_discount) > this.cash_patient_payable_total)
  //       {
  //         this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
  //         return false;
  //       }
  //       else{
  //         this.cash_patienttotal =  Math.round(this.cash_patient_payable_total - parseFloat(this.invoice_data.cash_discount));
  //       }
  //     }
  //   }
  // }
  public getPatientDetails()
  {
      var sendJson = {
       patient_id : this.patient_id
      }
      this.loaderService.display(true)
      this.bill.getPatientDetails(sendJson).subscribe((result) => {
        this.loaderService.display(false)
        if(result.status == "Success")
        {
          this.patient_details = result.data;
          this.co_ins = result.data.co_ins;
          this.ins_data =  result.data.ins_data;
          this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA
          this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK
          this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE
          this.getPatientCptRate()
        }
        else
        {
          this.patient_details = [];
        }
      }, (err) => {
        console.log(err);
      });
    }
    public validateNumber(event) {
      const keyCode = event.keyCode;   
       const excludedKeys = [8, 37, 39, 46];   
      //  console.log(keyCode)
       // 9 TAB
       //110 && 190 (decimal point .)
       if (!((keyCode >= 48 && keyCode <= 57) ||
        (keyCode >= 96 && keyCode <= 105 ) || ( keyCode == 109 ) || ( keyCode == 173 ) || ( keyCode == 190 )
      || ( keyCode == 110 ) || ( keyCode == 9 ) || (excludedKeys.includes(keyCode)))) {
        event.preventDefault();
      }
    }
  public getPatientCptRate()
  {
      var sendJson = {
        insurance_tpa_id : this.insurance_tpa_id,
        insurance_network_id :this.insurance_network_id ,
        cpt_code_ids : this.cpt_code_ids
      }
      this.loaderService.display(true)
      this.bill.getPatientCptRate(sendJson).subscribe((result) => {
        this.loaderService.display(false)
        if(result.status == "Success")
        {
          this.cpt_rate = result.data;
          this.getTotal();  
        }
        else
        {
          this.getTotal();  
          this.cpt_rate = [];
        }
      }, (err) => {
        console.log(err);
      });
    }
 
    public getTotal() {
      
      var i=0;
      var table_invoice_list=[];
      var bill_total = 0;
      var gross_total = 0;
      var patient_payable_total : number =   0;
      var net_total = 0;
      var patient_cpt_pay = 0;
      for(let invoice of this.invoice_list)
      {
        var rate = this.find_rate(invoice);
        var patient_payable = this.find_patient_payable(invoice,rate);
        var patient_pay_tot = 0;
        var item_total = rate * invoice.QUANTITY;
        var total = item_total - patient_payable;
        var in_status = 1;
        var prior_auth = '';
        if(invoice.PRIOR_AUTHORIZATION == null)
        {
          prior_auth = '';
        }
        else
        {
          prior_auth = invoice.PRIOR_AUTHORIZATION;
        }
        if(invoice.INSURANCE_STATUS == 0)
        {
          patient_pay_tot = item_total;
          total = 0;
          in_status = 0;
        }
        else{
          patient_pay_tot = parseFloat(patient_payable);
          in_status = 1
        }
        bill_total = bill_total + total;
        gross_total = gross_total + item_total;
        patient_payable_total = patient_payable_total + patient_pay_tot;
        net_total = gross_total - patient_payable_total;
        
        table_invoice_list.push( {
          LAB_INVESTIGATION_DETAILS_ID:invoice.LAB_INVESTIGATION_DETAILS_ID,
          PROCEDURE_CODE_NAME:invoice.PROCEDURE_CODE_NAME,
          PROCEDURE_CODE:invoice.PROCEDURE_CODE,
          QUANTITY:invoice.QUANTITY,
          PRIOR_AUTHORIZATION:prior_auth,
          DUPLICATE_INVOICE_NUMBER: invoice.DUPLICATE_INVOICE_NUMBER,
          BILLING_ID: invoice.BILLING_ID,
          DEPARTMENT_ID: invoice.DEPARTMENT_ID,
          RATE: rate,
          CO_PAYMENT:this.find_co_payment(invoice),
          CO_PAYMENT_TYPE:this.find_co_payment_type(invoice),
          PATIENT_PAYABLE:patient_payable,
          TOTAL:total,
          TOTAL_PATIENT_PAYABLE:patient_pay_tot,
          INSURANCE_STATUS:in_status,
        }
        );
      }
       // console.log("table_invoice_list"+JSON.stringify(table_invoice_list));
        this.table_invoice_list = table_invoice_list;
      
        this.bill_total = bill_total;
        this.gross_total = gross_total;
        this.patient_payable_total = patient_payable_total;
        this.patienttotal = this.patient_payable_total
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
       // this.getdiscAmount()
      //  console.log("this.patient_total "+JSON.stringify(this.patient_total));
      this.checkstatusInvoice()
  }
  public selectCoinPaymentType(val) {
    this.payment_mode=val;
    //console.log("this.payment_mode  "+this.payment_mode);
  }
  public selectType(val,value){
    if(value == 1){
      this.invoice_data.claim_discount_type = val;
      this.invoice_data.claim_amount = "0"
      this.invoice_data.claim_discount = "0"
      this.claim_patienttotal = this.claim_patient_payable_total
    //  this.getclaimAmount()
    }
    if(value == 0){
      this.invoice_data.cash_discount_type = val;
      this.invoice_data.cash_amount = "0"
      this.invoice_data.cash_discount = "0"
      this.cash_patienttotal = this.cash_patient_payable_total
    //  this.getcashAmount()
    }
  }
  changeCheckbox(checked,i) {
    if(checked != 0)
    {
        this.invoice_list[i].INSURANCE_STATUS = 0;
        this.getTotal();
    }
    else
    {
        this.invoice_list[i].INSURANCE_STATUS = 1;
        this.getTotal();
    }
  }
  public find_co_payment(invoice)
  {
    if(this.patient_details.patient_data.OP_REGISTRATION_TYPE != 1)
    {
      return 0;
    }
    if(invoice.PROCEDURE_CODE_CATEGORY == 33)
    {
      return this.patient_details.ins_data.OP_INS_DEDUCTIBLE;
    }
    if(this.cpt_rate.length <= 0)
    {
      //console.log("invoice");
      return 0;
    }
    if(this.patient_details.ins_data.OP_INS_IS_ALL == 1 )
    {
      return this.patient_details.ins_data.OP_INS_ALL_VALUE;
    }
    else if(this.co_ins.length > 0)
    {
      for (let co of this.co_ins) 
      {
        if(invoice.PROCEDURE_CODE_CATEGORY == co.COIN_ID)
        {
            return co.COIN_VALUE;
        }
      }
      return "0";
    }
    else
    {
      return "0";
    }  
  }
  public find_co_payment_type(invoice)
  {
    if(this.patient_details.patient_data.OP_REGISTRATION_TYPE != 1)
    {
      return '';
    }
    if(invoice.PROCEDURE_CODE_CATEGORY == 33)
    {
      return this.patient_details.ins_data.OP_INS_DEDUCT_TYPE;
    }
    if(this.cpt_rate.length <= 0)
    {
      return "%";
    }
    if(this.patient_details.ins_data.OP_INS_IS_ALL == 1 )
    {
      return this.patient_details.ins_data.OP_INS_ALL_TYPE;
    }
    else if(this.co_ins.length > 0)
    {
      for (let co of this.co_ins) 
      {
        if(invoice.PROCEDURE_CODE_CATEGORY == co.COIN_ID)
        {
            return co.COIN_VALUE_TYPE;
        }
      }
      return "";
    }
    else
    {
      return "";
    } 
  }
  public find_patient_payable(invoice,rate)
  {
    if(this.patient_details.patient_data.OP_REGISTRATION_TYPE != 1)
    {
      return (parseFloat(rate) * invoice.QUANTITY).toFixed(2)
    }
    if(this.cpt_rate.length <= 0)
    {
      return (parseFloat(rate) * invoice.QUANTITY).toFixed(2)
    }
    if(this.cpt_rate.length > 0)
    {
      var data = 0;
      for(let val of this.cpt_rate)
      {
        if(invoice.CURRENT_PROCEDURAL_CODE_ID == val.cpt_code_id && val.rate == 0)
        {
           data =  data + (parseFloat(rate) * invoice.QUANTITY)
        }
      }
      if(data > 0)
        return data;
    }
    if(invoice.PROCEDURE_CODE_CATEGORY == 33)
    {
      if(this.patient_details.ins_data.OP_INS_DEDUCT_TYPE == 'AED')
      {
        return (this.patient_details.ins_data.OP_INS_DEDUCTIBLE)
      }
      else if(this.patient_details.ins_data.OP_INS_DEDUCT_TYPE == '%')
      {
        return (parseFloat(rate)*invoice.QUANTITY*(this.patient_details.ins_data.OP_INS_DEDUCTIBLE/100)).toFixed(2);
      }
      return 0;
    }
    else if(this.patient_details.ins_data.OP_INS_IS_ALL == 1 )
    {
      if(this.patient_details.ins_data.OP_INS_ALL_TYPE == 'AED')
      {
        return this.patient_details.ins_data.OP_INS_ALL_VALUE;
      }
      else if(this.patient_details.ins_data.OP_INS_ALL_TYPE == '%'){
        return (parseFloat(rate)*invoice.QUANTITY*(this.patient_details.ins_data.OP_INS_ALL_VALUE/100)).toFixed(2);
      }
      return 0;
    }
    else if(this.co_ins.length > 0)
    {
      for (let co of this.co_ins) 
      {
        if(invoice.PROCEDURE_CODE_CATEGORY == co.COIN_ID)
        {
          if(co.COIN_VALUE_TYPE == 'AED')
          {
            return co.COIN_VALUE
          }
          else if(co.COIN_VALUE_TYPE == '%'){
            return (parseFloat(rate)*invoice.QUANTITY*(co.COIN_VALUE/100)).toFixed(2)
          }
          return 0;
        }
      }
      return 0;
    }
    else
    {
      return 0;
    }
  }
  public find_rate(invoice)
  {
    
    if (this.cpt_rate.length > 0)
    {
      for (let rate of this.cpt_rate) 
      {
        
        if(invoice.CURRENT_PROCEDURAL_CODE_ID == rate.cpt_code_id && rate.rate > 0)
        {
          return rate.rate
        }
      }
    }
    return invoice.RATE
  }
  public getBillByAssessment()
  {
    // this.cash_department_id = 0;
    // this.claim_department_id = 0;
    var sendJson = {
      assessment_id : this.assessment_id,
      department_id: this.user_data.department,
      
    }
    this.bill.getBillByAssessment(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if(result.status == "Success")
      {
        // this.invoice_number = result.data.BILLING_INVOICE_NUMBER
       this.bill_details = result.data
        // this.billing_id = result.data.BILLING_ID
        // this.generate_status = result.data.GENERATED;
        
        for(let bill of result.data)
        {
          this.invoice_number = bill.BILLING_INVOICE_NUMBER
          this.bill_id = bill
          this.billing_id = bill.BILLING_ID
          this.generate_status = bill.GENERATED;
          if(bill.BILL_TYPE == 1)
          {
            this.claim_invoice_number = bill.DUPLICATE_INVOICE_NUMBER
            // this.claim_invoice_number = bill.BILLING_INVOICE_NUMBER
            this.claim_billing_id = bill.BILLING_ID
            this.claim_bill_id = bill;
            this.claim_generate_status = bill.GENERATED;
            if(this.user_data.department==0){
              this.claim_list = bill.bill_details;
            }
            
            this.credit_full_list.push(bill);
            this.invoice_data.claim_discount_type = 2;
            this.invoice_data.claim_amount = bill.PATIENT_DISCOUNT;
            this.invoice_data.claim_discount = bill.PATIENT_DISCOUNT;
            this.claim_patienttotal = parseFloat(bill.PATIENT_PAYABLE) - parseFloat(bill.PATIENT_DISCOUNT);
            this.claim_gross_total = parseFloat(bill.BILLED_AMOUNT);
            this.claim_net_total = parseFloat(bill.INSURED_AMOUNT);
            this.claim_bill_total = parseFloat(bill.INSURED_AMOUNT);
            this.claim_patient_payable_total = parseFloat(bill.PATIENT_PAYABLE);
            this.claim_payment_mode = bill.PAYMENT_MODE
            this.payment_mode = bill.PAYMENT_MODE
            if(bill.DEPARTMENT_ID>0){
              this.claim_department_id = 1;
            }
            else{
              this.claim_department_id = 0;
            }
            var i=0
            for(let data of this.claim_list)
            {
              data["RATE"] = data.TOTAL_AMOUNT
              data["CO_PAYMENT"] = data.COINS
              data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
              data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
              this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
              i=i+1;
            }
          }
          if(bill.BILL_TYPE == 0)
          {
            this.cash_invoice_number = bill.DUPLICATE_INVOICE_NUMBER
            // this.cash_invoice_number = bill.BILLING_INVOICE_NUMBER
            this.cash_billing_id = bill.BILLING_ID
            this.cash_generate_status = bill.GENERATED;
            this.cash_bill_id = bill;
            if(this.user_data.department==0){
              this.cash_list = bill.bill_details;
            }
            
            this.cash_full_list.push(bill);
            this.invoice_data.cash_discount_type = 2;
            this.invoice_data.cash_amount = bill.PATIENT_DISCOUNT;
            this.invoice_data.cash_discount = bill.PATIENT_DISCOUNT;
            // console.log("this.invoice_data.cash_discount_type")
            // console.log(this.invoice_data.cash_discount_type)
            this.cash_patienttotal = parseFloat(bill.BILLED_AMOUNT) - parseFloat(bill.PATIENT_DISCOUNT);
            // console.log( this.cash_patienttotal)
            this.cash_gross_total = parseFloat(bill.BILLED_AMOUNT);
            this.cash_net_total = parseFloat(bill.INSURED_AMOUNT);
            this.cash_bill_total = parseFloat(bill.INSURED_AMOUNT);
            this.cash_patient_payable_total = parseFloat(bill.PATIENT_PAYABLE) 
            this.cash_payment_mode = bill.PAYMENT_MODE
            this.payment_mode = bill.PAYMENT_MODE
            if(bill.DEPARTMENT_ID>0){
              this.cash_department_id = 1;
            }
            else{
              this.cash_department_id = 0;
            }
            var i=0
            for(let data of this.cash_list)
            {
              data["RATE"] = data.TOTAL_AMOUNT
              data["CO_PAYMENT"] = data.COINS
              data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
              data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
             // this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
              i=i+1;
            }
          }
        }
        // console.log(this.invoice_data)
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
        this.get_investigation();
        //this.getdiscAmount();
       
      }  
      // if(result.status == "Success")
      // {
      //   this.invoice_number = result.data.DUPLICATE_INVOICE_NUMBER
      //   this.bill_id = result.data
      //   this.billing_id = result.data.BILLING_ID
      //   this.generate_status = result.data.GENERATED;
      //   this.invoice_data.discount_type = 2;
      //   this.invoice_data.amount = result.data.PATIENT_DISCOUNT;
      //   this.invoice_data.discount = result.data.PATIENT_DISCOUNT;
      //   this.patient_payable_total = result.data.PAID_BY_PATIENT  -  result.data.PATIENT_DISCOUNT;
      //   this.patienttotal = result.data.PAID_BY_PATIENT - result.data.PATIENT_DISCOUNT;
      //  // this.getpercentage();
      //  if(result.data.GENERATED == 1)
      //  {
      //    this.getPatient()
      //    this.gross_total = result.data.BILLED_AMOUNT
      //   //  console.log(this.gross_total);
      //    this.net_total = result.data.INSURED_AMOUNT
      //    this.bill_total = result.data.INSURED_AMOUNT
      //    this.table_invoice_list = result.data.bill_details;
      //    for(let data of this.table_invoice_list)
      //    {
      //      data["RATE"] = data.TOTAL_AMOUNT
      //      data["CO_PAYMENT"] = data.COINS
      //      data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
      //      data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
      //    }
      //  }
      //  else
      //  {
      //   this.get_investigation();
      //   this.getdiscAmount();
      //  }
        
      // }   
      else
      {
        this.get_investigation();
      //  this.getdiscAmount();
        // this.bill_id = result.status;
      } 
    }, (err) => {
      console.log(err);
    });
  }
  public getPatient()
  {
    var sendJson = {
      patient_id : this.patient_id
    }
    this.loaderService.display(true)
    this.bill.getPatientDetails(sendJson).subscribe((result) => {
      this.loaderService.display(false)
      if(result.status == "Success")
      {
        this.patient_details = result.data;
        this.co_ins = result.data.co_ins;
        this.ins_data =  result.data.ins_data;
        this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA
        this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK
        this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE
      }
      else
      {
        this.patient_details = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  public generatePatientBill()
  {
   // console.log("this.payment_mode  "+this.payment_mode);
  //  if(parseFloat(this.invoice_data.discount) > this.patient_payable_total)
  //  {
  //    this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
  //    this.invoice_data.discount = "0"
  //    return false;
  //  }
    var sendJson = {
      lab_details_ids : this.lab_details_ids,
      billing_id : this.billing_id,
      patient_id : this.patient_id,
      assessment_id : this.assessment_id,
      payment_type : this.payment_type,
      cpt_code_ids : this.cpt_code_ids,
      price : this.price,
      co_payment : this.co_payment,
      co_payment_type : this.co_payment_type,
      patient_pay : this.patient_pay,
      billing_date :this.dateVal,
      billed_amount : this.gross_total,
      paid_by_patient : this.patient_payable_total,
      insured_amount : this.net_total,
      user_id : this.user_data.user_id,
      total : this.total_rate,
    //  discount:this.invoice_data.discount,
      prior_authorization : this.prior_authorization,
      patient_payable_total :this.patient_total,
      insurance_payable_total :this.total_rate,
      insurance_status : this.checked,
      payment_mode:this.payment_mode,
      date: defaultDateTime(),
      timeZone: getTimeZone()
    }
      this.bill.generatePatientBill(sendJson).subscribe((result) => {
        this.loaderService.display(false)
        
        if(result.status == "Success")
        {
          this.notifier.notify( 'success', result.message ); 
          this.invoice_number = result.billing_no
          this.status = result.status
          this.billing_id = result.billing_id
         this.getBillByAssessment();
        }   
        else
        {
          this.notifier.notify( 'error', result.message );
        }
      }, (err) => {
        console.log(err);
      });

  }
  public savePatientBill()
  {
   
    
    if(this.claim_list.length > 0 && parseFloat(this.invoice_data.claim_discount) > this.claim_patient_payable_total)
    {
      this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
      this.invoice_data.claim_discount = "0"
      return false;

    }

    if(this.cash_list.length > 0 && parseFloat(this.invoice_data.cash_discount) > this.cash_patient_payable_total)
    {
      this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
      // this.invoice_data.cash_discount = "0"
      return false;
    }
    
      this.data();
      this.loaderService.display(true)
      this.bill.savediscount(this.invoice).subscribe((result) => {
        this.loaderService.display(false)
        if(result.status == "Success")
        {
          this.notifier.notify( 'success', result.message ); 
          // this.claim_billing_id = result.claim_billing_id
          // this.cash_billing_id = result.cash_billing_id
          this.getBillByAssessment();
        }   
        else
        {
          this.notifier.notify( 'error', result.message );
        }
      }, (err) => {
        console.log(err);
      });
    
  }
  public getPostData(value)
  {
    this.cpt_code_ids = [];
    this.lab_details_ids = [];
    this.price = []
    this.co_payment = []
    this.co_payment_type = []
    this.patient_pay = []
    this.total_rate = []
    this.checked = []
    this.patient_total = []
    if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1)
    {
      if(this.patient_details.ins_data)
      this.patient_type_detail_id = this.patient_details.ins_data.OP_INS_DETAILS_ID
    }
    else if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 3)
    {
      if(this.patient_details.corporate_data)
      this.patient_type_detail_id = this.patient_details.corporate_data.OP_CORPORATE_DETAILS_ID
    }
    if(value == 1)
    {
      var i= 0;
      for (var data of this.claim_list) 
      {
        this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
        this.cpt_code_ids[i] = data.PROCEDURE_CODE
        this.price[i] = data.RATE
        this.co_payment[i] = data.CO_PAYMENT
        this.co_payment_type[i] = data.CO_PAYMENT_TYPE
        this.patient_pay[i] = data.PATIENT_PAYABLE
        this.total_rate[i] = data.TOTAL
        this.checked[i] = data.INSURANCE_STATUS
        this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
        i = i+1; 
      }
     
      return  {
        lab_details_ids : this.lab_details_ids,
        billing_id : this.claim_billing_id,
        patient_id : this.patient_id,
        assessment_id : this.assessment_id,
        payment_type : this.payment_type,
        cpt_code_ids : this.cpt_code_ids,
        price : this.price,
        co_payment : this.co_payment,
        co_payment_type : this.co_payment_type,
        patient_pay :this.patient_pay,
        billing_date :this.dateVal,
        billed_amount : this.claim_gross_total,
        paid_by_patient : this.claim_patient_payable_total,
        insured_amount : this.claim_net_total,
        user_id : this.user_data.user_id,
        total : this.total_rate,
        prior_authorization : this.prior_authorization,
        patient_payable_total : this.patient_total,
        insurance_payable_total : this.total_rate,
        payment_mode:this.payment_mode,
        date: defaultDateTime(),
        timeZone: getTimeZone(),
        bill_type : 1,
        patient_type : this.patient_details.patient_data.OP_REGISTRATION_TYPE,
        patient_type_detail_id: this.patient_type_detail_id,
        
      }
    }
    if(value == 0)
    {
     
      var i= 0;
      for (var data of this.cash_list) 
      {
        this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
        this.cpt_code_ids[i] = data.PROCEDURE_CODE
        this.price[i] = data.RATE
        this.co_payment[i] = data.CO_PAYMENT
        this.co_payment_type[i] = data.CO_PAYMENT_TYPE
        this.patient_pay[i] = data.PATIENT_PAYABLE
        this.total_rate[i] = data.TOTAL
        this.checked[i] = data.INSURANCE_STATUS
        this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
        i = i+1; 
      }
      return  {
        lab_details_ids : this.lab_details_ids,
        billing_id : this.cash_billing_id,
        patient_id : this.patient_id,
        assessment_id : this.assessment_id,
        payment_type : this.payment_type,
        cpt_code_ids : this.cpt_code_ids,
        price : this.price,
        co_payment : this.co_payment,
        co_payment_type : this.co_payment_type,
        patient_pay :this.patient_pay,
        billing_date :this.dateVal,
        billed_amount : this.cash_gross_total,
        paid_by_patient : this.cash_patient_payable_total,
        insured_amount : this.cash_net_total,
        user_id : this.user_data.user_id,
        total : this.total_rate,
        prior_authorization : this.prior_authorization,
        patient_payable_total : this.patient_total,
        insurance_payable_total : this.total_rate,
        insurance_status : this.checked,
        payment_mode:this.payment_mode,
        date: defaultDateTime(),
        timeZone: getTimeZone(),
        bill_type : 0,
        patient_type : this.patient_details.patient_data.OP_REGISTRATION_TYPE,
        patient_type_detail_id: this.patient_type_detail_id,
        
      }
    }
  }
  public checkstatusInvoice()
  {
    for(let data of this.table_invoice_list)
    {
      data["BILL_TYPE"] = 2;
      if(this.cash_full_list.length > 0){
        for(let cash of this.cash_full_list)
        {
          for (let cash1 of cash.bill_details) {
            if(cash1.LAB_INVESTIGATION_DETAILS_ID == data.LAB_INVESTIGATION_DETAILS_ID)
            {
              data["BILL_TYPE"] = 0
            }
          }
        }
      }
      if(this.credit_full_list.length > 0){
        for(let claim of this.credit_full_list)
        {
          for (let claim1 of claim.bill_details) 
          {
            if(data.LAB_INVESTIGATION_DETAILS_ID == claim1.LAB_INVESTIGATION_DETAILS_ID)
            {
              data["BILL_TYPE"] = 1
            }
          }
        }
      }
    }
    // console.log(this.table_invoice_list)
  }

  public getBill(i,invoice)
  {
    console.log(invoice.BILLING_ID);
    const post2Data = {
      billing_id: invoice.BILLING_ID,
    };
    this.loaderService.display(true);
    this.bill.getBill(post2Data).subscribe((result: {}) => {
    this.loaderService.display(false);
     if (result['status'] === 'Success') {
      this.invoice = result["data"];
      // this.cash_department_id = 0;
      // this.claim_department_id = 0;
      if(this.invoice["BILL_TYPE"]==1){
             this.claim_invoice_number = this.invoice["DUPLICATE_INVOICE_NUMBER"]
            this.claim_billing_id = this.invoice["BILLING_ID"]
            this.claim_bill_id = this.invoice;
            this.claim_generate_status = this.invoice["GENERATED"];
            this.claim_list = this.invoice["bill_details"];
            this.invoice_data.claim_discount_type = 2;
            this.invoice_data.claim_amount = this.invoice["PATIENT_DISCOUNT"];
            this.invoice_data.claim_discount = this.invoice["PATIENT_DISCOUNT"];
            this.claim_patienttotal = parseFloat(this.invoice["PATIENT_PAYABLE"]) - parseFloat(this.invoice["PATIENT_DISCOUNT"]);
            this.claim_gross_total = parseFloat(this.invoice["BILLED_AMOUNT"]);
            this.claim_net_total = parseFloat(this.invoice["INSURED_AMOUNT"]);
            this.claim_bill_total = parseFloat(this.invoice["INSURED_AMOUNT"]);
            this.claim_patient_payable_total = parseFloat(this.invoice["PATIENT_PAYABLE"]);
            this.claim_payment_mode = this.invoice["PAYMENT_MODE"]
            this.payment_mode = this.invoice["PAYMENT_MODE"]
            if(this.invoice["DEPARTMENT_ID"]>0){
              this.claim_department_id = 1;
            }
            else{
              this.claim_department_id = 0;
            }
            var i=0
            for(let data of this.claim_list)
            {
              data["RATE"] = data.TOTAL_AMOUNT
              data["CO_PAYMENT"] = data.COINS
              data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
              data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
              this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
              i=i+1;
            }

      }else{
        this.cash_invoice_number = this.invoice["DUPLICATE_INVOICE_NUMBER"]
        this.cash_billing_id = this.invoice["BILLING_ID"]
        this.cash_generate_status = this.invoice["GENERATED"];
        this.cash_bill_id = this.invoice;
        this.cash_list = this.invoice["bill_details"];
        this.invoice_data.cash_discount_type = 2;
        this.invoice_data.cash_amount = this.invoice["PATIENT_DISCOUNT"];
        this.invoice_data.cash_discount = this.invoice["PATIENT_DISCOUNT"];
        this.cash_patienttotal = parseFloat(this.invoice["BILLED_AMOUNT"]) - parseFloat(this.invoice["PATIENT_DISCOUNT"]);
        this.cash_gross_total = parseFloat(this.invoice["BILLED_AMOUNT"]);
        this.cash_net_total = parseFloat(this.invoice["INSURED_AMOUNT"]);
        this.cash_bill_total = parseFloat(this.invoice["INSURED_AMOUNT"]);
        this.cash_patient_payable_total = parseFloat(this.invoice["PATIENT_PAYABLE"]) 
        this.cash_payment_mode = this.invoice["PAYMENT_MODE"]
        this.payment_mode = this.invoice["PAYMENT_MODE"]
        if(this.invoice["DEPARTMENT_ID"]>0){
          this.cash_department_id = 1;
        }
        else{
          this.cash_department_id = 0;
        }
        var i=0
        for(let data of this.cash_list)
        {
          data["RATE"] = data.TOTAL_AMOUNT
          data["CO_PAYMENT"] = data.COINS
          data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
          data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
          i=i+1;
        }
      }
     }
   });
  }

  public data()
  {
    this.cpt_code_ids = [];
    this.lab_details_ids = [];
    this.price = []
    this.co_payment = []
    this.co_payment_type = []
    this.patient_pay = []
    this.total_rate = []
    this.checked = []
    this.patient_total = []
    if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1)
    {
      if(this.patient_details.ins_data)
      this.patient_type_detail_id = this.patient_details.ins_data.OP_INS_DETAILS_ID
    }
    else if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 3)
    {
      if(this.patient_details.corporate_data)
      this.patient_type_detail_id = this.patient_details.corporate_data.OP_CORPORATE_DETAILS_ID
    }
    var i= 0;
    if(this.claim_list.length > 0){
      for (var data of this.claim_list) 
      {
        this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
        this.cpt_code_ids[i] = data.PROCEDURE_CODE
        this.price[i] = data.RATE
        this.co_payment[i] = data.CO_PAYMENT
        this.co_payment_type[i] = data.CO_PAYMENT_TYPE
        this.patient_pay[i] = data.PATIENT_PAYABLE
        this.total_rate[i] = data.TOTAL
        this.checked[i] = data.INSURANCE_STATUS
        this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
        i = i+1; 
      }
   
      this.invoice.claim_invoice =  {
        lab_details_ids : this.lab_details_ids,
        billing_id : this.claim_billing_id,
        patient_id : this.patient_id,
        assessment_id : this.assessment_id,
        payment_type : this.payment_type,
        cpt_code_ids : this.cpt_code_ids,
        price : this.price,
        co_payment : this.co_payment,
        co_payment_type : this.co_payment_type,
        patient_pay :this.patient_pay,
        billing_date :this.dateVal,
        billed_amount : this.claim_gross_total,
        paid_by_patient : this.claim_patient_payable_total,
        insured_amount : this.claim_net_total,
        user_id : this.user_data.user_id,
        total : this.total_rate,
        prior_authorization : this.prior_authorization,
        patient_payable_total : this.patient_total,
        insurance_payable_total : this.total_rate,
        payment_mode:this.payment_mode,
        date: defaultDateTime(),
        timeZone: getTimeZone(),
        bill_type : 1,
        discount:this.invoice_data.claim_discount,
        patient_type : this.patient_details.patient_data.OP_REGISTRATION_TYPE,
        patient_type_detail_id: this.patient_type_detail_id,
      }
    }
    if(this.cash_list.length > 0){
      this.cash_cpt_code_ids = [];
      this.cash_lab_details_ids = [];
      this.cash_price = []
      this.cash_co_payment = []
      this.cash_co_payment_type = []
      this.cash_patient_pay = []
      this.cash_total_rate = []
      this.cash_checked = []
      this.cash_patient_total = []
      var i= 0;
      for (var data of this.cash_list) 
      {
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
        i = i+1; 
      }
      this.invoice.cash_invoice =  {
        lab_details_ids : this.cash_lab_details_ids,
        billing_id : this.cash_billing_id,
        patient_id : this.patient_id,
        assessment_id : this.assessment_id,
        payment_type : this.payment_type,
        cpt_code_ids : this.cash_cpt_code_ids,
        price : this.cash_price,
        co_payment : this.cash_co_payment,
        co_payment_type : this.cash_co_payment_type,
        patient_pay :this.cash_patient_pay,
        billing_date :this.dateVal,
        billed_amount : this.cash_gross_total,
        paid_by_patient : this.cash_patient_payable_total,
        insured_amount : this.cash_net_total,
        user_id : this.user_data.user_id,
        total : this.cash_total_rate,
        prior_authorization : this.prior_authorization,
        patient_payable_total : this.cash_patient_total,
        insurance_payable_total : this.cash_total_rate,
        insurance_status : this.cash_checked,
        payment_mode:this.payment_mode,
        date: defaultDateTime(),
        timeZone: getTimeZone(),
        bill_type : 0,
        discount:this.invoice_data.cash_discount,
        patient_type : this.patient_details.patient_data.OP_REGISTRATION_TYPE,
        patient_type_detail_id: this.patient_type_detail_id,
      }
    }
  }
  // public savePatientBill()
  // {
  //  // console.log("this.payment_mode  "+this.payment_mode);
  // //  if(parseFloat(this.invoice_data.discount) > this.patient_payable_total)
  // //  {
  // //    this.notifier.notify('warning','The discount amount should be less than or equal to patient payable amount')
  // //    this.invoice_data.discount = "0"
  // //    return false;
  // //  }
  //   var sendJson = {
  //     lab_details_ids : this.lab_details_ids,
  //     billing_id : this.billing_id,
  //     patient_id : this.patient_id,
  //     assessment_id : this.assessment_id,
  //     payment_type : this.payment_type,
  //     cpt_code_ids : this.cpt_code_ids,
  //     price : this.price,
  //     co_payment : this.co_payment,
  //     co_payment_type : this.co_payment_type,
  //     patient_pay : this.patient_pay,
  //     billing_date :this.dateVal,
  //     billed_amount : this.gross_total,
  //     paid_by_patient : this.patient_payable_total,
  //     insured_amount : this.net_total,
  //     user_id : this.user_data.user_id,
  //     total : this.total_rate,
  //     prior_authorization : this.prior_authorization,
  //     patient_payable_total :this.patient_total,
  //     insurance_payable_total :this.total_rate,
  //     insurance_status : this.checked,
  //     payment_mode:this.payment_mode,
  //    // discount:this.invoice_data.discount,
  //     date: defaultDateTime(),
  //     timeZone: getTimeZone()
      
  //   }
  //  // this.getdiscAmount();
  //   //console.log("this.payment_mode "+this.payment_mode);
  //     this.bill.savePatientBill(sendJson).subscribe((result) => {
  //       this.loaderService.display(false)
        
  //       if(result.status == "Success")
  //       {
  //         this.notifier.notify( 'success', result.message ); 
  //         this.invoice_number = result.billing_no
  //         this.status = result.status
  //         this.billing_id = result.billing_id
  //        this.getBillByAssessment();
  //       }   
  //       else
  //       {
  //         this.notifier.notify( 'error', result.message );
  //       }
  //     }, (err) => {
  //       console.log(err);
  //     });

  // }
  
 /*printBill(): void {
    var printContents = document.getElementById('printBill').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
   }*/
  
  // printBill(): void {
  //   let printContents, popupWin;
  //   printContents = document.getElementById('printBill').innerHTML;
  //   popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
  //   popupWin.document.open();
  //   popupWin.document.write('<html><head><link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /></head><body onload="window.print();window.close();">' + printContents + '</body></html>');
  //   popupWin.document.close();
    
  // }  
  // public getPostData(value)
  // {
  //   this.cpt_code_ids = [];
  //   this.lab_details_ids = [];
  //   //this.prior_authorization = []
  //   this.price = []
  //   this.co_payment = []
  //   this.co_payment_type = []
  //   this.patient_pay = []
  //   this.total_rate = []
  //   this.checked = []
  //   this.patient_total = []
  //   if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1)
  //   {
  //     if(this.patient_details.ins_data)
  //     this.patient_type_detail_id = this.patient_details.ins_data.OP_INS_DETAILS_ID
  //   }
  //   else if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 3)
  //   {
  //     if(this.patient_details.corporate_data)
  //     this.patient_type_detail_id = this.patient_details.corporate_data.OP_CORPORATE_DETAILS_ID
  //   }
  //   if(value == 1)
  //   {
  //     var i= 0;
  //     for (var data of this.claim_list) 
  //     {
  //       this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
  //       this.cpt_code_ids[i] = data.PROCEDURE_CODE
  //     //    this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
  //       this.price[i] = data.RATE
  //       this.co_payment[i] = data.CO_PAYMENT
  //       this.co_payment_type[i] = data.CO_PAYMENT_TYPE
  //       this.patient_pay[i] = data.PATIENT_PAYABLE
  //       this.total_rate[i] = data.TOTAL
  //       this.checked[i] = data.INSURANCE_STATUS
  //       this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
  //       i = i+1; 
  //     }
     
  //     return  {
  //       lab_details_ids : this.lab_details_ids,
  //       billing_id : this.claim_billing_id,
  //       patient_id : this.patient_id,
  //       assessment_id : this.assessment_id,
  //       payment_type : this.payment_type,
  //       cpt_code_ids : this.cpt_code_ids,
  //       price : this.price,
  //       co_payment : this.co_payment,
  //       co_payment_type : this.co_payment_type,
  //       patient_pay :this.patient_pay,
  //       billing_date :this.dateVal,
  //       billed_amount : this.claim_gross_total,
  //       paid_by_patient : this.claim_patient_payable_total,
  //       insured_amount : this.claim_net_total,
  //       user_id : this.user_data.user_id,
  //       total : this.total_rate,
  //       prior_authorization : this.prior_authorization,
  //       patient_payable_total : this.patient_total,
  //       insurance_payable_total : this.total_rate,
  //       payment_mode:this.payment_mode,
  //       date: defaultDateTime(),
  //       timeZone: getTimeZone(),
  //       bill_type : 1,
  //       discount:this.invoice_data.claim_discount,
  //       patient_type : this.patient_details.patient_data.OP_REGISTRATION_TYPE,
  //       patient_type_detail_id: this.patient_type_detail_id,
        
  //     }
  //   }
  //   if(value == 0)
  //   {
  //     if(this.cash_list.length > 0){
  //       if(parseFloat(this.invoice_data.cash_discount) > this.cash_patient_payable_total)
  //       {
  //         this.invoice_data.cash_discount = "0"
  //         return this.notifier.notify('warning','The cash invoice discount amount should be less than or equal to patient payable amount')
  //       }
  //     }else{
  //       this.invoice_data.cash_discount = "0"
  //     }
  //     var i= 0;
  //     for (var data of this.cash_list) 
  //     {
  //       this.lab_details_ids[i] = data.LAB_INVESTIGATION_DETAILS_ID
  //       this.cpt_code_ids[i] = data.PROCEDURE_CODE
  //      // this.prior_authorization[i] = data.PRIOR_AUTHORIZATION
  //       this.price[i] = data.RATE
  //       this.co_payment[i] = data.CO_PAYMENT
  //       this.co_payment_type[i] = data.CO_PAYMENT_TYPE
  //       this.patient_pay[i] = data.PATIENT_PAYABLE
  //       this.total_rate[i] = data.TOTAL
  //       this.checked[i] = data.INSURANCE_STATUS
  //       this.patient_total[i] = data.TOTAL_PATIENT_PAYABLE
  //       i = i+1; 
  //     }
  //     return  {
  //       lab_details_ids : this.lab_details_ids,
  //       billing_id : this.cash_billing_id,
  //       patient_id : this.patient_id,
  //       assessment_id : this.assessment_id,
  //       payment_type : this.payment_type,
  //       cpt_code_ids : this.cpt_code_ids,
  //       price : this.price,
  //       co_payment : this.co_payment,
  //       co_payment_type : this.co_payment_type,
  //       patient_pay :this.patient_pay,
  //       billing_date :this.dateVal,
  //       billed_amount : this.cash_gross_total,
  //       paid_by_patient : this.cash_patient_payable_total,
  //       insured_amount : this.cash_net_total,
  //       user_id : this.user_data.user_id,
  //       total : this.total_rate,
  //       prior_authorization : this.prior_authorization,
  //       patient_payable_total : this.patient_total,
  //       insurance_payable_total : this.total_rate,
  //       insurance_status : this.checked,
  //       payment_mode:this.payment_mode,
  //       date: defaultDateTime(),
  //       timeZone: getTimeZone(),
  //       bill_type : 0,
  //       discount:this.invoice_data.cash_discount,
  //       patient_type : this.patient_details.patient_data.OP_REGISTRATION_TYPE,
  //       patient_type_detail_id: this.patient_type_detail_id,
        
  //     }
  //   }
  // }
  // public save()
  // {
  //   if(this.patient_details.patient_data.OP_REGISTRATION_TYPE == 1)
  //   {
  //     if(this.claim_list.length > 0){
  //       if(parseFloat(this.invoice_data.claim_discount) > this.claim_patient_payable_total)
  //       {
  //         this.invoice_data.claim_discount = "0"
  //         return this.notifier.notify('warning','The credit invoice discount amount should be less than or equal to patient payable amount')
  //       }
  //     }
  //     if(this.cash_list.length > 0){
  //       if(parseFloat(this.invoice_data.cash_discount) > this.cash_patient_payable_total)
  //       {
  //         this.invoice_data.cash_discount = "0"
  //         return this.notifier.notify('warning','The cash invoice discount amount should be less than or equal to patient payable amount')
  //       }
  //     }
  //     else{
  //       this.invoice_data.cash_discount = "0"
  //     }
      
  //     if(this.claim_list.length > 0 || this.cash_list.length > 0){
  //       var data = this.saveclaimPatientBill();
  //       var datas = this.savecashPatientBill();
         
  //     // this.getBillByAssessment();
  //     }
  //     else
  //     {
  //       this.notifier.notify( 'warning', "Please add atleast one item of any invoice..!" ); 
  //     }
  //   }
  //   else
  //   {
  //     if(this.cash_list.length > 0){
  //       this.savecashPatientBill();
  //     // this.getBillByAssessment();
  //     }
  //     else
  //     {
  //       this.notifier.notify( 'warning', "Please add atleast one item of any invoice..!" ); 
  //     }
  //   }
  // }

  // public saveclaimPatientBill()
  // {
    
  //   var sendJson = this.getPostData(1)
    
  //     this.bill.savePatientBill(sendJson).subscribe((result) => {
  //       this.loaderService.display(false)
        
  //       if(result.status == "Success")
  //       {
  //        //this.notifier.notify( 'success', result.message ); 
  //         this.invoice_number = result.billing_no
  //         this.status = result.status
  //         this.billing_id = result.billing_id
  //         this.invoice_data.claim_discount_type = 2
  //      //  this.getBillByAssessment();
  //       }   
  //       else
  //       {
  //         this.notifier.notify( 'error', result.message );
  //       }
  //     }, (err) => {
  //       console.log(err);
  //     });

  // }
  // public savecashPatientBill()
  // {
  //   if(this.cash_list.length > 0){
  //     if(parseFloat(this.invoice_data.cash_discount) > this.cash_patient_payable_total)
  //     {
  //       this.invoice_data.cash_discount = "0"
  //       return this.notifier.notify('warning','The cash invoice discount amount should be less than or equal to patient payable amount')
  //     }
  //   }
  //   else{
  //     this.invoice_data.cash_discount = "0"
  //   }
  //   var sendJson = this.getPostData(0)
  //     this.bill.savePatientBill(sendJson).subscribe((result) => {
  //       this.loaderService.display(false)
        
  //       if(result.status == "Success")
  //       {
  //        // this.notifier.notify( 'success', result.message ); 
  //         this.invoice_number = result.billing_no
  //         this.status = result.status
  //         this.billing_id = result.billing_id
  //         this.invoice_data.cash_discount_type = 2
  //       // this.getBillByAssessment();
  //       }   
  //       else
  //       {
  //         this.notifier.notify( 'error', result.message );
  //       }
  //     }, (err) => {
  //       console.log(err);
  //     });

  // }
 
  private confirms(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true,
      ariaLabelledBy: 'modal-basic-title',
      size: 'sm'
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
      return  `with: ${reason}`;
    }
  } 
}
