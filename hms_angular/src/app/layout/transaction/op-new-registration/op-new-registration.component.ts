import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { DatePipe } from '@angular/common';
import { routerTransition } from '../../../router.animations';
import { OpRegistrationService, AppointmentService, ReportService } from '../../../shared';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../../../shared/class/Utils';
import { LoaderService, } from '../../../shared';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { NotifierService } from 'angular-notifier';
import * as EmailValidator from 'email-validator';
import { HttpEventType, HttpResponse } from '@angular/common/http';
import { AppSettings } from './../../../app.settings';
import { NgbModal, ModalDismissReasons, NgbModalOptions } from '@ng-bootstrap/ng-bootstrap';
// import { IDropdownSettings } from 'ng-multiselect-dropdown';
@Component({
  selector: 'app-op-new-registration',
  templateUrl: './op-new-registration.component.html',
  styleUrls: ['./op-new-registration.component.scss'],
  animations: [routerTransition()]
})
export class OpNewRegistrationComponent implements OnInit {
  private notifier: NotifierService;
  public user_rights: any = {};
  public INSURANCE_CONST: string;
  public countries: any = [];
  public types: any = [];
  public info_sources: any = [];
  public emirates: any = [];
  public tpa_receiver: any = [];
  public networks: any = [];
  public ins_com_pay: any = [];
  public co_ins: any = [];
  public enc_type: any = [];
  public enc_str_type: any = [];
  public co_in_types: any = [];
  public country_data: any = [];
  public nationality_data: any = [];
  public tpa_data: any = [];
  public inscopay_data: any = [];
  public error__message: any = '';
  public settings = AppSettings;
  public index;
  public co_in_selects = {
    type_id: '',
    type_name: '',
    error__message: '',
    deduct_type: 'AED',
    deduct_val: '20'
  };
  dropdownSettings = {};
  tpadropdownSettings = {};
  time: any;
  public search_by_opnumber : "";
  public search_by_pnonenumber : "";
  public search_by_eidnumber : "";
  public opData = {
    general: 0,
    dental: 0,
    covid: 0,
    patient_number: '',
    dateVal: new Date(),
    client_date: new Date(),
    sel_pay_type: '2',
    reg_id: '',
    gender: 1,
    f_name: '',
    m_name: '',
    l_name: '',
    birthdate: '',
    age: 0,
    months: 0,
    days: 0,
    mobile_no: '',
    email: '',
    address: '',
    po_box: '',
    country: '',
    nationality: '',
    national_id: '111-1111-1111111-1',
    sel_emirates: '',
    res_no: '',
    city: '',
    service: '',
    visa_stat: '',
    fax: '',
    sel_source_of_info: '',
    sel_tpa_receiver: '',
    sel_ins_co: '',
    sel_network: '',
    memebr_id: '',
    policy_no: '',
    valid_from: '',
    valid_to: '',
    deductible: '20',
    deductible_type: 'AED',
    rest_of_all_check: false,
    rest_of_all_type: 'AED',
    rest_of_all: '20',
    user_id: 1,
    app_id: 0,
    co_in_selects: [],
    timeZone:  Moment.tz.guess(),
    upload_file: [],
    corporate_company: '',
    corporate_address: "",
    ins_detail_id : 0,
    insurance_status :0,
    edit_ins :0,
    removeUpload : []
  };
  public F_HasData_tmp: any = '';
  public F_EIDNumber_tmp: any = '';
  public F_Name_tmp: any = '';
  public F_Phone_tmp: any = '';
  public F_Mobile_tmp: any = '';
  public F_Email_tmp: any = '';
  public F_Emirate_tmp: any = '';
  public F_City_tmp: any = '';
  public F_Pobox_tmp: any = '';
  public F_CityID_tmp: any = '';
  public F_Sex_tmp: any = '';
  public F_DOB_tmp: any = '';
  public F_Nationality_tmp: any = '';
  public op_ins_det_id = 0;
  public tpa_options: any = [''];
  public inscopay_options: any = [''];
  public country_options: any = [''];
  public company_options: any = [''];
  public gender = 1;
  public birthdate: any;
  public age: number;
  public months: number;
  public days: number;
  public national_id_mask = [/[1-9]/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/];
  attachment: any = [];
  pat_list: any;
  closeResult: string;
  corporate_company: any = [];
  company_data: any = [];
  CORPORATE_CONSTANT = AppSettings.CORPORATE_CONSTANT;
  EID = AppSettings.EIDCARD;
  patient_details: any;
  old_ins_details: any ;
  old_insurance: any;
  cur_ins_details: any;
  edit: number = 0;
  insurance_change = 0;
  fileName: any = [];
  filelist: any;

