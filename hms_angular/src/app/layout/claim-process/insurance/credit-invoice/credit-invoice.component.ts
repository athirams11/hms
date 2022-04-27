import { Component, OnInit, HostListener, Input, Output, EventEmitter, NgModule, ViewChild, OnChanges, SimpleChanges } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LoaderService} from '../../../../shared';
import moment from 'moment-timezone';
import { OpRegistrationService, DoctorsService, NursingAssesmentService } from '../../../../shared';
import { BillingService } from 'src/app/shared/services/billing.service';
import { AppSettings } from 'src/app/app.settings';
import { formatTime, formatDateTime, formatDate } from '../../../../shared/class/Utils';
import { interval, observable, Subscription } from 'rxjs';
@Component({
  selector: 'app-credit-invoice',
  templateUrl: './credit-invoice.component.html',
  styleUrls: ['./credit-invoice.component.scss']
})
export class CreditInvoiceComponent implements OnInit {
 
  @Input() id: string;
  @Output() pageChange: EventEmitter<number>;
  public subscription: Subscription;
  source = interval(50000);
  public f=0;
  public collectionSize = '';
  public checkall;
  public claim_checkall;
  public select_satat;
  public status: any;
  public selectall_satat = 1;
  public pageSize = 100;
  public user_rights: any = {};
  public filesForsubmission: any = [];
  public file_list: any = [];
  public networks: any = [];
  public ins_com_pay: any = [];
  public network_name: any = [];
  public unclaimed_list: any = {};
  public claimXml_list: any = [];
  public start = 0;
  public invoice_flag;
  public claim_flag;
  public ins_company = 0;
  public ins_network = 0;
  public now = new Date();
  public date: any = new Date();
  public patient_id;
  public assessment_id;
  public bill_ids: any = [];
  public limit = 100;
  public select_box: [];
  public claim_check_box_flag;
  public claim_collectiom;
  public check_box_flag = 0;
  public check_box_flag2 = 0;
  public checked: [''];
  public tpa_id;
  public tpa_cose;
  public datval: Date;
  public tpa_code;
  public popup_box = '';
  public gender: any = [ 'Female', 'Male'];
  public gender_sur: any = [ 'Ms.', 'Mr.'];
  closeResult: string;
  public index;
  //public test_result:any= [];
  public prior_authorisation:any= [];
  public bill_details:any=[];
  public billing_details_id:any=[];
  public lab_details_id:any=[];
  // public check=0;
  public appData = {
    fromDateval:  new Date(),
    toDateval: new Date(),
    insCompany: '',
    insNetwork: '',
    tpa:'',
    search_text: '',
    checked: ['']
     };
     public varification_data = {
      name: '',
      patient_no: '',
      tpa_reciever: '',
      ins_co_payer: '',
      network: '',
      member_id: '',
      policy_no: '',
      claim_amount: '',
      status: '',
      bill_id: ''
     };
     ps = 100;
     public submit_flag;
     public submitedFile_collection: any = '';
     public submitedFile_list: any = [];
     public submited_files = {
      fromDateval:  new Date(),
      toDateval: new Date(),
      search_text: '',
     };
     public notDownloadedfile_list: any = [];
     public notDownloadedfile_colletion;
  p = 10;
  pc = 100;
  public collection: any = '';
  public claim_collection: any = '';
  page = 1;
  filter = '';
  filter_ap_list = '';
  public user_data: any;
  public cpt_code_ids: any = {};
  public cpt_code_id: any = {};
  public invoice_list: any = [];
  public patient_details: any = [];
  public co_ins: any = [];
  insurance_tpa_id: any;
  insurance_network_id: any;
  public ins_data: any = [];
  payment_type: any;
  invoice_number: any;
  public cpt_rate: any = [];
  table_invoice_list: any[];
  public bill_total = 0;
  public bill_id : any ={};
  public gross_total = 0;
  public patient_payable_total = 0;
  public net_total = 0; 
  public billing_id :number = 0;
  public patient_pay_tot: number = 0;
  public price: any = {};
  public co_payment_type: any = {};
  public co_payment: any = {};
  public patient_pay: any = {};
  public billing_data: any = [];
  public total_rate: any = {};
  public varify_flag = 0;
  public xml_file = {
    file_id: '',
    file_name: '',
    file_content : ''
  };
  public lab_investigation_id:any;
  public lab_investigation_result: any = [];
  public lab_investigation_result_value:any=[];
  public lab_investigation_edit:any=[];
  public test_result: any = [];
  time: any;
  constructor(  public bill: BillingService, public nas: NursingAssesmentService, public Doc: DoctorsService, private loaderService: LoaderService, private modalService: NgbModal, public rest: OpRegistrationService , public notifier: NotifierService ) {
  }
  // public datepipe: DatePipe,

