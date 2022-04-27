import { Component, OnInit, Input, SimpleChanges, OnChanges, NgModule, ɵConsole, Output, EventEmitter} from '@angular/core';
import { DatePipe } from '@angular/common';
import { Router } from '@angular/router';
import { NursingAssesmentService, ConsultingService } from 'src/app/shared';
import { NotifierService } from 'angular-notifier';
import { NgForm } from '@angular/forms';
import { LoaderService } from '../../../../../shared';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
@Component({
  selector: 'app-prescription',
  templateUrl: './prescription.component.html',
  styleUrls: ['./prescription.component.scss']
})
export class PrescriptionComponent implements OnInit, OnChanges {
  frequency: any;
  dosage: any = 'Unit(s)' ;
  frequencyType: string;
  eRxReferenceNo: number = 0;
  eRxprescription_uniqueid : any = "";
  routeofadmin: any;
  diagnosis_data: any;
  other_diagnosis = '';
  get_status: number = 0;
  edit: number = 0;
  closeResult: string;
  previous_list: any = [];
  cancel_list: any = [];
  index: any;
  start: number;
  limit: number;
  public p = 5;
  public collection:any= '';
  page :number = 1;
  copyPreviousdata: any = [];
  constructor(private modalService: NgbModal,private loaderService: LoaderService, public datepipe: DatePipe, private router: Router, public rest2: NursingAssesmentService, public rest: ConsultingService, notifierService: NotifierService) {
    this.notifier = notifierService;
    this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() doctor_id = 0 ;
  @Input() selected_visit: any = [];
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  todaysDate = defaultDateTime();
  public now = defaultDateTime();
  public date: any;
  public loading = false;
  private notifier: NotifierService;
  public user_rights: any = {};
  public user_data: any = {};
  public medicine_id_array:  any = [];
  public generic_id_array:  any = [];
  public generic_id_arr:  any = {};
  public medicine_id_arr:  any = {};
  public strength_arr:  any = {};
  public dosage_arr:  any = {};
  public uom_arr:  any = {};
  public multiple_frequency_arr:  any = {};
  public frequency_arr:  any = {};
  public route_arr:  any = {};
  public remarks_arr:  any = {};
  public period_arr:  any = {};
  public total_quantity_arr:  any = {};
  doctor_prescription_id = 0;
  consultation_id = 0;
  user_id = 0;
  public medicine_list = [];
  public generic_list = [];
  public num_rows: any = [''];
  print_data: any = [];
  public instructions : string;
  public editIndex: number = 0;
  public prescription_data = {
    frequency : "",
    instructions : "",
    generic_data : [],
    medicine_data : [],
    generic_name : "",
    medicine_name : "",
    medicine_code :  "",
    generic_code :  "",
    generic_id_arr :  "",
    medicine_id_arr :  "",
    strength_arr :  "",
    dosage_arr :  "",
    uom_arr :  "",
    multiple_frequency_arr :"",
    frequency_arr : "",
    route_arr : "",
    route_arr_id : "",
    remarks_arr : 0,
    period_arr : "",
    total_quantity_arr : 0,
    review_on : '',
  };
  dateVal = ''
  public prescribed_data : any = [];
  public get_prescribed_data : any = [];
  model: any;
  searching = false;
  searchFailed = false;
  public institution = JSON.parse(localStorage.getItem('institution'));
  public logo_path = JSON.parse(localStorage.getItem('logo_path'));
  ngOnInit() {

    this.getPrescription();
    this.list_frequency();
    this.listRouteOfAdmin();
    this.getPatientDiagnosis();
    this.getPreviousPrescription();
    this.getCancelPrescription();
   //console.log(this.selected_visit)
   }
   ngOnChanges(changes: SimpleChanges) {
   // this.listMedicine();
    this.getPrescription();
    // this.page = 1
    // this.getPreviousPrescription(this.page -1);

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
  public getEvent()
  {
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      this.save_notify = 1
      this.saveNotify.emit(this.save_notify)
      // if(this.get_status == 1)
      // {
      //   if(this.prescribed_data.length != this.get_prescribed_data.length || this.edit == 1)
      //   {
      //     this.save_notify = 1
      //     this.saveNotify.emit(this.save_notify)
      //   }
      // }
      // else{
      //   if(this.prescribed_data.length != 0)
      //   {
      //     this.save_notify = 1
      //     this.saveNotify.emit(this.save_notify)
      //   }
      //   else
      //   {
      //     this.save_notify = 0
      //     this.saveNotify.emit(this.save_notify)
      //   }
      // }
    }
    else {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
    }
    
  }
  public set_item_medicine($event) {
   const item = $event.item;
   //this.prescription_data.generic_id_arr[i]= item.MEDICINE_ID;
   this.prescription_data.medicine_id_arr = item.MEDICINE_ID;
   //this.prescription_data.route_arr = item.ROUTE_OF_ADMIN;
   this.getMedicine(item);
   //console.log("medicine_data"+JSON.stringify($event.item.ROUTE_OF_ADMIN));
   //let index;
   let stringToSplit = $event.item.DOSAGE_FORM_PACKAGE;
   //for(let x of stringToSplit.split(" "))
   //{
    this.dosage = stringToSplit;
   // console.log("dosage"+this.dosage);
    if(this.dosage.includes('TABLET'))
    {
      this.dosage = 'TABLET(s)'
      return; // this.dosage
    }
    else if(this.dosage.includes('BOTTLE'))
    {
      this.dosage = 'BOTTLE(s)'
      return;
    }
    else if(this.dosage.includes('CREAM'))
    {
      this.dosage = 'TUBE(s)'
      return;
    }
    else{
      this.dosage = 'Unit(s)'
      return;
    }
   //}
   
  }
  public set_item_route($event, i) {
    const item = $event.item;
    //this.prescription_data.generic_id_arr[i]= item.MEDICINE_ID;
    this.prescription_data.route_arr_id = item.MASTER_ROUTE_OF_ADMIN_ID;
    this.prescription_data.route_arr = item.DESCRIPTION;
   // this.listRouteOfAdmin(item, i);
   // console.log("prescription_data"+JSON.stringify(this.prescription_data));
    
   }
  public list_frequency()
  {
    this.loaderService.display(true);
    let postData = {
      master_id : 5
    };
    this.rest.get_master_data(postData).subscribe((result) => {
      if(result["status"] == "Success")
      {
        this.loaderService.display(false);
        this.frequency = result["master_list"];
       // console.log("frequency  ="+result);
        
      } else {
        this.loaderService.display(false);
        this.frequency = [];
      }
    }, (err) => {
     // console.log(err);
    });
  }
  public listRouteOfAdmin()
  {
    this.loaderService.display(true);
    const postData = {
      //routeofadmin_id : data.MASTER_ROUTE_OF_ADMIN
    };
    this.rest.listRouteOfAdmin(postData).subscribe((result) => {
      if(result["status"] == "Success")
      {
        this.loaderService.display(false);
        this.routeofadmin = result["data"];
        this.prescription_data.route_arr =this.routeofadmin.DESCRIPTION;
        this.prescription_data.route_arr_id = this.routeofadmin.MASTER_ROUTE_OF_ADMIN_ID;

       // console.log("frequency  ="+result);
        
      } else {
        this.loaderService.display(false);
        this.routeofadmin = [];
      }
    }, (err) => {
      //console.log(err);
    });
  }
  public getMedicine(data) {
    const postData = {
      medicine_id : data.MEDICINE_ID
    };
    this.loaderService.display(true);
    this.loaderService.display(true);
    this.rest.getMedicine(postData).subscribe((result: {}) => {
     if (result['status'] == 'Success') {
        const medilist_data = result['data'];
        this.prescription_data.medicine_name = medilist_data.TRADE_NAME;
        this.prescription_data.medicine_code = medilist_data.DDC_CODE;
        this.prescription_data.generic_name = medilist_data.TRADE_NAMES;
        this.prescription_data.generic_code = medilist_data.SCIENTIFIC_CODE;
        this.prescription_data.generic_id_arr = medilist_data.GENERIC_ID;
      //  console.log("predf"+JSON.stringify(this.prescription_data))
          this.loaderService.display(false);
      } else {
          this.loaderService.display(false);
      }

   });
  }
  public getRouteOfAdmin() {
    const postData = {
      route_arr_id : this.prescription_data.route_arr_id
    };
    this.loaderService.display(true);
    this.rest.getRouteOfAdmin(postData).subscribe((result: {}) => {
     if (result['status'] == 'Success') {
        const get_route = result['data'];
        this.prescription_data.route_arr = get_route.DESCRIPTION;
      //  console.log("route_arr"+JSON.stringify(get_route.DESCRIPTION))
          this.loaderService.display(false);
      } else {
          this.loaderService.display(false);
      }

   });
  }
  public addDrug(pre_data) {
    this.edit = 0
    
   // console.log("predsd"+JSON.stringify(pre_data))
    if(this.prescription_data.generic_name == "" || this.prescription_data.generic_data == [""])
    {
      this.notifier.notify( 'error', 'Invalid Drug' );
    } else if(this.prescription_data.uom_arr == "" || !+this.prescription_data.uom_arr){
      this.notifier.notify( 'error', 'Invalid Quantity' );
    }  else if( this.prescription_data.frequency_arr == "" || !+this.prescription_data.frequency_arr){
      this.notifier.notify( 'error', 'Invalid Frequency Value' );
    } else if(this.prescription_data.frequency == "" ){
       this.notifier.notify( 'error', 'Invalid Frequency Type' );
    } else if(this.prescription_data.strength_arr == "" || !+this.prescription_data.strength_arr){
       this.notifier.notify( 'error', 'Invalid Duration' );
  //   } else if(this.prescription_data.route_arr_id == ""){
  //     this.notifier.notify( 'error', 'Invalid Route of admin' );
   }
    
    else{
      if(this.editIndex>0)
      {
        this.prescribed_data[this.editIndex - 1] = this.prescription_data;
        this.editIndex = 0;
      }
      else{
        this.prescribed_data.push(this.prescription_data);
      }
    this. prescription_data = {
      frequency : "",
      instructions : "",
      generic_data : [],
      medicine_data : [],
      generic_name : "",
      medicine_name : "",
      medicine_code :  "",
      generic_code :  "",
      generic_id_arr :  "",
      medicine_id_arr :  "",
      strength_arr :  "",
      dosage_arr :  "",
      uom_arr :  "",
      multiple_frequency_arr :"",
      frequency_arr : "",
      route_arr : "",
      route_arr_id : "",
      remarks_arr : 0,
      period_arr : "",
      total_quantity_arr : 0,
      review_on : '',
    };
  }
  this.getEvent()
}
  public listMedicine() {
    const postData = {
    medicine_id : 0
    };
    this.loaderService.display(true);
    this.rest.listMedicine(postData).subscribe((result) => {
    if (result['status'] == 'Success') {
      this.loaderService.display(false);
      const medicine_list = [];
      for ( const val of result['data']) {
        medicine_list.push({
          'TRADE_NAME': val['TRADE_NAME'] + ' - ' +  val['DDC_CODE'],
          'SCIENTIFIC_NAME' : val['SCIENTIFIC_NAME'] + ' - ' +  val['SCIENTIFIC_CODE'],
          'MEDICINE_ID': val['MEDICINE_ID']
        });
      }
      this.medicine_list = medicine_list;
    } else {
      this.loaderService.display(false);
      this.medicine_list = [];
    }
    }, (err) => {
   // console.log(err);
    });
  }
  public instruction() {
    if(this.prescription_data.uom_arr != "" && this.prescription_data.frequency_arr != "" && this.prescription_data.frequency != "" && this.prescription_data.strength_arr != "")
    {
    if(this.prescription_data.frequency == '17')
    {
      this.frequencyType = 'Per Day'
    }
    if(this.prescription_data.frequency == '18')
    {
      this.frequencyType = 'Per Hour'
    }
    if(this.prescription_data.frequency == '19')
    {
      this.frequencyType = 'Per Week'
    }
    if(this.prescription_data.frequency == '20')
    {
      this.frequencyType = 'Per Month'
    }

    this.prescription_data.instructions= 'Take'+' '+this.prescription_data.uom_arr+' '+this.dosage+', '+this.prescription_data.frequency_arr+' Time(s) '+this.frequencyType+' For '+this.prescription_data.strength_arr+' Day(s) .';
  }
  }
  public totalQty(){
    if(this.prescription_data.uom_arr != "" && this.prescription_data.frequency_arr != "" && this.prescription_data.frequency != "" && this.prescription_data.strength_arr != "")
    {
     let sum : number = 0;
        const quantity = this.prescription_data.uom_arr;
        const frequencyValue = this.prescription_data.frequency_arr;
        const Duration = this.prescription_data.strength_arr;
        if(this.prescription_data.frequency == '17')
        {
          sum = parseFloat(quantity) * parseFloat(frequencyValue) * parseFloat(Duration) ;
         // console.log("sum"+sum);
        }
        if(this.prescription_data.frequency == '18')
        {
          sum = parseFloat(quantity) * parseFloat(frequencyValue) * parseFloat(Duration)* 24;
        }
        if(this.prescription_data.frequency == '19')
        {
          sum = parseFloat(quantity) * parseFloat(frequencyValue)/7 * parseFloat(Duration);
        }
        if(this.prescription_data.frequency == '20')
        {
          sum = parseFloat(quantity);
        }
        
        this.prescription_data.total_quantity_arr = sum;   
    }
   
  }
   
  deleterow(prescriptions) {
    const index: number = this.prescribed_data.indexOf(prescriptions);
    if (index !== -1) {
        this.prescribed_data.splice(index, 1);
    } 
    this.edit = 0
    this.getEvent()       
}
editrow(prescriptions,i) {
  this.editIndex=i+1;
  this.edit = 1
 // console.log("pred"+JSON.stringify(this.prescribed_data))
  this.prescription_data.generic_data=prescriptions.generic_data;
  this.prescription_data.medicine_id_arr=prescriptions.medicine_id_arr;
  this.prescription_data.generic_name=prescriptions.generic_name;
  this.prescription_data.strength_arr=prescriptions.strength_arr;
  this.prescription_data.total_quantity_arr=prescriptions.total_quantity_arr;
  this.prescription_data.route_arr=prescriptions.route_arr;
  this.prescription_data.route_arr_id=prescriptions.route_arr_id;
  this.prescription_data.instructions=prescriptions.instructions;
  this.prescription_data.remarks_arr=prescriptions.remarks_arr;
  this.prescription_data.uom_arr=prescriptions.uom_arr;
  this.prescription_data.frequency=prescriptions.frequency;
  this.prescription_data.frequency_arr=prescriptions.frequency_arr;
 // console.log("sdsdg"+JSON.stringify(this.prescription_data.generic_data))
 this.getEvent()   
     
}
  public savePrescription() {

    let flag = 0;
    for (var medicine of this.prescription_data.medicine_id_arr) {
      if (medicine != '') {
        flag = 1;
      }
    }
    var data = {
      patient_id :this.patient_id,
      assessment_id : this.assessment_id,
      doctor_id : this.user_data.user_id,
      user_id : this.user_data.user_id,
      consultation_id : this.consultation_id,
      doctor_prescription_id: this.doctor_prescription_id,
      client_date : this.formatDateTime(new Date()),
    }
   
    //console.log("pres"+this.prescribed_data)
    var prescribed_data = {
      patient_id :this.patient_id,
      assessment_id : this.assessment_id,
      doctor_id : this.user_data.user_id,
      user_id : this.user_data.user_id,
      consultation_id : this.consultation_id,
      doctor_prescription_id: this.doctor_prescription_id,
      client_date : this.formatDateTime(new Date()),
      date : defaultDateTime(),
      timeZone: getTimeZone(),
      prescription_array : this.prescribed_data
    };
    this.loaderService.display(true);
    this.rest.savePrescription(prescribed_data).subscribe((result) => {
      this.edit = 0
   
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
    if (result.status == 'Success') {
    
      this.loaderService.display(false);
      this.doctor_prescription_id = result.data_id;
     // this.doctor_prescription_id = result.data_id;
      this.getPrescription();
      this.notifier.notify( 'success', 'Prescription details saved succesfully..!' );
    } else {
      this.loaderService.display(false);
      this.notifier.notify( 'error', result.msg );
    }
    }, (err) => {
    //  console.log(err);
    });
  }
  public uploadToeRx(val=0) {

    var data = {
      patient_id :this.patient_id,
      assessment_id : this.assessment_id,
      doctor_id : this.user_data.user_id,
      user_id : this.user_data.user_id,
      client_date : this.formatDateTime(new Date()),
      eRxtype: val,
      eRxprescription_uniqueid: this.eRxprescription_uniqueid

    };
   
    this.loaderService.display(true);
    this.rest.uploadToeRx(data).subscribe((result) => {
  

    if (result.status == 'Success') {
      this.loaderService.display(false);
      this.eRxReferenceNo = result.eRxReferenceNo;
      this.eRxprescription_uniqueid = result.eRxprescription_uniqueid
     // this.doctor_prescription_id = result.data_id;
      this.notifier.notify( 'success', result.message );
      this.getPrescription();
      this.getCancelPrescription();
    } else {
      this.loaderService.display(false);
      this.notifier.notify( 'error', result.message );
    }
    }, (err) => {
    //  console.log(err);
    });
  }


  public getPrescription() {
    const postData = {
    patient_id : this.patient_id,
    assessment_id : this.assessment_id,
    };
    this.loaderService.display(true);
    this.rest.getPrescription(postData).subscribe((result) => {
      //console.log(this.selected_visit)
    if (result.status == 'Success') {
      this.get_status = 1
      this.loaderService.display(false);
      this.print_data = result.data.PRESCRIPTION_DETAILS;
      this.doctor_prescription_id = result.data.DOCTOR_PRESCRIPTION_ID;
      this.eRxReferenceNo = result.data.ERX_REFERENCE_NUMBER;
      this.eRxprescription_uniqueid = result.data.ERX_PRESCRIPTION_UNIQUE_ID;
      let i = 0;
      var num_rows = [];
      var prescribed_data : any = [];
      for (const val of result.data.PRESCRIPTION_DETAILS) {
        //     const temp = {
        //     'SCIENTIFIC_NAME' : val.SCIENTIFIC_NAME,
        //     'SCIENTIFIC_CODE' : val.SCIENTIFIC_CODE,
        //     'MEDICINE_ID' : val.MEDICINE_ID,
        // };
           const temp_1 = {
            'TRADE_NAMES' :  val.TRADE_NAMES,
            'TRADE_NAME' :  val.TRADE_NAME +' - '+val.SCIENTIFIC_NAME,
            'MEDICINE_ID' : val.MEDICINE_ID,
            'SCIENTIFIC_NAME' : val.SCIENTIFIC_NAME,
            'SCIENTIFIC_CODE' : val.SCIENTIFIC_CODE,
            'DDC_CODE' : val.DDC_CODE,
        };
      num_rows[i] = i;
      var get_data : any = {};
      
     // this.prescription_data.generic_data[i] = temp;
     get_data.generic_data = temp_1;
     // this.prescribed_data.generic_id_arr[i] = val.MEDICINE_ID;
     get_data.generic_name = val.TRADE_NAMES;
     get_data.medicine_name = val.TRADE_NAME;
     get_data.medicine_id_arr = val.MEDICINE_ID;
     get_data.strength_arr = val.STRENGTH;
     get_data.dosage_arr = val.DOSAGE;
     get_data.uom_arr = val.UOM;
     get_data.multiple_frequency_arr = val.MULTIPLE_FREQUENCY;
     get_data.frequency_arr = val.FREQUENCY;
     get_data.frequency = val.FREQUENCY_TYPE;
     get_data.instructions = val.INSTRUCTION;
     get_data.route_arr = val.DESCRIPTION;
     get_data.route_arr_id = val.ROUTE;
     get_data.remarks_arr = val.REMARKS;
     get_data.period_arr = val.PERIOD;
     get_data.total_quantity_arr = val.TOTAL_QUANTITY;
     prescribed_data.push(get_data);
      i = i + 1;
      
      }
      this.prescribed_data = prescribed_data;
     // this.get_prescribed_data = prescribed_data;
      this.num_rows = num_rows;

    } else {
      this.loaderService.display(false);
      this.print_data = [];
      this.doctor_prescription_id = 0;
      this.eRxReferenceNo = 0;
      this.generic_id_array = [];
      this.medicine_id_array = [];
      this.num_rows = ['0'];
      this.prescribed_data = []
      this.diagnosis_data = []
      this.prescription_data = {
        frequency : "",
        instructions : "",
        generic_data : [],
        medicine_data : [],
        generic_name : "",
        medicine_name : "",
        medicine_code :  "",
        generic_code :  "",
        generic_id_arr :  "",
        medicine_id_arr :  "",
        strength_arr :  "",
        dosage_arr :  "",
        uom_arr :  "",
        multiple_frequency_arr :"",
        frequency_arr : "",
        route_arr : "",
        route_arr_id : "",
        remarks_arr : 0,
        period_arr : "",
        total_quantity_arr : 0,
        review_on : '',
      };
    }
    }, (err) => {
    //  console.log(err);
    });
  }
  // public addrow(index) {
  //   this.num_rows[index + 1] = '';
  //  // this.prescription_data.generic_id_arr[index + 1] = '';
  //  // this.prescription_data.medicine_id_arr[index + 1] = '';
  //   this.prescription_data.strength_arr[index + 1] = '';
  //   this.prescription_data.dosage_arr[index + 1] = '';
  //   this.prescription_data.uom_arr[index + 1] = '';
  //   this.prescription_data.multiple_frequency_arr[index + 1] = '';
  //   this.prescription_data.frequency_arr[index + 1] = '';
  //   this.prescription_data.route_arr[index + 1] = '';
  //   this.prescription_data.period_arr[index + 1] = '';
  //   this.prescription_data.remarks_arr[index + 1] = '';
  //   this.prescription_data.total_quantity_arr[index + 1] = '';
  // }
  // public deleterow(index) {
  //   this.num_rows.splice(index, 1);
  //   this.prescription_data.generic_data.splice(index, 1);
  //   this.prescription_data.medicine_data.splice(index, 1);
  //   this.generic_id_array.splice(index, 1);
  //   this.medicine_id_array.splice(index, 1);
  //   this.prescription_data.generic_id_arr.splice(index, 1);
  //   this.prescription_data.medicine_id_arr.splice(index, 1);
  //   this.prescription_data.strength_arr.splice(index, 1);
  //   this.prescription_data.dosage_arr.splice(index, 1);
  //   this.prescription_data.uom_arr.splice(index, 1);
  //   this.prescription_data.multiple_frequency_arr.splice(index, 1);
  //   this.prescription_data.frequency_arr.splice(index, 1);
  //   this.prescription_data.route_arr.splice(index, 1);
  //   this.prescription_data.period_arr.splice(index, 1);
  //   this.prescription_data.remarks_arr.splice(index, 1);
  //   this.prescription_data.total_quantity_arr.splice(index, 1);
  // }
  // public Multiplefrequency(checked, i) {
  //   //console.log('check=' + checked);
  //   if (checked == 1) {
  //     this.prescription_data.multiple_frequency_arr = 0;
  //   } else {
  //     this.prescription_data.multiple_frequency_arr[i] = 1;
  //   }
  // }
  public confirm_copy_prescription(val,content)
  {
    if(this.prescribed_data.length > 0)
    {
      this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',size: 'sm',centered:true}).result.then((result) => {
        this.closeResult = `Closed with: ${result}`;
      }, (reason) => {
        this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      });
    }
    else
    {
      this.copy_prescription(val);
    }
  }
  public open(content)
  {
    if(this.print_data.length > 0)
    {
      this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',size: 'lg',centered:true}).result.then((result) => {
        this.closeResult = `Closed with: ${result}`;
      }, (reason) => {
        this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      });
    }
  }
  public getPreviousCopyData(data)
  {
    var copyPreviousdata = []
    var i = 0
    for(let val of data)
    {
      const temp_1 = {
        'TRADE_NAMES' :  val.TRADE_NAMES,
        'TRADE_NAME' :  val.TRADE_NAME +' - '+val.SCIENTIFIC_NAME,
        'MEDICINE_ID' : val.MEDICINE_ID,
        'SCIENTIFIC_NAME' : val.SCIENTIFIC_NAME,
        'SCIENTIFIC_CODE' : val.SCIENTIFIC_CODE,
        'DDC_CODE' : val.DDC_CODE,
    };
      var get_data : any = {};
      get_data.generic_data = temp_1;
      get_data.generic_name = val.TRADE_NAMES;
      get_data.medicine_name = val.TRADE_NAME;
      get_data.medicine_id_arr = val.MEDICINE_ID;
      get_data.strength_arr = val.STRENGTH;
      get_data.dosage_arr = val.DOSAGE;
      get_data.uom_arr = val.UOM;
      get_data.multiple_frequency_arr = val.MULTIPLE_FREQUENCY;
      get_data.frequency_arr = val.FREQUENCY;
      get_data.frequency = val.FREQUENCY_TYPE;
      get_data.instructions = val.INSTRUCTION;
      get_data.route_arr = val.DESCRIPTION;
      get_data.route_arr_id = val.ROUTE;
      get_data.remarks_arr = val.REMARKS;
      get_data.period_arr = val.PERIOD;
      get_data.total_quantity_arr = val.TOTAL_QUANTITY;
      copyPreviousdata.push(get_data);
      i = i + 1;
    }
    this.copyPreviousdata = copyPreviousdata
    console.log(this.copyPreviousdata)
    console.log(this.prescribed_data)
  }
  public confirmCopyPrescribe(content,flag)
  {
    if(this.prescribed_data.length > 0)
    {
      this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',size: 'sm',centered:true}).result.then((result) => {
        this.closeResult = `Closed with: ${result}`;
      }, (reason) => {
        this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      });
    }
    else
    {
      this.copyPreviousData(flag)
    }
  }
  public copyPreviousData(flag)
  {
    this.prescribed_data = this.copyPreviousdata
    if(this.copyPreviousdata.length != 0 && flag==2)
    {
      this.notifier.notify("info","Previous prescribed data duplicated to current prescribe")
    }
    else{
            this.notifier.notify("info","Cancelled prescribed data duplicated to current prescribe")
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
  public copy_prescription(val)
  {
    if(val == 1)
    {
      this.get_prescribed_data = this.prescribed_data
      if(this.get_prescribed_data.length != 0)
      {
        this.notifier.notify("info","Prescription data copied")
      }
    }
    else
    {
      this.prescribed_data = this.get_prescribed_data
      if(this.prescribed_data.length != 0)
      {
        this.notifier.notify("info","Prescription data added")
      }
      this.getEvent();
    }
  }
  public getPatientDiagnosis() {
    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id,
    };
    this.loaderService.display(true);
    this.rest.getPatientDiagnosis(postData).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status == 'Success') 
      {
        this.diagnosis_data = result.data.PATIENT_DIAGNOSIS_DETAILS
        this.other_diagnosis = result.data.PATIENT_OTHER_DIAGNOSIS
      } 
      else 
      {
        this.diagnosis_data = []
      }
     });
  }
  public getPreviousPrescription(page = 0) 
  {
    const limit = 5;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    if(this.dateVal == '')
    {
      this.limit = 5
    }
    const post2Data = {
      assessment_id : this.assessment_id,
      patient_id : this.patient_id,
      start : this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
    this.rest.getPreviousPrescription(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') 
      {
        this.previous_list = result['data'];
        this.collection = result['total_count'];
        const i = this.previous_list.length;
        this.index = i + 5;
      } 
      else 
      {
      this.previous_list = [];
      }
    });
  }
  getPrevioussearchlist() {

    const limit = 5;
    this.start = 0;
    this.limit = limit;
    const post2Data = {
      search_text : this.formatDateTime(this.dateVal),
      assessment_id : this.assessment_id,
      patient_id : this.patient_id,
      start : this.start,
      limit : this.limit,
      timeZone: Moment.tz.guess()
    };
    this.loaderService.display(true);
    this.rest.getPreviousPrescription(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
     if (result['status'] === 'Success') {
        this.previous_list = result['data'];
        this.collection = result['total_count'];
        const i = this.previous_list.length;
        this.index = i + 5;
     } else {
        this.previous_list = [];
        this.collection = 0;
        this.page = 0
     }

   });
  }


  public getCancelPrescription(page = 0) 
  {
    const limit = 5;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    if(this.dateVal == '')
    {
      this.limit = 5
    }
    const post2Data = {
      assessment_id : this.assessment_id,
      patient_id : this.patient_id,
      start : this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
    this.rest.getCancelPrescription(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') 
      {
        this.cancel_list = result['data'];
        this.collection = result['total_count'];
        const i = this.cancel_list.length;
        this.index = i + 5;
      } 
      else 
      {
      this.cancel_list = [];
      }
    });
  }

  getCancelsearchlist() {

    const limit = 5;
    this.start = 0;
    this.limit = limit;
    const post2Data = {
      search_text : this.formatDateTime(this.dateVal),
      assessment_id : this.assessment_id,
      patient_id : this.patient_id,
      start : this.start,
      limit : this.limit,
      timeZone: Moment.tz.guess()
    };
    this.loaderService.display(true);
    this.rest.getCancelPrescription(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
     if (result['status'] === 'Success') {
        this.cancel_list = result['data'];
        this.collection = result['total_count'];
        const i = this.cancel_list.length;
        this.index = i + 5;
     } else {
        this.cancel_list = [];
        this.collection = 0;
        this.page = 0
     }

   });
  }
  public clear_search_cancel()
  {
    this.dateVal = '';
    this.getCancelPrescription()
  }
  public getToday(): string 
  {
    return new Date().toISOString().split('T')[0]
  }
  public clear_search()
  {
    this.dateVal = '';
    this.getPreviousPrescription()
  }
  medicine_search = (text$: Observable<string>) =>
  text$.pipe(
  debounceTime(500),
    distinctUntilChanged(),
    tap(() => this.searching = true),
    switchMap(term =>
    this.rest.medicine_search(term).pipe(
      tap(() => this.searchFailed = false),
      catchError(() => {
        this.searchFailed = true;
        return of(['']);
      })
      )
  ),
  tap(() => this.searching = false)
  )
  formatter = (x: {TRADE_NAME: String, MEDICINE_ID: Number,  DDC_CODE: String ,SCIENTIFIC_NAME: String, SCIENTIFIC_CODE: Number,TRADE_NAMES : String }) => x.TRADE_NAMES;

  generic_search = (text$: Observable<string>) =>
  text$.pipe(
  debounceTime(500),
    distinctUntilChanged(),
    tap(() => this.searching = true),
    switchMap(term =>
    this.rest.generic_search(term).pipe(
      tap(() => this.searchFailed = false),
      catchError(() => {
        this.searchFailed = true;
        return of(['']);
      })
      )
  ),
  tap(() => this.searching = false)
  )
  formatters = (x: {TRADE_NAME: String, DDC_CODE: String, SCIENTIFIC_NAME: String,  MEDICINE_ID: Number, SCIENTIFIC_CODE: Number,TRADE_NAMES : String }) => x.TRADE_NAMES;

  routeofadmin_search = (text$: Observable<string>) =>
  text$.pipe(
  debounceTime(500),
    distinctUntilChanged(),
    tap(() => this.searching = true),
    switchMap(term =>
    this.rest.routeofadmin_search(term).pipe(
      tap(() => this.searchFailed = false),
      catchError(() => {
        this.searchFailed = true;
        return of(['']);
      })
      )
  ),
  tap(() => this.searching = false)
  )
  formatterss = (x: {CODE: String, DESCRIPTION: String,  MASTER_ROUTE_OF_ADMIN: Number }) => x.DESCRIPTION;
}
