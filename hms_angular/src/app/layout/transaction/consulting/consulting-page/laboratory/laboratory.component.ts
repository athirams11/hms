import { Component, OnInit, Input, SimpleChanges, OnChanges, NgModule, Output, EventEmitter } from '@angular/core';
import { ConsultingService, NursingAssesmentService, DoctorsService} from './../../../../../shared/services';
import { AppSettings } from './../../../../../app.settings';
import {DatePipe, CommonModule, JsonPipe } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import { FormGroup, FormControl } from '@angular/forms';
import * as moment from 'moment';
import { NgxLoadingComponent }  from 'ngx-loading';
import { LoaderService } from '../../../../../shared';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import { SelectDropDownModule } from 'ngx-select-dropdown';
import { ModalDismissReasons, NgbModalOptions, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import { FormsModule } from '@angular/forms';
import { resource } from 'selenium-webdriver/http';
import Moment from 'moment-timezone';
@Component({
  selector: 'app-laboratory',
  templateUrl: './laboratory.component.html',
  styleUrls: ['./laboratory.component.scss']
})
@NgModule({
  imports: [

    SelectDropDownModule
  ],
})
export class LaboratoryComponent implements OnInit , OnChanges {
  public investigation_data: any = [];
  public status = 0;
  closeResult: string;
  remove_data: any;
   constructor(public dropdown: SelectDropDownModule,private modalService: NgbModal, private loaderService: LoaderService, public datepipe: DatePipe, private router: Router, public rest2: ConsultingService, public rest: NursingAssesmentService, notifierService: NotifierService, public rest1: DoctorsService) {
     this.notifier = notifierService;
    //  this.laboratory_data.laboratory_quantity = "1"
    }

  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() selected_visit: any = [];
  public todaysDate = defaultDateTime();
  @Input() vital_values: any = [];
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  public notifier: NotifierService;
  public laboratory_data_arr: any = [''];
  public priority_list: any = [];
  public cpt_list: any = [];
  public dropdownSettings1 = {};
  public dropdownSettings2 = {};
  public dropdownSettings3 = {};
  public loading = false;
  public lab_investigation_values: any = [];
  public user_data: any = {};
  public num_rows: any = {};
  public lab_id_array: any = {};
  public current_procedural_code_id_arr: any = {};
  public settings = AppSettings;
  public index;
  public user_rights: any = {};
  public lab_investigation_id = 0;
  public bill = 0;
  public sum = 0;
  public total = 0;
  public searcher: any = '';
  public searchlength: number;
  public alliyas_array: any = [''];
  public laboratory_data = {
    cptcode : [],
    patient_allergies_id: 0,
    patient_id: 0,
    assessment_id: 0,
    patient_no_known_allergies: 0,
    laboratory_description: [''],
    laboratory_allias_data: [],
    laboratory_alliasname: [''],
    laboratory_cptname: [''],
    laboratory_cptcode: [],
    laboratory_quantity: ["1"],
    laboratory_rate: [''],
    laboratory_change_of_future: [''],
    laboratory_remarks: [''],
    laboratory_priority: [''],
    laboratory_billedamount : 0,
    laboratory_un_billedamount : 0,
    laboratory_instruction : '',
    current_procedural_code_id_arr:[],
    current_procedural_code_id: [''],
    laboratory_cptcode_id: [''],
    user_id : ''
  };
  public cpt_add_data = {
    patient_id: 0,
    assessment_id: 0,
    lab_investigation_id : 0,
    lab_investigation_details_id : 0,
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
    billedamount : 0,
    un_billedamount : 0,
    instruction : '',
    current_procedural_code_id:0,
    cptcode_id: 0,
    user_id : 0,
    client_date  : new Date(),
    date  : defaultDateTime(),
    timeZone : ''
  };
  current_procedural_code_id: any[];
  public config = {};
  bill_status: any;
 model: any;
    searching = false;
    searchFailed = false;



  ngOnInit() {
    this.clear();
   // this.getGpCpt();
    this.get_investigation();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.get_priority();
    this.getbillStatus();
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.laboratory_data.user_id = this.user_data.user_id;
    
    this.config = {
      displayKey: 'PROCEDURE_CODE_NAME', // if objects array passed which key to be displayed defaults to description
      search: true, // true/false for the search functionlity defaults to false,
      height: '', // height of the list so that if there are more no of items it can show a scroll defaults to auto. With auto height scroll will never appear
      placeholder: 'Select', // text to be displayed when no item is selected defaults to Select,
      customComparator: () => {}, // a custom function using which user wants to sort the items. default is undefined and Array.sort() will be used in that case,
      limitTo: this.cpt_list.length, // a number thats limits the no of options displayed in the UI similar to angular's limitTo pipe
      moreText: 'more', // text to be displayed whenmore than one items are selected like Option 1 + 5 more
      noResultsFound: 'No results found!', // text to be displayed when no items are found while searching
      searchPlaceholder: 'Search', // label thats displayed in search input,
      searchOnKey: '', // key on which search should be performed this will be selective search. if undefined this will be extensive search on all keys
    };

  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.get_priority();
    // this.getCPTlist();
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.laboratory_data.user_id = this.user_data.user_id;
    this.get_investigation();

  }
  search($event, i: number) {
   this.searcher = $event.objects[i];
  
  }
  public getEvent()
  {
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      this.save_notify = 1
      this.saveNotify.emit(this.save_notify)
      // if(this.laboratory_data.laboratory_cptcode_id.length == 0)
      // {
      //   this.save_notify = 0
      //   this.saveNotify.emit(this.save_notify)
      // }
      // else
      // {
      //   this.save_notify = 1  
      //   this.saveNotify.emit(this.save_notify)
      // }
    }
    else {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
    }
  }
   public set_item($event, i = 0) {
    const item = $event.item;
    // this.laboratory_data.laboratory_alliasname[i] = $event.item.PROCEDURE_CODE_ALIAS_NAME
    this.laboratory_data.current_procedural_code_id_arr[i] = item.CURRENT_PROCEDURAL_CODE_ID;
    this.cpt_add_data.current_procedural_code_id = item.CURRENT_PROCEDURAL_CODE_ID;
    // console.log(item)
    this.getCpt(item, i);
   }
   public getCpt(data, i) {

     const postData = {
       current_procedural_code_id : data.CURRENT_PROCEDURAL_CODE_ID
     };
     this.loaderService.display(true);
   
     this.loaderService.display(true);
     this.rest1.getCPT(postData).subscribe((result: {}) => {
      if (result['status'] === 'Success') {

        
         const cptlist_data = result['data'];
         this.laboratory_data.laboratory_alliasname[i] = cptlist_data.PROCEDURE_CODE_ALIAS_NAME;
          this.laboratory_data.laboratory_cptcode[i] = cptlist_data.PROCEDURE_CODE;
          this.laboratory_data.laboratory_description[i] = cptlist_data.PROCEDURE_CODE_DESCRIPTION;
          this.laboratory_data.laboratory_cptcode_id[i] = cptlist_data.CURRENT_PROCEDURAL_CODE_ID;
          this.laboratory_data.laboratory_rate[i] = cptlist_data.CPT_RATE;
          this.cpt_add_data.alliasname = cptlist_data.PROCEDURE_CODE_ALIAS_NAME;
          this.cpt_add_data.cptcode = cptlist_data.PROCEDURE_CODE;
          this.cpt_add_data.description = cptlist_data.PROCEDURE_CODE_DESCRIPTION;
          this.cpt_add_data.cptcode_id = cptlist_data.CURRENT_PROCEDURAL_CODE_ID;
          this.cpt_add_data.rate = cptlist_data.CPT_RATE;
          // this.calculateBilledamount()
          //this.calculateBillamount()
          this.calculateBilledamount()
         // this.laboratory_data_arr[i+1] = '';
         if(this.laboratory_data.laboratory_cptcode_id[i+1] == '' || this.laboratory_data.laboratory_cptcode_id[i+1] == null ){
          this.addDrugrow(i)
         }
           this.loaderService.display(false);
       } else {
           this.loaderService.display(false);
       }

    });
   
   }
   public saveInvestigation()
   {
     if(this.cpt_add_data.current_procedural_code_id == 0)
     {
       this.notifier.notify( 'error', 'Please select a CPT!' );
       return;
     }
     this.get_investigation();
     this.cpt_add_data.lab_investigation_id = this.lab_investigation_id
     this.cpt_add_data.patient_id = this.patient_id,
     this.cpt_add_data.assessment_id = this.assessment_id,
     this.cpt_add_data.user_id = this.user_data.user_id
     this.cpt_add_data.client_date = new Date()
     this.cpt_add_data.date = defaultDateTime()
     this.cpt_add_data.timeZone =  Moment.tz.guess()
     this.calculateBillamount();
     this.cpt_add_data.billedamount = Math.round(this.cpt_add_data.billedamount) + Math.round(this.cpt_add_data.rate)
     this.loaderService.display(true);
     this.rest1.saveInvestigation(this.cpt_add_data).subscribe((result) => {
       this.loaderService.display(false);
       this.save_notify = 2
        this.saveNotify.emit(this.save_notify)
       if(result.status == "Success")
       {
          
          this.clearInvestigation();
          this.notifier.notify('success',result.msg)
          this.get_investigation();
          this.cpt_add_data.alliasname = ''
          this.cpt_add_data.cptcode = ''
          this.cpt_add_data.description = ''
          this.cpt_add_data.cptcode_id = 0
          this.cpt_add_data.rate = 0
          this.cpt_add_data.description =  ''
          this.cpt_add_data.priority =  0
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
          this.clearInvestigation();
       }
       else
       {
        this.notifier.notify('error',result.msg)
       }
     }, (err) => {
       //console.log(err);
     });
   }
   
   public editCPT(data)
   {
      window.scrollTo(0, 0);
      this.cpt_add_data.alliasname = data.PROCEDURE_CODE_ALIAS_NAME;
      this.cpt_add_data.cptcode = data.PROCEDURE_CODE;
      this.cpt_add_data.description = data.DESCRIPTION;
      this.cpt_add_data.cptcode_id = data.CURRENT_PROCEDURAL_CODE_ID;
      this.cpt_add_data.current_procedural_code_id = data.CURRENT_PROCEDURAL_CODE_ID
      this.cpt_add_data.rate = data.RATE;
      this.cpt_add_data.priority =  data.LAB_PRIORITY_ID
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
      // console.log( this.cpt_add_data)
  
   }
   public clearInvestigation()
   {
    //  const rate =  parseFloat(this.cpt_add_data.quantity) * Math.round(this.cpt_add_data.rate)
    //  if(this.cpt_add_data.billedamount < rate)
    //  {
    //   this.cpt_add_data.billedamount = 0
    //  }
    //  else
    //  {
    //   this.cpt_add_data.billedamount = Math.round(this.cpt_add_data.billedamount) - rate
    //  }
        this.cpt_add_data.alliasname = '';
        this.cpt_add_data.cptcode = '';
        this.cpt_add_data.description = '';
        this.cpt_add_data.cptcode_id = 0;
        this.cpt_add_data.current_procedural_code_id = 0
        this.cpt_add_data.rate = 0;
        this.cpt_add_data.priority =  0
        this.cpt_add_data.remarks = ''
        this.cpt_add_data.quantity = '1'
        this.cpt_add_data.change_of_future = ''
        this.cpt_add_data.lab_investigation_details_id = 0
        // this.cpt_add_data.lab_investigation_id = data.LAB_INVESTIGATION_DETAILS_ID
        const temp = {
          'PROCEDURE_CODE': '',
          'PROCEDURE_CODE_NAME': '',
          'CURRENT_PROCEDURAL_CODE_ID': '',
          'PROCEDURE_CODE_CATEGORY': ''
        };
        this.cpt_add_data.cpt_data[0] = temp;
       // console.log( this.cpt_add_data)
      
   }
   public clear()
   {
        this.cpt_add_data.alliasname = '';
        this.cpt_add_data.cptcode = '';
        this.cpt_add_data.description = '';
        this.cpt_add_data.cptcode_id = 0;
        this.cpt_add_data.current_procedural_code_id = 0
        this.cpt_add_data.rate = 0;
        this.cpt_add_data.priority =  0
        this.cpt_add_data.remarks = ''
        this.cpt_add_data.quantity = '1'
        this.cpt_add_data.change_of_future = ''
        this.cpt_add_data.lab_investigation_details_id = 0
        // this.cpt_add_data.lab_investigation_id = data.LAB_INVESTIGATION_DETAILS_ID
        const temp = {
          'PROCEDURE_CODE': '',
          'PROCEDURE_CODE_NAME': '',
          'CURRENT_PROCEDURAL_CODE_ID': '',
          'PROCEDURE_CODE_CATEGORY': ''
        };
        this.cpt_add_data.cpt_data[0] = temp;
        this.cpt_add_data.un_billedamount = 0
        this.cpt_add_data.billedamount = 0
        this.cpt_add_data.instruction = ''
        this.cpt_add_data.lab_investigation_id = 0
        this.status = 0
      
   }
   public remove(data)
   {
    this.remove_data = data
   }
   public deleteInvestigation()
   {
    this.cpt_add_data.billedamount = Math.round(this.cpt_add_data.billedamount) - Math.round(this.remove_data.RATE)
    const postData = {
      lab_investigation_details_id : this.remove_data.LAB_INVESTIGATION_DETAILS_ID,
      lab_investigation_id : this.cpt_add_data.lab_investigation_id,
      billedamount : this.cpt_add_data.billedamount
    };
   
    this.loaderService.display(true);
    this.rest1.deleteInvestigation(postData).subscribe((result) => {
      if (result.status === 'Success') {
        this.loaderService.display(false);
        this.notifier.notify("success",result.msg)
        this.get_investigation();
        // this.cpt_add_data.billedamount = 0
       // this.calculateBillamount()
      }
      else{
        this.notifier.notify("error",result.msg)
      }
    });
   }
   private confirms(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true,
      ariaLabelledBy: 'modal-basic-title',
      size: 'sm',
      centered : true
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
  public addDrugrow(index) {
    this.laboratory_data_arr [index + 1] = '';
    this.index = index;
    this.laboratory_data.laboratory_alliasname[index + 1] = '';
    this.laboratory_data.laboratory_cptcode[index + 1] = '';
    this.laboratory_data.laboratory_description[index + 1] = '';
    this.laboratory_data.laboratory_quantity[index + 1] = '1';
    this.laboratory_data.laboratory_rate[index + 1] = '';
    this.laboratory_data.laboratory_change_of_future[index + 1] = '';
    this.laboratory_data.laboratory_remarks[index + 1] = '';
    this.laboratory_data.laboratory_priority[index + 1] = '';
    this.calculateBilledamount();
  }

  public deleteDrugrow(index) {
    this.laboratory_data_arr.splice(index,1);
    this.laboratory_data.laboratory_allias_data.splice(index,1);
    this.laboratory_data.laboratory_alliasname.splice(index,1);
    this.laboratory_data.laboratory_cptcode.splice(index,1);
    this.laboratory_data.laboratory_description.splice(index,1);
    this.laboratory_data.laboratory_quantity.splice(index,1);
    this.laboratory_data.laboratory_rate.splice(index,1);
    this.laboratory_data.laboratory_change_of_future.splice(index,1);
    this.laboratory_data.laboratory_remarks.splice(index,1);
    this.laboratory_data.laboratory_priority.splice(index,1);
    this.laboratory_data.laboratory_cptcode_id.splice(index,1);
    this.calculateBilledamount();
    this.getEvent()
  }

  public billchange(amount, nos, i) {

    amount = +amount;
    nos = +nos;
    this.sum = this.laboratory_data.laboratory_billedamount;
    if (amount && nos) {
      amount = Number(amount);
      nos =  Number(nos);
    this.bill = Number(amount * nos);
     this.total = Number(this.total);
     this.sum = Number(this.sum);
     this.bill = Number(this.bill);
     this.total = this.sum - this.bill;
    

   }
   this.laboratory_data.laboratory_billedamount = this.total;
   

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
  
  public get_priority() {
    const postData = {
      master_id : 11
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
  public save_lab_investigations() {
    var rel = 0
    this.laboratory_data.laboratory_cptcode_id.forEach((item, index) => {
        if (this.laboratory_data.laboratory_cptcode_id[index] == '' || this.laboratory_data.laboratory_cptcode_id[index] == null) {
         // this.notifier.notify( 'error', 'Please enter atleast one data' );
          rel = 1;
         
        } 
    });
    if(rel == 1)
    {
      this.notifier.notify( 'error', 'Please enter atleast one data' );
      return true;
    }
    // if (this.laboratory_data.laboratory_cptcode_id[''] === [ ] || this.laboratory_data.laboratory_cptcode_id [0] === '0' ) {
    //   this.notifier.notify( 'error', 'Please enter atleast one data' );
    //   return;
    // } 
  //   else if (this.laboratory_data.laboratory_quantity[0] === '' || this.laboratory_data.laboratory_quantity [0] === '0' ) {
  //   this.notifier.notify( 'error', 'Please enter the quantity' );
  //     return;
  //  } 
   else {
    const postData = {
        assessment_id: this.assessment_id,
        patient_id: this.patient_id,
        user_id : this.user_data.user_id,
        lab_investigation_id : this.lab_investigation_id,
        billed_amount : this.laboratory_data.laboratory_billedamount,
        un_billed_amount : this.laboratory_data.laboratory_un_billedamount,
        lab_investigation_priority_id : '',
        instruction_to_cashier : this.laboratory_data.laboratory_instruction,
        current_procedural_code_id_arr : this.laboratory_data.laboratory_cptcode_id,
        description_arr: this.laboratory_data.laboratory_description,
        rate_arr: this.laboratory_data.laboratory_rate,
        change_to_future_arr : this.laboratory_data.laboratory_change_of_future,
        remarks_arr : this.laboratory_data.laboratory_remarks,
        lab_priority_id_arr : this.laboratory_data.laboratory_priority,
        quantity_arr : this.laboratory_data.laboratory_quantity,
        client_date : this.formatDateTime(this.todaysDate),
        date :defaultDateTime(),
        timeZone : getTimeZone()
    };
  this.loaderService.display(true);
    this.rest1.saveLab_investigation(postData).subscribe((result) => {
      window.scrollTo(0, 0);
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if (result['status'] === 'Success') {
        
        this.loaderService.display(false);
        this.notifier.notify( 'success', 'Investigative Procedure details saved successfully..!');
        this.get_investigation();
        this.calculateBilledamount()
      } else {
        this.loaderService.display(false);
        this.notifier.notify( 'error', ' Failed' );
      }
    });
  }


 }
public calculateBilledamount() {
  
    let sum = 0;
    this.laboratory_data.laboratory_rate.forEach((item, index) => {
      const quantity = this.laboratory_data.laboratory_quantity[index];
      const rate = this.laboratory_data.laboratory_rate[index];
      const qty = 1;
      if ( (quantity == '' || quantity == null) && rate) {
        sum = sum + qty * parseFloat(rate);
      }
      if ( quantity && rate) {
        sum = sum + parseFloat(quantity) * parseFloat(rate);
      }
    });
    sum = Math.round((sum + Number.EPSILON) * 100) / 100
    this.sum = this.laboratory_data.laboratory_billedamount = sum;
    // console.log(sum)
  }
  public calculateBillamount() {
  
    // this.cpt_add_data.billedamount = this.cpt_add_data.billedamount + (this.cpt_add_data.rate)
    let sum = 0;
    var total = 0
    if(this.investigation_data.length > 0){
      for(let data of this.investigation_data)
      {
        if(this.cpt_add_data.lab_investigation_details_id == data.LAB_INVESTIGATION_DETAILS_ID)
        {
          sum =  Math.round(this.cpt_add_data.rate)
        }
        else
        {
          total = total +  parseFloat(data.RATE)
        }
      }
      this.cpt_add_data.billedamount = total + sum;
    }
    else{
     this.cpt_add_data.billedamount = 0;
    }
      
  }
  public getbillStatus() {
    const sendJson = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id
    };
    this.rest1.getbillStatus(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.bill_status = result.data.bill_status;
      } else {
      }
    }, (err) => {
      //console.log(err);
    });
  }
  public get_investigation() {
    const postData = {
    patient_id : this.patient_id,
    assessment_id : this.assessment_id,
  };
  this.loaderService.display(true);
  this.laboratory_data_arr = [""]
  this.rest1.get_lab_investigation(postData).subscribe((result) => {
    if (result.status === 'Success') {
    this.status = 1
    this.loaderService.display(false);
    this.laboratory_data.laboratory_billedamount = result.data.BILLED_AMOUNT;
    this.laboratory_data.laboratory_un_billedamount = result.data.UN_BILLED_AMOUNT;
    this.laboratory_data.laboratory_instruction = result.data.INSTRUCTION_TO_CASHIER;
    this.lab_investigation_id = result.data.LAB_INVESTIGATION_ID;
    this.cpt_add_data.lab_investigation_id = result.data.LAB_INVESTIGATION_ID;

    this.cpt_add_data.billedamount = parseFloat(result.data.BILLED_AMOUNT);
    this.cpt_add_data.un_billedamount = result.data.UN_BILLED_AMOUNT;
    this.cpt_add_data.instruction = result.data.INSTRUCTION_TO_CASHIER;
    let i = 0;
    const lab_id_array = [];
    const num_rows = [];
    const laboratory_data_arr = [];
    this.investigation_data = result.data.LAB_INVESTIGATION_DETAILS
      for (const val of result.data.LAB_INVESTIGATION_DETAILS) {
        laboratory_data_arr.push(i);
        const temp = [];
        lab_id_array[i] = temp;
        num_rows[i] = i;
        this.laboratory_data.laboratory_allias_data[i] =  val;
        this.laboratory_data.laboratory_alliasname[i] =  val.PROCEDURE_CODE_NAME;
        this.laboratory_data.laboratory_cptcode[i] = val.PROCEDURE_CODE;
        this.laboratory_data.laboratory_description[i] = val.DESCRIPTION;
        this.laboratory_data.laboratory_quantity[i] = val.QUANTITY;
        this.laboratory_data.laboratory_rate[i] = val.RATE;
        this.laboratory_data.laboratory_change_of_future[i] = val.CHANGE_TO_FUTURE;
        this.laboratory_data.laboratory_remarks[i] = val.REMARKS;
        this.laboratory_data.laboratory_priority[i] = val.LAB_PRIORITY_ID;
        this.laboratory_data.laboratory_cptcode_id[i] = val.CURRENT_PROCEDURAL_CODE_ID;
        i = i + 1;
        
      }
      laboratory_data_arr.push("")
      this.laboratory_data.laboratory_quantity.push("1");
      this.lab_id_array = lab_id_array;
      this.num_rows = num_rows;
      this.laboratory_data_arr = laboratory_data_arr;
     


      } else {
        this.status = 0
        this.loaderService.display(false);
        this.laboratory_data = {
          cptcode : [],
          patient_allergies_id: 0,
          patient_id: 0,
          assessment_id: 0,
          patient_no_known_allergies: 0,
          laboratory_description: [''],
          laboratory_allias_data: [],
          laboratory_alliasname: [''],
          laboratory_cptname: [''],
          laboratory_cptcode: [],
          laboratory_quantity: ["1"],
          laboratory_rate: [''],
          laboratory_change_of_future: [''],
          laboratory_remarks: [''],
          laboratory_priority: [''],
          laboratory_billedamount : 0,
          laboratory_un_billedamount : 0,
          laboratory_instruction : '',
          current_procedural_code_id_arr: [''],
          current_procedural_code_id: [''],
          laboratory_cptcode_id: [''],
          user_id : ''
        };
        this.investigation_data = []
        this.laboratory_data.laboratory_billedamount = 0;
        this.laboratory_data.laboratory_un_billedamount = 0;
        this.laboratory_data.laboratory_instruction = '';
        this.lab_investigation_id = 0;
        if(formatDate(this.todaysDate) != formatDate(this.selected_visit.CREATED_TIME))
        {
            this.laboratory_data_arr = ["0"]
        }
        else
        {
          this.getGpCpt();
        }
        
      
    }
    }, (err) => {
     // console.log(err);
  });
 }
  public getGpCpt() {
    
      const postData = {
        current_procedure_code : "9"
      };
      this.loaderService.display(true);
      window.scrollTo(0, 0);
      this.rest.getCPTByCode(postData).subscribe((result: {}) => {
        this.loaderService.display(false);
        if (result['status'] === 'Success') {
          const cpt = result['data'];
          // console.log(cpt)
         
            if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
            {
                this.laboratory_data.laboratory_allias_data["0"] = cpt;
                this.laboratory_data.laboratory_alliasname["0"] = cpt.PROCEDURE_CODE_ALIAS_NAME;
                this.laboratory_data.laboratory_cptcode["0"] = cpt.PROCEDURE_CODE;
                this.laboratory_data.laboratory_description["0"] = cpt.PROCEDURE_CODE_DESCRIPTION;
                this.laboratory_data.laboratory_cptcode_id["0"] = cpt.CURRENT_PROCEDURAL_CODE_ID;
                this.laboratory_data.laboratory_rate["0"] = cpt.CPT_RATE;

                this.cpt_add_data.alliasname = cpt.PROCEDURE_CODE_ALIAS_NAME;
                this.cpt_add_data.cptcode = cpt.PROCEDURE_CODE;
                this.cpt_add_data.description = cpt.PROCEDURE_CODE_DESCRIPTION;
                this.cpt_add_data.cptcode_id = cpt.CURRENT_PROCEDURAL_CODE_ID;
                this.cpt_add_data.rate = cpt.CPT_RATE;
                this.cpt_add_data.cpt_data[0] = cpt;
                this.cpt_add_data.current_procedural_code_id = cpt.CURRENT_PROCEDURAL_CODE_ID;
            }
          //  console.log(this.laboratory_data)
            // this.get_investigation();
            // this.calculateBilledamount()
           // this.calculateBillamount()
            if(this.laboratory_data.laboratory_cptcode_id["1"] == '' || this.laboratory_data.laboratory_cptcode_id["1"] == null)
            {
              this.addDrugrow(0);
            }
        } else {
            this.loaderService.display(false);
        }
      });
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
 formatter = (x: {PROCEDURE_CODE_NAME: String, CURRENT_PROCEDURAL_CODE_ID: Number, PROCEDURE_CODE_CATEGORY: Number, PROCEDURE_CODE: String }) => x.PROCEDURE_CODE_NAME;

}
