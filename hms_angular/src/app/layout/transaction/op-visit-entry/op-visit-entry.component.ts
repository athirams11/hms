import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import {DatePipe} from '@angular/common';
import { routerTransition } from '../../../router.animations';
import { AppointmentService, OpVisitService, ReportService, OpRegistrationService } from '../../../shared';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../../../shared/class/Utils';
import moment from 'moment-timezone';
import {NgbModal, ModalDismissReasons} from '@ng-bootstrap/ng-bootstrap';
import { interval,  Subscription } from 'rxjs';
@Component({
  selector: 'app-op-visit-entry',
  templateUrl: './op-visit-entry.component.html',
  styleUrls: ['./op-visit-entry.component.scss'],
  animations: [routerTransition()],
  providers: [AppointmentService, OpVisitService]
})
export class OpVisitEntryComponent implements OnInit, OnDestroy {
  private notifier: NotifierService;
  public subscription: Subscription;
  source = interval(5000);
  public search_by_opnumber : "";
  public f=0;
  public user_rights: any = {};
  public user: any = {};
  public current_date: any = "";
  public user_id : any = 0;
  public gender: any = [ 'Female', 'Male'];
  public gender_sur: any = [ 'Ms.', 'Mr.'];
  public specialized_in: any = [];
  public tpa_receiver: any = [];
  public networks: any = [];
  public ins_com_pay: any = [];
  public dr_list: any = [];
  public visit_list: any = [];
  doctor_id: any = '';
  navigationSubscription;
  have_voucher_or_coupon :boolean = false;
  public co_in_types: any = [];
  public co_in_selects = {
    type_id : '',
    type_name : '',
    error__message : '',
    deduct_type : 'AED',
    deduct_val : '20'
  };
  dateVal = new Date();
  todate = new Date();
  filter_ap_list = '';
  success_message = '';
  failed_message = '';
  public edit_ins_data = false;
  public edit_company_data = false;
  public appoinment_list: any = [];
  public patient_details: any = [];
  public visitData = {
    op_number : '',
    p_id : '',
    client_date :  new Date(),
    date: new Date(),
    timeZone : '',
    appoinment_id : '',
    department : '',
    visit_doctor : '',
    visit_doctor_name : '',
    visit_reason : '',
    err_msg: '',
    sucss_msg: '',
    ph_no:'',
    user_id:0,
    eid_number : "",
    discount_site : 0,
    giftcard : 0,
    reference_code : '',
    dicountedCPTdata : []
  };

