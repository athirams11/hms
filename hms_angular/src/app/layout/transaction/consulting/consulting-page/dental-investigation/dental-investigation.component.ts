import { Component, OnInit,Input,NgModule,ViewChild,Output,EventEmitter, SimpleChanges, OnChanges, ɵConsole } from '@angular/core';
import { ConsultingService,NursingAssesmentService, DoctorsService} from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import {DatePipe, CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import * as moment from 'moment';
import { LoaderService } from '../../../../../shared';
import { ModalDismissReasons, NgbModal, NgbModalOptions, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { Observable, of } from 'rxjs';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { AppSettings } from 'src/app/app.settings';
@Component({
  selector: 'app-dental-investigation',
  templateUrl: './dental-investigation.component.html',
  styleUrls: ['./dental-investigation.component.scss']
})
export class DentalInvestigationComponent implements OnInit , OnChanges {
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
  public value: number = 0;
  public isDisabled: boolean;
  public loading = false;
  public notifier: NotifierService;
  public user_rights : any ={};
  public user_data : any ={};
  public change: string = '';
  public closeResult: string;
  public cdt_index: number = 0;
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
  public institution = JSON.parse(localStorage.getItem('institution'));
  public logo_path = JSON.parse(localStorage.getItem('logo_path'));
  public settings = AppSettings;
  public tooth_issue = ' ';
  public tooth_arr: string;
  public tooth_index = '';
  public dental_list: any;
  public procedure = '';
  public tooth_color = '';
  public tooth_data: any;
  public patient_type = 1;
  public status = false;
  public doctor_department: any;
  public department = 0;
  public dental: { id: number, label: string,  universal :number,palmer :number,value: number,alreadyExist:number,image:string,tooth_issue:string,procedure:string,color:string,check:number}[] = [
    { "id": 1, "label": "tooth_11" ,"universal": 1, palmer: 8, value: 11,alreadyExist:0, "image": "assets/images/1.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 2, "label": "tooth_12" ,"universal": 2, palmer: 7, value: 12,alreadyExist:0, "image": "assets/images/2.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 3, "label": "tooth_13" ,"universal": 3, palmer: 6, value: 13,alreadyExist:0, "image": "assets/images/3.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 4, "label": "tooth_14" ,"universal": 4, palmer: 5, value: 14,alreadyExist:0, "image": "assets/images/4.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 5, "label": "tooth_15" ,"universal": 5, palmer: 4, value: 15,alreadyExist:0, "image": "assets/images/5.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 6, "label": "tooth_16" ,"universal": 6, palmer: 3, value: 16,alreadyExist:0, "image": "assets/images/6.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 7, "label": "tooth_17" ,"universal": 7, palmer: 2, value: 17,alreadyExist:0, "image": "assets/images/7.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 8, "label": "tooth_18" ,"universal": 8, palmer: 1, value: 18,alreadyExist:0, "image": "assets/images/8.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 9, "label": "tooth_21"  ,"universal": 9,  palmer: 1, value: 21,alreadyExist:0, "image": "assets/images/9.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 10, "label": "tooth_22" ,"universal": 10, palmer: 2, value: 22,alreadyExist:0, "image": "assets/images/10.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 11, "label": "tooth_23" ,"universal": 11, palmer: 3, value: 23,alreadyExist:0, "image": "assets/images/11.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 12, "label": "tooth_24" ,"universal": 12, palmer: 4, value: 24,alreadyExist:0, "image": "assets/images/12.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 13, "label": "tooth_25" ,"universal": 13, palmer: 5, value: 25,alreadyExist:0, "image": "assets/images/13.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 14, "label": "tooth_26" ,"universal": 14, palmer: 6, value: 26,alreadyExist:0, "image": "assets/images/14.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 15, "label": "tooth_27" ,"universal": 15, palmer: 7, value: 27,alreadyExist:0, "image": "assets/images/15.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 16, "label": "tooth_28" ,"universal": 16, palmer: 8, value: 28,alreadyExist:0, "image": "assets/images/16.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 17, "label": "tooth_48" ,"universal": 32,  palmer: 8, value: 48,alreadyExist:0, "image": "assets/images/32.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 18, "label": "tooth_47" ,"universal": 31,  palmer: 7, value: 47,alreadyExist:0, "image": "assets/images/31.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 19, "label": "tooth_46" ,"universal": 30,  palmer: 6, value: 46,alreadyExist:0, "image": "assets/images/30.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 20, "label": "tooth_45" ,"universal": 29,  palmer: 5, value: 45,alreadyExist:0, "image": "assets/images/29.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 21, "label": "tooth_44" ,"universal": 28,  palmer: 4, value: 44,alreadyExist:0, "image": "assets/images/28.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 22, "label": "tooth_43" ,"universal": 27,  palmer: 3, value: 43,alreadyExist:0, "image": "assets/images/27.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 23, "label": "tooth_42" ,"universal": 26,  palmer: 2, value: 42,alreadyExist:0, "image": "assets/images/26.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 24, "label": "tooth_41" ,"universal": 25,  palmer: 1, value: 41,alreadyExist:0, "image": "assets/images/25.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 25, "label": "tooth_38" ,"universal": 24,  palmer: 1, value: 31,alreadyExist:0, "image": "assets/images/24.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 26, "label": "tooth_37" ,"universal": 23,  palmer: 2, value: 32,alreadyExist:0, "image": "assets/images/23.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 27, "label": "tooth_36" ,"universal": 22,  palmer: 3, value: 33,alreadyExist:0, "image": "assets/images/22.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 28, "label": "tooth_35" ,"universal": 21,  palmer: 4, value: 34,alreadyExist:0, "image": "assets/images/21.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 29, "label": "tooth_34" ,"universal": 20,  palmer: 5, value: 35,alreadyExist:0, "image": "assets/images/20.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 30, "label": "tooth_33" ,"universal": 19,  palmer: 6, value: 36,alreadyExist:0, "image": "assets/images/19.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 31, "label": "tooth_32","universal":  18,  palmer: 7, value: 37,alreadyExist:0, "image": "assets/images/18.png", "tooth_issue": "", "procedure":"","color":"",check:0},
    { "id": 32, "label": "tooth_31" ,"universal": 17,  palmer: 8, value: 38,alreadyExist:0, "image": "assets/images/17.png", "tooth_issue": "", "procedure":"","color":"",check:0},
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
  public tooth_number: number;

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
  public data_copy: boolean = false;
  private modalRef: NgbModalRef;
  public current_procedural_code_id: any[];
  public bill_status: any;
  public model: any;
  public searching = false;
  public searchFailed = false;
  public laboratory_data_arr: any = [];
  public lab_investigation_id: any;
  public procedure_id: number = 0;
  public cdt_list: any;
  public cdt_options: any;
  public cdt_data: any = [];
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
  constructor(private modalService: NgbModal,
    private loaderService : LoaderService,
    public datepipe: DatePipe,private router: Router, 
    public rest2:ConsultingService,public rest:NursingAssesmentService,
    notifierService: NotifierService,public rest1: DoctorsService
  )
  {
    this.notifier = notifierService;
  }
  ngOnInit() {
    this.getDentalHistory();
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
  }
  ngOnChanges(changes: SimpleChanges) {
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.getDentalHistory(); 
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
  downloadFile() {
    const post3Data = {
      assessment_id : 0,
      patient_id : this.patient_id
    };
    this.loaderService.display(true);
    this.rest2.downloadDentalHistoryPdf(post3Data).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        const FileSaver = require('file-saver');
        //  window.open(this.settings.API_ENDPOINT+result.data);
        const pdfUrl = this.settings.API_ENDPOINT+result.data;
        const pdfName = result.filename;
        FileSaver.saveAs(pdfUrl, pdfName);
      }
    })
  }
  public notify()
  {
    this.notifier.notify("error","Not allowed to further procedure on this teeth")
  }
  public isEmptyObject(obj) {
    
    return Object.keys(obj).length;
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
    this.modalRef = this.modalService.open(content,{ariaLabelledBy: 'modal-basic-title',size:'lg'});
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
  // this.dental_data.patient_type = val;
    this.patient_type = val;

  }
  showConfirm(e: any,confirm) {
    if(Object.keys(this.dental_data.complients).length > 0 || Object.keys(this.dental_data.child_complients).length > 0)
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
  getPopup(i,data,toothDescription,listDescription,multiselectiontooth)
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
            this.dental_data.tooth_issue[this.tooth_index] = this.dental_data.complients[this.tooth_index][index].description
            
          }
          else
          {
            // console.log("else")
            // console.log(this.dental_data)
            this.dental_data.tooth_issue[this.tooth_index] = '';
        
            
          }
        }
        this.open(toothDescription)
        this.tooth_number = data.universal

      }
      if(this.dental_data.patient_type == 2)
      {
        if(this.tooth_index in this.dental_data.child_complients) {
          this.dental_data.tooth_issue[this.tooth_index] = '';
          
          const index = this.dental_data.child_complients[this.tooth_index].findIndex(complient=> complient.procedure === this.procedure);
          if(index > -1)
          {
            
            this.dental_data.tooth_issue[this.tooth_index] = this.dental_data.child_complients[this.tooth_index][index].description
            
          }
          else
          {
          
            this.dental_data.tooth_issue[this.tooth_index] = '';
          
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
      this.notifier.notify('warning','Selected procedure is removed')
      if(this.dental_data.child_complients[tooth_index].length == 0)
      {
        if(this.modalRef)
          this.modalRef.close()
      }
      this.getEvents()
    }
  }
  delete(tooth_index,procedure)
  {
    if(this.dental_data.patient_type == 1)
    {
      const index = this.dental_data.complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
      if(index > -1)
      {
        this.dental_data.complients[tooth_index].splice(index, 1);
      }
      this.notifier.notify('warning','Selected procedure is removed')

      this.getEvents()
    }
    if(this.dental_data.patient_type == 2)
    {
      const index = this.dental_data.child_complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
      if(index > -1)
      {
        this.dental_data.child_complients[tooth_index].splice(index, 1);
      }
      this.notifier.notify('warning','Selected procedure is removed')
      this.getEvents()
    }
  }
  editProcedure(tooth_index,data,toothDescription)
  {
      this.procedure = data.procedure;
      this.tooth_color = data.color
      this.procedure_id = data.procedure_id
      this.tooth_number = data.tooth_number
      this.tooth_index = tooth_index
      this.dental_data.tooth_issue[tooth_index] = data.description
      this.tooth_data = data
      this.open(toothDescription)
      
  }
  getPopups(tooth_index,procedure)
  {
    if(this.dental_data.patient_type == 1)
    {
      if(this.dental_data.complients[tooth_index])
      {
        const index = this.dental_data.complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
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
      if(this.dental_data.complients[tooth_index])
      {
        const index = this.dental_data.child_complients[tooth_index].findIndex(complient=> complient.procedure === procedure);
        if(index > -1)
        {
          this.dental_data.child_complients[tooth_index].splice(index, 1);
        }
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
    this.procedure = ''
    this.procedure_id = 0
    this.tooth_color = ''
    this.tooth_index = ''
    this.tooth_data = ''
    this.tooth_number = 0
    this.tooth_issue = ' ';
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
    
    this.getEvents()
    //this.getDentalHistory();
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
  selectProcedure(data)
  {
    if(this.procedure != data.procedures){

      this.procedure = data.procedures;
      this.tooth_color = data.color
      this.procedure_id = data.id
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

   /* if(!this.dental_data.tooth_issue[this.tooth_index])
    {
      this.notifier.notify("error","Please enter the tooth issue")
     
    }*/
    //else{
      if(!this.dental_data.tooth_issue[this.tooth_index])
      {
        this.dental_data.tooth_issue[this.tooth_index] = ""
        // this.open(toothDescription)
      }
      if(this.dental_data.patient_type == 1){
        if (this.tooth_index in this.dental_data.complients) {
            //do stuff with someObject["someKey"]
          const index = this.dental_data.complients[this.tooth_index].findIndex(complient=> complient.procedure === this.procedure);
          if(index > -1)
          {
            this.dental_data.complients[this.tooth_index][index].description = this.dental_data.tooth_issue[this.tooth_index]
            
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
              date : this.formatDate(new Date())
            }
            
              this.dental_data.complients[this.tooth_index].push(data);
            
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
            date : this.formatDate(new Date())
          };
          this.dental_data.complients[this.tooth_index] = [data1];
          
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
              date : this.formatDate(new Date())
              
            }
            this.dental_data.child_complients[this.tooth_index].push(data);
            
            
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
            date : this.formatDate(new Date())
          };
          this.dental_data.child_complients[this.tooth_index] = [data1];
          
          
        }
      }
      if(this.modalRef)
          this.modalRef.close()
    }
  //}
  public saveDentalHistory()
  {
    if(this.dental_data.dental_complaint_id == 0)
    {
      if(this.dental_data.patient_type == 1)
      {
        if(Object.keys(this.dental_data.complients).length ==  0)
        {
          this.notifier.notify('error','Please select at least one tooth')
          return false
        }
      }
      if(this.dental_data.patient_type == 2)
      {
        if(Object.keys(this.dental_data.child_complients).length ==  0)
        {
          this.notifier.notify('error','Please select at least one tooth')
          return false
        }
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
      assessment_id: 0,
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
    this.loaderService.display(true);
    this.rest2.saveDentalHistory(dental_data).subscribe((result) => {
      this.loaderService.display(false);
      window.scrollTo(0, 0)
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if(result['status'] == 'Success')
      {
        this.notifier.notify( 'success',result['msg'] );
        this.listNotAllowedProcedure()
        this.getDentalHistory();
      } 
      else 
      {
        this.notifier.notify( 'error',result['msg']);
      }
    }
  )}
  public getDentalHistory() {
  
    const post3Data = {
      assessment_id : 0,
      patient_id : this.patient_id
    };
    this.loaderService.display(true);
    this.rest2.getDentalHistory(post3Data).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        this.clear_dental()
        this.dental_list = result['data'];
        this.dental_data.dental_complaint_id = this.dental_list.DENTAL_COMPLAINT_ID
        var i =0
        if(this.dental_list.PATIENT_TYPE == 1)
        {
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
                  date : val.CREATEDATE,
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
                date : val.CREATEDATE,

              }
              this.dental_data.complients[val.TOOTH_INDEX] = [data1];
            }
            i=i+1
          }
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
                date : val.CREATEDATE

              }
              this.dental_data.child_complients[val.TOOTH_INDEX] = [data1];
            }
            i=i+1
          }
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
    });
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
  }

  saveMultiSelectionteeth()
  {
    /*if(!this.dental_data.multi_tooth_issue)
    {
      this.notifier.notify("error","Please enter the tooth issue")
      // this.open(toothDescription)
    }*/
    //else{

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
        }
        
       
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
        }
        
       
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
  //}
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
  
  
  
  