  constructor(private loaderService: LoaderService, 
    public rest: OpRegistrationService,
    private modalService: NgbModal, 
    public apRest: AppointmentService, 
    public report: ReportService, 
    public datepipe: DatePipe, 
    private router: Router, 
    private route: ActivatedRoute, 
    notifierService: NotifierService) {
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.CORPORATE_CONSTANT = AppSettings.CORPORATE_CONSTANT
    this.EID = AppSettings.EIDCARD
    this.opData.dateVal = defaultDateTime();
    this.route.params.subscribe(params => {
    const app_id = Number.parseInt(params['app_id']);
      // const app_id = params['app_id'];
      //console.log((params))
     this.birthdate = moment(new Date()).format('YYYY-MM-DD')
      this.opData.app_id = app_id;
      this.getDropdowns();
      this.listCorporateCompany()
      this.getAppointmentData(app_id);
      this.opData.timeZone = Moment.tz.guess();
      this.dropdownSettings = {
        singleSelection: true,
        idField: 'COUNTRY_ID',
        textField: 'COUNTRY_NAME',
        selectAllText: 'Select All',
        unSelectAllText: 'UnSelect All',
        itemsShowLimit: 5,
        allowSearchFilter: true
      };
      this.tpadropdownSettings = {
        singleSelection: true,
        idField: 'TPA_ID',
        textField: 'TPA_NAME',
        selectAllText: 'Select All',
        unSelectAllText: 'UnSelect All',
        itemsShowLimit: 5,
        allowSearchFilter: true
      };
      // TPA_ECLAIM_LINK_ID 
    });
    var id= ""
     id = this.route.snapshot.paramMap.get('id');
     var ids = +this.route.snapshot.paramMap.get('id');
    // console.log(id)
    // console.log(ids)
    //  if (id != null && id !== 0) {
      if ( id != null && !parseFloat(id) || ids != 0 && ids != null)   {
      this.opData.patient_number = '';
     // this.listCorporateCompany();
      
      this.getPatientDetails(id);

      // console.log(id)
    } else {
      this.getDropdowns();
      this.listCorporateCompany()
    }
  }
  public formatDateTime(data) {
    if (data) {
      data = moment(data, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y HH:MM:ss');
      return data;
    }
  }
  public formatDate(data) {
    if (data) {
      data = moment(data, 'yyyy-MM-D').format('DD-MM-Y');
      return data;
    }
  }
  public selectedDeductType(val) {
    this.opData.deductible_type = val;
    // this.co_in_selects.deduct_type = val;
  }
  public selectedRestAllType(val) {
    this.opData.rest_of_all_type = val;
    // this.co_in_selects.deduct_type = val;
  }
  public selectedCoinType(val) {
    // this.opData.rest_of_all_type = val;
    this.co_in_selects.deduct_type = val;
  }
  public addSelectedCoinType(co_in_type, type_name, co_ins_ded_type, deduct_val) {

    if (co_in_type == '' || co_in_type == null) {
      this.notifier.notify('error', 'Invalid  co-in selected!');
    } else if (deduct_val === '' || deduct_val == null) {
      this.notifier.notify('error', 'Invalid  co-in amount!');
    } else {
      const co_in_added = {
        type_id: co_in_type,
        type_name: type_name,
        deduct_type: co_ins_ded_type,
        deduct_val: deduct_val
      };
      this.opData.co_in_selects.push(co_in_added);
    }

  }

  public readeld(){
    var event = document.createEvent('Event');
    event.initEvent('EID_EVENT_HMS');
    document.dispatchEvent(event);
    this.loaderService.display(true);
    //this.getvalues();
      setTimeout(() => {
        this.getvalues();
    }, 3000);
  
  }

  public getvalues(){
    this.loaderService.display(false);
    var check =(<HTMLInputElement>document.getElementById('F_HasData_tmp')).value;
    console.log("hi");
    console.log(check);
    if(check=="true"){
      var hidderValue =(<HTMLInputElement>document.getElementById('F_Name_tmp')).value;
      this.opData.national_id=(<HTMLInputElement>document.getElementById('F_EIDNumber_tmp')).value;
      let test = hidderValue.split(" ");
      this.opData.f_name=test[0];
      this.opData.m_name=test[1];
      this.opData.l_name=test[2];
      this.opData.mobile_no = (<HTMLInputElement>document.getElementById('F_Mobile_tmp')).value;
      this.opData.email=(<HTMLInputElement>document.getElementById('F_Email_tmp')).value;
      this.opData.city=(<HTMLInputElement>document.getElementById('F_City_tmp')).value;
      this.opData.po_box=(<HTMLInputElement>document.getElementById('F_Pobox_tmp')).value;
      var gender=(<HTMLInputElement>document.getElementById('F_Sex_tmp')).value;
      if(gender === 'F'){
        this.opData.gender=0;
      }
      else{
        this.opData.gender=1;
      }
      var datavals=(<HTMLInputElement>document.getElementById('F_DOB_tmp')).value;
      var stringDate1=datavals;
      var splitDate1 = stringDate1.split('-');
      var year1  = splitDate1[2];
      var month1 = splitDate1[1];
      var day1 = splitDate1[0];
      datavals = year1+"-"+month1+"-"+day1;
      this.opData.birthdate=datavals;
      this.CalculateAge();
      var emr=(<HTMLInputElement>document.getElementById('F_Emirate_tmp')).value;
      const pass_data = {
        app_id: emr
      };
      this.loaderService.display(true);
      this.apRest.getemri(pass_data).subscribe((result) => {
        this.loaderService.display(false);
        if (result['status'] === 'Success') {
          const app_data = result['data'];
          //console.log(app_data.OPTIONS_ID);
          this.opData.sel_emirates = app_data.OPTIONS_ID;
        }
      });
    }
    else{
      this.notifier.notify('warning', 'Please insert card in properly !');
    }
  }

  public getNetworks(data) {
    this.networks = ['']
    // console.log('data ' + data['value'].TPA_ID);
    // console.log("opData.sel_tpa_receiver  " + JSON.stringify(this.opData.sel_tpa_receiver));
    // this.opData.sel_tpa_receiver = data.value.TPA_ID;
    // this.opData.sel_tpa_receiver = data;
    const opdata = {
      tpa_id: data['value'].TPA_ID
    };
    this.loaderService.display(true);
    this.rest.getOpNetworks(opdata).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.networks = result['data'];
      }

    });
  }
  public getInsNetwork(data_id) {
    this.networks = ['']
    const opdata = {
      tpa_id: data_id
    };
    this.loaderService.display(true);
    this.rest.getOpNetworks(opdata).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.networks = result['data'];
      }

    });
  }

  public CalculateAge(): void {
    // console.log(this.opData.birthdate)
    // console.log(this.birthdate)
    if(this.opData.birthdate > this.birthdate)
    {
      this.opData.birthdate = ''
      this.opData.age = 0
      this.opData.months = 0
      this.opData.days = 0
      // this.notifier.notify("warning","DoB should be less than or equal to current date")
      return  
    }
    else{
      if (this.opData.birthdate) {
        const today = moment();
        const today2 = moment();
        const birthdate = moment(this.opData.birthdate);
        this.opData.age = today.diff(birthdate, 'years');
        this.opData.months = today.subtract(this.opData.age, 'years').diff(birthdate, 'months');
        this.opData.days = today2.subtract(this.opData.age, 'years').subtract(this.opData.months, 'months').diff(birthdate, 'days');
        // this.days = Math.floor((timeDiff / (1000 * 3600 * 24))/365);
      }
    }
  }
  public addNewOpRegistration() {
    
    // if (this.opData.sel_pay_type === '' || this.opData.sel_pay_type == null) {
    //   this.notifier.notify('warning', 'Please select type !');
    //  // console.log('opData.sel_pay_type ' + this.opData.sel_pay_type);

    //   return false;
    // }
    if (this.opData.f_name === '' || this.opData.f_name == null) {
      this.notifier.notify('warning', 'Please enter patient name !');
     // console.log('opData.sel_pay_type ' + this.opData.sel_pay_type);

      return false;
    }
    if (this.opData.gender > 1 || this.opData.gender > 1) {
      this.notifier.notify('warning', 'Please select correct gender !');
      return false;
    }
    if (this.opData.birthdate === '' || this.opData.birthdate == null) {
      this.notifier.notify('warning', 'Please select date of birth !');

      return false;
    }
    if (this.opData.mobile_no === '' || this.opData.mobile_no == null) {
      this.notifier.notify('warning', 'Please enter mobile number !');
      return false;
    }
    if (this.opData.email !== '') {
      if (EmailValidator.validate(this.opData.email)) {
      } else {
        this.notifier.notify('warning', 'Please enter proper email id!');
        return false;
      }
    } if (this.opData.address === '' || this.opData.address == null) {
      this.notifier.notify('warning', 'Please enter patient address !');

      return false;
    }
    // if (this.opData.national_id === '' || this.opData.national_id == null) {
    //   this.notifier.notify('warning', 'Please enter national id !');

    //   return false;
    // }
    if (this.opData.sel_pay_type === '1' && this.op_ins_det_id == 0) {
      if (this.opData.sel_tpa_receiver === '' || this.opData.sel_tpa_receiver == null) {
        this.notifier.notify('warning', 'Please select TPA reciever !');
        return false;
      } if (this.opData.sel_ins_co === '' || this.opData.sel_ins_co == null) {
        this.notifier.notify('warning', 'Please select Insurance co payer !');
       // console.log("this.opData.sel_ins_co " + this.opData.sel_ins_co);
        // console.log("co_pay.INSURANCE_PAYERS_ECLAIM_LINK_ID  "+co_pay.INSURANCE_PAYERS_ECLAIM_LINK_ID );


        return false;
      } if (this.opData.sel_network === '' || this.opData.sel_network == null) {
        this.notifier.notify('warning', 'Please select Insurance nerwork !');
        return false;
      } if (this.opData.memebr_id === '' || this.opData.memebr_id == null) {
        this.notifier.notify('warning', 'Please enter Insurance member id !');
        return false;
      } if (this.opData.policy_no === '' || this.opData.policy_no == null) {
        this.notifier.notify('warning', 'Please enter Insurance policy number !');
        return false;
      } if (this.opData.valid_from === '' || this.opData.valid_from == null) {
        this.notifier.notify('warning', 'Please enter Insurance validity !');
        return false;
      } if (this.opData.valid_to === '' || this.opData.valid_to == null) {
        this.notifier.notify('warning', 'Please enter Insurance validity !');
        return false;
      }
    } 
    if (this.opData.sel_pay_type == '3') {
      if (this.opData.corporate_company == '' || this.opData.corporate_company == null) {
        this.notifier.notify('warning', 'Please select corporate company !');
        return false;
      } 
    } 
    // if(this.opData.sel_pay_type == '1' && this.old_ins_details && this.old_ins_details!=false)
    // {
    //   var rel = 0;
    //   if(this.old_ins_details.length > 0){
    //    for(let data of this.old_ins_details)
    //     {
    //       if(data.OP_INS_STATUS == 1)
    //       {
    //         rel = 1
    //       }
    //     }
    //     if(rel == 0)
    //     {
    //       this.notifier.notify('warning', 'Please activate a insurance company !');
    //       return false;
    //     }
    //   }
    // }
    this.loaderService.display(true);
    this.opData.timeZone =Moment.tz.guess();
   // console.log(this.opData); 
    this.rest.addNewOpRegistration(this.opData).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.notifier.notify('success', result.msg);
        this.clear_data();
        this.country_data = [];
        this.nationality_data = [];
        this.tpa_data = [];
        this.inscopay_data = [];
        this.search_by_opnumber = "";
        this.search_by_pnonenumber = "";
        this.search_by_eidnumber= "";
        this.getDropdowns();
      }
      else if(result['status'] === 'warning')
      {
        this.notifier.notify('warning', result.msg);
      }
       else {
        this.notifier.notify('error', result.msg);
      }
      // MessageBox.show(this.dialog, `Hello, World!`);
    }, (err) => {
      console.log(err);
    });


  }
  public clear_data()
  {
    this.opData = {
      general: 0,
      dental: 0,
      covid: 0,
      patient_number: '',
      client_date: this.formatDateTime(new Date()),
      sel_pay_type: '2',
      gender: 1,
      f_name: '',
      m_name: '',
      l_name: '',
      birthdate: '',
      age: 0,
      months: 0,
      days: 0,
      mobile_no: '',
      email: '',
      address: '',
      po_box: '',
      country: '',
      nationality: '',
      national_id: '',
      sel_emirates: '',
      res_no: '',
      city: '',
      service: '',
      visa_stat: '',
      fax: '',
      sel_source_of_info: '',
      sel_tpa_receiver: '',
      sel_ins_co: '',
      sel_network: '',
      memebr_id: '',
      policy_no: '',
      valid_from: '',
      valid_to: '',
      deductible: '20',
      deductible_type: 'AED',
      rest_of_all_check: false,
      rest_of_all_type: 'AED',
      rest_of_all: '20',
      user_id: 1,
      app_id: 0,
      reg_id: '',
      dateVal: defaultDateTime(),   
      co_in_selects: [],
      timeZone: this.time,
      upload_file: [],
      corporate_company : "",
      corporate_address : "",
      ins_detail_id : 0,
      insurance_status :0,
      edit_ins :0,
      removeUpload : []

    };
    this.attachment = [];
    this.fileName = [];
    this.filelist = [];
  }
  
  public getAppointmentData(app_id) {
    if (app_id != null && app_id !== NaN) {

      const pass_data = {
        app_id: app_id
      };
      this.loaderService.display(true);
      this.apRest.getAppointMent(pass_data).subscribe((result) => {
        this.loaderService.display(false);
        if (result['status'] === 'Success') {
          const app_data = result['data'];

          this.opData.f_name = app_data.PATIENT_NAME;
          this.opData.gender = app_data.APP_GENDER;
          this.opData.age = app_data.APP_AGE;
          this.opData.mobile_no = app_data.PATIENT_PHONE_NO;
          this.opData.email = app_data.PATIENT_EMAIL;

          this.getDropdowns();
        }

      }, (err) => {
        console.log(err);
      });
    }
  }
  ontpa_ItemSelect(event) {
   // console.log("event  " + JSON.stringify(event));

    this.getNetworks(event.TPA_ID);
  }
  onItemSelect(item: any) {
    // console.log(item);
  }
  onSelectAll(items: any) {
    // console.log(items);
  }
  public getDropdowns() {
    this.countries = [];
    const myDate = new Date();
    this.loaderService.display(true);
    this.rest.getOpDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        if (data['INSURANCE_CONST']) {
          this.INSURANCE_CONST = data['INSURANCE_CONST'];
        }
        if(this.opData.reg_id == ''){
          if (data['op_no']['status'] === 'Success') {
              this.opData.patient_number = data['op_no']['data'];
          }
        }
        if (data['country']['status'] === 'Success') {
          this.countries = data['country']['data'];
          this.country_options = this.countries;
          for (let index = 0; index < this.countries.length; index++) {
            if (this.countries[index].COUNTRY_ID == this.opData.country) {
              this.country_data = this.countries[index];
            } if (this.countries[index].COUNTRY_ID == this.opData.nationality) {
              this.nationality_data = this.countries[index];
            }
          }
        }
        if (data['type']['status'] === 'Success') {
          this.types = data['type']['data'];
        }
        if (data['info_source']['status'] === 'Success') {
          this.info_sources = data['info_source']['data'];
        }
        if (data['emirates']['status'] === 'Success') {
          this.emirates = data['emirates']['data'];
        }

        if (data['tpa_receiver']['status'] === 'Success') {
          this.tpa_receiver = data['tpa_receiver']['data'];
          this.tpa_options = this.tpa_receiver;
          for (let index = 0; index < this.tpa_options.length; index++) {
            if (this.tpa_options[index].TPA_ID == this.opData.sel_tpa_receiver) {
              this.tpa_data = this.tpa_options[index];
            }
          }
          // for (let index = 0; index < this.tpa_receiver.length; index++) {


          // }
        }
        // if (data['networks']['status'] === 'Success') {
        //   this.networks = data['networks']['data'];
        // }
        if (data['ins_com_pay']['status'] === 'Success') {
          this.ins_com_pay = data['ins_com_pay']['data'];
          this.inscopay_options = this.ins_com_pay;
          for (let index = 0; index < this.inscopay_options.length; index++) {
            if (this.inscopay_options[index].INSURANCE_PAYERS_ID == this.opData.sel_ins_co) {
              this.inscopay_data = this.inscopay_options[index];
            }
          }
        }
        if (data['co_ins']['status'] === 'Success') {
          this.co_ins = data['co_ins']['data'];
        }

        if (data['enc_type']['status'] === 'Success') {
          this.enc_type = data['enc_type']['data'];
        }
        if (data['enc_str_type']['status'] === 'Success') {
          this.enc_str_type = data['enc_str_type']['data'];
        }
        if (data['co_in_types']['status'] === 'Success') {
          this.co_in_types = data['co_in_types']['data'];
        }

      }

    });
  }
  public attachmentPath (filename)
  {
    var path = '';
    path  = AppSettings.API_ENDPOINT+AppSettings.OP_ATTACHMENT_PATH + filename;

    return path;
  }
  public keyDownFunction() {
    // console.log(event);
    // if (event.keyCode == 13) {
      if(this.search_by_opnumber != "" && this.search_by_opnumber != null)
      {
        this.getPatientDetails(this.search_by_opnumber);
      }
      else
      {
        this.clear_data();
        this.getDropdowns();
      }
     
    // }
  }
  public searchByEIDnumber() {
    if(this.search_by_eidnumber != "" && this.search_by_eidnumber != null)
    {
      this.getPatientByEIDnumber(this.search_by_eidnumber);
    }
    else
    {
      this.clear_data();
      this.getDropdowns();
    }
  }
  getPatientByEIDnumber(search_by_opnumber: string) {
    this.clear_data(); 
    this.getDropdowns();
    const sendJson = {
      eid_number: search_by_opnumber
    };
    this.loaderService.display(true);
    this.apRest.getPatientByEIDnumber(sendJson).subscribe((result) => {
     
      if (result.status == 'Success') {
        const patient_details = result.data;
        this.old_ins_details = result.data.old_insurance;
        if(this.old_insurance != false)
        {
          for(let data of this.old_ins_details)
          {
            data["EDIT"] = 0;
          }
        }
        this.cur_ins_details = patient_details.ins_data;
        this.opData.reg_id = patient_details.patient_data.OP_REGISTRATION_ID;
        this.opData.patient_number = patient_details.patient_data.OP_REGISTRATION_NUMBER;
        this.opData.dateVal = patient_details.patient_data.OP_REGISTRATION_DATE;
        this.opData.sel_pay_type = "2";
        this.opData.gender = patient_details.patient_data.GENDER;
        this.opData.f_name = patient_details.patient_data.FIRST_NAME;
        this.opData.m_name = patient_details.patient_data.MIDDLE_NAME;
        this.opData.l_name = patient_details.patient_data.LAST_NAME;
        this.opData.birthdate = patient_details.patient_data.DOB;
        this.opData.age = patient_details.patient_data.AGE;
        this.opData.months = patient_details.patient_data.MONTHS;
        this.opData.days = patient_details.patient_data.DAYS;
        this.opData.mobile_no = patient_details.patient_data.MOBILE_NO;
        this.opData.email = patient_details.patient_data.EMAIL_ID;
        this.opData.address = patient_details.patient_data.ADDRESS;
        this.opData.po_box = patient_details.patient_data.PO_BOX_NO;
        this.opData.country = patient_details.patient_data.COUNTRY;
        this.opData.nationality = patient_details.patient_data.NATIONALITY;
        this.opData.national_id = patient_details.patient_data.NATIONAL_ID;
        this.opData.sel_emirates = patient_details.patient_data.EMIRATES;
        this.opData.res_no = patient_details.patient_data.RES_NO;
        this.opData.city = patient_details.patient_data.CITY;
        this.opData.service = patient_details.patient_data.SERVICE;
        this.opData.visa_stat = patient_details.patient_data.VISA_STATUS;
        this.opData.fax = patient_details.patient_data.FAX;
        this.opData.sel_source_of_info = patient_details.patient_data.SOURCE_OF_INFO;

        this.opData.user_id = patient_details.patient_data.CREATED_USER;
        this.opData.app_id = 0;
        if(patient_details.ins_data != false)
        {
          this.opData.insurance_status = 1;
          this.opData.ins_detail_id = patient_details.ins_data.OP_INS_DETAILS_ID;
          this.op_ins_det_id = patient_details.ins_data.OP_INS_DETAILS_ID;
          this.insurance_change = 1;
        }
        else
        {
          this.opData.ins_detail_id = 0;
          this.op_ins_det_id = 0;
        }
        // this.opData.sel_tpa_receiver = patient_details.ins_data.OP_INS_TPA;
        // this.opData.sel_ins_co = patient_details.ins_data.OP_INS_PAYER;
        // this.opData.sel_network = patient_details.ins_data.OP_INS_NETWORK;
        // this.opData.memebr_id = patient_details.ins_data.OP_INS_MEMBER_ID;
        // this.opData.policy_no = patient_details.ins_data.OP_INS_POLICY_NO;
        // this.opData.valid_from = patient_details.ins_data.OP_INS_VALID_FROM;
        // this.opData.valid_to = patient_details.ins_data.OP_INS_VALID_TO;
        // this.opData.deductible = patient_details.ins_data.OP_INS_DEDUCTIBLE;
        // this.opData.deductible_type = patient_details.ins_data.OP_INS_DEDUCT_TYPE;
        // if( patient_details.ins_data.OP_INS_IS_ALL == 1)
        // {  
        //   // console.log(patient_details.ins_data.OP_INS_IS_ALL);
        //   // console.log(patient_details.ins_data.OP_INS_IS_ALL);
        //   this.opData.rest_of_all_check = patient_details.ins_data.OP_INS_IS_ALL;
        //   this.opData.rest_of_all_type = patient_details.ins_data.OP_INS_ALL_TYPE;
        //   this.opData.rest_of_all = patient_details.ins_data.OP_INS_ALL_VALUE;
        // }
        // else
        // {
        //   this.opData.rest_of_all_check = false;
        //   this.opData.rest_of_all_type = 'AED';
        //   this.opData.rest_of_all = '20';
        // }
        
       
       
        // if(patient_details.co_ins.length > 0)
        // {
        //   for(let data of patient_details.co_ins)
        //   {
        //     const co_in_added = {
        //       type_id: data.COIN_ID,
        //       type_name: data.COIN_NAME,
        //       deduct_type: data.COIN_VALUE_TYPE,
        //       deduct_val: data.COIN_VALUE
        //     };
        //     this.opData.co_in_selects.push(co_in_added);
        //   }
        //   this.co_in_selects = {
        //     type_id: '',
        //     type_name: '',
        //     error__message: '',
        //     deduct_type: 'AED',
        //     deduct_val: '20'
        //   };
        // }
        // else{
        //   this.co_in_selects = {
        //     type_id: '',
        //     type_name: '',
        //     error__message: '',
        //     deduct_type: 'AED',
        //     deduct_val: '20'
        //   };
        // }
        // this.getInsNetwork(patient_details.ins_data.OP_INS_TPA);
        this.opData.corporate_company = patient_details.corporate_data.CORPORATE_COMPANY_ID;
        this.opData.corporate_address = patient_details.corporate_data.CORPORATE_COMPANY_ADDRESS;
       
        this.attachment =  patient_details.op_attachment
        this.setconDropdown();
        this.settpaDropdown();
        this.setinsDropdown();
        this.setcompanyDropdown();
        this.opData.patient_number = patient_details.patient_data.OP_REGISTRATION_NUMBER
        this.loaderService.display(false);
      } else {
        this.notifier.notify('error', 'Invalid Emirates ID number!');
        this.clear_data();
        this.getDropdowns();
        this.listCorporateCompany();
        this.attachment = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  open(content) {
   
    if(this.search_by_pnonenumber != "" && this.search_by_pnonenumber != null)
    {
      var sendJson = {
        ph_no : this.search_by_pnonenumber
      }
      this.loaderService.display(true)
      this.apRest.getPatientsByPhoneNo(sendJson).subscribe((result) => {
        this.loaderService.display(false)
        if(result.status == "Success")
        {
          this.pat_list = result.data;
          if(this.pat_list.length > 0)
          {
            this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title'}).result.then((result) => {
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
    else{
      this.notifier.notify('warning', 'Please enter a phone number for search!');
    }
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


  public pdfexport(p_number=0,val=0) {
    if (p_number !=0 && p_number != null) {
      const postData = {
        visit_id :0,
        p_id : this.opData.reg_id,
        type: val,
        p_number: p_number,
        date : defaultDateTime(),
        timeZone : moment.tz.guess(),
        time : this.formatDateTime (new Date())
      };
      this.loaderService.display(true);
      this.apRest.downloadgeneralconsent(postData).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        //const FileSaver = require('file-saver');
        window.open(this.settings.API_ENDPOINT+result.data);
      }
      else{
        this.notifier.notify("error",result.message)
      }
    })
    }
  }

  public getPatientDetails(id) {
    this.clear_data(); 
    this.getDropdowns();
    this.opData.patient_number = id;
    const sendJson = {
      op_number: id
    };
    this.loaderService.display(true);
    this.apRest.getPatientDetails(sendJson).subscribe((result) => {
      
      if (result.status == 'Success') {
        const patient_details = result.data;
        this.opData.sel_tpa_receiver = "";
        this.opData.sel_ins_co = "";
        this.old_ins_details = result.data.old_insurance;
        if(this.old_insurance != false)
        {
          for(let data of this.old_ins_details)
          {
            data["EDIT"] = 0;
          }
        }
        this.cur_ins_details = patient_details.ins_data;
       // console.log( this.old_ins_details)
       this.opData.general = patient_details.patient_data.general;
       this.opData.dental = patient_details.patient_data.dental;
       this.opData.covid = patient_details.patient_data.covid;
        this.opData.reg_id = patient_details.patient_data.OP_REGISTRATION_ID;
        this.opData.patient_number = patient_details.patient_data.OP_REGISTRATION_NUMBER;
        this.opData.dateVal = patient_details.patient_data.OP_REGISTRATION_DATE;
        this.opData.sel_pay_type = "2";
        this.opData.gender = patient_details.patient_data.GENDER;
        this.opData.f_name = patient_details.patient_data.FIRST_NAME;
        this.opData.m_name = patient_details.patient_data.MIDDLE_NAME;
        this.opData.l_name = patient_details.patient_data.LAST_NAME;
        this.opData.birthdate = patient_details.patient_data.DOB;
        this.opData.age = patient_details.patient_data.AGE;
        this.opData.months = patient_details.patient_data.MONTHS;
        this.opData.days = patient_details.patient_data.DAYS;
        this.opData.mobile_no = patient_details.patient_data.MOBILE_NO;
        this.opData.email = patient_details.patient_data.EMAIL_ID;
        this.opData.address = patient_details.patient_data.ADDRESS;
        this.opData.po_box = patient_details.patient_data.PO_BOX_NO;
        this.opData.country = patient_details.patient_data.COUNTRY;
        this.opData.nationality = patient_details.patient_data.NATIONALITY;
        this.opData.national_id = patient_details.patient_data.NATIONAL_ID;
        this.opData.sel_emirates = patient_details.patient_data.EMIRATES;
        this.opData.res_no = patient_details.patient_data.RES_NO;
        this.opData.city = patient_details.patient_data.CITY;
        this.opData.service = patient_details.patient_data.SERVICE;
        this.opData.visa_stat = patient_details.patient_data.VISA_STATUS;
        this.opData.fax = patient_details.patient_data.FAX;
        this.opData.sel_source_of_info = patient_details.patient_data.SOURCE_OF_INFO;

        this.opData.user_id = patient_details.patient_data.CREATED_USER;
        this.opData.app_id = 0;
       if(patient_details.ins_data != false)
        {
          this.opData.insurance_status = 1;
          this.opData.ins_detail_id = patient_details.ins_data.OP_INS_DETAILS_ID;
          this.op_ins_det_id = patient_details.ins_data.OP_INS_DETAILS_ID;
          this.insurance_change = 1;

        }
        else
        {
          this.opData.ins_detail_id = 0;
          this.op_ins_det_id = 0;
        }
        // if(patient_details.ins_data != false)
        // {
        //   this.opData.sel_tpa_receiver = patient_details.ins_data.OP_INS_TPA;
        //   this.opData.sel_ins_co = patient_details.ins_data.OP_INS_PAYER;
        //   this.opData.sel_network = patient_details.ins_data.OP_INS_NETWORK;
        //   this.opData.memebr_id = patient_details.ins_data.OP_INS_MEMBER_ID;
        //   this.opData.policy_no = patient_details.ins_data.OP_INS_POLICY_NO;
        //   this.opData.valid_from = patient_details.ins_data.OP_INS_VALID_FROM;
        //   this.opData.valid_to = patient_details.ins_data.OP_INS_VALID_TO;
        //   this.opData.deductible = patient_details.ins_data.OP_INS_DEDUCTIBLE;
        //   this.opData.deductible_type = patient_details.ins_data.OP_INS_DEDUCT_TYPE;
        // }
        // if( patient_details.ins_data.OP_INS_IS_ALL == 1)
        // {
        //   this.opData.rest_of_all_check = patient_details.ins_data.OP_INS_IS_ALL;
        //   this.opData.rest_of_all_type = patient_details.ins_data.OP_INS_ALL_TYPE;
        //   this.opData.rest_of_all = patient_details.ins_data.OP_INS_ALL_VALUE;
        // }
        // else
        // {
        //   this.opData.rest_of_all_check = false;
        //   this.opData.rest_of_all_type = 'AED';
        //   this.opData.rest_of_all = '20';
        // }
        
        // if(patient_details.co_ins.length > 0)
        // {
        //   for(let data of patient_details.co_ins)
        //   {
        //     const co_in_added = {
        //       type_id: data.COIN_ID,
        //       type_name: data.COIN_NAME,
        //       deduct_type: data.COIN_VALUE_TYPE,
        //       deduct_val: data.COIN_VALUE
        //     };
        //     this.opData.co_in_selects.push(co_in_added);
        //   }
        //   this.co_in_selects = {
        //     type_id: '',
        //     type_name: '',
        //     error__message: '',
        //     deduct_type: 'AED',
        //     deduct_val: '20'
        //   };
        // }
        // else{
        //   this.co_in_selects = {
        //     type_id: '',
        //     type_name: '',
        //     error__message: '',
        //     deduct_type: 'AED',
        //     deduct_val: '20'
        //   };
        // }
        // if(patient_details.ins_data != false)
        //   this.getInsNetwork(patient_details.ins_data.OP_INS_TPA);
        this.opData.corporate_company = patient_details.corporate_data.CORPORATE_COMPANY_ID;
        this.opData.corporate_address = patient_details.corporate_data.CORPORATE_COMPANY_ADDRESS;
        this.attachment =  patient_details.op_attachment
        this.filelist = patient_details.op_attachment;
        this.setconDropdown();
        this.settpaDropdown();
        this.setinsDropdown();
        this.setcompanyDropdown();
        // this.listCorporateCompany();
        this.opData.patient_number = id
        this.loaderService.display(false);
      } else {
        this.notifier.notify('error', 'Invalid patient number!');
        this.clear_data();
        this.getDropdowns();
        this.listCorporateCompany();
        this.attachment = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  removeFile(id)
  {
    this.opData.removeUpload.push(id);
  }
  public getToday() 
  {
    return moment(new Date()).format('YYYY-MM-DD')
 }
  getcountry($event) {
    if(this.country_data)
      this.opData.country = this.country_data.COUNTRY_ID;
    else
      this.opData.country = "";
  }
  getNationality($event) {
    if(this.nationality_data)
      this.opData.nationality = this.nationality_data.COUNTRY_ID;
    else
      this.opData.nationality = "";
  }
  getTpa() {
    if(this.tpa_data)
      this.opData.sel_tpa_receiver = this.tpa_data.TPA_ID;
    else
      this.opData.sel_tpa_receiver = "";
  }
  getInspayer() {
    if(this.inscopay_data)
      this.opData.sel_ins_co = this.inscopay_data.INSURANCE_PAYERS_ID;
    else
      this.opData.sel_ins_co = ''
  }
  getcompany() {
    if(this.company_data){
      this.opData.corporate_company = this.company_data.CORPORATE_COMPANY_ID;
      this.opData.corporate_address = this.company_data.CORPORATE_COMPANY_ADDRESS;;
    }
    else{
      this.opData.corporate_company = "";
      this.opData.corporate_address = "";
    }
  }
  public setconDropdown() {
    for (let index = 0; index < this.countries.length; index++) {
      if (this.countries[index].COUNTRY_ID == this.opData.country) {
        this.country_data = this.countries[index];
        break;
      } 
      if (this.countries[index].COUNTRY_ID == this.opData.nationality) {
        this.nationality_data = this.countries[index];
        break;
      }
    }
  }
  public settpaDropdown() {
    if(this.opData.sel_tpa_receiver)
    for (let index = 0; index < this.tpa_options.length; index++) {
      if (this.tpa_options[index].TPA_ID == this.opData.sel_tpa_receiver) {
        this.tpa_data = this.tpa_options[index];
        break;
      }
    } 
  }
  public setinsDropdown() {
    for (let index = 0; index < this.inscopay_options.length; index++) {
      if (this.inscopay_options[index].INSURANCE_PAYERS_ID == this.opData.sel_ins_co) {
        this.inscopay_data = this.inscopay_options[index];
        break;
      }
    }
  }
  selectFiles($event,upload_file) {
    console.log(upload_file)
    if ($event.target.files && $event.target.files[0]) {
        var filesAmount = $event.target.files.length;
        for (let i = 0; i < filesAmount; i++) {
          this.fileName.push($event.target.files[i])
          var reader = new FileReader();
          reader.onload = (event:any) => {
            var image = event.target.result
            var splitted = image.split(",");
            this.opData.upload_file.push(splitted[1]); 
          }
          reader.readAsDataURL($event.target.files[i]);
        }
    }
    upload_file.value = ""
  }

  public validateNumber(event) {
    const keyCode = event.keyCode;  
    // console.log(keyCode)  
    const excludedKeys = [8, 37, 39, 46];   
     if (!((keyCode >= 48 && keyCode <= 57) ||
      (keyCode >= 96 && keyCode <= 105 ) || ( keyCode == 109 ) || ( keyCode == 173 ) || ( keyCode == 61 ) ||
      ( keyCode == 107 ) || ( keyCode == 37  ) || (excludedKeys.includes(keyCode)))) {
      event.preventDefault();
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
          this.company_options = this.corporate_company;
          for (let index = 0; index < this.company_options.length; index++) {
            if (this.company_options[index].CORPORATE_COMPANY_ID == this.opData.sel_ins_co) {
              this.company_data = this.company_options[index];
            }
          }
     }
   });
  }
  public setcompanyDropdown() {
    for (let index = 0; index < this.company_options.length; index++) {
      if (this.company_options[index].CORPORATE_COMPANY_ID == this.opData.corporate_company) {
        this.company_data = this.company_options[index];
        break;
      }
    }
  }
  public changeInsurance(old_ins,i)
  {
    this.edit = 1
    this.opData.edit_ins = 1
    this.old_ins_details[i].EDIT = 1;
    if(old_ins)
    {
      this.old_insurance = old_ins
    }
    if(this.old_insurance != false)
    {
      this.opData.sel_tpa_receiver = this.old_insurance.OP_INS_TPA;
      this.opData.sel_ins_co = this.old_insurance.OP_INS_PAYER;
      this.opData.sel_network = this.old_insurance.OP_INS_NETWORK;
      this.opData.memebr_id = this.old_insurance.OP_INS_MEMBER_ID;
      this.opData.policy_no = this.old_insurance.OP_INS_POLICY_NO;
      this.opData.valid_from = this.old_insurance.OP_INS_VALID_FROM;
      this.opData.valid_to = this.old_insurance.OP_INS_VALID_TO;
      this.opData.deductible = this.old_insurance.OP_INS_DEDUCTIBLE;
      this.opData.deductible_type = this.old_insurance.OP_INS_DEDUCT_TYPE;
      this.opData.ins_detail_id = this.old_insurance.OP_INS_DETAILS_ID
      this.opData.insurance_status = this.old_insurance.OP_INS_STATUS
    }
    if( this.old_insurance.OP_INS_IS_ALL == 1)
    {
      this.opData.rest_of_all_check = this.old_insurance.OP_INS_IS_ALL;
      this.opData.rest_of_all_type = this.old_insurance.OP_INS_ALL_TYPE;
      this.opData.rest_of_all = this.old_insurance.OP_INS_ALL_VALUE;
    }
    else
    {
      this.opData.rest_of_all_check = false;
      this.opData.rest_of_all_type = 'AED';
      this.opData.rest_of_all = '20';
    }
    this.opData.co_in_selects = []
    if(this.old_insurance.old_co_ins)
    {
      if(this.old_insurance.old_co_ins.length > 0)
      {
        for(let data of this.old_insurance.old_co_ins)
        {
          const co_in_added = {
            type_id: data.COIN_ID,
            type_name: data.COIN_NAME,
            deduct_type: data.COIN_VALUE_TYPE,
            deduct_val: data.COIN_VALUE
          };
          this.opData.co_in_selects.push(co_in_added);
        }
        this.co_in_selects = {
          type_id: '',
          type_name: '',
          error__message: '',
          deduct_type: 'AED',
          deduct_val: '20'
        };
      }
      else
      {
        this.co_in_selects = {
          type_id: '',
          type_name: '',
          error__message: '',
          deduct_type: 'AED',
          deduct_val: '20'
        };
      }
    }
    this.getInsNetwork(this.old_insurance.OP_INS_TPA);
    this.settpaDropdown();
    this.setinsDropdown();
    this.insurance_change = 0;
  }
  public getOldIns(old_ins)
  {
      this.old_insurance = old_ins
  }
  public activeInsurance()
  {
      this.opData.insurance_status = 1
      this.opData.ins_detail_id = this.old_insurance.OP_INS_DETAILS_ID
      this.op_ins_det_id = this.old_insurance.OP_INS_DETAILS_ID
      this.notifier.notify('info','Insurance data activated')
  }
  public deactiveInsurance()
  {
      this.opData.insurance_status = 0
      this.opData.ins_detail_id = this.old_insurance.OP_INS_DETAILS_ID
      this.op_ins_det_id = this.old_insurance.OP_INS_DETAILS_ID
      this.notifier.notify('info','Insurance data deactivated')
  }

  public UpdateInsurance(status)
  {
    if(status == 0)
    {
      if (this.opData.sel_pay_type === '1') {
        if (this.opData.sel_tpa_receiver === '' || this.opData.sel_tpa_receiver == null) {
          this.notifier.notify('warning', 'Please select TPA reciever !');
          return false;
        } if (this.opData.sel_ins_co === '' || this.opData.sel_ins_co == null) {
          this.notifier.notify('warning', 'Please select Insurance co payer !');
          return false;
        } if (this.opData.sel_network === '' || this.opData.sel_network == null) {
          this.notifier.notify('warning', 'Please select Insurance nerwork !');
          return false;
        } if (this.opData.memebr_id === '' || this.opData.memebr_id == null) {
          this.notifier.notify('warning', 'Please enter Insurance member id !');
          return false;
        } if (this.opData.policy_no === '' || this.opData.policy_no == null) {
          this.notifier.notify('warning', 'Please enter Insurance policy number !');
          return false;
        } if (this.opData.valid_from === '' || this.opData.valid_from == null) {
          this.notifier.notify('warning', 'Please enter Insurance validity !');
          return false;
        } if (this.opData.valid_to === '' || this.opData.valid_to == null) {
          this.notifier.notify('warning', 'Please enter Insurance validity !');
          return false;
        }
      } 
    }
    if(status == 1 || status == 2){
      if(this.old_insurance != false)
      {
        this.opData.sel_tpa_receiver = this.old_insurance.OP_INS_TPA;
        this.opData.sel_ins_co = this.old_insurance.OP_INS_PAYER;
        this.opData.sel_network = this.old_insurance.OP_INS_NETWORK;
        this.opData.memebr_id = this.old_insurance.OP_INS_MEMBER_ID;
        this.opData.policy_no = this.old_insurance.OP_INS_POLICY_NO;
        this.opData.valid_from = this.old_insurance.OP_INS_VALID_FROM;
        this.opData.valid_to = this.old_insurance.OP_INS_VALID_TO;
        this.opData.deductible = this.old_insurance.OP_INS_DEDUCTIBLE;
        this.opData.deductible_type = this.old_insurance.OP_INS_DEDUCT_TYPE;
        this.opData.ins_detail_id = this.old_insurance.OP_INS_DETAILS_ID
        this.op_ins_det_id = this.old_insurance.OP_INS_DETAILS_ID
        this.opData.insurance_status = this.old_insurance.OP_INS_STATUS
      }
      if( this.old_insurance.OP_INS_IS_ALL == 1)
      {
        this.opData.rest_of_all_check = this.old_insurance.OP_INS_IS_ALL;
        this.opData.rest_of_all_type = this.old_insurance.OP_INS_ALL_TYPE;
        this.opData.rest_of_all = this.old_insurance.OP_INS_ALL_VALUE;
      }
      else
      {
        this.opData.rest_of_all_check = false;
        this.opData.rest_of_all_type = 'AED';
        this.opData.rest_of_all = '20';
      }
      this.opData.co_in_selects = []
      if(this.old_insurance.old_co_ins)
      {
        if(this.old_insurance.old_co_ins.length > 0)
        {
          for(let data of this.old_insurance.old_co_ins)
          {
            const co_in_added = {
              type_id: data.COIN_ID,
              type_name: data.COIN_NAME,
              deduct_type: data.COIN_VALUE_TYPE,
              deduct_val: data.COIN_VALUE
            };
            this.opData.co_in_selects.push(co_in_added);
          }
          this.co_in_selects = {
            type_id: '',
            type_name: '',
            error__message: '',
            deduct_type: 'AED',
            deduct_val: '20'
          };
        }
        else
        {
          this.co_in_selects = {
            type_id: '',
            type_name: '',
            error__message: '',
            deduct_type: 'AED',
            deduct_val: '20'
          };
        }
      }
    }
    
    const INS_DATA = {
      OP_INS_TPA : this.opData.sel_tpa_receiver,
      OP_INS_PAYER :  this.opData.sel_ins_co,
      OP_INS_NETWORK :  this.opData.sel_network,
      OP_INS_MEMBER_ID :  this.opData.memebr_id,
      OP_INS_POLICY_NO :  this.opData.policy_no,
      OP_INS_VALID_FROM :  this.opData.valid_from,
      OP_INS_VALID_TO :  this.opData.valid_to,
      OP_INS_DEDUCTIBLE :  this.opData.deductible,
      OP_INS_DEDUCT_TYPE :  this.opData.deductible_type,
      OP_INS_DETAILS_ID :  this.opData.ins_detail_id,
      OP_INS_STATUS : this.opData.insurance_status,
      OP_INS_IS_ALL : this.opData.rest_of_all_check,
      OP_INS_ALL_TYPE : this.opData.rest_of_all_type,
      OP_INS_ALL_VALUE : this.opData.rest_of_all
    }
    if(status == 1)
    {
      INS_DATA.OP_INS_STATUS = 1
    }
    if(status == 2)
    {
      INS_DATA.OP_INS_STATUS = 0
    }
    const sendJson = {
      OP_REGISTRATION_ID :  this.opData.reg_id,
      INS_DATA : INS_DATA,
      CO_IN_DATA : this.opData.co_in_selects,
      STATUS : status

    };
    
   
    this.loaderService.display(true);
      this.rest.updateInsuranceDetails(sendJson).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status === 'Success') {
          this.old_ins_details = result["data"]["old_ins"]
          this.getcurrentIns();
          if(status == 0)
            this.notifier.notify( 'success', result.message );
          if(status == 1)
            this.notifier.notify( 'success', 'Insurance details activated' );
          if(status == 2)
            this.notifier.notify('success','Insurance details deactivated')
          this.edit = 0
          this.opData.edit_ins = 0
          this.tpa_data = []
          this.inscopay_data = []
          // this.opData.ins_detail_id = 0
          // this.op_ins_det_id = 0
          // this.insurance_change = 0;
          // this.opData.insurance_status = 1;
          this.opData.ins_detail_id = result.data.ins_data.OP_INS_DETAILS_ID;
          this.op_ins_det_id = result.data.ins_data.OP_INS_DETAILS_ID;
          // this.insurance_change = 1;

          // this.getTpa();
          // this.getInspayer();
          // this.getDropdowns();
          // this.getPatientDetails(this.opData.patient_number);
          
        } else {
          this.notifier.notify( 'error', result.message );

        }
      }, (err) => {
        console.log(err);
      });
  }

  public getcurrentIns()
  {
    this.opData.ins_detail_id = 0
    // if(this.cur_ins_details != false)
    // {
      this.opData.sel_tpa_receiver = '';  
      this.opData.sel_ins_co = '';     
      this.opData.sel_network = '';
      this.opData.memebr_id = '';
      this.opData.policy_no = '';
      this.opData.valid_from = '';
      this.opData.valid_to = '';
      this.opData.deductible = '20';
      this.opData.deductible_type = 'AED';
     
    
      this.opData.rest_of_all_check = false;
      this.opData.rest_of_all_type = 'AED';
      this.opData.rest_of_all = '20';
      this.opData.co_in_selects = []
  
        this.co_in_selects = {
          type_id: '',
          type_name: '',
          error__message: '',
          deduct_type: 'AED',
          deduct_val: '20'
        };
    // this.notifier.notify('success','Insurance details deactivated')
  }
  private confirms(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true,
      ariaLabelledBy: 'modal-basic-title',
      size: 'sm',
      centered : false
    };
   
    this.modalService.open(content, ngbModalOptions).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
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
  configer = {
    displayKey: "COUNTRY_NAME",
    search: true, 
    height: '200px',
    placeholder: 'Select Country',
    limitTo: 239, 
    moreText: '.........', 
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'COUNTRY_NAME'
  }
  configers = {
    displayKey: "COUNTRY_NAME",
    search: true, 
    height: '200px',
    placeholder: 'Select Nationality',
    limitTo:  239, 
    moreText: '.........', 
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'COUNTRY_NAME'
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

}