  public discountData = {
    description: '',
    cpt_data: [],
    alliasname: "",
    cptname: "",
    cptcode: "",
    rate: 0,
    current_procedural_code_id:0,
    cptcode_id: 0
  };
  corporate_company: any = [];
  company_data: any = [];
  public national_id_mask = [/[1-9]/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/];
  pat_list : any = [];
  closeResult: string;
  time: any;
  company_options: any = [];
  CORPORATE_COMPANY_ADDRESS: any;
  public tpa_options: any = [''];
  public country_data: any = [];
  public nationality_data: any = [];
  public tpa_data: any = [];
  public inscopay_data: any = [];
  inscopay_options: any;
  public cdt_options: any = [];
  public cdt_list: any = [];
  public cdt_data: any = [];
  public discount_sites: any = [];
  public doctor_list : any = [];
  edit: number;
  editIndex: number;
  public dicountedCPTdata : any = [];
  constructor(private loaderService: LoaderService, public rest: AppointmentService, 
    public opv: OpVisitService, public datepipe: DatePipe, public op : OpRegistrationService,
    private router: Router, notifierService: NotifierService, 
    private modalService: NgbModal,public report: ReportService) {
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
    this.notifier = notifierService;
  }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    var data = JSON.parse(localStorage.getItem('user'));
    this.visitData.date = defaultDateTime();
    this.dateVal = defaultDateTime();
    this.todate = defaultDateTime();
    this.user_id = data.user_id;
    this.visitData.user_id = data.user_id;
    this.getDropdowns();
    this.subscription = this.source.subscribe(x => this.getVisitListByDate(1));
    this.getAppointmentsByDate();
    this.getVisitListByDate();
    this.time = moment.tz.guess();
  }
  initialiseInvites() {
    this.getDropdowns();
    this.getAppointmentsByDate();
    this.getVisitListByDate();
    this.getDropdowns_doctor();
  }
  ngOnDestroy() {
    if(this.navigationSubscription) {
      this.navigationSubscription.unsubscribe();
    }
    if(this.subscription) {  
      this.subscription.unsubscribe();
    }
  }
  public selectgiftcard(val)
  {
    this.visitData.giftcard = val
  }
  public getCPTBySites(discount = '')
  {
    if(this.visitData.discount_site > 0){
      const postData = {
        discount_site_id : this.visitData.discount_site
      };
      this.loaderService.display(true);
      this.opv.getCPTBySites(postData).subscribe((result: {}) => {
        this.loaderService.display(false);
        if (result['status'] == 'Success')
        {
          this.cdt_list = result["data"];
          this.cdt_options=this.cdt_list;
          for (let index = 0; index < this.cdt_options.length; index++) {
            if (this.cdt_options[index].CURRENT_PROCEDURAL_CODE_ID === this.discountData.current_procedural_code_id) {
              this.cdt_data = this.cdt_options[index];
            }
          }
          if(discount != '')
            this.discount(discount);
        }
        else
        {
          this.cdt_list = result["data"];
          this.cdt_options   = result["data"];
          this.cdt_data = []
        }
      });
    }
  }
  public addCPTdata() {
    this.edit = 0
    if(this.discountData.current_procedural_code_id == 0 )
    {
      this.notifier.notify( 'error', 'Please select CPT / CDT' );
    }
    else
    {
      const index = this.dicountedCPTdata.findIndex(d => d.current_procedural_code_id === this.discountData.current_procedural_code_id);
      if (index == -1) {
        this.dicountedCPTdata.push(this.discountData);
      } 
      else
      {
        this.notifier.notify("error","Already added")
      }
      this.discountData = {
        description: '',
        cpt_data: [],
        alliasname: "",
        cptname: "",
        cptcode: "",
        rate: 0,
        current_procedural_code_id:0,
        cptcode_id: 0
      };
      this.cdt_data = []
      this.getCPTBySites()
    }
  }
  deleterow(discountdata) {
    const index: number = this.dicountedCPTdata.indexOf(discountdata);
    console.log(discountdata)
    if (index !== -1) {
        this.dicountedCPTdata.splice(index, 1);
    } 
    this.edit = 0     
  }
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatDate (date) {
    return formatDate(date);
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  public selectedDeductType(val) {
    this.patient_details.ins_data.OP_INS_DEDUCT_TYPE = val;
  }
  public selectedRestAllType(val) {
    this.patient_details.ins_data.OP_INS_ALL_TYPE = val;
  }
  public selectedCoinType(val) {
    this.co_in_selects.deduct_type = val;
  }

  public addSelectedCoinType(co_in_type, type_name, co_ins_ded_type, deduct_val) {
    this.co_in_selects.error__message = '';
    if (co_in_type === '' || co_in_type == null) {
      // this.co_in_selects.error__message = "<strong>Invalid </strong> co-in selected";
      this.notifier.notify( 'error', 'Invalid co-in selected!' );
      setTimeout(() => {
        this.co_in_selects.error__message = '';
      }, 4000);
    } else if (deduct_val === '' || deduct_val == null) {
      // this.co_in_selects.error__message = "<strong>Invalid </strong> co-in amount";
      this.notifier.notify( 'error', 'Invalid co-in amount!' );
      setTimeout(() => {
        this.co_in_selects.error__message = '';
      }, 4000);
    } else {
      const co_in_added = {
        COIN_ID : co_in_type,
        COIN_NAME : type_name,
        COIN_VALUE_TYPE : co_ins_ded_type,
        COIN_VALUE : deduct_val
      };
      // co_in_added = this.co_in_selects;
      this.patient_details.co_ins.push(co_in_added);
      // console.log(this.patient_details.co_ins);
    }

  }
  public saveDiscountedTreatments()
  {
    this.loaderService.display(true);
    this.opv.addVisit(this.visitData).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.notifier.notify( 'success', result.message );

        
      } else {
        this.patient_details = [];
        this.notifier.notify( 'error', result.message );

      }
    
    });
  }
  public addVisit(discount) {

    this.visitData.timeZone = this.time
    this.visitData.date = defaultDateTime()
    if (this.visitData.op_number === '' || this.visitData.op_number == null || (this.visitData.op_number.length < 10)) {
      this.notifier.notify( 'error', 'Invalid Patient Number!' );
    } else if (this.visitData.p_id == null || this.visitData.p_id === '0' || this.visitData.p_id === '') {
      this.notifier.notify( 'error', 'Patient Number Not Exists!' );
    } else if (this.visitData.department === '' || this.visitData.department == null ) {
      this.notifier.notify( 'error', 'Invalid Department!' );
    } else if (this.visitData.visit_doctor === '' || this.visitData.visit_doctor == null ) {
      this.notifier.notify( 'error', 'Invalid Visit Doctor!' );
    } else if (this.have_voucher_or_coupon === true && this.visitData.discount_site === 0) {
      this.notifier.notify( 'error', 'Please select a site!' );
    } else if (this.have_voucher_or_coupon === true && this.visitData.discount_site > 0 && this.dicountedCPTdata.length === 0) {
      this.notifier.notify( 'error', 'Please select discounted CPT/CDT !' );
      this.discount(discount)
    } else {
      if(this.have_voucher_or_coupon === false)
      {
        this.visitData.discount_site = 0
        this.visitData.giftcard = 0
        this.visitData.reference_code = ''
        this.dicountedCPTdata = []
      }
      this.visitData.dicountedCPTdata = this.dicountedCPTdata;
      this.loaderService.display(true);
      this.opv.addVisit(this.visitData).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status === 'Success') {
          this.notifier.notify( 'success', result.message );
          this.visitData = {
            op_number : '',
            p_id : '',
            client_date :  new Date(),
            date: defaultDateTime(),
            timeZone : this.time,
            appoinment_id : '',
            department : '',
            visit_doctor : '',
            visit_doctor_name : '',
            visit_reason : '',
            err_msg: '',
            sucss_msg: '',
            ph_no:'',
            user_id: this.user_id,
            eid_number : '',
            discount_site : 0,
            giftcard : 0,
            reference_code : '',
            dicountedCPTdata : []
          };
          this.have_voucher_or_coupon = false
          this.discountData = {
            description: '',
            cpt_data: [],
            alliasname: "",
            cptname: "",
            cptcode: "",
            rate: 0,
            current_procedural_code_id:0,
            cptcode_id: 0
          };
          this.dicountedCPTdata = []
          this.patient_details = [];
          this.getPatientDetails();
          this.getVisitListByDate();
        } else {
          this.patient_details = [];
          this.notifier.notify( 'error', result.message );

        }
      }, (err) => {
        console.log(err);
      });
    }
  }

  public getDropdowns() {
    this.loaderService.display(true);
    this.opv.getOpDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        if (data['tpa_receiver']['status'] === 'Success') {
          this.tpa_receiver = data['tpa_receiver']['data'];
          this.tpa_options = this.tpa_receiver;
          for (let index = 0; index < this.tpa_options.length; index++) {
            if(this.patient_details.ins_data){
              if (this.tpa_options[index].TPA_ID == this.patient_details.ins_data.OP_INS_TPA) {
                this.tpa_data = this.tpa_options[index];
              }
            }
          }
        }
      
        if (data['ins_com_pay']['status'] === 'Success') {
          this.ins_com_pay = data['ins_com_pay']['data'];
        }

        if (data['co_in_types']['status'] === 'Success') {
          this.co_in_types = data['co_in_types']['data'];
        }
        if (data['specialized_in']['status'] === 'Success') {
          this.specialized_in = data['specialized_in']['data'];
        }
        if (data['ins_com_pay']['status'] === 'Success') {
          this.ins_com_pay = data['ins_com_pay']['data'];
          this.inscopay_options = this.ins_com_pay;
          for (let index = 0; index < this.inscopay_options.length; index++) {
            if(this.patient_details.ins_data){
              if (this.inscopay_options[index].INSURANCE_PAYERS_ID == this.patient_details.ins_data.OP_INS_PAYER) {
                this.inscopay_data = this.inscopay_options[index];
              }
            }
          }
        }
        if (data['discount_sites']['status'] === 'Success') {
          this.discount_sites = data['discount_sites']['data'];
        }
        

      }

    });
  }
  public validateNumber(event) {
    const keyCode = event.keyCode;  
    // console.log(keyCode)  
    const excludedKeys = [8,9, 37, 39, 46];   
     if (!((keyCode >= 48 && keyCode <= 57) ||
      (keyCode >= 96 && keyCode <= 105 ) || ( keyCode == 109 ) || ( keyCode == 173 ) || ( keyCode == 61 ) ||
      ( keyCode == 107 ) || ( keyCode == 37  ) || (excludedKeys.includes(keyCode)))) {
      event.preventDefault();
    }
  }
  public getDrByDateDept() {
    const sendJson = {
      dateVal : this.dateVal,
      department : this.visitData.department
    };
    this.loaderService.display(true);
    this.opv.getDrByDateDept(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.dr_list = result.data;
      } else {
        this.dr_list = [];
      }
    }, (err) => {
      console.log(err);
    });


  }
 
  public getDropdowns_doctor() {
    this.doctor_list = [];
    this.loaderService.display(true)
    this.rest.getDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false)
      if(data['status'] == 'Success')
      {
        if(data['doctors_list']["status"] == 'Success')
        {
          this.doctor_list = data['doctors_list']['data'];
        }
      }
      
    });
  }
  public getPatientDetails() {
    this.edit_ins_data = false;
    if (this.visitData.op_number.length >= 10) {
      const sendJson = {
        op_number : this.visitData.op_number
      };
      this.loaderService.display(true);
      this.opv.getPatientDetails(sendJson).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status === 'Success') {
        //  console.log('patient_details' + JSON.stringify(result.data));
          this.patient_details = result.data;
          this.visitData.p_id = this.patient_details.patient_data.OP_REGISTRATION_ID;
          this.visitData.ph_no =this.patient_details.patient_data.MOBILE_NO;
          this.visitData.op_number =this.patient_details.patient_data.OP_REGISTRATION_NUMBER;
          this.visitData.eid_number =this.patient_details.patient_data.NATIONAL_ID;
          this.listCorporateCompany();
          this.settpaDropdown();
          this.setinsDropdown();
          this.setcompanyDropdown();
          if(this.patient_details.ins_data != false)
          this.getInsNetwork(this.patient_details.ins_data.OP_INS_TPA);
        } else {
          this.patient_details = [];
        }
      }, (err) => {
        console.log(err);
      });
    }

  }
  public updateInsuranceDetails() {

    if (this.patient_details.patient_data.OP_REGISTRATION_TYPE === '1') {
      if (this.patient_details.ins_data.OP_INS_TPA === '' || this.patient_details.ins_data.OP_INS_TPA == null) {
        this.notifier.notify('warning', 'Please select TPA reciever !');
        return false;
      } if (this.patient_details.ins_data.OP_INS_PAYER === '' || this.patient_details.ins_data.OP_INS_PAYER == null) {
        this.notifier.notify('warning', 'Please select Insurance co payer !');
        return false;
      } if (this.patient_details.ins_data.OP_INS_NETWORK === '' || this.patient_details.ins_data.OP_INS_NETWORK == null) {
        this.notifier.notify('warning', 'Please select Insurance nerwork !');
        return false;
      } if (this.patient_details.ins_data.OP_INS_MEMBER_ID === '' || this.patient_details.ins_data.OP_INS_MEMBER_ID == null) {
        this.notifier.notify('warning', 'Please enter Insurance member id !');
        return false;
      } if (this.patient_details.ins_data.OP_INS_POLICY_NO === '' || this.patient_details.ins_data.OP_INS_POLICY_NO == null) {
        this.notifier.notify('warning', 'Please enter Insurance policy number !');
        return false;
      } if (this.patient_details.ins_data.OP_INS_VALID_FROM === '' || this.patient_details.ins_data.OP_INS_VALID_FROM == null) {
        this.notifier.notify('warning', 'Please enter Insurance validity !');
        return false;
      } if (this.patient_details.ins_data.OP_INS_VALID_TO === '' || this.patient_details.ins_data.OP_INS_VALID_TO == null) {
        this.notifier.notify('warning', 'Please enter Insurance validity !');
        return false;
      }
    } 
    if (this.visitData.op_number.length >= 10) {
      const sendJson = {
        OP_REGISTRATION_ID : this.patient_details.patient_data.OP_REGISTRATION_ID,
        INS_DATA : this.patient_details.ins_data,
        CO_IN_DATA : this.patient_details.co_ins,

      };
      this.loaderService.display(true);
      this.opv.updateInsuranceDetails(sendJson).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status === 'Success') {
         this.edit_ins_data = false;
          this.notifier.notify( 'success', result.message );

          this.getPatientDetails();
          this.listCorporateCompany();
        }
        else if(result.status === 'warning') 
        {
          this.notifier.notify( 'warning', result.msg );
        }
        else {
          this.notifier.notify( 'error', result.message );

        }
      }, (err) => {
        console.log(err);
      });
    }

  }
  public updateCompanyDetails() {
    if (this.visitData.op_number.length >= 10) {
      const sendJson = {
        OP_REGISTRATION_ID : this.patient_details.patient_data.OP_REGISTRATION_ID,
        CORPORATE_DATA : this.patient_details.corporate_data

      };
      this.loaderService.display(true);
      this.opv.updateCompanyDetails(sendJson).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status === 'Success') {
         this.edit_company_data = false;
          this.notifier.notify( 'success', result.message );

          this.getPatientDetails();
        } else {
          this.notifier.notify( 'error', result.message );

        }
      }, (err) => {
        console.log(err);
      });
    }

  }
  public getVisitListByDate(f=0) {

    const sendJson = {
      dateVal : this.formatDateTime (this.dateVal),
      timeZone: moment.tz.guess(),
      user_group:this.user_rights.user_group,
      user_id:this.user_id,
      doctor_id : this.doctor_id,
      search_by_opnumber : this.search_by_opnumber,
      todate : this.formatDateTime (this.todate),

    };

    // const sendJson = {
    //   dateVal : this.formatDateTime (this.dateVal),
    //   timeZone: moment.tz.guess(),
    //   user_group:this.user_rights.user_group,
    //   user_id:this.user_id,
    // };
    if (f==0) {
      this.loaderService.display(true);
    }
    this.opv.getVisitListByDate(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.visit_list = result.data;
        for(let visit of this.visit_list)
        {
          if(visit.VISIT_STATUS == 1 && visit.BILL_STATUS == null  && visit.STAT == null)
          visit["ACTION"] = "Nursing Assessment Started"
          else if(visit.VISIT_STATUS == 1 && visit.STAT == 1 && visit.BILL_STATUS == null && visit.DOCTOR_STAT == null)
          visit["ACTION"] = "Nursing Assessment Completed"
          else if(visit.VISIT_STATUS == 2)
          visit["ACTION"] = "Visit cancelled"
          else if(visit.VISIT_STATUS == 1 && visit.STAT == 1 && visit.BILL_STATUS == null && visit.DOCTOR_STAT == 1  )
          visit["ACTION"] = "Doctor Assessment Completed"
          else if(visit.VISIT_STATUS == 1 && visit.BILL_STATUS == 1)
          visit["ACTION"] = "Billing Completed"
        }
      } else {
        this.visit_list = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getAppointmentsByDate() {
    const sendJson = {
      dateVal : this.formatDateTime (this.dateVal),
      timeZone: moment.tz.guess(),
      user_group:this.user_rights.user_group,
      user_id:this.user_id,
      doctor_id : this.doctor_id,
      search_by_opnumber : this.search_by_opnumber,
      todate : this.formatDateTime (this.todate),

    };
    this.loaderService.display(true);
    this.rest.getAppointmentsByfromtoDate(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.appoinment_list = result.data;
        for(let appointment of this.appoinment_list)
        {
          if(appointment.APPOINTMENT_STATUS == '2')
          appointment["ACTION"] = "Cancelled"
          if(appointment.PATIENT_VISIT_LIST_ID != null && appointment.NURSING_ASSESSMENT_ID == null)
          appointment["ACTION"] = "Arrived"
          if(appointment.NURSING_ASSESSMENT_ID != null && appointment.STAT != 1)
          appointment["ACTION"] = "Nursing Assessment Started"
          if(appointment.NURSING_ASSESSMENT_ID != null && appointment.STAT == 1 && appointment.BILL_STATUS == null)
          appointment["ACTION"] = "Nursing Assessment Completed"
          if(appointment.BILL_STATUS == 1)
          appointment["ACTION"] = "Billing Completed"
       
        }
      } else {
        this.appoinment_list = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  open(content) {
   
    if(this.visitData.ph_no != "" && this.visitData.ph_no != null)
    {
      var sendJson = {
        ph_no : this.visitData.ph_no
      }
      this.loaderService.display(true)
      this.rest.getPatientsByPhoneNo(sendJson).subscribe((result) => {
        this.loaderService.display(false)
        if(result.status == "Success")
        {
          this.pat_list = result.data;
          if(this.pat_list.length > 0)
          {
            this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',  centered: true }).result.then((result) => {
              this.closeResult = `Closed with: ${result}`;
            }, (reason) => {
              this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
            });
            
          }
        }
        else
        {
          this.pat_list = [];
          
        }
      }, (err) => {
        console.log(err);
      });
      
    }
  }
  discount(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {
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
  public selectPatient(patient)
  {
    this.visitData.p_id = patient.reg_id;
    this.visitData.op_number = patient.OP_no;
    if (this.visitData.op_number !='') {
      this.getPatientDetails()
    }
  }
  public keyDownFunction(event,val,content = '') {
    if(event.keyCode == 13) {
      if(val == 1){
        this.getPatientDetails()
      }
      if(val == 2){
        this.serachByEIDnumber()
      }
      if(val == 3){
        this.open(content)
      }
    }
  }
  public serachByEIDnumber() {
    if(this.visitData.eid_number != "" && this.visitData.eid_number != null)
    {
      this.getPatientByEIDnumber(this.visitData.eid_number);
    }
  }
  getPatientByEIDnumber(serach_by_opnumber: string) {
    const sendJson = {
      eid_number: serach_by_opnumber
    };
    this.loaderService.display(true);
    this.rest.getPatientByEIDnumber(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status == 'Success') {
        this.patient_details = result.data;
        this.visitData.p_id = this.patient_details.patient_data.OP_REGISTRATION_ID;
        this.visitData.ph_no =this.patient_details.patient_data.MOBILE_NO;
        this.visitData.op_number =this.patient_details.patient_data.OP_REGISTRATION_NUMBER;
        this.visitData.eid_number =this.patient_details.patient_data.NATIONAL_ID;
        this.listCorporateCompany();
        this.settpaDropdown();
        this.setinsDropdown();
        this.setcompanyDropdown();
        if(this.patient_details.ins_data != false)
        this.getInsNetwork(this.patient_details.ins_data.OP_INS_TPA);
        this.listCorporateCompany();
      }else{
        this.notifier.notify('error',"Invalid EID Number")
      }
    }, (err) => {
      console.log(err);
    });
  }
  config_company = {
    displayKey: "CORPORATE_COMPANY_NAME",
    search: true, 
    height: '135px',
    placeholder: 'Select Corporate Company',
    limitTo: this.corporate_company.length, 
    moreText: '.........', 
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'CORPORATE_COMPANY_NAME'
  }
  getcompany() {
    if(this.company_data){
      this.patient_details.corporate_data.OP_CORPORATE_COMPANY_ID = this.company_data.CORPORATE_COMPANY_ID;
      this.CORPORATE_COMPANY_ADDRESS = this.company_data.CORPORATE_COMPANY_ADDRESS;;
    }
  }
  public listCorporateCompany() {
    const post2Data = {
      company_status: 1,
    };
    this.loaderService.display(true);
   this.report.listCorporateCompany(post2Data).subscribe((result: {}) => {
    this.loaderService.display(false);
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.corporate_company = result['data'];
  //   console.log(this.corporate_company)
          this.company_options = this.corporate_company;
          for (let index = 0; index < this.company_options.length; index++) {
            if(this.patient_details.corporate_data != false){
              if (this.company_options[index].CORPORATE_COMPANY_ID == this.patient_details.corporate_data.OP_CORPORATE_COMPANY_ID) {
                this.company_data = this.company_options[index];
              }
            }
           // console.log(this.company_data)
          }
     }
   });
  }
  public setcompanyDropdown() {
    for (let index = 0; index < this.company_options.length; index++) {
      if (this.company_options[index].CORPORATE_COMPANY_ID == this.patient_details.corporate_data.OP_CORPORATE_COMPANY_ID) {
        this.company_data = this.company_options[index];
        break;
      }
    }
  }
  config = {
    displayKey: "TPA",
    search: true, 
    height: '300px',
    placeholder: 'Select TPA / Receiver',
    limitTo: this.tpa_receiver.length, 
    moreText: '.........', 
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'TPA'
  }
  configs = {
    displayKey: "INSURANCE_PAYER",
    search: true, 
    height: '300px', 
    placeholder: 'Select Insurance Co. / Payer',
    limitTo: this.ins_com_pay.length, 
    moreText: '.........', 
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'INSURANCE_PAYER'
  }
  cdt_config = {
    displayKey:"CPT", 
    search:true, 
    height: '250px', 
    placeholder:'CPT / CDT Name', 
    limitTo: this.cdt_options.length, 
    moreText: '.........', 
    noResultsFound: 'No results found!', 
    searchPlaceholder:'Search' ,
    searchOnKey: 'CPT' 
  }
  public getInsNetwork(data_id) {
    this.networks = ['']
    const opdata = {
      tpa_id: data_id
    };
    this.loaderService.display(true);
    this.op.getOpNetworks(opdata).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.networks = result['data'];
      }

    });
  }
  public getNetworks(data) {
    this.networks = ['']
    const opdata = {
      tpa_id: data['value'].TPA_ID
    };
    this.loaderService.display(true);
    this.op.getOpNetworks(opdata).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.networks = result['data'];
      }

    });
  }
  getTpa() {
    if(this.tpa_data)
    {
      this.patient_details.ins_data.OP_INS_TPA = this.tpa_data.TPA_ID;
    }
    else
    this.patient_details.ins_data.OP_INS_TPA = "";
  }
  getInspayer($event) {
    if(this.inscopay_data)
      this.patient_details.ins_data.OP_INS_PAYER = this.inscopay_data.INSURANCE_PAYERS_ID;
    else
      this.patient_details.ins_data.OP_INS_PAYER = ''
  }
  getCPTdata()
  {
    if(this.cdt_data)
    {
      this.discountData.current_procedural_code_id = this.cdt_data.CURRENT_PROCEDURAL_CODE_ID
      this.discountData.cptcode = this.cdt_data.PROCEDURE_CODE
      this.discountData.cpt_data = this.cdt_data
      this.discountData.rate = this.cdt_data.CPT_RATE
      this.discountData.alliasname = this.cdt_data.PROCEDURE_CODE_ALIAS_NAME
      this.discountData.cptname = this.cdt_data.PROCEDURE_CODE_NAME
    }
  }
  public settpaDropdown() {
    if(this.patient_details.ins_data)
    for (let index = 0; index < this.tpa_options.length; index++) {
      if (this.tpa_options[index].TPA_ID == this.patient_details.ins_data.OP_INS_TPA) {
        this.tpa_data = this.tpa_options[index];
        break;
      }
    } 
  }
  public setinsDropdown() {
    if(this.patient_details.ins_data)
    for (let index = 0; index < this.inscopay_options.length; index++) {
      if (this.inscopay_options[index].INSURANCE_PAYERS_ID == this.patient_details.ins_data.OP_INS_PAYER) {
        this.inscopay_data = this.inscopay_options[index];
        break;
      }
    }
  }
}