  ngOnInit() {
    this.getDropdowns();
    this.checkall = 0;
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.appData.fromDateval = new Date(this.appData.toDateval.getFullYear(), this.appData.toDateval.getMonth(), 1);
    this.time = moment.tz.guess();
    this.unClaimedInvoiceList();
    this.subscription = this.source.subscribe(x => this.unClaimedInvoiceList(1));
  }
  // public formatDateTime (data) {
  //   if (this.now) {
  //     this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm ');
  //   }
  //   if (data) {
  //     data = moment(data, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm');
  //     return data;
  //   }
  // }
  // public formatDateTimeremitance (data) {
  //   if (this.now) {
  //     this.date =  moment(this.now, 'yyyy/MM/D HH:mm.ss a').format('D-MM-Y h:mm');
  //   }
  //   if (data) {
  //     data = moment(data, 'yyyy/MM/D HH:mm.ss a').format('D-MM-Y hh:mm');
  //     return data;
  //   }
  // }
  // public formatTime (time) {
  //   let retVal = '';
  //   if (time !== '') {
  //     retVal =  moment(time, 'HH:mm.ss').format('HH:MM:ss');
  //   }
  //   return  retVal;
  // }
  // public formatDate(data) {
  //   if (this.now) {
  //     this.date =  moment(this.now, 'yyyy-MM-D HH').format('D-MM-YHH');
  //   }
  //   if (data) {
  //     data = moment(data, 'yyyy-MM-D HH').format('D-MM-Y');
  //     return data;
  //   }
  // }
  ngOnDestroy() {
    if (this.subscription) {  
      this.subscription.unsubscribe();
   }
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
  public getDropdowns() {

    const myDate = new Date();
    this.loaderService.display(true);
    this.rest.getOpDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {

        if (data['networks']['status'] === 'Success') {
          this.networks = data['networks']['data'];
        }
        if (data['ins_com_pay']['status'] === 'Success') {
          this.ins_com_pay = data['ins_com_pay']['data'];
        }
        if (data['tpa_receiver']['status'] === 'Success') {
          this.network_name = data['tpa_receiver']['data'];
        }
      }

    });
  }
  public unClaimedInvoiceList(f=0) {
    if (f==0) {
      this.unclaimed_list = [];
    } 
      const post2Data: any = {
      invoice_date_from: this.formatDateTime(this.appData.fromDateval),
      invoice_date_to : this.formatDateTime(this.appData.toDateval),
      search_text: this.appData.search_text,
      insurance_payer_id: this.appData.insCompany,
      insurance_tpa_id: this.appData.tpa,
      insurance_network_id: this.appData.insNetwork,
      start: this.start,
      limit: this.limit,
      timeZone:this.time
};
    if (f==0) {
      this.loaderService.display(true);
    }    
    this.rest.unClaimedInvoiceList(post2Data).subscribe((result: {}) => {
    this.status = result['status'];
     if (result['status'] === 'Success') {
        //console.log(result);
        this.loaderService.display(false);
        this.unclaimed_list = result['data'];
        this.collection = result['count'];
        this.invoice_flag = this.collection;
        this.check_box_flag = 0;
        this.checkall === 0;
        this.dissable(this.unclaimed_list.length);
        // console.log('collection size' + this.collection);

        this.loaderService.display(false);

     }
     this.invoice_flag = this.collection;
    // console.log('collection size' + this.collection);
     this.loaderService.display(false);
   });
  }
  public saveBillresult() {
    var i=this.table_invoice_list.length;
  //  console.log("i  "+i);
    
   for (let index = 0; index < this.table_invoice_list.length; index++) {
    this.billing_details_id[index] = this.bill_details[index].BILLING_DETAILS_ID;
    this.prior_authorisation[index] = this.prior_authorisation[index];
    // this.lab_details_id =this.bill_details[index].LAB_INVESTIGATION_DETAILS_ID
  }
  // console.log("this.billing_details_id  "+this.billing_details_id);
  // console.log("this.prior_authorisation  "+this.prior_authorisation);
    const post2Data: any = {
      billing_details_ids:this.billing_details_id,
      //test_result:this.test_result[i],
      prior_authorization:this.prior_authorisation,
      lab_details_id:this.lab_details_id,
};
  this.loaderService.display(true);
  this.rest.saveBillresult(post2Data).subscribe((result: {}) => {
  this.status = result['status'];
   if (result['status'] === 'Success') {
      this.loaderService.display(false);
      this.notifier.notify( 'success', result['msg']);
      console.log(result);
   }
   else{
   this.loaderService.display(false);
   this.notifier.notify( 'error', result['msg']);
   }
 });
}
  


  public selectAll() {
    // console.log('this.check_box_flag ' + this.check_box_flag);
    if (this.check_box_flag === 0) {
      for (let index = 0; index < this.unclaimed_list.length; index++) {
        this.unclaimed_list[index]['check'] = 1;
      //  console.log(' this.unclaimed_list[index][check]' + this.unclaimed_list[index]['check']);
      }
      this.checkall = 1;

      this.check_box_flag = 1;
    } else {
         this.checkall = '';

        // console.log('else falg');
         for (let index = 0; index < this.unclaimed_list.length; index++) {
          this.unclaimed_list[index]['check'] = 0;
          //console.log(' this.unclaimed_list[index][check] = ' + this.unclaimed_list[index]['check']);
        }
        this.check_box_flag = 0;
    }

  }
