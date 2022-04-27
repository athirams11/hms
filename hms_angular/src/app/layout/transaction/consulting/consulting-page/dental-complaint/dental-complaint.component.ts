import { Component, OnInit,Input,NgModule,ViewChild,Output,EventEmitter, SimpleChanges, OnChanges, ɵConsole } from '@angular/core';
import { ConsultingService,NursingAssesmentService, DoctorsService} from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import {DatePipe, CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { LoaderService } from '../../../../../shared';
import { ModalDismissReasons, NgbModal, NgbModalOptions, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { Observable, of } from 'rxjs';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Nl2BrPipe } from 'nl2br-pipe';
@Component({
  selector: 'app-dental-complaint',
  templateUrl: './dental-complaint.component.html',
  styleUrls: ['./dental-complaint.component.scss']
})
export class DentalComplaintComponent implements OnInit {

  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() consultation_id: number = 0;
  @Input() vital_values:any = [];
  @Input() selected_visit: any = [];
  todaysDate = new Date();
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  public now = new Date();
  public date:any;
  value: number = 0;
  isDisabled: boolean;
  public loading = false;
  notifier: NotifierService;
  public user_rights : any ={};
  public user_data : any ={};
  change: string = '';
  closeResult: string;
  cdt_index: number = 0;
  public dental_data = {
    universal: [],
    palmer: [],
    FDI : [],
    ID : [],
    user_id : 0,
    patient_id: 0,
    assessment_id : 0,
    consultation_id: 0,
    client_date : new Date(),
    timeZone : '',
    tooth_issue : [''],
    tooth_complaint : [],
    complients : {},
    child_complients : {},
    procedure : [],
    child_tooth_value : [],
    child_tooth_number : [],
    color : [],
    tooth_data : [""],
    dental_complaint_id : 0,
    patient_type : 1,
    dental_cdt_data : [""],
    dental_cdt_code : [""],
    dental_cdt_quantity : ["1"],
    dental_cdt_id : [""],
    multi_dental_cdt_id : "",
    multi_cdt_code : "",
    multi_cdt_quantity : 1,
    multi_cdt_data : [""],
    multi_tooth_issue : '',
    multi_tooth_index : ''
    };

  tooth_issue = ' ';
  tooth_arr: string;
  tooth_index = '';
  dental_list: any;
  procedure = '';
  tooth_color = '';
  tooth_data: any;
  patient_type = 1;
  status = false;
  doctor_department: any;
  department = 0;
  public dental: { id: number, label: string,  universal :number,palmer :number,value: number,alreadyExist:number,image:string,tooth_issue:string,procedure:string,color:string,check:number}[] = [
    { "id": 1, "label": "tooth_11" ,"universal": 1, palmer: 8, value: 11,alreadyExist:0, "image": "assets/images/1.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 2, "label": "tooth_12" ,"universal": 2, palmer: 7, value: 12,alreadyExist:0, "image": "assets/images/2.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 3, "label": "tooth_13" ,"universal": 3, palmer: 6, value: 13,alreadyExist:0, "image": "assets/images/3.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 4, "label": "tooth_14" ,"universal": 4, palmer: 5, value: 14,alreadyExist:0, "image": "assets/images/4.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 5, "label": "tooth_15" ,"universal": 5, palmer: 4, value: 15,alreadyExist:0, "image": "assets/images/5.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 6, "label": "tooth_16" ,"universal": 6, palmer: 3, value: 16,alreadyExist:0, "image": "assets/images/6.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 7, "label": "tooth_17" ,"universal": 7, palmer: 2, value: 17,alreadyExist:0, "image": "assets/images/7.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 8, "label": "tooth_18" ,"universal": 8, palmer: 1, value: 18,alreadyExist:0, "image": "assets/images/8.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 9, "label": "tooth_21"  ,"universal": 9,  palmer: 1, value: 21,alreadyExist:0, "image": "assets/images/9.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 10, "label": "tooth_22" ,"universal": 10, palmer: 2, value: 22,alreadyExist:0, "image": "assets/images/10.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 11, "label": "tooth_23" ,"universal": 11, palmer: 3, value: 23,alreadyExist:0, "image": "assets/images/11.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 12, "label": "tooth_24" ,"universal": 12, palmer: 4, value: 24,alreadyExist:0, "image": "assets/images/12.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 13, "label": "tooth_25" ,"universal": 13, palmer: 5, value: 25,alreadyExist:0, "image": "assets/images/13.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 14, "label": "tooth_26" ,"universal": 14, palmer: 6, value: 26,alreadyExist:0, "image": "assets/images/14.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 15, "label": "tooth_27" ,"universal": 15, palmer: 7, value: 27,alreadyExist:0, "image": "assets/images/15.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 16, "label": "tooth_28" ,"universal": 16, palmer: 8, value: 28,alreadyExist:0, "image": "assets/images/16.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 17, "label": "tooth_48" ,"universal": 32,  palmer: 8, value: 48,alreadyExist:0, "image": "assets/images/32.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 18, "label": "tooth_47" ,"universal": 31,  palmer: 7, value: 47,alreadyExist:0, "image": "assets/images/31.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 19, "label": "tooth_46" ,"universal": 30,  palmer: 6, value: 46,alreadyExist:0, "image": "assets/images/30.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 20, "label": "tooth_45" ,"universal": 29,  palmer: 5, value: 45,alreadyExist:0, "image": "assets/images/29.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 21, "label": "tooth_44" ,"universal": 28,  palmer: 4, value: 44,alreadyExist:0, "image": "assets/images/28.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 22, "label": "tooth_43" ,"universal": 27,  palmer: 3, value: 43,alreadyExist:0, "image": "assets/images/27.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 23, "label": "tooth_42" ,"universal": 26,  palmer: 2, value: 42,alreadyExist:0, "image": "assets/images/26.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 24, "label": "tooth_41" ,"universal": 25,  palmer: 1, value: 41,alreadyExist:0, "image": "assets/images/25.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 25, "label": "tooth_38" ,"universal": 24,  palmer: 1, value: 31,alreadyExist:0, "image": "assets/images/24.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 26, "label": "tooth_37" ,"universal": 23,  palmer: 2, value: 32,alreadyExist:0, "image": "assets/images/23.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 27, "label": "tooth_36" ,"universal": 22,  palmer: 3, value: 33,alreadyExist:0, "image": "assets/images/22.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 28, "label": "tooth_35" ,"universal": 21,  palmer: 4, value: 34,alreadyExist:0, "image": "assets/images/21.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 29, "label": "tooth_34" ,"universal": 20,  palmer: 5, value: 35,alreadyExist:0, "image": "assets/images/20.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 30, "label": "tooth_33" ,"universal": 19,  palmer: 6, value: 36,alreadyExist:0, "image": "assets/images/19.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 31, "label": "tooth_32","universal":  18,  palmer: 7, value: 37,alreadyExist:0, "image": "assets/images/18.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
    { "id": 32, "label": "tooth_31" ,"universal": 17,  palmer: 8, value: 38,alreadyExist:0, "image": "assets/images/17.png", "tooth_issue": "", "procedure":"","color":"" ,check:0},
  ];
  public dental_child: { id: number, label: string,  value: number,alreadyExist:number,image:string,tooth_issue:string,procedure:string,color:string,check:number}[] = [
    { "id": 1, "label": "A" , value: 1, alreadyExist:0, "image": "assets/images/c1.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 2, "label": "B" , value: 2, alreadyExist:0, "image": "assets/images/c2.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 3, "label": "C" , value: 3, alreadyExist:0, "image": "assets/images/c3.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 4, "label": "D" , value: 4, alreadyExist:0, "image": "assets/images/c4.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 5, "label": "E" , value: 5, alreadyExist:0, "image": "assets/images/c5.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 6, "label": "F" , value: 6, alreadyExist:0, "image": "assets/images/c6.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 7, "label": "G" , value: 7, alreadyExist:0, "image": "assets/images/c7.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 8, "label": "H" , value: 8, alreadyExist:0, "image": "assets/images/c8.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 9, "label": "I" , value: 9, alreadyExist:0, "image": "assets/images/c9.png" , "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 10,"label": "J" , value: 10,alreadyExist:0, "image": "assets/images/c10.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 11,"label": "T" , value: 20,alreadyExist:0, "image": "assets/images/c20.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 12,"label": "S" , value: 19,alreadyExist:0, "image": "assets/images/c19.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 13,"label": "R" , value: 18,alreadyExist:0, "image": "assets/images/c18.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 14,"label": "Q" , value: 17,alreadyExist:0, "image": "assets/images/c17.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 15,"label": "P" , value: 16,alreadyExist:0, "image": "assets/images/c16.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 16,"label": "O" , value: 15,alreadyExist:0, "image": "assets/images/c15.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 17,"label": "N" , value: 14,alreadyExist:0, "image": "assets/images/c14.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 18,"label": "M" , value: 13,alreadyExist:0, "image": "assets/images/c13.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 19,"label": "L" , value: 12,alreadyExist:0, "image": "assets/images/c12.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 20,"label": "K" , value: 11,alreadyExist:0, "image": "assets/images/c11.png", "tooth_issue": "", "procedure":"","color":"",check:0},
  ];
  public dental_procedure: { id: number, procedures: string, color :string,selected:number }[] = [
    { "id": 1, "procedures": "Decayed" ,"color": "ash",selected:0},
    { "id": 2, "procedures": "Filled" ,"color": "green", selected:0},
    { "id": 3, "procedures": "Crown & Bridge" ,"color": "warning", selected:0},
    { "id": 4, "procedures": "Denture" ,"color": "brown", selected:0},
    { "id": 5, "procedures": "Extraction" ,"color": "orange",selected:0},
    { "id": 6, "procedures": "Impaction" ,"color": "red", selected:0},
    { "id": 7, "procedures": "Radiology" ,"color": "rose", selected:0},
    { "id": 8, "procedures": "Missing" ,"color": "lightblue",selected:0},
    { "id": 9, "procedures": "Endodontic" ,"color": "darkorgange", selected:0},
    { "id": 10, "procedures": "Implant" ,"color": "pink", selected:0},
    { "id": 11, "procedures": "Fracture Restoration" ,"color": "skyblue",selected:0},
    { "id": 12, "procedures": "Periodontal" ,"color": "blue", selected:0},
    { "id": 14, "procedures": "Scaling" ,"color": "lightpink", selected:0},
    { "id": 15, "procedures": "Veneer" ,"color": "crimson",selected:0},
    { "id": 16, "procedures": "Surgery" ,"color": "gold", selected:0},
    { "id": 17, "procedures": "Whitening" ,"color": "olive", selected:0},
    { "id": 13, "procedures": "Others" ,"color": "violet", selected:0},
  ];
  public adult_tooth: { universal :number}[] = [
    { "universal": 1}, { "universal": 2}, { "universal": 3}, { "universal": 4}, { "universal": 5}, 
    { "universal": 6}, { "universal": 7}, { "universal": 8}, { "universal": 9}, { "universal": 10},
    { "universal": 11},{ "universal": 12},{ "universal": 13},{ "universal": 14},{ "universal": 15},
    { "universal": 16},{ "universal": 17}, { "universal": 18},{ "universal": 19},{ "universal": 20},
    { "universal": 21},{ "universal": 22},{ "universal": 23},{ "universal": 24},{ "universal": 25},
    { "universal": 26},{ "universal": 27},{ "universal": 28},{ "universal": 29},{ "universal": 30},
    { "universal": 31},{ "universal": 32},
  ];
  public child_tooth: {  label:string,value: number}[] = [
    { "label": "A" , value: 1},
    { "label": "B" , value: 2},
    { "label": "C" , value: 3},
    { "label": "D" , value: 4},
    { "label": "E" , value: 5},
    { "label": "F" , value: 6},
    { "label": "G" , value: 7},
    { "label": "H" , value: 8},
    { "label": "I" , value: 9},
    { "label": "J" , value: 10},
    { "label": "T" , value: 20},
    { "label": "S" , value: 19},
    { "label": "R" , value: 18},
    { "label": "Q" , value: 17},
    { "label": "P" , value: 16},
    { "label": "O" , value: 15},
    { "label": "N" , value: 14},
    { "label": "M" , value: 13},
    { "label": "L" , value: 12},
    { "label": "K" , value: 11},
  ];
  public round_log = [
    {
      count:0,
    },
    {
      count:4,
      order:[0,0,0,0],
      skew:0 
    },
    {
      count:4,
      order:[0,0,1,1],
      skew:0
    },
    {
      count:6,
      order:[0,0,1,1,2,2],
      skew:-30
    },
    {
      count:4,
      order:[0,1,2,3],
      skew:0
      
    },
    {
      count:5,
      order:[0,1,2,3,4],
      skew:-15
    },
    {
      count:6,
      order:[0,1,2,3,4,5],
      skew:-30
    },
    {
      count:7,
      order:[0,1,2,3,4,5,6],
      skew:-38
    },
    {
      count:8,
      order:[0,1,2,3,4,5,6,7],
      skew:-45
    },
    {
      count:9,
      order:[0,1,2,3,4,5,6,7,8],
      skew:-50
    },
    {
      count:10,
      order:[0,1,2,3,4,5,6,7,8,9],
      skew:-50
    },
    {
      count:11,
      order:[0,1,2,3,4,5,6,7,8,9,10],
      skew:-55
    }

  ]
  tooth_number: number;

  public laboratory_data = {
    cptcode : [],
    patient_allergies_id: 0,
    patient_id: 0,
    assessment_id: 0,
    patient_no_known_allergies: 0,
    laboratory_description: [],
    laboratory_allias_data: [],
    laboratory_alliasname: [''],
    laboratory_cptname: [''],
    laboratory_cptcode: [],
    laboratory_quantity: [],
    laboratory_rate: [],
    laboratory_remarks: [''],
    laboratory_priority: [''],
    laboratory_billedamount : 0,
    laboratory_un_billedamount : 0,
    laboratory_instruction : '',
    current_procedural_code_id_arr:[],
    current_procedural_code_id: [],
    laboratory_tooth_number : [],
    laboratory_tooth_issue : [],
    laboratory_tooth_index : [],
    laboratory_tooth_procedure : [],
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
  data_copy: boolean = false;
  private modalRef: NgbModalRef;
  current_procedural_code_id: any[];
  public config = {};
  bill_status: any;
  model: any;
  searching = false;
  searchFailed = false;
  public laboratory_data_arr: any = [];
  lab_investigation_id: any;
  public procedure_id: number = 0;
  cdt_list: any;
  cdt_options: any;
  cdt_data: any = [];
  public alerdyExsits_Extraction: any = [];
  public alerdyExsits_Implant: any = [];
  public alerdyExsits_Extraction_child: any = [];
  public alerdyExsits_Implant_child: any = [];
  public alerdyExsits_child: any = [];
  public alerdyExsits: any = [];
  public showCheckboxs: number = 0;     
  multi_tooth_index: any = [];
  multi_tooth_number: any = [];
  multi_tooth_data :  any = []
  constructor(private modalService: NgbModal,private loaderService : LoaderService,
    public datepipe: DatePipe,private router: Router, 
    public rest2:ConsultingService,public rest:NursingAssesmentService,
    notifierService: NotifierService,public rest1: DoctorsService)
  {
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.getbillStatus();
    // this.getdentalProcedure()
    this.getDentalComplaints();
    this.getDentalInvestigation();  
    this.listNotAllowedProcedure();  
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.doctor_department = JSON.parse(localStorage.getItem('doctor_department'));
    if(this.doctor_department.length > 0)
    {
      for(let dep of this.doctor_department)
      {
        if(dep.OPTIONS_TYPE == 8 && dep.DEPARTMENT_ID ==  43)
        {
          this.department = 1;
        }

      }
    }
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.formatDaTime ();
    this.getCurrentDentalByDentalCode()
  }
  ngOnChanges(changes: SimpleChanges) {
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    // this.getdentalProcedure()
    this.getDentalComplaints();
    this.getDentalInvestigation();  
    this.listNotAllowedProcedure();  

  }

  showCheckbox(val){
    this.showCheckboxs = val;
    if(val == 0)
    {
      var i =0
      for(let data of this.dental)
      {
        this.dental[i].check = 0
        i=i+1
      }
      var j =0
      for(let data of this.dental_child)
      {
        this.dental[j].check = 0
        j=j+1
      }
    }
  }
  multiselctTooths(i)
  {
    if(this.dental_data.patient_type == 1)
    {
      if(this.dental[i].check == 0)
      {
        this.dental[i].check = 1
      }
      else
      {
        this.dental[i].check = 0
      }
    }
    if(this.dental_data.patient_type == 2)
    {
      if(this.dental_child[i].check == 0)
      {
        this.dental_child[i].check = 1
      }
      else
      {
        this.dental_child[i].check = 0
      }
    }
   
    // console.log(this.dental[i])
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
  private open(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true
    };
    //console.log(ngbModalOptions);
    this.modalRef = this.modalService.open(content,{ariaLabelledBy: 'modal-basic-title'});
    this.modalRef.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private open_popup(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true
    };
    //console.log(ngbModalOptions);
    this.modalRef = this.modalService.open(content,{ariaLabelledBy: 'modal-basic-title',size:'lg',windowClass:'col-sm-12'});
    this.modalRef.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  public arrayOne(n: number): any[] {
    var array1 = Array(n);
    //console.log(array1);
    return array1;
  }
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


  public haskey (object,key) {
    if (key in object) {
      return true;
    }
    return false;
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
  
  public OnApplyNotify(event)
  {
    //console.log('event',event)
    this.save_notify = event
    this.saveNotify.emit(this.save_notify)
  }
  
  
  
  public formatDaTime () {
    if (this.now ) {
      this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
  public selectType(val) {
   this.patient_type = val;

  }
  showConfirm(e: any,confirm) {
    if(Object.keys(this.dental_data.complients).length > 0 || Object.keys(this.dental_data.child_complients).length > 0 || this.laboratory_data.current_procedural_code_id.length > 0)
    {
      if(this.dental_data.patient_type != this.patient_type)
      {
        this.confirms(confirm)
        if(this.status==this.status)
        {
          e.preventDefault();
        }
      }
    }
    else
    {
      this.dental_data.patient_type = this.patient_type
    }
    
  }
  finishMulitiSelection(content = '')
  {
    this.multi_tooth_index = []
    this.multi_tooth_number = []
    this.multi_tooth_data = []
    this.cdt_data = []
    this.dental_data.multi_dental_cdt_id = ""
    this.dental_data.multi_cdt_quantity = 1
    this.dental_data.multi_tooth_issue = ""
    this.dental_data.multi_cdt_code = ""
    this.dental_data.multi_tooth_index = ""
    this.dental_data.multi_cdt_data = []
    if(this.dental_data.patient_type == 1)
    {
      var i = 0
      for(let data of this.dental)
      {
        if(data.check == 1)
        {
          this.multi_tooth_index.push(i)
          this.multi_tooth_number.push(data.universal)
          this.multi_tooth_data.push(data)
        }
        i=i+1
      }
    }
    if(this.dental_data.patient_type == 2)
    {
      var j =0
      for(let val of this.dental_child)
      {
        if(val.check == 1)
        {
          this.multi_tooth_index.push(j)
          this.multi_tooth_number.push(val.value)
          this.multi_tooth_data.push(val)
        }
        j=j+1
      }
    }
    if(this.multi_tooth_index.length < 2)
    {
      this.notifier.notify("error","Please select two or more teeth")
      return false;
    }
    else if(this.procedure == "")
    {
      this.notifier.notify('error',"Please select a procedure")
      return false;
    }
    else
    {
      // this.open(content);
    }
    
  }
  saveMultiSelectionteeth()
  {

    if(!this.dental_data.multi_dental_cdt_id)
    {
      this.notifier.notify("error","Please select CPT/CDT")
      // this.open(toothDescription)
    }
    else if(!this.dental_data.multi_cdt_quantity || this.dental_data.multi_cdt_quantity == 0)
    {
      this.notifier.notify("error","Please enter quantity")
      // this.open(toothDescription)
    }
    // else if(!this.dental_data.multi_tooth_issue)
    // {
    //   this.notifier.notify("error","Please enter the tooth issue")
    //   // this.open(toothDescription)
    // }
    else{
      if(!this.dental_data.multi_tooth_issue)
      {
        this.dental_data.multi_tooth_issue = ""
        // this.open(toothDescription)
      }
      if(this.dental_data.patient_type == 1){
        this.multi_tooth_index.forEach((element,i) => {
          if (element in this.dental_data.complients) {
            //do stuff with someObject["someKey"]
          const index = this.dental_data.complients[element].findIndex(complient=> complient.procedure === this.procedure);
          // console.log("index")
          // console.log(index)
          if(index > -1)
          {
            // this.laboratory_data_arr[index] = index
            this.dental_data.complients[element][index].description = this.dental_data.multi_tooth_issue
            this.dental_data.complients[element][index].allias_data = this.dental_data.multi_cdt_data
            this.dental_data.complients[element][index].alliasname = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"]
            this.dental_data.complients[element][index].cptcode = this.dental_data.multi_cdt_code
            this.dental_data.complients[element][index].cpt_description = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"]
            this.dental_data.complients[element][index].cdtcode_id = this.dental_data.multi_dental_cdt_id
            this.dental_data.complients[element][index].quantity = this.dental_data.multi_cdt_quantity

            this.laboratory_data.laboratory_tooth_index.forEach((item, index) => {
              if(this.laboratory_data.laboratory_tooth_index[index] == element &&  this.laboratory_data.laboratory_tooth_procedure[index] == this.procedure)  {
                ind = index;
              } 
            });
            if(ind > -1)
            {
              this.laboratory_data.laboratory_tooth_number[ind] = this.multi_tooth_number[i]
              this.laboratory_data.laboratory_tooth_index[ind] = element
              this.laboratory_data.laboratory_tooth_procedure[ind] = this.procedure
              this.laboratory_data.laboratory_tooth_issue[ind] = this.dental_data.complients[element][index].description
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.multi_cdt_code;
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.multi_dental_cdt_id;
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.multi_cdt_quantity;
              if(this.dental_data.multi_cdt_data)
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.multi_cdt_data
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"];
              this.laboratory_data.laboratory_description[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"];
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.multi_cdt_data["RATE"];
            }
            
          }
          else
          {

            var data = {
              tooth_index: element,
              procedure: this.procedure,
              procedure_id: this.procedure_id,
              description: this.dental_data.multi_tooth_issue,
              color: this.tooth_color,
              tooth_number : this.multi_tooth_number[i],
              allias_data : this.dental_data.multi_cdt_data,
              alliasname :  this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"],
              cptcode : this.dental_data.multi_cdt_code,
              cpt_description : this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"],
              cdtcode_id : this.dental_data.multi_dental_cdt_id,
              quantity : this.dental_data.multi_cdt_quantity,
              date : this.formatDate(new Date())
            }

            this.dental_data.complients[element].push(data);
            var ind = -1
            this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
              if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
                ind = index;
              } 
            });
          //   console.log("ind")
          // console.log(ind)
            if(ind > -1)
            {
              
              this.laboratory_data.laboratory_tooth_index[ind] = (element)
              this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
              this.laboratory_data.laboratory_tooth_number[ind] = (this.multi_tooth_number[i])
              this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.multi_tooth_issue)
              // this.laboratory_data.laboratory_allias_data[ind] = (this.cpt_add_data.cpt_data[0])
              // this.laboratory_data.laboratory_alliasname[ind] = (this.cpt_add_data.alliasname)
              // this.laboratory_data.laboratory_cptcode[ind] = (this.cpt_add_data.cptcode)
              // this.laboratory_data.laboratory_description[ind] = (this.cpt_add_data.description)
              // this.laboratory_data.current_procedural_code_id[ind] = (this.cpt_add_data.current_procedural_code_id)
              // this.laboratory_data.laboratory_rate[ind] = (this.cpt_add_data.rate)
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.multi_cdt_code;
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.multi_dental_cdt_id;
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.multi_cdt_quantity;
              if(this.dental_data.multi_cdt_data)
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.multi_cdt_data
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"];
              this.laboratory_data.laboratory_description[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"];
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.multi_cdt_data["RATE"];
              // this.laboratory_data.laboratory_quantity[ind] = (1)
            }
            else
            {
              this.laboratory_data_arr.push("")
              this.laboratory_data.laboratory_tooth_index.push(element)
              this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
              this.laboratory_data.laboratory_tooth_number.push(this.multi_tooth_number[i])
              this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.multi_tooth_issue)
              // this.laboratory_data.laboratory_allias_data.push(this.cpt_add_data.cpt_data[0])
              // this.laboratory_data.laboratory_alliasname.push(this.cpt_add_data.alliasname)
              // this.laboratory_data.laboratory_cptcode.push(this.cpt_add_data.cptcode)
              // this.laboratory_data.laboratory_description.push(this.cpt_add_data.description)
              // this.laboratory_data.current_procedural_code_id.push(this.cpt_add_data.current_procedural_code_id)
              // this.laboratory_data.laboratory_rate.push(this.cpt_add_data.rate)
              this.laboratory_data.laboratory_allias_data.push(this.dental_data.multi_cdt_data)
              this.laboratory_data.laboratory_alliasname.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"])
              this.laboratory_data.laboratory_cptcode.push(this.dental_data.multi_cdt_code)
              this.laboratory_data.laboratory_description.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"])
              this.laboratory_data.current_procedural_code_id.push(this.dental_data.multi_dental_cdt_id)
              this.laboratory_data.laboratory_rate.push(this.dental_data.multi_cdt_data["RATE"])
              this.laboratory_data.laboratory_quantity.push(this.dental_data.multi_cdt_quantity)
              // this.laboratory_data.laboratory_quantity.push(1)
            this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)

            }

          }
        }
        else
        {

          var data1 =  {
            tooth_index: element,
            procedure: this.procedure,
            procedure_id: this.procedure_id,
            description: this.dental_data.multi_tooth_issue,
            color: this.tooth_color,
            tooth_number : this.multi_tooth_number[i],
            allias_data : this.dental_data.multi_cdt_data,
            alliasname :  this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"],
            cptcode : this.dental_data.multi_cdt_code,
            cpt_description : this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"],
            cdtcode_id : this.dental_data.multi_dental_cdt_id,
            quantity : this.dental_data.multi_cdt_quantity,
            date : this.formatDate(new Date())
          };
          this.dental_data.complients[element] = [data1];
          var ind = -1
          this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
            if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
              ind = index;
            } 
          });
          // console.log("ind")
          // console.log(ind)
          if(ind > -1)
          {
            this.laboratory_data.laboratory_tooth_index[ind] = (element)
            this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
            this.laboratory_data.laboratory_tooth_number[ind] = (this.multi_tooth_number[i])
            this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.multi_tooth_issue)
            // this.laboratory_data.laboratory_allias_data[ind] = (this.cpt_add_data.cpt_data[0])
            // this.laboratory_data.laboratory_alliasname[ind] = (this.cpt_add_data.alliasname)
            // this.laboratory_data.laboratory_cptcode[ind] = (this.cpt_add_data.cptcode)
            // this.laboratory_data.laboratory_description[ind] = (this.cpt_add_data.description)
            // this.laboratory_data.current_procedural_code_id[ind] = (this.cpt_add_data.current_procedural_code_id)
            // this.laboratory_data.laboratory_rate[ind] = (this.cpt_add_data.rate)
            // this.laboratory_data.laboratory_quantity[ind] = (1)
            this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.multi_cdt_data
            this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"];
            this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.multi_cdt_code;
            this.laboratory_data.laboratory_description[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"];
            this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.multi_dental_cdt_id;
            this.laboratory_data.laboratory_rate[ind] = this.dental_data.multi_cdt_data["RATE"];
            this.laboratory_data.laboratory_quantity[ind] = this.dental_data.multi_cdt_quantity;
          }
          else
          {
            this.laboratory_data_arr.push("")
            this.laboratory_data.laboratory_tooth_index.push(element)
            this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
            this.laboratory_data.laboratory_tooth_number.push(this.multi_tooth_number[i])
            this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.multi_tooth_issue)
            this.laboratory_data.laboratory_allias_data.push(this.dental_data.multi_cdt_data)
            this.laboratory_data.laboratory_alliasname.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"])
            this.laboratory_data.laboratory_cptcode.push(this.dental_data.multi_cdt_code)
            this.laboratory_data.laboratory_description.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"])
            this.laboratory_data.current_procedural_code_id.push(this.dental_data.multi_dental_cdt_id)
            this.laboratory_data.laboratory_rate.push(this.dental_data.multi_cdt_data["RATE"])
            this.laboratory_data.laboratory_quantity.push(this.dental_data.multi_cdt_quantity)
            // this.laboratory_data.laboratory_allias_data.push(this.cpt_add_data.cpt_data[0])
            // this.laboratory_data.laboratory_alliasname.push(this.cpt_add_data.alliasname)
            // this.laboratory_data.laboratory_cptcode.push(this.cpt_add_data.cptcode)
            // this.laboratory_data.laboratory_description.push(this.cpt_add_data.description)
            // this.laboratory_data.current_procedural_code_id.push(this.cpt_add_data.current_procedural_code_id)
            // this.laboratory_data.laboratory_rate.push(this.cpt_add_data.rate)
            // this.laboratory_data.laboratory_quantity.push(1)
            this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)
          }

        }
        
        this.dental[element].tooth_issue =  this.dental_data.multi_tooth_issue;
        this.dental_data.procedure[element] = this.procedure
        this.dental_data.color[element] = this.tooth_color
        this.dental_data.universal[element] = (this.multi_tooth_number[i])
        this.dental_data.palmer[element] = (this.multi_tooth_data.palmer)
        this.dental_data.FDI[element] =  (this.multi_tooth_data.value)
        this.dental[element].check = 1;
        this.dental_data.tooth_complaint[element] = 1
        // this.dental[element].procedure = this.procedure
        this.dental[element].color = this.tooth_color
        // console.log(this.laboratory_data)
        });
        
  
      }
      if(this.dental_data.patient_type == 2)
      {
        this.multi_tooth_index.forEach((element,i) => {
          if (element in this.dental_data.child_complients) {
          const index = this.dental_data.child_complients[element].findIndex(complient=> complient.procedure === this.procedure);
          if(index > -1)
          {
            this.dental_data.child_complients[element][index].description = this.dental_data.multi_tooth_issue
            this.dental_data.child_complients[element][index].allias_data = this.dental_data.multi_cdt_data
            this.dental_data.child_complients[element][index].alliasname = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"]
            this.dental_data.child_complients[element][index].cptcode = this.dental_data.multi_cdt_code
            this.dental_data.child_complients[element][index].cpt_description = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"]
            this.dental_data.child_complients[element][index].cdtcode_id = this.dental_data.multi_dental_cdt_id
            this.dental_data.child_complients[element][index].quantity = this.dental_data.multi_cdt_quantity

            this.laboratory_data.laboratory_tooth_index.forEach((item, index) => {
              if(this.laboratory_data.laboratory_tooth_index[index] == element &&  this.laboratory_data.laboratory_tooth_procedure[index] == this.procedure)  {
                ind = index;
              } 
            });
            if(ind > -1)
            {
              this.laboratory_data.laboratory_tooth_number[ind] = this.multi_tooth_number[i]
              this.laboratory_data.laboratory_tooth_index[ind] = element
              this.laboratory_data.laboratory_tooth_procedure[ind] = this.procedure
              this.laboratory_data.laboratory_tooth_issue[ind] = this.dental_data.child_complients[element][index].description
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.multi_cdt_code;
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.multi_dental_cdt_id;
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.multi_cdt_quantity;
              if(this.dental_data.multi_cdt_data)
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.multi_cdt_data
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"];
              this.laboratory_data.laboratory_description[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"];
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.multi_cdt_data["RATE"];
            }
            
          }
          else
          {

            var data = {
              tooth_index: element,
              procedure: this.procedure,
              procedure_id: this.procedure_id,
              description: this.dental_data.multi_tooth_issue,
              color: this.tooth_color,
              tooth_number : this.multi_tooth_number[i],
              allias_data : this.dental_data.multi_cdt_data,
              alliasname :  this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"],
              cptcode : this.dental_data.multi_cdt_code,
              cpt_description : this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"],
              cdtcode_id : this.dental_data.multi_dental_cdt_id,
              quantity : this.dental_data.multi_cdt_quantity,
              date : this.formatDate(new Date())
            }

            this.dental_data.child_complients[element].push(data);
            var ind = -1
            this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
              if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
                ind = index;
              } 
            });
          //   console.log("ind")
          // console.log(ind)
            if(ind > -1)
            {
              
              this.laboratory_data.laboratory_tooth_index[ind] = (element)
              this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
              this.laboratory_data.laboratory_tooth_number[ind] = (this.multi_tooth_number[i])
              this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.multi_tooth_issue)
              // this.laboratory_data.laboratory_allias_data[ind] = (this.cpt_add_data.cpt_data[0])
              // this.laboratory_data.laboratory_alliasname[ind] = (this.cpt_add_data.alliasname)
              // this.laboratory_data.laboratory_cptcode[ind] = (this.cpt_add_data.cptcode)
              // this.laboratory_data.laboratory_description[ind] = (this.cpt_add_data.description)
              // this.laboratory_data.current_procedural_code_id[ind] = (this.cpt_add_data.current_procedural_code_id)
              // this.laboratory_data.laboratory_rate[ind] = (this.cpt_add_data.rate)
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.multi_cdt_code;
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.multi_dental_cdt_id;
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.multi_cdt_quantity;
              if(this.dental_data.multi_cdt_data)
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.multi_cdt_data
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"];
              this.laboratory_data.laboratory_description[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"];
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.multi_cdt_data["RATE"];
              // this.laboratory_data.laboratory_quantity[ind] = (1)
            }
            else
            {
              this.laboratory_data_arr.push("")
              this.laboratory_data.laboratory_tooth_index.push(element)
              this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
              this.laboratory_data.laboratory_tooth_number.push(this.multi_tooth_number[i])
              this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.multi_tooth_issue)
              // this.laboratory_data.laboratory_allias_data.push(this.cpt_add_data.cpt_data[0])
              // this.laboratory_data.laboratory_alliasname.push(this.cpt_add_data.alliasname)
              // this.laboratory_data.laboratory_cptcode.push(this.cpt_add_data.cptcode)
              // this.laboratory_data.laboratory_description.push(this.cpt_add_data.description)
              // this.laboratory_data.current_procedural_code_id.push(this.cpt_add_data.current_procedural_code_id)
              // this.laboratory_data.laboratory_rate.push(this.cpt_add_data.rate)
              this.laboratory_data.laboratory_allias_data.push(this.dental_data.multi_cdt_data)
              this.laboratory_data.laboratory_alliasname.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"])
              this.laboratory_data.laboratory_cptcode.push(this.dental_data.multi_cdt_code)
              this.laboratory_data.laboratory_description.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"])
              this.laboratory_data.current_procedural_code_id.push(this.dental_data.multi_dental_cdt_id)
              this.laboratory_data.laboratory_rate.push(this.dental_data.multi_cdt_data["RATE"])
              this.laboratory_data.laboratory_quantity.push(this.dental_data.multi_cdt_quantity)
              // this.laboratory_data.laboratory_quantity.push(1)
            this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)

            }

          }
        }
        else
        {

          var data1 =  {
            tooth_index: element,
            procedure: this.procedure,
            procedure_id: this.procedure_id,
            description: this.dental_data.multi_tooth_issue,
            color: this.tooth_color,
            tooth_number : this.multi_tooth_number[i],
            allias_data : this.dental_data.multi_cdt_data,
            alliasname :  this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"],
            cptcode : this.dental_data.multi_cdt_code,
            cpt_description : this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"],
            cdtcode_id : this.dental_data.multi_dental_cdt_id,
            quantity : this.dental_data.multi_cdt_quantity,
            date : this.formatDate(new Date())
          };
          this.dental_data.child_complients[element] = [data1];
          var ind = -1
          this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
            if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
              ind = index;
            } 
          });
          // console.log("ind")
          // console.log(ind)
          if(ind > -1)
          {
            this.laboratory_data.laboratory_tooth_index[ind] = (element)
            this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
            this.laboratory_data.laboratory_tooth_number[ind] = (this.multi_tooth_number[i])
            this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.multi_tooth_issue)
            // this.laboratory_data.laboratory_allias_data[ind] = (this.cpt_add_data.cpt_data[0])
            // this.laboratory_data.laboratory_alliasname[ind] = (this.cpt_add_data.alliasname)
            // this.laboratory_data.laboratory_cptcode[ind] = (this.cpt_add_data.cptcode)
            // this.laboratory_data.laboratory_description[ind] = (this.cpt_add_data.description)
            // this.laboratory_data.current_procedural_code_id[ind] = (this.cpt_add_data.current_procedural_code_id)
            // this.laboratory_data.laboratory_rate[ind] = (this.cpt_add_data.rate)
            // this.laboratory_data.laboratory_quantity[ind] = (1)
            this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.multi_cdt_data
            this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"];
            this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.multi_cdt_code;
            this.laboratory_data.laboratory_description[ind] = this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"];
            this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.multi_dental_cdt_id;
            this.laboratory_data.laboratory_rate[ind] = this.dental_data.multi_cdt_data["RATE"];
            this.laboratory_data.laboratory_quantity[ind] = this.dental_data.multi_cdt_quantity;
          }
          else
          {
            this.laboratory_data_arr.push("")
            this.laboratory_data.laboratory_tooth_index.push(element)
            this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
            this.laboratory_data.laboratory_tooth_number.push(this.multi_tooth_number[i])
            this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.multi_tooth_issue)
            this.laboratory_data.laboratory_allias_data.push(this.dental_data.multi_cdt_data)
            this.laboratory_data.laboratory_alliasname.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_ALIAS_NAME"])
            this.laboratory_data.laboratory_cptcode.push(this.dental_data.multi_cdt_code)
            this.laboratory_data.laboratory_description.push(this.dental_data.multi_cdt_data["PROCEDURE_CODE_DESCRIPTION"])
            this.laboratory_data.current_procedural_code_id.push(this.dental_data.multi_dental_cdt_id)
            this.laboratory_data.laboratory_rate.push(this.dental_data.multi_cdt_data["RATE"])
            this.laboratory_data.laboratory_quantity.push(this.dental_data.multi_cdt_quantity)
            // this.laboratory_data.laboratory_allias_data.push(this.cpt_add_data.cpt_data[0])
            // this.laboratory_data.laboratory_alliasname.push(this.cpt_add_data.alliasname)
            // this.laboratory_data.laboratory_cptcode.push(this.cpt_add_data.cptcode)
            // this.laboratory_data.laboratory_description.push(this.cpt_add_data.description)
            // this.laboratory_data.current_procedural_code_id.push(this.cpt_add_data.current_procedural_code_id)
            // this.laboratory_data.laboratory_rate.push(this.cpt_add_data.rate)
            // this.laboratory_data.laboratory_quantity.push(1)
            this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)
          }

        }
        
        this.dental[element].tooth_issue =  this.dental_data.multi_tooth_issue;
        this.dental_data.procedure[element] = this.procedure
        this.dental_data.color[element] = this.tooth_color
        this.dental_data.universal[element] = (this.multi_tooth_number[i])
        this.dental_data.palmer[element] = (this.multi_tooth_data.palmer)
        this.dental_data.FDI[element] =  (this.multi_tooth_data.value)
        this.dental[element].check = 1;
        this.dental_data.tooth_complaint[element] = 1
        // this.dental[element].procedure = this.procedure
        this.dental[element].color = this.tooth_color
        // console.log(this.laboratory_data)
        });
      }
      var i =0
      for(let data of this.dental)
      {
        this.dental[i].check = 0
        i=i+1
      }
      var j =0
      for(let data of this.dental_child)
      {
        this.dental[j].check = 0
        j=j+1
      }
      for(let val of this.dental)
      {
        val['procedure'] = ''
        val['color'] = ''
        val['tooth_issue'] = ''
        val['check'] = 0
      }
      for(let val of this.dental_child)
      {
        val['procedure'] = ''
        val['color'] = ''
        val['tooth_issue'] = ''
        val['check'] = 0
      }
      this.multi_tooth_number = []
      this.multi_tooth_index = []
      this.multi_tooth_data = []
      this.showCheckbox(0)
      if(this.modalRef)
          this.modalRef.close()
      
    }
  }
  getPopup(i,data,toothDescription,listDescription,multiselectiontooth)
  {

    // this.finishMulitiSelection(multiselectiontooth);
    this.multi_tooth_index = []
    this.multi_tooth_number = []
    this.multi_tooth_data = []
    this.cdt_data = []
    this.dental_data.multi_dental_cdt_id = ""
    this.dental_data.multi_cdt_quantity = 1
    this.dental_data.multi_tooth_issue = ""
    this.dental_data.multi_cdt_code = ""
    this.dental_data.multi_tooth_index = ""
    this.dental_data.multi_cdt_data = []
    var k = 0
    for(let data of this.dental)
    {
      if(data.check == 1)
      {
        this.multi_tooth_index.push(k)
        this.multi_tooth_number.push(data.universal)
        this.multi_tooth_data.push(data)
      }
      k=k+1
    }
    var j =0
    for(let data of this.dental_child)
    {
      if(data.check == 1)
      {
        this.multi_tooth_index.push(j)
        this.multi_tooth_number.push(data.value)
        this.multi_tooth_data.push(data)
      }
      j=j+1
    }
    // console.log(this.multi_tooth_number)
    // else
    // {
    //   const found = this.multi_tooth_index.find(element => element == i);
    //   if(found)
    //   {
    //     this.open(multiselectiontooth)
    //   } 
    // }

    const found = this.multi_tooth_index.find(element => element == i);
    if(found)
    {
      if(this.procedure == "")
      {
        this.notifier.notify('error',"Please select a procedure")
        return false;
      }
      else
      {
        this.open(multiselectiontooth)
      }
      
    }
    else
    {
 // console.log(i)
    //console.log(this.dental_data.complients[this.tooth_index])
    this.cdt_data = []
    this.tooth_index = i
    this.tooth_data = data
    if(this.procedure == "")
    {
      if(this.dental_data.complients[this.tooth_index] && Object.keys(this.dental_data.complients).length > 0)
      {
        this.tooth_number = data.universal
        this.open(listDescription)
        return false;
      }
      else if(this.dental_data.child_complients[this.tooth_index] && Object.keys(this.dental_data.child_complients).length > 0)
      {
        this.tooth_number = data.value
        this.open(listDescription)
        return false;
      }
      else
      {
        this.notifier.notify('error',"Please select a procedure")
        return false;
      }
    }
   
    if(this.dental_data.patient_type == 1)
    {
      if (this.tooth_index in this.dental_data.complients) {
          //do stuff with someObject["someKey"]
        //const checkProcedureExistence = roleParam => this.dental_data.complients[this.tooth_index].some( ({procedure}) => procedure == roleParam)
        //console.log(this.procedure);
        
        const index = this.dental_data.complients[this.tooth_index].findIndex(complient=> complient.procedure === this.procedure);
        if(index > -1)
        {
          // console.log("if")
          // console.log(this.dental_data)
          
          this.cdt_data = this.dental_data.complients[this.tooth_index][index].allias_data
          this.dental_data.tooth_issue[this.tooth_index] = this.dental_data.complients[this.tooth_index][index].description
          this.dental_data.dental_cdt_code[this.tooth_index] = this.dental_data.complients[this.tooth_index][index].cptcode
          this.dental_data.dental_cdt_data[this.tooth_index] = this.dental_data.complients[this.tooth_index][index].allias_data
          this.dental_data.dental_cdt_id[this.tooth_index] = this.dental_data.complients[this.tooth_index][index].cdtcode_id
          this.dental_data.dental_cdt_quantity[this.tooth_index] = this.dental_data.complients[this.tooth_index][index].quantity
         
        }
        else
        {
          // console.log("else")
          // console.log(this.dental_data)
          this.dental_data.tooth_issue[this.tooth_index] = '';
          this.dental_data.dental_cdt_id[this.tooth_index] = '';
          this.dental_data.dental_cdt_code[this.tooth_index] = '';
          this.dental_data.dental_cdt_quantity[this.tooth_index] = 1;
          this.dental_data.dental_cdt_data[this.tooth_index] = '';
          this.cdt_data = []
         
        }
      }
      this.open(toothDescription)
      this.tooth_number = data.universal

    }
    if(this.dental_data.patient_type == 2)
    {
      if(this.tooth_index in this.dental_data.child_complients) {
        this.dental_data.tooth_issue[this.tooth_index] = '';
        // this.dental_data.dental_cdt_id[this.tooth_index] = '';
        // this.dental_data.dental_cdt_code[this.tooth_index] = '';
        // this.dental_data.dental_cdt_quantity[this.tooth_index] = '';
        // this.dental_data.dental_cdt_data[this.tooth_index] = '';
        const index = this.dental_data.child_complients[this.tooth_index].findIndex(complient=> complient.procedure === this.procedure);
        if(index > -1)
        {
         
          this.cdt_data = this.dental_data.child_complients[this.tooth_index][index].allias_data
          this.dental_data.tooth_issue[this.tooth_index] = this.dental_data.child_complients[this.tooth_index][index].description
          this.dental_data.dental_cdt_code[this.tooth_index] = this.dental_data.child_complients[this.tooth_index][index].cptcode
          this.dental_data.dental_cdt_data[this.tooth_index] = this.dental_data.child_complients[this.tooth_index][index].allias_data
          this.dental_data.dental_cdt_id[this.tooth_index] = this.dental_data.child_complients[this.tooth_index][index].cdtcode_id
          this.dental_data.dental_cdt_quantity[this.tooth_index] = this.dental_data.child_complients[this.tooth_index][index].quantity
         
        }
        else
        {
        
          this.dental_data.tooth_issue[this.tooth_index] = '';
          this.dental_data.dental_cdt_id[this.tooth_index] = '';
          this.dental_data.dental_cdt_code[this.tooth_index] = '';
          this.dental_data.dental_cdt_quantity[this.tooth_index] = 1;
          this.dental_data.dental_cdt_data[this.tooth_index] = '';
          this.cdt_data = []
         
        }
      }
      this.open(toothDescription)
      this.tooth_number = data.value

    }
    }
   
  }
  deleteProcedure(tooth_index,procedure,listDescription)
  {
    if(this.dental_data.patient_type == 1)
    {
      const index = this.dental_data.complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
      if(index > -1)
      {
        this.dental_data.complients[tooth_index].splice(index, 1);
      }
      this.laboratory_data.laboratory_tooth_index.forEach((item, i) => {
        if(this.laboratory_data.laboratory_tooth_procedure[i] == procedure && this.laboratory_data.laboratory_tooth_index[i] == tooth_index) 
        { 
          this.deleteDrugrow(i);
        } 
      });
      this.notifier.notify('warning','Selected procedure is removed')
      if(this.dental_data.complients[tooth_index].length == 0)
      {
        if(this.modalRef)
          this.modalRef.close()
      }
      this.getEvents()
    }
    if(this.dental_data.patient_type == 2)
    {
      const index = this.dental_data.child_complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
      if(index > -1)
      {
        this.dental_data.child_complients[tooth_index].splice(index, 1);
      }
      this.laboratory_data.laboratory_tooth_index.forEach((item, i) => {
        if(this.laboratory_data.laboratory_tooth_procedure[i] == procedure && this.laboratory_data.laboratory_tooth_index[i] == tooth_index) 
        {
          //console.log(this.laboratory_data)
          this.deleteDrugrow(i);
        } 
      });
      if(this.laboratory_data_arr.length == 0)
      {
        this.addDrugrow(-1)
      }
      this.notifier.notify('warning','Selected procedure is removed')
      if(this.dental_data.child_complients[tooth_index].length == 0)
      {
        if(this.modalRef)
          this.modalRef.close()
      }
      this.getEvents()
    }
  }

  getPopups(tooth_index,procedure)
  {
    if(this.dental_data.patient_type == 1)
    {
      var i =0
      for(let data of this.dental)
      {
        this.dental[i].check = 0
        i=i+1
      }
      if(this.dental_data.complients[tooth_index])
      {
        const index = this.dental_data.complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
        if(index > -1)
        {
          this.dental_data.complients[tooth_index].splice(index, 1);
        }
        this.laboratory_data.laboratory_tooth_index.forEach((item, i) => {
          if(this.laboratory_data.laboratory_tooth_procedure[i] == procedure && this.laboratory_data.laboratory_tooth_index[i] == tooth_index) 
          { 
            this.deleteDrugrow(i);
          } 
        });
        if(this.laboratory_data_arr.length == 0)
        {
          this.addDrugrow(-1)
        }
        // this.dental[this.tooth_index].check = 0;
        // this.dental_data.tooth_issue[this.tooth_index] = ''
        // this.dental[this.tooth_index].tooth_issue = ''
        // this.dental[this.tooth_index].procedure = ''
        // this.dental[this.tooth_index].color = ''
        this.notifier.notify('warning','Selected procedure is removed')
        this.getEvents()
      }
    }
    if(this.dental_data.patient_type == 2)
    {
      var i =0
      for(let data of this.dental_child)
      {
        this.dental[i].check = 0
        i=i+1
      }
      if(this.dental_data.complients[tooth_index])
      {
        const index = this.dental_data.child_complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
        if(index > -1)
        {
          this.dental_data.child_complients[tooth_index].splice(index, 1);
        }
        this.laboratory_data.laboratory_tooth_index.forEach((item, i) => {
          if(this.laboratory_data.laboratory_tooth_procedure[i] == procedure && this.laboratory_data.laboratory_tooth_index[i] == tooth_index) 
          { 
            this.deleteDrugrow(i);
          } 
        });
        // this.dental_child[this.tooth_index].check = 0;
        // this.dental_data.tooth_issue[this.tooth_index] = ''
        // this.dental_child[this.tooth_index].tooth_issue = ''
        // this.dental_child[this.tooth_index].procedure = ''
        // this.dental_child[this.tooth_index].color = ''
        this.notifier.notify('warning','Selected procedure is removed')
        this.getEvents()
      }
    }
      //  this.tooth_index = '';
  }
  clear_data()
  {
    this.status = true;
    this.dental_data.universal= []
    this.dental_data.palmer= []
    this.dental_data.FDI = []
    this.dental_data.ID = []
    this.dental_data.tooth_issue = []
    this.dental_data.tooth_complaint = []
    this.dental_data.complients = {}
    this.dental_data.child_complients = {}
    this.dental_data.procedure = []
    this.dental_data.child_tooth_value = []
    this.dental_data.child_tooth_number = []
    this.dental_data.color = []
    this.dental_data.dental_cdt_code = []
    this.dental_data.dental_cdt_data = []
    this.dental_data.dental_cdt_id = []
    this.dental_data.dental_cdt_quantity = []
    this.dental_data.multi_cdt_code = ""
    this.dental_data.multi_cdt_data = []
    this.dental_data.multi_cdt_quantity = 1
    this.dental_data.multi_dental_cdt_id = ""
    this.dental_data.multi_tooth_index = ""
    this.dental_data.multi_tooth_issue = ""
    this.multi_tooth_data = []
    this.multi_tooth_index = []
    this.multi_tooth_number = []
    this.showCheckboxs = 0
    // this.dental_data.dental_complaint_id = []
    this.dental_data.patient_type = this.patient_type

    this.patient_type = 0;
    this.procedure = ''
    this.tooth_color = ''
    this.tooth_index = ''
    this.tooth_data = ''
    this.tooth_number = 0
    this.tooth_issue = ' ';
    this.procedure_id = 0
    for(let val of this.dental)
    {
      val['procedure'] = ''
      val['color'] = ''
      val['tooth_issue'] = ''
      val['check'] = 0
    }
    for(let val of this.dental_child)
    {
      val['procedure'] = ''
      val['color'] = ''
      val['tooth_issue'] = ''
      val['check'] = 0
    }
    this.notifier.notify('warning',"All datas are cleared")
    if(this.dental_data.patient_type == 1)
    {
      var i=0;
      for(let val of this.dental)
      {
        // this.dental_data.tooth_complaint[i] = val.check;
        this.dental_data.tooth_issue[i] = val.tooth_issue;
        this.dental_data.procedure[i] = val.procedure;
        this.dental_data.color[i] = val.color;
        this.dental_data.universal[i] = val.universal;
        this.dental_data.FDI[i] = val.value;
        this.dental_data.palmer[i] = val.palmer;
        this.dental_data.patient_type = 1;
        this.tooth_color = val.color;
        this.procedure = val.procedure;
        i=i+1
      }
    }
    if(this.dental_data.patient_type ==2)
    {
      var j=0;
      for(let val of this.dental_child)
      {
        // this.dental_data.tooth_complaint[j] = val.check;
        this.dental_data.tooth_issue[j] = val.tooth_issue;
        this.dental_data.procedure[j] = val.procedure;
        this.dental_data.color[j] = val.color;
        this.dental_data.child_tooth_number[j] = val.label;
        this.dental_data.child_tooth_value[j] = val.value;
        this.dental_data.patient_type = 2;
        this.tooth_color = val.color;
        this.procedure = val.procedure;
        j=j+1
      }
    }
    this. laboratory_data = {
      cptcode : [],
      patient_allergies_id: 0,
      patient_id: 0,
      assessment_id: 0,
      patient_no_known_allergies: 0,
      laboratory_description: [],
      laboratory_allias_data: [],
      laboratory_alliasname: [''],
      laboratory_cptname: [''],
      laboratory_cptcode: [],
      laboratory_quantity: [],
      laboratory_rate: [],
      laboratory_remarks: [''],
      laboratory_priority: [''],
      laboratory_billedamount : 0,
      laboratory_un_billedamount : 0,
      laboratory_instruction : '',
      current_procedural_code_id_arr:[],
      current_procedural_code_id: [],
      laboratory_tooth_number : [],
      laboratory_tooth_issue : [],
      laboratory_tooth_index : [],
      laboratory_tooth_procedure : [],
      user_id : ''
    };
    this.laboratory_data_arr = [];
    this.addDrugrow(-1)
    //this.getDentalComplaints();
  }
  clear()
  {
    this.dental_data.universal= []
    this.dental_data.palmer= []
    this.dental_data.FDI = []
    this.dental_data.ID = []
    this.dental_data.tooth_issue = []
    this.dental_data.tooth_complaint = []
    this.dental_data.complients = []
    this.dental_data.child_complients = []
    this.dental_data.procedure = []
    this.dental_data.child_tooth_value = []
    this.dental_data.child_tooth_number = []
    this.dental_data.color = []
    this.dental_data.dental_cdt_code = []
    this.dental_data.dental_cdt_data = []
    this.dental_data.dental_cdt_id = []
    this.dental_data.dental_cdt_quantity = []
    this.dental_data.multi_cdt_code = ""
    this.dental_data.multi_cdt_data = []
    this.dental_data.multi_cdt_quantity = 1
    this.dental_data.multi_dental_cdt_id = ""
    this.dental_data.multi_tooth_index = ""
    this.dental_data.multi_tooth_issue = ""
    this.multi_tooth_data = []
    this.multi_tooth_index = []
    this.multi_tooth_number = []
    this.procedure = ''
    this.procedure_id = 0
    this.tooth_color = ''
    this.tooth_index = ''
    this.tooth_data = ''
    this.tooth_number = 0
    this.tooth_issue = ' ';
    this.showCheckboxs = 0
    for(let val of this.dental)
    {
      val['procedure'] = ''
      val['color'] = ''
      val['tooth_issue'] = ''
      val['check'] = 0
    }
    for(let val of this.dental_child)
    {
      val['procedure'] = ''
      val['color'] = ''
      val['check'] = 0
    }
    // console.log(this.dental)
    // console.log(this.dental_child)
    if(this.dental_data.patient_type == 1)
    {
      var i=0;
      for(let val of this.dental)
      {
        // this.dental_data.tooth_complaint[i] = val.check;
        this.dental_data.tooth_issue[i] = val.tooth_issue;
        this.dental_data.procedure[i] = val.procedure;
        this.dental_data.color[i] = val.color;
        this.dental_data.universal[i] = val.universal;
        this.dental_data.FDI[i] = val.value;
        this.dental_data.palmer[i] = val.palmer;
        this.dental_data.patient_type = 1;
        this.tooth_color = val.color;
        this.procedure = val.procedure;
        i=i+1
      }
    }
    if(this.dental_data.patient_type ==2)
    {
      var j=0;
      for(let val of this.dental_child)
      {
        // this.dental_data.tooth_complaint[j] = val.check;
        this.dental_data.tooth_issue[j] = val.tooth_issue;
        this.dental_data.procedure[j] = val.procedure;
        this.dental_data.color[j] = val.color;
        this.dental_data.child_tooth_number[j] = val.label;
        this.dental_data.child_tooth_value[j] = val.value;
        this.dental_data.patient_type = 2;
        this.tooth_color = val.color;
        this.procedure = val.procedure;
        j=j+1
      }
    }
    this. laboratory_data = {
      cptcode : [],
      patient_allergies_id: 0,
      patient_id: 0,
      assessment_id: 0,
      patient_no_known_allergies: 0,
      laboratory_description: [],
      laboratory_allias_data: [],
      laboratory_alliasname: [''],
      laboratory_cptname: [''],
      laboratory_cptcode: [],
      laboratory_quantity: [],
      laboratory_rate: [],
      laboratory_remarks: [''],
      laboratory_priority: [''],
      laboratory_billedamount : 0,
      laboratory_un_billedamount : 0,
      laboratory_instruction : '',
      current_procedural_code_id_arr:[],
      current_procedural_code_id: [],
      laboratory_tooth_number : [],
      laboratory_tooth_issue : [],
      laboratory_tooth_index : [],
      laboratory_tooth_procedure : [],
      user_id : ''
    };
   // this.lab_investigation_id = 0;
  //  var k= 0;
  //  for(let data of this.laboratory_data_arr)
  //  {
  //     this.deleteDrugrow(k);
  //     k=k+1
  //  }
  this.laboratory_data_arr = []
   this.addDrugrow(-1)
    this.getEvents()
    //this.getDentalComplaints();
  }
  public getEvents()
  {

    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      var flag = 0
        for(let data of this.dental_data.tooth_issue){
        // console.log(data)
          if(data != '')
          {
            flag = 1
          }
        }
        if(flag == 1)
        {
          this.save_notify = 1
          this.saveNotify.emit(this.save_notify)
        }
        else{
          this.save_notify = 0
          this.saveNotify.emit(this.save_notify)
        }

    }
    else
    {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
    }

    // console.log(this.saveNotify.emit(this.save_notify))
  }
  public getdentalProcedure() {
    this.loaderService.display(true);   
    this.rest2.getdentalProcedure().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        if (data['DENTAL_PROCEDURE']['status'] === 'Success') {
          this.dental_procedure = data['DENTAL_PROCEDURE']['data'];
        }
      } 
    });
  }
  selectProcedure(data)
  {
    if(this.procedure != data.procedures){

      this.procedure = data.procedures;
      this.tooth_color = data.color
      this.procedure_id = data.id
        const postData = {
          dental_procedure_id : this.procedure_id
        };
        this.loaderService.display(true);
        this.rest2.getCDTByProcedureId(postData).subscribe((result: {}) => {
          this.loaderService.display(false);
          if (result['status'] == 'Success')
          {
            this.cdt_list = result["data"];
            this.cdt_options=this.cdt_list;
            for (let index = 0; index < this.cdt_options.length; index++) {
              if (this.cdt_options[index].CURRENT_PROCEDURAL_CODE_ID == this.dental_data.dental_cdt_id[this.tooth_index]) {
                this.cdt_data = this.cdt_options[index];
              }
            }
          }
          else
          {
            this.cdt_list = result["data"];
            this.cdt_options   = result["data"];
            this.cdt_data = []
          }
        });
    }
    else
    {
      this.procedure = '';
      this.tooth_color = '';
      this.procedure_id = 0

    }

  }
  saveIssue(toothDescription)
  {

    if(!this.dental_data.dental_cdt_id[this.tooth_index])
    {
      this.notifier.notify("error","Please select CPT/CDT")
      // this.open(toothDescription)
    }
    else if(!this.dental_data.dental_cdt_quantity[this.tooth_index] || this.dental_data.dental_cdt_quantity[this.tooth_index] == 0)
    {
      this.notifier.notify("error","Please enter quantity")
      // this.open(toothDescription)
    }
     
    else{
      if(!this.dental_data.tooth_issue[this.tooth_index])
      {
        this.dental_data.tooth_issue[this.tooth_index] = ""
        // this.open(toothDescription)
      }
      if(this.dental_data.patient_type == 1){
        if (this.tooth_index in this.dental_data.complients) {
            //do stuff with someObject["someKey"]
          const index = this.dental_data.complients[this.tooth_index].findIndex(complient=> complient.procedure === this.procedure);
          // console.log("index")
          // console.log(index)
          if(index > -1)
          {
            // this.laboratory_data_arr[index] = index
            this.dental_data.complients[this.tooth_index][index].description = this.dental_data.tooth_issue[this.tooth_index]
            this.dental_data.complients[this.tooth_index][index].allias_data = this.dental_data.dental_cdt_data[this.tooth_index]
            this.dental_data.complients[this.tooth_index][index].alliasname = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME
            this.dental_data.complients[this.tooth_index][index].cptcode = this.dental_data.dental_cdt_code[this.tooth_index]
            this.dental_data.complients[this.tooth_index][index].cpt_description = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION
            this.dental_data.complients[this.tooth_index][index].cdtcode_id = this.dental_data.dental_cdt_id[this.tooth_index]
            this.dental_data.complients[this.tooth_index][index].quantity = this.dental_data.dental_cdt_quantity[this.tooth_index]

            this.laboratory_data.laboratory_tooth_index.forEach((item, index) => {
              if(this.laboratory_data.laboratory_tooth_index[index] == this.tooth_index &&  this.laboratory_data.laboratory_tooth_procedure[index] == this.procedure)  {
                ind = index;
              } 
            });
            if(ind > -1)
            {
              this.laboratory_data.laboratory_tooth_number[ind] = this.tooth_data.universal
              this.laboratory_data.laboratory_tooth_index[ind] = this.tooth_index
              this.laboratory_data.laboratory_tooth_procedure[ind] = this.procedure
              this.laboratory_data.laboratory_tooth_issue[ind] = this.dental_data.complients[this.tooth_index][index].description
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.dental_cdt_code[this.tooth_index];
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.dental_cdt_id[this.tooth_index];
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.dental_cdt_quantity[this.tooth_index];
              if(this.dental_data.dental_cdt_data[this.tooth_index])
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.dental_cdt_data[this.tooth_index]
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME;
              this.laboratory_data.laboratory_description[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION;
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.dental_cdt_data[this.tooth_index].RATE;
            }
            
          }
          else
          {

            var data = {
              tooth_index: this.tooth_index,
              procedure: this.procedure,
              procedure_id: this.procedure_id,
              description: this.dental_data.tooth_issue[this.tooth_index],
              color: this.tooth_color,
              tooth_number : this.tooth_data.universal,
              allias_data : this.dental_data.dental_cdt_data[this.tooth_index],
              alliasname :  this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME,
              cptcode : this.dental_data.dental_cdt_code[this.tooth_index],
              cpt_description : this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION,
              cdtcode_id : this.dental_data.dental_cdt_id[this.tooth_index],
              quantity : this.dental_data.dental_cdt_quantity[this.tooth_index],
              date : this.formatDate(new Date())
            }

            this.dental_data.complients[this.tooth_index].push(data);
            var ind = -1
            this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
              if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
                ind = index;
              } 
            });
          //   console.log("ind")
          // console.log(ind)
            if(ind > -1)
            {
              
              this.laboratory_data.laboratory_tooth_index[ind] = (this.tooth_index)
              this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
              this.laboratory_data.laboratory_tooth_number[ind] = (this.tooth_data.universal)
              this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.tooth_issue[this.tooth_index])
              // this.laboratory_data.laboratory_allias_data[ind] = (this.cpt_add_data.cpt_data[0])
              // this.laboratory_data.laboratory_alliasname[ind] = (this.cpt_add_data.alliasname)
              // this.laboratory_data.laboratory_cptcode[ind] = (this.cpt_add_data.cptcode)
              // this.laboratory_data.laboratory_description[ind] = (this.cpt_add_data.description)
              // this.laboratory_data.current_procedural_code_id[ind] = (this.cpt_add_data.current_procedural_code_id)
              // this.laboratory_data.laboratory_rate[ind] = (this.cpt_add_data.rate)
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.dental_cdt_code[this.tooth_index];
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.dental_cdt_id[this.tooth_index];
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.dental_cdt_quantity[this.tooth_index];
              if(this.dental_data.dental_cdt_data[this.tooth_index])
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.dental_cdt_data[this.tooth_index]
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME;
              this.laboratory_data.laboratory_description[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION;
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.dental_cdt_data[this.tooth_index].RATE;
              // this.laboratory_data.laboratory_quantity[ind] = (1)
            }
            else
            {
              this.laboratory_data_arr.push("")
              this.laboratory_data.laboratory_tooth_index.push(this.tooth_index)
              this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
              this.laboratory_data.laboratory_tooth_number.push(this.tooth_data.universal)
              this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.tooth_issue[this.tooth_index])
              // this.laboratory_data.laboratory_allias_data.push(this.cpt_add_data.cpt_data[0])
              // this.laboratory_data.laboratory_alliasname.push(this.cpt_add_data.alliasname)
              // this.laboratory_data.laboratory_cptcode.push(this.cpt_add_data.cptcode)
              // this.laboratory_data.laboratory_description.push(this.cpt_add_data.description)
              // this.laboratory_data.current_procedural_code_id.push(this.cpt_add_data.current_procedural_code_id)
              // this.laboratory_data.laboratory_rate.push(this.cpt_add_data.rate)
              this.laboratory_data.laboratory_allias_data.push(this.dental_data.dental_cdt_data[this.tooth_index])
              this.laboratory_data.laboratory_alliasname.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME)
              this.laboratory_data.laboratory_cptcode.push(this.dental_data.dental_cdt_code[this.tooth_index])
              this.laboratory_data.laboratory_description.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION)
              this.laboratory_data.current_procedural_code_id.push(this.dental_data.dental_cdt_id[this.tooth_index])
              this.laboratory_data.laboratory_rate.push(this.dental_data.dental_cdt_data[this.tooth_index].RATE)
              this.laboratory_data.laboratory_quantity.push(this.dental_data.dental_cdt_quantity[this.tooth_index])
              // this.laboratory_data.laboratory_quantity.push(1)
            this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)

            }

          }
        }
        else
        {

          var data1 =  {
            tooth_index: this.tooth_index,
            procedure: this.procedure,
            procedure_id: this.procedure_id,
            description: this.dental_data.tooth_issue[this.tooth_index],
            color: this.tooth_color,
            tooth_number : this.tooth_data.universal,
            allias_data : this.dental_data.dental_cdt_data[this.tooth_index],
            alliasname :  this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME,
            cptcode : this.dental_data.dental_cdt_code[this.tooth_index],
            cpt_description : this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION,
            cdtcode_id : this.dental_data.dental_cdt_id[this.tooth_index],
            quantity : this.dental_data.dental_cdt_quantity[this.tooth_index],
            date : this.formatDate(new Date())
          };
          this.dental_data.complients[this.tooth_index] = [data1];
          var ind = -1
          this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
            if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
              ind = index;
            } 
          });
          // console.log("ind")
          // console.log(ind)
          if(ind > -1)
          {
            this.laboratory_data.laboratory_tooth_index[ind] = (this.tooth_index)
            this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
            this.laboratory_data.laboratory_tooth_number[ind] = (this.tooth_data.universal)
            this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.tooth_issue[this.tooth_index])
            // this.laboratory_data.laboratory_allias_data[ind] = (this.cpt_add_data.cpt_data[0])
            // this.laboratory_data.laboratory_alliasname[ind] = (this.cpt_add_data.alliasname)
            // this.laboratory_data.laboratory_cptcode[ind] = (this.cpt_add_data.cptcode)
            // this.laboratory_data.laboratory_description[ind] = (this.cpt_add_data.description)
            // this.laboratory_data.current_procedural_code_id[ind] = (this.cpt_add_data.current_procedural_code_id)
            // this.laboratory_data.laboratory_rate[ind] = (this.cpt_add_data.rate)
            // this.laboratory_data.laboratory_quantity[ind] = (1)
            this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.dental_cdt_data[this.tooth_index]
            this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME;
            this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.dental_cdt_code[this.tooth_index];
            this.laboratory_data.laboratory_description[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION;
            this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.dental_cdt_id[this.tooth_index];
            this.laboratory_data.laboratory_rate[ind] = this.dental_data.dental_cdt_data[this.tooth_index].RATE;
            this.laboratory_data.laboratory_quantity[ind] = this.dental_data.dental_cdt_quantity[this.tooth_index];
          }
          else
          {
            this.laboratory_data_arr.push("")
            this.laboratory_data.laboratory_tooth_index.push(this.tooth_index)
            this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
            this.laboratory_data.laboratory_tooth_number.push(this.tooth_data.universal)
            this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.tooth_issue[this.tooth_index])
            this.laboratory_data.laboratory_allias_data.push(this.dental_data.dental_cdt_data[this.tooth_index])
            this.laboratory_data.laboratory_alliasname.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME)
            this.laboratory_data.laboratory_cptcode.push(this.dental_data.dental_cdt_code[this.tooth_index])
            this.laboratory_data.laboratory_description.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION)
            this.laboratory_data.current_procedural_code_id.push(this.dental_data.dental_cdt_id[this.tooth_index])
            this.laboratory_data.laboratory_rate.push(this.dental_data.dental_cdt_data[this.tooth_index].RATE)
            this.laboratory_data.laboratory_quantity.push(this.dental_data.dental_cdt_quantity[this.tooth_index])
            // this.laboratory_data.laboratory_allias_data.push(this.cpt_add_data.cpt_data[0])
            // this.laboratory_data.laboratory_alliasname.push(this.cpt_add_data.alliasname)
            // this.laboratory_data.laboratory_cptcode.push(this.cpt_add_data.cptcode)
            // this.laboratory_data.laboratory_description.push(this.cpt_add_data.description)
            // this.laboratory_data.current_procedural_code_id.push(this.cpt_add_data.current_procedural_code_id)
            // this.laboratory_data.laboratory_rate.push(this.cpt_add_data.rate)
            // this.laboratory_data.laboratory_quantity.push(1)
            this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)
          }

        }
      }
      if(this.dental_data.patient_type == 2)
      {
        if(this.tooth_index in this.dental_data.child_complients) 
        {
          const index = this.dental_data.child_complients[this.tooth_index].findIndex(complient=> complient.procedure === this.procedure);
          if(index > -1)
          {
            // this.laboratory_data_arr[index] = index
            this.dental_data.child_complients[this.tooth_index][index].description = this.dental_data.tooth_issue[this.tooth_index]
            this.dental_data.child_complients[this.tooth_index][index].allias_data = this.dental_data.dental_cdt_data[this.tooth_index]
            this.dental_data.child_complients[this.tooth_index][index].alliasname = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME
            this.dental_data.child_complients[this.tooth_index][index].cptcode = this.dental_data.dental_cdt_code[this.tooth_index]
            this.dental_data.child_complients[this.tooth_index][index].cpt_description = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION
            this.dental_data.child_complients[this.tooth_index][index].cdtcode_id = this.dental_data.dental_cdt_id[this.tooth_index]
            this.dental_data.child_complients[this.tooth_index][index].quantity = this.dental_data.dental_cdt_quantity[this.tooth_index]
            this.laboratory_data.laboratory_tooth_index.forEach((item, index) => {
              if(this.laboratory_data.laboratory_tooth_index[index] == this.tooth_index &&  this.laboratory_data.laboratory_tooth_procedure[index] == this.procedure)  {
                ind = index;
              } 
            });
            if(ind > -1)
            {
              this.laboratory_data.laboratory_tooth_number[ind] = this.tooth_data.value
              this.laboratory_data.laboratory_tooth_index[ind] = this.tooth_index
              this.laboratory_data.laboratory_tooth_procedure[ind] = this.procedure
              this.laboratory_data.laboratory_tooth_issue[ind] = this.dental_data.child_complients[this.tooth_index][index].description
              this.laboratory_data.laboratory_tooth_procedure[ind] = this.procedure
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.dental_cdt_code[this.tooth_index];
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.dental_cdt_id[this.tooth_index];
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.dental_cdt_quantity[this.tooth_index];
              if(this.dental_data.dental_cdt_data[this.tooth_index])
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.dental_cdt_data[this.tooth_index]
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME;
              this.laboratory_data.laboratory_description[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION;
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.dental_cdt_data[this.tooth_index].RATE;
            }
          }
          else
          {
            var data = {
              tooth_index: this.tooth_index,
              procedure: this.procedure,
              procedure_id: this.procedure_id,
              description: this.dental_data.tooth_issue[this.tooth_index],
              color: this.tooth_color,
              tooth_number : this.tooth_data.value,
              allias_data : this.dental_data.dental_cdt_data[this.tooth_index],
              alliasname :  this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME,
              cptcode : this.dental_data.dental_cdt_code[this.tooth_index],
              cpt_description : this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION,
              cdtcode_id : this.dental_data.dental_cdt_id[this.tooth_index],
              quantity : this.dental_data.dental_cdt_quantity[this.tooth_index],
              date : this.formatDate(new Date())
            }
            this.dental_data.child_complients[this.tooth_index].push(data);
            var ind = -1
            this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
              if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
                ind = index;
              } 
            });
            if(ind > -1)
            {

              this.laboratory_data.laboratory_tooth_index[ind] = (this.tooth_index)
              this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
              this.laboratory_data.laboratory_tooth_number[ind] = (this.tooth_data.value)
              this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.tooth_issue[this.tooth_index])
              this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.dental_cdt_code[this.tooth_index];
              this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.dental_cdt_id[this.tooth_index];
              this.laboratory_data.laboratory_quantity[ind] = this.dental_data.dental_cdt_quantity[this.tooth_index];
              if(this.dental_data.dental_cdt_data[this.tooth_index])
              this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.dental_cdt_data[this.tooth_index]
              this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME;
              this.laboratory_data.laboratory_description[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION;
              this.laboratory_data.laboratory_rate[ind] = this.dental_data.dental_cdt_data[this.tooth_index].RATE;
            }
            else
            {
              this.laboratory_data_arr.push("")
              this.laboratory_data.laboratory_tooth_index.push(this.tooth_index)
              this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
              this.laboratory_data.laboratory_tooth_number.push(this.tooth_data.value)
              this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.tooth_issue[this.tooth_index])
              this.laboratory_data.laboratory_allias_data.push(this.dental_data.dental_cdt_data[this.tooth_index])
              this.laboratory_data.laboratory_alliasname.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME)
              this.laboratory_data.laboratory_cptcode.push(this.dental_data.dental_cdt_code[this.tooth_index])
              this.laboratory_data.laboratory_description.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION)
              this.laboratory_data.current_procedural_code_id.push(this.dental_data.dental_cdt_id[this.tooth_index])
              this.laboratory_data.laboratory_rate.push(this.dental_data.dental_cdt_data[this.tooth_index].RATE)
              this.laboratory_data.laboratory_quantity.push(this.dental_data.dental_cdt_quantity[this.tooth_index])
              this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)
            }
           
          }
        }
        else
        {
          var data1 =  {
            tooth_index: this.tooth_index,
            procedure: this.procedure,
            procedure_id: this.procedure_id,
            description: this.dental_data.tooth_issue[this.tooth_index],
            color: this.tooth_color,
            tooth_number : this.tooth_data.value,
            allias_data : this.dental_data.dental_cdt_data[this.tooth_index],
            alliasname :  this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME,
            cptcode : this.dental_data.dental_cdt_code[this.tooth_index],
            cpt_description : this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION,
            cdtcode_id : this.dental_data.dental_cdt_id[this.tooth_index],
            quantity : this.dental_data.dental_cdt_quantity[this.tooth_index],
            date : this.formatDate(new Date())
          };
          this.dental_data.child_complients[this.tooth_index] = [data1];
          var ind = -1
          this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
            if(this.laboratory_data.current_procedural_code_id[index] == "" || this.laboratory_data.current_procedural_code_id[index] == 0 || this.laboratory_data.current_procedural_code_id[index] == null)  {
              ind = index;
            } 
          });
          
          if(ind > -1)
          {
            this.laboratory_data.laboratory_tooth_index[ind] = (this.tooth_index)
            this.laboratory_data.laboratory_tooth_procedure[ind] = (this.procedure)
            this.laboratory_data.laboratory_tooth_number[ind] = (this.tooth_data.value)
            this.laboratory_data.laboratory_tooth_issue[ind] = (this.dental_data.tooth_issue[this.tooth_index])
            this.laboratory_data.laboratory_cptcode[ind] = this.dental_data.dental_cdt_code[this.tooth_index];
            this.laboratory_data.current_procedural_code_id[ind] = this.dental_data.dental_cdt_id[this.tooth_index];
            this.laboratory_data.laboratory_quantity[ind] = this.dental_data.dental_cdt_quantity[this.tooth_index];
            if(this.dental_data.dental_cdt_data[this.tooth_index])
            this.laboratory_data.laboratory_allias_data[ind] = this.dental_data.dental_cdt_data[this.tooth_index]
            this.laboratory_data.laboratory_alliasname[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME;
            this.laboratory_data.laboratory_description[ind] = this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION;
            this.laboratory_data.laboratory_rate[ind] = this.dental_data.dental_cdt_data[this.tooth_index].RATE;
          }
          else
          {
            this.laboratory_data_arr.push("")
            this.laboratory_data.laboratory_tooth_index.push(this.tooth_index)
            this.laboratory_data.laboratory_tooth_procedure.push(this.procedure)
            this.laboratory_data.laboratory_tooth_number.push(this.tooth_data.value)
            this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.tooth_issue[this.tooth_index])
            this.laboratory_data.laboratory_tooth_issue.push(this.dental_data.tooth_issue[this.tooth_index])
            this.laboratory_data.laboratory_allias_data.push(this.dental_data.dental_cdt_data[this.tooth_index])
            this.laboratory_data.laboratory_alliasname.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_ALIAS_NAME)
            this.laboratory_data.laboratory_cptcode.push(this.dental_data.dental_cdt_code[this.tooth_index])
            this.laboratory_data.laboratory_description.push(this.dental_data.dental_cdt_data[this.tooth_index].PROCEDURE_CODE_DESCRIPTION)
            this.laboratory_data.current_procedural_code_id.push(this.dental_data.dental_cdt_id[this.tooth_index])
            this.laboratory_data.laboratory_rate.push(this.dental_data.dental_cdt_data[this.tooth_index].RATE)
            this.laboratory_data.laboratory_quantity.push(this.dental_data.dental_cdt_quantity[this.tooth_index])
            this.addDrugrow(this.laboratory_data.current_procedural_code_id.length - 1)
          }
        }
      }
      if(this.modalRef)
          this.modalRef.close()
    }
  }
  public saveDental()
  {
    this.dental_data.assessment_id = this.assessment_id
    this.dental_data.patient_id = this.patient_id
    this.dental_data.client_date = this.now
    this.dental_data.user_id =this.user_data.user_id
    this.dental_data.timeZone = Moment.tz.guess()
    // if(this.dental_data.dental_complaint_id == 0)
    // {
    //   if(this.dental_data.patient_type == 1)
    //   {
    //     if(Object.keys(this.dental_data.complients).length ==  0)
    //     {
    //       this.notifier.notify('error','Please select at least one tooth')
    //       return false
    //     }
    //   }
    //   if(this.dental_data.patient_type == 2)
    //   {
    //     if(Object.keys(this.dental_data.child_complients).length ==  0)
    //     {
    //       this.notifier.notify('error','Please select at least one tooth')
    //       return false
    //     }
    //   }
    // }
    if(this.lab_investigation_id == 0)
    {
      var rel = 0
      // console.log(this.laboratory_data.laboratory_tooth_index)
        this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
          if(this.laboratory_data.current_procedural_code_id[index] > 0)
          {
            if(this.laboratory_data.laboratory_tooth_number[index] == 0 || this.laboratory_data.laboratory_tooth_number[index] == null) {
              rel = 2;
              
            } 
            // alert(this.laboratory_data.laboratory_tooth_procedure[index])
            if(this.laboratory_data.laboratory_tooth_procedure[index] != "" && this.laboratory_data.laboratory_tooth_issue[index] == '' || this.laboratory_data.laboratory_tooth_issue[index] == null) 
            {
              document.getElementById("laboratory_issue"+index).focus();
              rel = 3;
            } 
          }
        });
        if(this.laboratory_data.current_procedural_code_id.length == 0)
        {
           this.notifier.notify( 'error', 'Please enter atleast one CDT data' );
           return true;
        }
        // if(rel == 2)
        // {
        //   this.notifier.notify( 'error', 'Please select tooth number' );
        //   return true;
        // }
        /*if(rel == 3)
        {
          this.notifier.notify( 'error', 'Please enter notes' );
          return true;
        }*/
    }
    else
    {
      // console.log(this.laboratory_data.laboratory_tooth_index)
      if(this.laboratory_data.current_procedural_code_id.length > 0)
      {
        var rel = 0
        this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
          if(this.laboratory_data.current_procedural_code_id[index] > 0)
          {
            if(this.laboratory_data.laboratory_tooth_number[index] == 0 || this.laboratory_data.laboratory_tooth_number[index] == null) {
              rel = 2;
              
            } 
            if(this.laboratory_data.laboratory_tooth_procedure[index] != '' && this.laboratory_data.laboratory_tooth_issue[index] == '' || this.laboratory_data.laboratory_tooth_issue[index] == null) 
            {
              document.getElementById("laboratory_issue"+index).focus();
              rel = 3;
            } 
          }
        });
        if(rel == 1)
        {
          this.notifier.notify( 'error', 'Please select CPT / CDT Name' );
          return true;
        }
        // if(rel == 2)
        // {
        //   this.notifier.notify( 'error', 'Please select tooth number' );
        //   return true;
        // }
        /*if(rel == 3)
        {
          this.notifier.notify( 'error', 'Please enter notes' );
          return true;
        }*/
      }
    }
    if(Object.keys(this.dental_data.complients).length ==  0)
    {
      this.dental_data.complients = {}
    }
    if(Object.keys(this.dental_data.child_complients).length ==  0)
    {
      this.dental_data.child_complients = {}
    }
    const dental_data = {
      assessment_id: this.assessment_id,
      patient_id: this.patient_id,
      user_id : this.user_data.user_id,
      dental_complaint_id : this.dental_data.dental_complaint_id,
      child_complients : this.dental_data.child_complients,
      complients: this.dental_data.complients,
      consultation_id: this.dental_data.consultation_id,
      patient_type : this.dental_data.patient_type,
      client_date : this.formatDateTime(this.todaysDate),
      date :defaultDateTime(),
      timeZone : getTimeZone()
  };
    const laboratory_data = {
      assessment_id: this.assessment_id,
      patient_id: this.patient_id,
      user_id : this.user_data.user_id,
      lab_investigation_id : this.lab_investigation_id,
      current_procedural_code_id_arr : this.laboratory_data.current_procedural_code_id,
      description_arr: this.laboratory_data.laboratory_description,
      rate_arr: this.laboratory_data.laboratory_rate,
      remarks_arr : this.laboratory_data.laboratory_tooth_issue,
      quantity_arr : this.laboratory_data.laboratory_quantity,
      tooth_number : this.laboratory_data.laboratory_tooth_number,
      tooth_index : this.laboratory_data.laboratory_tooth_index,
      tooth_procedure : this.laboratory_data.laboratory_tooth_procedure,
      client_date : this.formatDateTime(this.todaysDate),
      instruction_to_cashier : this.laboratory_data.laboratory_instruction,
      date :defaultDateTime(),
      timeZone : getTimeZone()
  };
    const postData = 
    {
      dental_data : dental_data,
      dental_investigative : laboratory_data
    }
    this.loaderService.display(true);
    this.rest2.saveDentalComplaints(postData).subscribe((result) => {
      this.loaderService.display(false);
      window.scrollTo(0, 0)
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if(result['status'] == 'Success')
      {
        
        this.notifier.notify( 'success',result['msg'] );
        this.getDentalComplaints();
        this.getDentalInvestigation();
      } else {
        this.notifier.notify( 'error',result['msg']);
      }
    }
  )}
  public getDentalComplaints() {
    const post3Data = {
      assessment_id : this.assessment_id,
      patient_id : this.patient_id
    };
    this.loaderService.display(true);
    this.rest2.getDentalComplaints(post3Data).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.clear_dental()
        this.dental_list = result['data'];
        this.dental_data.dental_complaint_id = this.dental_list.DENTAL_COMPLAINT_ID
        var i =0
        if(this.dental_list.PATIENT_TYPE == 1)
        {
          // this.alerdyExsits = result['data'].alerdyExsits
          // this.alerdyExsits_Implant = result['data'].alerdyExsits_Implant
          this.dental_data.patient_type = this.dental_list.PATIENT_TYPE
      
          var i=0;
          for(let val of this.dental_list.DENTAL_COMPLAINT)
          {
            if (val.TOOTH_INDEX in this.dental_data.complients) 
            {
              const index = this.dental_data.complients[val.TOOTH_INDEX].findIndex(complient=> complient.procedure === val.PROCEDURE);
              if(index > -1)
              {
                this.dental_data.complients[val.TOOTH_INDEX][index].description =  val.DESCRIPTION
                this.dental_data.complients[val.TOOTH_INDEX][index].tooth_index =  val.TOOTH_INDEX
                this.dental_data.complients[val.TOOTH_INDEX][index].procedure = val.PROCEDURE
                this.dental_data.complients[val.TOOTH_INDEX][index].color = val.PROCEDURE_COLOR
                this.dental_data.complients[val.TOOTH_INDEX][index].tooth_number = val.TOOTH_NUMBER
                this.dental_data.complients[val.TOOTH_INDEX][index].procedure_id = val.PROCEDURE_ID
                this.dental_data.complients[val.TOOTH_INDEX][index].date = val.CREATEDATE
              }
              else
              {
                var data = {
                  tooth_index: val.TOOTH_INDEX,
                  procedure: val.PROCEDURE,
                  procedure_id : val.PROCEDURE_ID,
                  description: val.DESCRIPTION,
                  color: val.PROCEDURE_COLOR,
                  tooth_number : val.TOOTH_NUMBER,
                  allias_data : '',
                  alliasname :'',
                  cptcode : '',
                  cpt_description : '',
                  cdtcode_id : '',
                  quantity : '',
                  date : val.CREATEDATE
                }
                this.dental_data.complients[val.TOOTH_INDEX].push(data);
              }
            }
            else
            {
              var data1 = {
                tooth_index: val.TOOTH_INDEX,
                procedure: val.PROCEDURE,
                procedure_id : val.PROCEDURE_ID,
                description: val.DESCRIPTION,
                color: val.PROCEDURE_COLOR,
                tooth_number : val.TOOTH_NUMBER,
                allias_data : '',
                alliasname :'',
                cptcode : '',
                cpt_description : '',
                cdtcode_id : '',
                quantity : '',
                date : val.CREATEDATE
              }
              this.dental_data.complients[val.TOOTH_INDEX] = [data1];
            }
            i=i+1
          }
          // console.log(this.dental_data.complients)
          // var j=0
          // for(let tooth of this.dental)
          // {
          //   for(let data of this.alerdyExsits)
          //   {
          //     if(data.TOOTH_NUMBER == tooth.universal)
          //     {
          //       this.dental[j].alreadyExist = 1
          //       this.dental[j].procedure = 'Procedure  :  '+data.PROCEDURE+ '\nNote  :  '+data.DESCRIPTION
          //     }
          //   }
          //   j=j+1
          // }
        }
        if(this.dental_list.PATIENT_TYPE == 2)
        {
          // this.alerdyExsits_child = result['data'].alerdyExsits_child
          // this.alerdyExsits_Implant_child = result['data'].alerdyExsits_Implant_child
          this.dental_data.patient_type = this.dental_list.PATIENT_TYPE
      
          var i=0;
          for(let val of this.dental_list.DENTAL_COMPLAINT)
          {
            if (val.TOOTH_INDEX in this.dental_data.child_complients) 
            {
              const index = this.dental_data.child_complients[val.TOOTH_INDEX].findIndex(complient=> complient.procedure === val.PROCEDURE);
              if(index > -1)
              {
                this.dental_data.child_complients[val.TOOTH_INDEX][index].description =  val.DESCRIPTION
                this.dental_data.child_complients[val.TOOTH_INDEX][index].tooth_index =  val.TOOTH_INDEX
                this.dental_data.child_complients[val.TOOTH_INDEX][index].procedure = val.PROCEDURE
                this.dental_data.child_complients[val.TOOTH_INDEX][index].color = val.PROCEDURE_COLOR
                this.dental_data.child_complients[val.TOOTH_INDEX][index].tooth_number = val.TOOTH_NUMBER
                this.dental_data.child_complients[val.TOOTH_INDEX][index].procedure_id = val.PROCEDURE_ID
                this.dental_data.child_complients[val.TOOTH_INDEX][index].date = val.CREATEDATE

              }
              else
              {
                var data = {
                  tooth_index: val.TOOTH_INDEX,
                  procedure: val.PROCEDURE,
                  procedure_id : val.PROCEDURE_ID,
                  description: val.DESCRIPTION,
                  color: val.PROCEDURE_COLOR,
                  tooth_number : val.TOOTH_NUMBER,
                  allias_data : '',
                  alliasname :'',
                  cptcode : '',
                  cpt_description : '',
                  cdtcode_id : '',
                  quantity : '',
                  date : val.CREATEDATE
                }
                this.dental_data.child_complients[val.TOOTH_INDEX].push(data);
              }
            }
            else
            {
              var data1 = {
                tooth_index: val.TOOTH_INDEX,
                procedure: val.PROCEDURE,
                procedure_id : val.PROCEDURE_ID,
                description: val.DESCRIPTION,
                color: val.PROCEDURE_COLOR,
                tooth_number : val.TOOTH_NUMBER,
                allias_data : '',
                alliasname :'',
                cptcode : '',
                cpt_description : '',
                cdtcode_id : '',
                quantity : '',
                date : val.CREATEDATE
              }
              this.dental_data.child_complients[val.TOOTH_INDEX] = [data1];
            }
            i=i+1
          }
          // var j=0
          // for(let tooth of this.dental_child)
          // {
          //   for(let data of this.alerdyExsits_child)
          //   {
          //     if(data.TOOTH_NUMBER == tooth.value)
          //     {
          //       this.dental_child[j].alreadyExist = 1
          //       this.dental_child[j].procedure = data.TOOTH_PROCEDURE
          //     }
          //   }
          //   j=j+1
          // }
          //  console.log(this.dental_child)
        }
        for(let val of this.dental)
        {
          // val['alreadyExist'] = 0
          val['color'] = ''
          val['tooth_issue'] = ''
          // val['check'] = 0
        }
        for(let val of this.dental_child)
        {
          // val['alreadyExist'] = 0 
          val['color'] = ''
          val['tooth_issue'] = ''
          // val['check'] = 0
        }
      
      }
      else 
      {
        this.clear();
        this.clear_dental()
      }
      this.getDentalInvestigation()
      this.listNotAllowedProcedure()
    });
  } 
  public notify()
  {
    this.notifier.notify("error","Not allowed to further procedure on this teeth")
  }
  public listNotAllowedProcedure() {
    const post3Data = {
      assessment_id : this.assessment_id,
      patient_id : this.patient_id
    };
    this.loaderService.display(true);
    this.rest2.listNotAllowedProcedure(post3Data).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.alerdyExsits = result['data'].alerdyExsits
        var i=0
        for(let tooth of this.dental)
        {
          for(let data of this.alerdyExsits)
          {
            if(data.TOOTH_NUMBER == tooth.universal)
            {
              this.dental[i].alreadyExist = 1
              this.dental[i].procedure = 'Procedure  :  '+data.PROCEDURE+ '\nNote  :  '+data.DESCRIPTION
            }
          }
          i=i+1
        }
        this.alerdyExsits_child = result['data'].alerdyExsits_child
      
        var j=0
        for(let tooth of this.dental_child)
        {
          for(let data of this.alerdyExsits_child)
          {
            if(data.TOOTH_NUMBER == tooth.value)
            {
              this.dental_child[j].alreadyExist = 1
              this.dental_child[j].procedure = 'Procedure  :  '+data.PROCEDURE+ '\nNote  :  '+data.DESCRIPTION
            }
          }
          j=j+1
        }
        // console.log(this.dental_child)
      }
      else 
      {
        this.clear();
        this.clear_dental()
      }
    });
  } 
  public clear_dental()
  {
    this.dental_data.dental_complaint_id = 0;
    this.dental_data.universal = []
    this.dental_data.palmer = []
    this.dental_data.FDI = []
    this.dental_data.ID = []
    this.dental_data.client_date =  new Date(),
    this.dental_data.timeZone = ''
    this.dental_data.tooth_issue = []
    this.dental_data.tooth_complaint = []
    this.dental_data.child_complients = {}
    this.dental_data.complients = {}
    this.dental_data.procedure = []
    this.dental_data.child_tooth_value = []
    this.dental_data.child_tooth_number = []
    this.dental_data.color = []
    this.dental_data.tooth_data = []
    this.dental_data.patient_type = 1
    this.dental_data.multi_cdt_code = ""
    this.dental_data.multi_cdt_data = []
    this.dental_data.multi_cdt_quantity = 1
    this.dental_data.multi_dental_cdt_id = ""
    this.dental_data.multi_tooth_index = ""
    this.dental_data.multi_tooth_issue = ""
    this.multi_tooth_data = []
    this.multi_tooth_index = []
    this.multi_tooth_number = []
  }
  public set_item($event, i = 0) {
    const item = $event.item;
    this.laboratory_data.current_procedural_code_id_arr[i] = item.CURRENT_PROCEDURAL_CODE_ID;
    this.getCpt(item, i);
   }
   public setCPDitem($event) {
    const item = $event.item;
    this.dental_data.dental_cdt_id[this.tooth_index] = item.CURRENT_PROCEDURAL_CODE_ID;
    this.dental_data.dental_cdt_data[this.tooth_index] = item;
    this.dental_data.dental_cdt_code[this.tooth_index] = item.PROCEDURE_CODE;
    this.dental_data.dental_cdt_quantity[this.tooth_index] = 1;
    
   }
   getCPTdata()
   {
    //  console.log(this.cdt_data)
     if(this.cdt_data)
     {
       this.dental_data.dental_cdt_id[this.tooth_index] = this.cdt_data.CURRENT_PROCEDURAL_CODE_ID
       this.dental_data.dental_cdt_code[this.tooth_index] = this.cdt_data.PROCEDURE_CODE
       this.dental_data.dental_cdt_quantity[this.tooth_index] = 1
       this.dental_data.dental_cdt_data[this.tooth_index] = this.cdt_data
     }
   }
   getmultiCPTdata()
   {
    //  console.log(this.cdt_data)
     if(this.cdt_data)
     {
       this.dental_data.multi_dental_cdt_id = this.cdt_data.CURRENT_PROCEDURAL_CODE_ID
       this.dental_data.multi_cdt_code = this.cdt_data.PROCEDURE_CODE
       this.dental_data.multi_cdt_quantity = 1
       this.dental_data.multi_cdt_data = this.cdt_data
     }
   }
   cdt_config = {
    displayKey:"PROCEDURE_CODE_NAME", //if objects array passed which key to be displayed defaults to description
    search:true, //true/false for the search functionlity defaults to false,
    height: '250px', //height of the list so that if there are more no of items it can show a scroll defaults to auto. With auto height scroll will never appear
    placeholder:'CDT code / CDT allias name / CDT description...', // text to be displayed when no item is selected defaults to Select,
   // customComparator: ()=>{}, // a custom function using which user wants to sort the items. default is undefined and Array.sort() will be used in that case,
    // limitTo: 10, // a number thats limits the no of options displayed in the UI similar to angular's limitTo pipe
    moreText: '.........', // text to be displayed whenmore than one items are selected like Option 1 + 5 more
    noResultsFound: 'No results found!', // text to be displayed when no items are found while searching
    searchPlaceholder:'Search CDT' ,// label thats displayed in search input,
    searchOnKey: 'PROCEDURE_CODE_NAME' // key on which search should be performed this will be selective search. if undefined this will be extensive search on all keys
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
          this.laboratory_data.current_procedural_code_id[i] = cptlist_data.CURRENT_PROCEDURAL_CODE_ID;
          this.laboratory_data.laboratory_rate[i] = cptlist_data.CPT_RATE;
          this.laboratory_data.laboratory_quantity[i] = 1;
          // this.laboratory_data.laboratory_tooth_number[i] = 0;
          this.laboratory_data.laboratory_tooth_index[i] = -1;
          this.laboratory_data.laboratory_tooth_issue[i] = '';
          this.laboratory_data.laboratory_tooth_procedure[i] = ''
          this.addDrugrow(i)
          // const index = this.dental_data.complients[this.tooth_index].findIndex(complient=> complient.procedure === this.procedure);
          // if(index > -1)
          // {
          //   this.dental_data.complients[this.tooth_index][index].allias_data = cptlist_data
          //   this.dental_data.complients[this.tooth_index][index].alliasname = cptlist_data.PROCEDURE_CODE_ALIAS_NAME
          //   this.dental_data.complients[this.tooth_index][index].cptcode = cptlist_data.PROCEDURE_CODE
          //   this.dental_data.complients[this.tooth_index][index].cpt_description = cptlist_data.PROCEDURE_CODE_DESCRIPTION
          //   this.dental_data.complients[this.tooth_index][index].cdtcode_id = cptlist_data.CURRENT_PROCEDURAL_CODE_ID
          //   this.dental_data.complients[this.tooth_index][index].quantity = 1  
          // }
         
        
           this.loaderService.display(false);
       } else {
           this.loaderService.display(false);
       }

    });
   
   }
 
   public addDrugrow(index) {
    this.laboratory_data_arr [index + 1] = '';
    this.laboratory_data.laboratory_allias_data[index + 1] = '';
    this.laboratory_data.laboratory_alliasname[index + 1] = '';
    this.laboratory_data.laboratory_cptcode[index + 1] = '';
    this.laboratory_data.laboratory_description[index + 1] = '';
    this.laboratory_data.laboratory_quantity[index + 1] = '1';
    this.laboratory_data.laboratory_rate[index + 1] = '';
    this.laboratory_data.laboratory_remarks[index + 1] = '';
    this.laboratory_data.laboratory_priority[index + 1] = '';
    this.laboratory_data.laboratory_tooth_number[index + 1] = '';
    this.laboratory_data.laboratory_tooth_index[index + 1] = '';
    this.laboratory_data.laboratory_tooth_issue[index + 1] = '';
    this.laboratory_data.laboratory_tooth_procedure[index + 1] = ""
    this.laboratory_data.current_procedural_code_id[index + 1] = "";


  }

  public deleteDrugrow(index) {
    this.laboratory_data_arr.splice(index,1);
    this.laboratory_data.laboratory_allias_data.splice(index,1);
    this.laboratory_data.laboratory_alliasname.splice(index,1);
    this.laboratory_data.laboratory_cptcode.splice(index,1);
    this.laboratory_data.laboratory_description.splice(index,1);
    this.laboratory_data.laboratory_quantity.splice(index,1);
    this.laboratory_data.laboratory_rate.splice(index,1);
    this.laboratory_data.laboratory_remarks.splice(index,1);
    this.laboratory_data.laboratory_priority.splice(index,1);
    this.laboratory_data.laboratory_tooth_number.splice(index,1);
    this.laboratory_data.laboratory_tooth_index.splice(index,1);
    this.laboratory_data.laboratory_tooth_issue.splice(index,1);
    this.laboratory_data.laboratory_tooth_procedure.splice(index,1);
    this.laboratory_data.current_procedural_code_id.splice(index,1);
    

  }

  public getCurrentDentalByDentalCode() {
    
      const postData = {
        dental_code : 'D9311'
      };
      this.loaderService.display(true);
      window.scrollTo(0, 0);
      this.rest.getCurrentDentalByDentalCode(postData).subscribe((result: {}) => {
        this.loaderService.display(false);
        if (result['status'] === 'Success') {
          const cpt = result['data'];
          // console.log(cpt)
         
            if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
            {
              this.cpt_add_data.alliasname = cpt.PROCEDURE_CODE_ALIAS_NAME;
              this.cpt_add_data.cptcode = cpt.PROCEDURE_CODE;
              this.cpt_add_data.description = cpt.PROCEDURE_CODE_DESCRIPTION;
              this.cpt_add_data.cptcode_id = cpt.CURRENT_PROCEDURAL_CODE_ID;
              this.cpt_add_data.rate = cpt.CPT_RATE;
              this.cpt_add_data.cpt_data[0] = cpt;
              this.cpt_add_data.current_procedural_code_id = cpt.CURRENT_PROCEDURAL_CODE_ID;
              this.cpt_add_data.quantity = "1";
            }
            // if(this.laboratory_data.current_procedural_code_id["1"] == '' || this.laboratory_data.current_procedural_code_id["1"] == null)
            // {
            //   this.addDrugrow(0);
            // }
        } else {
            this.loaderService.display(false);
        }
      });
    }
    public saveDentalInvestigation() {
      var rel = 0
      this.laboratory_data.current_procedural_code_id.forEach((item, index) => {
          if (this.laboratory_data.current_procedural_code_id[index] == '' || this.laboratory_data.current_procedural_code_id[index] == null) {
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
          current_procedural_code_id_arr : this.laboratory_data.current_procedural_code_id,
          description_arr: this.laboratory_data.laboratory_description,
          rate_arr: this.laboratory_data.laboratory_rate,
          remarks_arr : this.laboratory_data.laboratory_tooth_issue,
          quantity_arr : this.laboratory_data.laboratory_quantity,
          tooth_number : this.laboratory_data.laboratory_tooth_number,
          tooth_index : this.laboratory_data.laboratory_tooth_index,
          client_date : this.formatDateTime(this.todaysDate),
          date :defaultDateTime(),
          timeZone : getTimeZone()
      };
    this.loaderService.display(true);
      this.rest1.saveDentalInvestigation(postData).subscribe((result) => {
        this.loaderService.display(false);
        window.scrollTo(0, 0);
        this.save_notify = 2
        this.saveNotify.emit(this.save_notify)
        if (result['status'] === 'Success') {
          this.lab_investigation_id = result['data_id']
          this.notifier.notify( 'success', 'Investigative Procedure details saved successfully..!');
        } else {
          this.notifier.notify( 'error', ' Failed' );
        }
      });
    }
  
  
   }
   public getDentalInvestigation() {
    const postData = {
    patient_id : this.patient_id,
    assessment_id : this.assessment_id,
  };
  this.loaderService.display(true);
  
  this.rest1.getDentalInvestigation(postData).subscribe((result) => {
    if (result.status === 'Success') {
    this.loaderService.display(false);
    this.lab_investigation_id = result.data.LAB_INVESTIGATION_ID;
    let i = 0;
    const lab_id_array = [];
    const num_rows = [];
    const laboratory_data_arr = [];
    this.laboratory_data.laboratory_instruction = result.data.INSTRUCTION_TO_CASHIER
      for (const val of result.data.LAB_INVESTIGATION_DETAILS) {
        laboratory_data_arr.push(i);
        const temp = [];
        lab_id_array[i] = temp;
        num_rows[i] = i;
        this.laboratory_data.laboratory_allias_data[i] =  val;
        this.laboratory_data.laboratory_alliasname[i] =  val.PROCEDURE_CODE_NAME;
        this.laboratory_data.current_procedural_code_id[i] = val.CURRENT_PROCEDURAL_CODE_ID
        this.laboratory_data.laboratory_cptcode[i] = val.PROCEDURE_CODE;
        this.laboratory_data.laboratory_description[i] = val.DESCRIPTION;
        this.laboratory_data.laboratory_quantity[i] = val.QUANTITY;
        this.laboratory_data.laboratory_rate[i] = val.RATE;
        this.laboratory_data.laboratory_tooth_issue[i] = val.REMARKS;
        this.laboratory_data.laboratory_tooth_number[i] = val.TOOTH_NUMBER
        this.laboratory_data.laboratory_tooth_index[i] = val.TOOTH_INDEX
        this.laboratory_data.laboratory_tooth_procedure[i] = val.TOOTH_PROCEDURE
        if(this.dental_data.patient_type == 1)
        {
          if(val.TOOTH_INDEX > -1)
          {
            if(this.dental_data.complients[val.TOOTH_INDEX])
            {
              const index = this.dental_data.complients[val.TOOTH_INDEX].findIndex(complient=> complient.procedure === val.TOOTH_PROCEDURE);
              if(index > -1)
              {
                this.dental_data.complients[val.TOOTH_INDEX][index].allias_data = val
                this.dental_data.complients[val.TOOTH_INDEX][index].alliasname = val.PROCEDURE_CODE_NAME
                this.dental_data.complients[val.TOOTH_INDEX][index].cptcode = val.PROCEDURE_CODE
                this.dental_data.complients[val.TOOTH_INDEX][index].cpt_description =  val.DESCRIPTION;
                this.dental_data.complients[val.TOOTH_INDEX][index].cdtcode_id = val.CURRENT_PROCEDURAL_CODE_ID
                this.dental_data.complients[val.TOOTH_INDEX][index].quantity = val.QUANTITY;
              }
            }
            
          }
        }
        if(this.dental_data.patient_type == 2)
        {
          if(val.TOOTH_INDEX > -1)
          {
            if(this.dental_data.child_complients[val.TOOTH_INDEX])
            {
              const index = this.dental_data.child_complients[val.TOOTH_INDEX].findIndex(complient=> complient.procedure === val.TOOTH_PROCEDURE);
              if(index > -1)
              {
                this.dental_data.child_complients[val.TOOTH_INDEX][index].allias_data = val
                this.dental_data.child_complients[val.TOOTH_INDEX][index].alliasname = val.PROCEDURE_CODE_NAME
                this.dental_data.child_complients[val.TOOTH_INDEX][index].cptcode = val.PROCEDURE_CODE
                this.dental_data.child_complients[val.TOOTH_INDEX][index].cpt_description =  val.DESCRIPTION;
                this.dental_data.child_complients[val.TOOTH_INDEX][index].cdtcode_id = val.CURRENT_PROCEDURAL_CODE_ID
                this.dental_data.child_complients[val.TOOTH_INDEX][index].quantity = val.QUANTITY;
              }
            }
          }
        }
        
        i = i + 1;
        
      }
      this.laboratory_data_arr = laboratory_data_arr
      this.addDrugrow(laboratory_data_arr.length - 1)
      


      } else {
        this.loaderService.display(false);
        this. laboratory_data = {
          cptcode : [],
          patient_allergies_id: 0,
          patient_id: 0,
          assessment_id: 0,
          patient_no_known_allergies: 0,
          laboratory_description: [],
          laboratory_allias_data: [],
          laboratory_alliasname: [''],
          laboratory_cptname: [''],
          laboratory_cptcode: [],
          laboratory_quantity: [],
          laboratory_rate: [],
          laboratory_remarks: [''],
          laboratory_priority: [''],
          laboratory_billedamount : 0,
          laboratory_un_billedamount : 0,
          laboratory_instruction : '',
          current_procedural_code_id_arr:[],
          current_procedural_code_id: [],
          laboratory_tooth_number : [],
          laboratory_tooth_issue : [],
          laboratory_tooth_index : [],
          laboratory_tooth_procedure : [],
          user_id : ''
        };
        this.lab_investigation_id = 0;
       this.addDrugrow(-1)
    }
    }, (err) => {
     // console.log(err);
  });
 }
public changeToothData(i)
{
  //console.log(this.laboratory_data.laboratory_tooth_index[i])
  if(this.laboratory_data.laboratory_tooth_index[i] != '')
  {
    if(this.dental_data.patient_type == 1)
    {
      if(this.laboratory_data.laboratory_tooth_index[i] in this.dental_data.complients) {
        this.dental_data.tooth_issue[this.laboratory_data.laboratory_tooth_index[i]] = '';
        const index = this.dental_data.complients[this.laboratory_data.laboratory_tooth_index[i]].findIndex(complient=> complient.procedure === this.laboratory_data.laboratory_tooth_procedure[i]);
        // console.log(index);
        if(index > -1)
        {
          this.dental_data.complients[this.laboratory_data.laboratory_tooth_index[i]][index].description = this.laboratory_data.laboratory_tooth_issue[i]
        }
      }
    }
    if(this.dental_data.patient_type == 2)
    {
      if(this.laboratory_data.laboratory_tooth_index[i] in this.dental_data.child_complients) {
        this.dental_data.tooth_issue[this.laboratory_data.laboratory_tooth_index[i]] = '';
        const index = this.dental_data.child_complients[this.laboratory_data.laboratory_tooth_index[i]].findIndex(complient=> complient.procedure === this.laboratory_data.laboratory_tooth_procedure[i]);
        if(index > -1)
        {
          this.dental_data.child_complients[this.laboratory_data.laboratory_tooth_index[i]][index].description = this.laboratory_data.laboratory_tooth_issue[i]
        }
      }
    }
  }
}
public changeProData(data,procedure)
{
  //console.log(data)
  if(this.dental_data.patient_type == 1)
  {
    this.laboratory_data.laboratory_tooth_index.forEach((item, i) => {
      if(this.laboratory_data.laboratory_tooth_procedure[i] == procedure && this.laboratory_data.laboratory_tooth_index[i] == this.tooth_index) 
      {
        this.laboratory_data.laboratory_tooth_issue[i] = data
      } 
    });
  }
  if(this.dental_data.patient_type == 2)
  {
    this.laboratory_data.laboratory_tooth_index.forEach((item, i) => {
      if(this.laboratory_data.laboratory_tooth_procedure[i] == procedure && this.laboratory_data.laboratory_tooth_index[i] == this.tooth_index) 
      {
        this.laboratory_data.laboratory_tooth_issue[i] = data
      } 
    });
  }
}
 public deleteCdtProcedure(i)
 {
   //console.log(this.laboratory_data.laboratory_tooth_index[i])
  if(this.dental_data.patient_type == 1)
    {
      if(this.laboratory_data.laboratory_tooth_index[i] != null && Object.keys(this.dental_data.complients).length > 0 && this.dental_data.complients[parseInt(this.laboratory_data.laboratory_tooth_index[i])])
      {
        const tooth_index = parseInt(this.laboratory_data.laboratory_tooth_index[i])
        const index = this.dental_data.complients[tooth_index].findIndex(complient=> complient.procedure === this.laboratory_data.laboratory_tooth_procedure[i]);
        if(index > -1)
        {
          this.dental_data.complients[tooth_index].splice(index, 1);
        }
        this.notifier.notify('warning','Selected procedure is removed')
        this.getEvents()
      }
        
    }
    if(this.dental_data.patient_type == 2)
    {
      if(this.laboratory_data.laboratory_tooth_index[i] != null && Object.keys(this.dental_data.child_complients).length > 0 && this.dental_data.child_complients[parseInt(this.laboratory_data.laboratory_tooth_index[i])])
      {
        const tooth_index = parseInt(this.laboratory_data.laboratory_tooth_index[i])
        const index = this.dental_data.child_complients[tooth_index].findIndex(complient=> complient.procedure === this.laboratory_data.laboratory_tooth_procedure[i]);
        if(index > -1)
        {
          this.dental_data.child_complients[tooth_index].splice(index, 1);
        }
        this.notifier.notify('warning','Selected procedure is removed')
        this.getEvents()
      }
     
    }
 }
 cptsearch = (text$: Observable<string>) =>
 text$.pipe(
   debounceTime(500),
    distinctUntilChanged(),

    tap(() => this.searching = true),
    switchMap(term =>
     this.rest2.cptDentalsearch(term,37,0).pipe(

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