public clear_search() {

  this.appData.search_text = '';
  this.submited_files.search_text ='';
  this.unClaimedInvoiceList();
}
// dissable all select button
public dissable(k) {
  this.selectall_satat = 0;
  let check = null;
  for (let index = 0; index < k; index++) {
    if (check == null) {
      check = this.unclaimed_list[index].OP_INS_PAYER;
    } else  {
      this.selectall_satat = 1;
    }
  }
}


public select(event, checkall, i, value) {
//  console.log('event  ' + event.BILLING_ID);

  let counter = 0;
  this.ins_company = event.OP_INS_PAYER;
  this.billing_id = event.BILLING_ID;
  for (let index = 0; index < this.unclaimed_list.length; index++) {
    if ( this.billing_id === this.unclaimed_list[index].BILLING_ID && this.unclaimed_list[index].check !== 1) {
      this.unclaimed_list[index]['check'] = 1;
    } else if (this.billing_id === this.unclaimed_list[index].BILLING_ID) {
    this.unclaimed_list[index]['check'] = 0;
    }
    if (this.unclaimed_list[index].check === 1) { counter ++; }
  }
  if (counter === 0) {
   // console.log('check counter' + JSON.stringify(this.unclaimed_list));
    this.ins_company = 0;
  }
// check the api for geting
  }
  public setFile() {
    let i = 0;
    for (let index = 0; index < this.unclaimed_list.length; index++) {
      if ( this.unclaimed_list[index]['check'] === 1) {
        this.filesForsubmission[index] = this.unclaimed_list[index];
        if (this.filesForsubmission[index].BILLING_ID !== 0 && this.filesForsubmission[index].BILLING_ID !== '') {
        this.bill_ids[i] = this.unclaimed_list[index].BILLING_ID;
        this.tpa_id = this.unclaimed_list[0].OP_INS_TPA;
        this.date = moment(new Date()).format("YMDHIS");
        this.popup_box = this.unclaimed_list[0].TPA_CODE.concat("-").concat(this.date);
        //console.log('popup_box ' + this.popup_box);

        this.tpa_code = this.unclaimed_list[0].TPA_CODE;
        }
        i = i + 1;
      }

    }
  }
  public getBillVarificationDetails(data) {
    const sendJson = {
      billing_id : data.BILLING_ID
    };
    this.loaderService.display(true);
    this.rest.getBillVarificationDetails(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.billing_data = result.data;
      //  console.log('billing data ' + this.billing_data);
        this.varification_data.name = this.billing_data.NAME + this.billing_data.MIDDLE_NAME + this.billing_data.LAST_NAME;
        this.varification_data.patient_no = this.billing_data.PATIENT_NO;
        this.varification_data.ins_co_payer = this.billing_data.INSURANCE_PAYERS_NAME;
        this.varification_data.network = this.billing_data.INS_NETWORK_NAME;
        this.varification_data.policy_no = this.billing_data.POLICY_NO;
        this.varification_data.tpa_reciever = this.billing_data.TPA_NAME;
        this.varification_data.claim_amount = this.billing_data.INSURED_AMOUNT;
        this.varification_data.member_id = this.billing_data.MEMBER_ID;
        this.varification_data.status = this.billing_data.VERIFICATION_STATUS;
        this.varification_data.bill_id = this.billing_data.BILLING_ID;
      } else {
      }
    }, (err) => {
      console.log(err);
    });
  }



  public SubmissioniFleList(page = 0) {
    if ( this.popup_box === '') {
      this.notifier.notify( 'error', ' Please select files');
    } else {
// console.log('bills ' + JSON.stringify(this.bill_ids));
// console.log('this.filesForsubmission, ' + this.tpa_id);

    const post2Data = {
      bill_ids: this.bill_ids,
      tpa_id: this.tpa_id,
      user_id: this.user_data.user_id,
      tpa_code : this.tpa_code,
      file_name : this.popup_box,
      client_date: this.appData.toDateval
  };
  this.loaderService.display(true);
  this.rest.GenerateSubmissionFiles(post2Data).subscribe((result: {}) => {
  this.status = result['status'];
   if (result['status'] === 'Success') {
    this.unClaimedInvoiceList();
    this.page = 1;
    this.notifier.notify( 'success',  result['message']);
    this.loaderService.display(false);
   } else {
    this.loaderService.display(false);
    this.notifier.notify( 'error', result['message']);
   }
  });
  }
}
  private open(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title', size: 'lg', windowClass:'col-md-12', }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private opens(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private opened(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title', size: 'lg', windowClass:'col-md-10', }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }

  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason == ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return  `with: ${reason}`;
    }
  }

                          // ...................................  bill   .....................

  public setbill(data) {
    //this.test_result=[];
    this.lab_investigation_result_value=[];
    this.prior_authorisation=[];
    this.patient_id = data.PATIENT_ID;
    this.assessment_id = data.NURSING_ASSESSMENT_ID;
    this.getBillByAssessment();
    // this.get_investigation();
    // this.getLabInvestigationResults(); 
  //  this.getPatientDetails();
    this.getBillVarificationDetails(data);
    this.selectall_satat = 0;
  }
  public varifyPatientData() {
   // console.log(' varification list   ' + JSON.stringify(this.varification_data));

    const sendJson = {
      billing_id: this.varification_data.bill_id,
      name: this.varification_data.name,
      member_id: this.varification_data.member_id,
      policy_no: this.varification_data.policy_no
    };
    this.loaderService.display(true);
    this.rest.confirmBillverificationData(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        for (let index = 0; index < this.unclaimed_list.length; index++) {
          if (this.unclaimed_list[index].BILLING_ID === this.varification_data.bill_id ) {
          this.unclaimed_list[status] = 1;
        }
      }
  //    console.log('this.unclaimed_list.status ' + this.unclaimed_list.status);

        this.notifier.notify( 'success',  result['message']);
        this.unClaimedInvoiceList();
      } else {
        this.notifier.notify( 'error', result['message']);
      }
    }, (err) => {
      console.log(err);
    });
  }

  public savePatientData() {
   // console.log(' save list   ' + JSON.stringify(this.varification_data));
    const sendJson = {
      billing_id: this.varification_data.bill_id,
      name: this.varification_data.name,
      member_id: this.varification_data.member_id,
      policy_no: this.varification_data.policy_no
    };
    this.loaderService.display(true);
    this.rest.updateBillverificationData(sendJson).subscribe((result) => {
      this.loaderService.display(false);
     // console.log(result);
      if (result['status'] == 'Success') {
        this.notifier.notify( 'success', result['message']);
      } else {
        this.notifier.notify( 'error', result['message']);
      }
    }, (err) => {
      console.log(err);
    });
  }
  public get_investigation() {
     // console.log('this.patient_id ' + this.patient_id);

    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id,
    };
    this.loaderService.display(true);
    this.Doc.getlabinvestigation(postData).subscribe((result) => {
      if (result.status === 'Success') {
        this.loaderService.display(false);
        this.invoice_list = result.data.LAB_INVESTIGATION_DETAILS;
        this.lab_investigation_id =result.data.LAB_INVESTIGATION_ID;
        // this.getLabInvestigationResults();
        // console.log("lab_investigation_id  "+this.lab_investigation_id);
        
        let i = 0;
        for (const data of result.data.LAB_INVESTIGATION_DETAILS) {
          this.cpt_code_ids[i] = data.CURRENT_PROCEDURAL_CODE_ID;
          i = i + 1;
        }
        this.cpt_code_ids = this.cpt_code_ids;
        this. getTotal();
        this.getPatientDetails();
    } else {
        this.loaderService.display(false);
        // this.lab_status=result.status
      }
      }, (err) => {
        console.log(err);
      });
  }

  public getPatientDetails() {
      const sendJson = {
      patient_id : this.patient_id
      };
      this.loaderService.display(true);
      this.bill.getPatientDetails(sendJson).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status === 'Success') {
          this.patient_details = result.data;
          this.co_ins = result.data.co_ins;
          this.ins_data =  result.data.ins_data;
          this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA;
          this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK;
          this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE;
          this.getPatientCptRate();
        } else {
          this.patient_details = [];
        }
      }, (err) => {
        console.log(err);
      });
  }
  public getBillByAssessment() {
      const sendJson = {
        assessment_id : this.assessment_id
      };
      this.bill.getBillByAssessment(sendJson).subscribe((result) => {
        this.loaderService.display(false);

        if (result.status === 'Success') {
          for(let bill of result.data)
          {
          //  this.generate_status = bill.GENERATED;
            if(bill.BILL_TYPE == 1)
            {
              this.invoice_number = bill.BILLING_INVOICE_NUMBER;
              this.bill_id = bill;
              this.bill_details = bill.bill_details;
              this.invoice_number = bill.BILLING_INVOICE_NUMBER
              this.bill_id = bill
              this.billing_id = bill.BILLING_ID
              this.gross_total = bill.BILLED_AMOUNT
              this.net_total = bill.INSURED_AMOUNT
              this.patient_payable_total = bill.PAID_BY_PATIENT
              this.bill_total = bill.INSURED_AMOUNT
              this.table_invoice_list = bill.bill_details;
              var i=0
              for(let data of this.table_invoice_list)
              {
                data["RATE"] = data.TOTAL_AMOUNT
                data["CO_PAYMENT"] = data.COINS
                data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
                data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
                this.prior_authorisation[i] = data.PRIOR_AUTHORIZATION
                i=i+1;
              }
              this.patient_details = bill.pateint_details.data;
              this.co_ins = bill.pateint_details.data.co_ins;
              this.ins_data =  bill.pateint_details.data.ins_data;
              this.insurance_tpa_id = this.patient_details.ins_data.OP_INS_TPA
              this.insurance_network_id = this.patient_details.ins_data.OP_INS_NETWORK
              this.payment_type = this.patient_details.patient_data.OP_REGISTRATION_TYPE
            }
        }
          // this.invoice_number = result.data.BILLING_INVOICE_NUMBER;
          // //this.bill_id = result.data;
          // this.bill_details = result.data.bill_details;
          
          // //console.log(" this.bill_details[0].BILLING_DETAILS_ID  "+ JSON.stringify(this.bill_details[0]));
          // this.getPatient()
          // this.gross_total = result.data.BILLED_AMOUNT
          // this.net_total = result.data.INSURED_AMOUNT
          // this.patient_payable_total = result.data.PAID_BY_PATIENT
          // this.bill_total = result.data.INSURED_AMOUNT
          // this.table_invoice_list = result.data.bill_details;
          // var i=0
          // for(let data of this.table_invoice_list)
          // {
            
          //   data["RATE"] = data.TOTAL_AMOUNT
          //   data["CO_PAYMENT"] = data.COINS
          //   data["CO_PAYMENT_TYPE"] = data.COINS_TYPE
          //   data["TOTAL"] = data.TOTAL_INSURED_AMOUNT
          //   this.prior_authorisation[i]= data.PRIOR_AUTHORIZATION
          //   i=i+1
          // }
          
          // console.log(this.table_invoice_list)
          // this.get_investigation();
          
        } else {
          this.get_investigation();
          // this.getLabInvestigationResults(); 
          //this.bill_id = result.status;
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
    public getPatientCptRate() {
        const sendJson = {
          insurance_tpa_id : this.insurance_tpa_id,
          insurance_network_id : this.insurance_network_id ,
          cpt_code_ids : this.cpt_code_ids
        };
        this.loaderService.display(true);
        this.bill.getPatientCptRate(sendJson).subscribe((result) => {
          this.loaderService.display(false);
          if (result.status === 'Success') {
            this.cpt_rate = result.data;
            this.getTotal();
          } else {
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
        DESCRIPTION:invoice.DESCRIPTION,
        PROCEDURE_CODE:invoice.PROCEDURE_CODE,
        QUANTITY:invoice.QUANTITY,
        PRIOR_AUTHORIZATION:prior_auth,
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
      this.net_total = net_total;
      //this.patient_pay_tot = patient_pay_tot;
      var i=0;
    for (const data of this.table_invoice_list) {
      this.cpt_code_id[i] = data.PROCEDURE_CODE;
      this.price[i] = data.RATE;
      this.co_payment[i] = data.CO_PAYMENT;
      this.prior_authorisation[i] = data.PRIOR_AUTHORIZATION;
      //this.test_result[i] = data.LAB_INVESTIGATION_RESULT;
      this.co_payment_type[i] = data.CO_PAYMENT_TYPE;
      this.patient_pay[i] = data.PATIENT_PAYABLE;
      this.total_rate[i] = data.TOTAL;
      i = i + 1;
    }
    //console.log(this.prior_authorisation);
}
public find_co_payment(invoice) {
    if (invoice.PROCEDURE_CODE_CATEGORY === 0) {
      return this.patient_details.ins_data.OP_INS_DEDUCTIBLE;
    }
    if (this.cpt_rate.length <= 0) {
      // console.log("invoice");
      return 0;
    }
    if (this.patient_details.ins_data.OP_INS_IS_ALL === 1 ) {
      return this.patient_details.ins_data.OP_INS_ALL_VALUE;
    } else if (this.co_ins.length > 0) {
      for (const co of this.co_ins) {
        if (invoice.PROCEDURE_CODE_CATEGORY === co.COIN_ID) {
            return co.COIN_VALUE;
        }
      }
      return '0';
    } else {
      return '0';
    }
  }
  public find_co_payment_type(invoice) {
    if (invoice.PROCEDURE_CODE_CATEGORY === 0) {
      return this.patient_details.ins_data.OP_INS_DEDUCT_TYPE;
    }
    if (this.cpt_rate.length <= 0) {
      return '%';
    }
    if (this.patient_details.ins_data.OP_INS_IS_ALL === 1 ) {
      return this.patient_details.ins_data.OP_INS_ALL_TYPE;
    } else if (this.co_ins.length > 0) {
      for (const co of this.co_ins) {
        if (invoice.PROCEDURE_CODE_CATEGORY === co.COIN_ID) {
            return co.COIN_VALUE_TYPE;
        }
      }
      return '';
    } else {
      return '';
    }
  }
  public find_patient_payable(invoice, rate) {
    if (this.patient_details.patient_data.OP_REGISTRATION_TYPE === AppSettings.SELF_PAYMENT) {
      return (parseFloat(rate) * invoice.QUANTITY).toFixed(2);
    }
    if (this.cpt_rate.length <= 0) {
      return (parseFloat(rate) * invoice.QUANTITY).toFixed(2);
    }
    if (invoice.PROCEDURE_CODE_CATEGORY === 0) {
      if (this.patient_details.ins_data.OP_INS_DEDUCT_TYPE === 'AED') {
        return (this.patient_details.ins_data.OP_INS_DEDUCTIBLE).toFixed(2);
      } else if (this.patient_details.ins_data.OP_INS_DEDUCT_TYPE === '%') {
        return (parseFloat(rate) * invoice.QUANTITY * (this.patient_details.ins_data.OP_INS_DEDUCTIBLE / 100)).toFixed(2);
      }
      return 0;
    } else if (this.patient_details.ins_data.OP_INS_IS_ALL === 1 ) {
      if (this.patient_details.ins_data.OP_INS_ALL_TYPE === 'AED') {
        return this.patient_details.ins_data.OP_INS_ALL_VALUE.toFixed(2);
      } else if (this.patient_details.ins_data.OP_INS_ALL_TYPE === '%') {
        return (parseFloat(rate) * invoice.QUANTITY * (this.patient_details.ins_data.OP_INS_ALL_VALUE / 100)).toFixed(2);
      }
      return 0;
    } else if (this.co_ins.length > 0) {
      for (const co of this.co_ins) {
        if (invoice.PROCEDURE_CODE_CATEGORY === co.COIN_ID) {
          if (co.COIN_VALUE_TYPE === 'AED') {
            return co.COIN_VALUE;
          } else if (co.COIN_VALUE_TYPE === '%') {
            return (parseFloat(rate) * invoice.QUANTITY * (co.COIN_VALUE / 100)).toFixed(2);
          }
          return 0;
        }
      }
      return 0;
    } else {
      return 0;
    }
  }
  public find_rate(invoice) {

    if (this.cpt_rate.length > 0) {
      for (const rate of this.cpt_rate) {

        if (invoice.CURRENT_PROCEDURAL_CODE_ID === rate.cpt_code_id) {
          return rate.rate;
        }
      }
    }
    return invoice.RATE;
  }
  // -----------------------------------------------------------------------------------------
  
  public getLabInvestigationResults() {
    const sendJson = {
      lab_investigation_id:this.lab_investigation_id
    };
    this.loaderService.display(true);
    this.bill.getLabInvestigationResults(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
       this.lab_investigation_result = result.data;
       let i=0;
        for (const value of this.lab_investigation_result) {
          this.lab_investigation_result_value[i]=value.VALUE;
          this.lab_investigation_edit[i]=value.Edit;
        i = i + 1;
        }
        //console.log("this.lab_investigation_result  "+this.lab_investigation_result);
        
      } else {
        this.loaderService.display(false);
      }
    }, (err) => {
      console.log(err);
    });
}
saveLabInvestigationResults(){
  // this.lab_investigation_result = result.data;
     let i=0;
      for (const value of this.lab_investigation_result) {
        value.VALUE = this.lab_investigation_result_value[i];
      i = i + 1;
      }
  const sendJson = {
    save_details:this.lab_investigation_result
  };
  this.loaderService.display(true);
  this.bill.saveLabInvestigationResults(sendJson).subscribe((result) => {
    this.loaderService.display(false);
    //console.log(result);
      if (result['status'] === 'Success') {
        this.notifier.notify( 'success', result['msg']);
      } else {
        this.notifier.notify( 'error', result['msg']);
      }
  }, (err) => {
    console.log(err);
  });

}
}
