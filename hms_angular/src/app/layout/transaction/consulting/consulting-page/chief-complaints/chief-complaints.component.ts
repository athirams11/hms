import { Component, OnInit,Input,Output,EventEmitter, SimpleChanges, OnChanges } from '@angular/core';
import { ViewChild, ViewContainerRef } from '@angular/core';
import { ConsultingService,NursingAssesmentService} from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../../../shared/class/Utils';
import { AppSettings } from './../../../../../app.settings';
import {DatePipe, CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import { FormGroup, FormControl } from '@angular/forms';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { LoaderService } from '../../../../../shared';
import { connectableObservableDescriptor } from 'rxjs/internal/observable/ConnectableObservable';
import { ModalDismissReasons, NgbModal, NgbModalOptions, NgbTabset } from '@ng-bootstrap/ng-bootstrap';
@Component({
  selector: 'app-chief-complaints',
  templateUrl: './chief-complaints.component.html',
  styleUrls: ['./chief-complaints.component.scss']
})
export class ChiefComplaintsComponent implements OnInit,OnChanges {
  @ViewChild('app_tabs')
  public app_tabs:NgbTabset;
  @ViewChild('appointment_form') af: any;
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
  public complaints: string;
  public summary_of_case: string;
  public instruction_to_nurse: string;
  public past_medical_history: string;
  public drug_history : string;
  public social_history : string;
  public clinical_examination : string;
  public umbilicus : string;
  public chest : string;
  public abdomen : string;
  public history_of_present_illness : string;
  public notes : string;
  public CNS : string;
  public EYE : string;
  public fever :string;
  public CVS :string;
  public complaint_id = 0;
  notifier: NotifierService;
  chief_complaint_form: any = [];
  public complaint_data: any = {};
  public get_complaint_data: any = {};
  public copy_complaint_data: any = {};
  public key_name = 1;
  public key_vale;
  public user_rights : any ={};
  public user_data : any ={};
  public vital_params : any = [];
  public param_values : any = [];
  public vital_form_values : any = [];
  public data_list : any = [];
  public complaint_list : any = [];
  public complaintget_list : any = [];
  current_date : any;
  change: string = '';
  closeResult: string;
  tooth_number: number;
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
    procedure : [],
    child_tooth_value : [],
    child_tooth_number : [],
    color : [],
    dental_complaint_id : [],
    patient_type : 1
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
  start: number;
  limit: number;
  public p = 2;
  public collection:any= '';
  page :number = 1;
  public dental: { id: number, label: string,  universal :number,palmer :number,value: number,check:number,image:string,tooth_issue:string,procedure:string,color:string}[] = [
    { "id": 1, "label": "tooth_11" ,"universal": 1, palmer: 8, value: 11,check:0, "image": "assets/images/1.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 2, "label": "tooth_12" ,"universal": 2, palmer: 7, value: 12,check:0, "image": "assets/images/2.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 3, "label": "tooth_13" ,"universal": 3, palmer: 6, value: 13,check:0, "image": "assets/images/3.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 4, "label": "tooth_14" ,"universal": 4, palmer: 5, value: 14,check:0, "image": "assets/images/4.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 5, "label": "tooth_15" ,"universal": 5, palmer: 4, value: 15,check:0, "image": "assets/images/5.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 6, "label": "tooth_16" ,"universal": 6, palmer: 3, value: 16,check:0, "image": "assets/images/6.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 7, "label": "tooth_17" ,"universal": 7, palmer: 2, value: 17,check:0, "image": "assets/images/7.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 8, "label": "tooth_18" ,"universal": 8, palmer: 1, value: 18,check:0, "image": "assets/images/8.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 9, "label": "tooth_21"  ,"universal": 9,  palmer: 1, value: 21,check:0, "image": "assets/images/9.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 10, "label": "tooth_22" ,"universal": 10, palmer: 2, value: 22,check:0, "image": "assets/images/10.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 11, "label": "tooth_23" ,"universal": 11, palmer: 3, value: 23,check:0, "image": "assets/images/11.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 12, "label": "tooth_24" ,"universal": 12, palmer: 4, value: 24,check:0, "image": "assets/images/12.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 13, "label": "tooth_25" ,"universal": 13, palmer: 5, value: 25,check:0, "image": "assets/images/13.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 14, "label": "tooth_26" ,"universal": 14, palmer: 6, value: 26,check:0, "image": "assets/images/14.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 15, "label": "tooth_27" ,"universal": 15, palmer: 7, value: 27,check:0, "image": "assets/images/15.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 16, "label": "tooth_28" ,"universal": 16, palmer: 8, value: 28,check:0, "image": "assets/images/16.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 17, "label": "tooth_48" ,"universal": 32,  palmer: 8, value: 48,check:0, "image": "assets/images/32.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 18, "label": "tooth_47" ,"universal": 31,  palmer: 7, value: 47,check:0, "image": "assets/images/31.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 19, "label": "tooth_46" ,"universal": 30,  palmer: 6, value: 46,check:0, "image": "assets/images/30.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 20, "label": "tooth_45" ,"universal": 29,  palmer: 5, value: 45,check:0, "image": "assets/images/29.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 21, "label": "tooth_44" ,"universal": 28,  palmer: 4, value: 44,check:0, "image": "assets/images/28.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 22, "label": "tooth_43" ,"universal": 27,  palmer: 3, value: 43,check:0, "image": "assets/images/27.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 23, "label": "tooth_42" ,"universal": 26,  palmer: 2, value: 42,check:0, "image": "assets/images/26.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 24, "label": "tooth_41" ,"universal": 25,  palmer: 1, value: 41,check:0, "image": "assets/images/25.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 25, "label": "tooth_38" ,"universal": 24,  palmer: 1, value: 31,check:0, "image": "assets/images/24.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 26, "label": "tooth_37" ,"universal": 23,  palmer: 2, value: 32,check:0, "image": "assets/images/23.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 27, "label": "tooth_36" ,"universal": 22,  palmer: 3, value: 33,check:0, "image": "assets/images/22.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 28, "label": "tooth_35" ,"universal": 21,  palmer: 4, value: 34,check:0, "image": "assets/images/21.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 29, "label": "tooth_34" ,"universal": 20,  palmer: 5, value: 35,check:0, "image": "assets/images/20.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 30, "label": "tooth_33" ,"universal": 19,  palmer: 6, value: 36,check:0, "image": "assets/images/19.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 31, "label": "tooth_32","universal":  18,  palmer: 7, value: 37,check:0, "image": "assets/images/18.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 32, "label": "tooth_31" ,"universal": 17,  palmer: 8, value: 38,check:0, "image": "assets/images/17.png", "tooth_issue": "", "procedure":"","color":""},
  ];
  public dental_child: { id: number, label: string,  value: number,check:number,image:string,tooth_issue:string,procedure:string,color:string}[] = [
    { "id": 1, "label": "A" , value: 1, check:0, "image": "assets/images/c1.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 2, "label": "B" , value: 2, check:0, "image": "assets/images/c2.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 3, "label": "C" , value: 3, check:0, "image": "assets/images/c3.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 4, "label": "D" , value: 4, check:0, "image": "assets/images/c4.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 5, "label": "E" , value: 5, check:0, "image": "assets/images/c5.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 6, "label": "F" , value: 6, check:0, "image": "assets/images/c6.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 7, "label": "G" , value: 7, check:0, "image": "assets/images/c7.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 8, "label": "H" , value: 8, check:0, "image": "assets/images/c8.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 9, "label": "I" , value: 9, check:0, "image": "assets/images/c9.png" , "tooth_issue": "", "procedure":"","color":""},
    { "id": 10,"label": "J" , value: 10,check:0, "image": "assets/images/c10.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 11,"label": "T" , value: 20,check:0, "image": "assets/images/c20.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 12,"label": "S" , value: 19,check:0, "image": "assets/images/c19.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 13,"label": "R" , value: 18,check:0, "image": "assets/images/c18.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 14,"label": "Q" , value: 17,check:0, "image": "assets/images/c17.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 15,"label": "P" , value: 16,check:0, "image": "assets/images/c16.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 16,"label": "O" , value: 15,check:0, "image": "assets/images/c15.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 17,"label": "N" , value: 14,check:0, "image": "assets/images/c14.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 18,"label": "M" , value: 13,check:0, "image": "assets/images/c13.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 19,"label": "L" , value: 12,check:0, "image": "assets/images/c12.png", "tooth_issue": "", "procedure":"","color":""},
    { "id": 20,"label": "K" , value: 11,check:0, "image": "assets/images/c11.png", "tooth_issue": "", "procedure":"","color":""},
  ];
  public dental_procedure: { id: number, procedures: string, color :string,selected:number }[] = [
    { "id": 1, "procedures": "Decayed" ,"color": "ash",selected:0},
    { "id": 2, "procedures": "Filled" ,"color": "green", selected:0},
    { "id": 3, "procedures": "Crown & Bridge" ,"color": "warning", selected:0},
    { "id": 4, "procedures": "Denture" ,"color": "brown", selected:0},
    { "id": 5, "procedures": "Extraction" ,"color": "orange",selected:0},
    { "id": 6, "procedures": "Impaction" ,"color": "red", selected:0},
    { "id": 7, "procedures": "Missing" ,"color": "lightblue",selected:0},
    { "id": 8, "procedures": "Endodontic" ,"color": "darkorgange", selected:0},
    { "id": 9, "procedures": "Implant" ,"color": "pink", selected:0},
    { "id": 10, "procedures": "Fracture Restoration" ,"color": "skyblue",selected:0},
    { "id": 11, "procedures": "Periodontal" ,"color": "blue", selected:0},
  ];
  accompanied_by_list: any;
  mode_of_arrivel_list: any;
  public assessment_notes: any = [];
  get_status: number = 0;
  data_copy: boolean = false;
  index: any;
  dateVal = ''
  constructor(private modalService: NgbModal,private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:ConsultingService,public rest:NursingAssesmentService,notifierService: NotifierService)
  {
    this.notifier = notifierService;
  }
  ngOnInit() {
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
    if(this.department == 1)
    {
      if(this.app_tabs)
      {
        this.app_tabs.select('app_new_tab')
      }
    }
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.getComplaints();
    this.getDentalComplaints(); 
    this.getAssesmentParameters();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    this.getPreviousComplaints();
    this.formatDaTime ();
    this.getNotesAssesmentValues();
 //  console.log(this.dental_data)
  }
  ngAfterViewChecked(): void {
  } 
  ngAfterViewInit() {
    if(this.department == 1)
    {
      this.switchNgBTab('app_new_tab');
    }
  }

  switchNgBTab(id: string) {
    this.app_tabs.select(id);
  }
  ngOnChanges(changes: SimpleChanges) {
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.getComplaints();
    this.getDentalComplaints(); 
    this.getAssesmentParameters();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    // this.page = 1
    // this.getPreviousComplaints(this.page -1);
    this.getNotesAssesmentValues();

  }
  // public labels: { id: number, label: string, value: string,key_name:string }[] = [
  //   { "id": 1, "label": " Complaints" , value: "0",key_name:"complaints"},
  //   { "id": 2, "label": " Summary of Case", value: "0",key_name:"summary_of_case"},
  //   { "id": 3, "label": " Instruction of Nurse" , value: "0",key_name:"instruction_to_nurse"},
  //   { "id": 4, "label": "Past Medical History" , value: "0",key_name:"past_medical_history"},
  //   { "id": 5, "label": "Drug History" , value: "0",key_name:"drug_history"},
  //   { "id": 6, "label": "Social History" , value: "0",key_name:"social_history"},
  //   { "id": 7, "label": "Clinical examinations" , value: "0",key_name:"clinical_examination"},
  //   { "id": 8, "label": "Umblicus" , value: "0",key_name:"umbilicus"},
  //   { "id": 9, "label": "Chest" , value: "0",key_name:"chest"},
  //   { "id": 10, "label": "Abdomen" , value: "0",key_name:"abdomen"},
  //   { "id": 11, "label": "History of present illness" , value: "0",key_name:"history_of_present_illness"},
  //   { "id": 12, "label": "Notes" , value: "0",key_name:"notes"},
  //   { "id": 13, "label": "CNS" , value: "0",key_name:"CNS"},
  //   { "id": 14, "label": "Eye" , value: "0",key_name:"EYE"},
  //   { "id": 15, "label": "Fever" , value: "0",key_name:"fever"},
  //   { "id": 16, "label": "CVS", value: '0',key_name:"CVS"}
  // ];
  public labels: { id: number, label: string, value: string,key_name:string }[] = [
    { "id": 1, "label": " Complaints" , value: "0",key_name:"complaints"},
    { "id": 2, "label": " Summary of Case", value: "0",key_name:"summary_of_case"},
    { "id": 3, "label": "Past Medical History" , value: "0",key_name:"past_medical_history"},
    { "id": 4, "label": "Drug History" , value: "0",key_name:"drug_history"},
    { "id": 5, "label": "Social History" , value: "0",key_name:"social_history"},
    { "id": 6, "label": "Clinical examinations" , value: "0",key_name:"clinical_examination"},
    { "id": 7, "label": "Other Notes" , value: "0",key_name:"notes"},
    
  ];
  public icon = 'fa fa-close';
  onDisableUser(datas){
    if(datas.value == this.value){
    datas.value = 1;
    this.key_vale = datas.key_name;
    this.complaint_data[datas.key_name] = null;
  }
    else{
      datas.value = 0;
      datas.key_name = this.key_vale;
    }
  }
  private open(content) {
    let ngbModalOptions: NgbModalOptions = {
      backdrop : 'static',
      keyboard : true
    };
    //console.log(ngbModalOptions);
    const modalRef = this.modalService.open(content , ngbModalOptions);
    modalRef.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
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
  public getNotesAssesmentValues() {
    const postData = {
      assessment_id : this.assessment_id
    } ;
    this.loaderService.display(true)
    this.rest.editNotesAssesmentValues(postData).subscribe((result) => {
    this.loaderService.display(false)
      if (result.status === 'Success') {
        this.assessment_notes = result.data;
      }
      else
      {
        this.assessment_notes = result.data;
      }

    }, (err) => {
      console.log(err);
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
  
  
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatDate (date) {
    return  formatDate(date);
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  public saveComplaints(datas)
  {
    var form_data = {
      "complaint_data": this.complaint_data
    };
    var flag = 0
    for(let data in this.complaint_data){
      // if (capital != null && capital.length < 1) {
      if(this.complaint_data[data] != null &&this.complaint_data[data].length >0)
      {
        flag = 1
      }
    }
    if(flag == 0)
    {
      this.notifier.notify( 'error', 'Please enter atleast one chief complaint' );
      return;
    }

      var postData = {
        complaint_data :this.complaint_data,
        complaint_id :this.complaint_id,
        assessment_id :this.assessment_id,
        patient_id :this.patient_id,
        client_date:this.now,
        date:this.date,
        user_id: this.user_data.user_id,
        timeZone: Moment.tz.guess()
    }
    this.loaderService.display(true);
    this.rest2.saveComplaints(postData).subscribe((result) => {
    window.scrollTo(0, 0)
    this.save_notify = 2
    this.saveNotify.emit(this.save_notify)
    if(result['status'] == 'Success')
    {
      this.loaderService.display(false);
      this.notifier.notify( 'success','Chief complaints saved successfully..!' );
      this.getComplaints();
      this.getPreviousComplaints();
    } else {
      this.loaderService.display(false);
      this.notifier.notify( 'error',' Failed to save' );
    }
    }
  )}
  public changedata(data)
  {
    if(this.complaint_data[data] == null)
    {
      this.complaint_data[data] = ''
      this.get_complaint_data[data] = ''
    }
    
    
  }
  public getToday(): string 
  {
    return new Date().toISOString().split('T')[0]
  }
  public getAssesmentParameters() {
    let postData = {
    test_methode : AppSettings.NURSING_ASSESMENT
    }
    this.loaderService.display(true);
    this.rest.getAssesmentParameters(postData).subscribe((result) => {
      if(result.status == 'Success')
      {
        this.loaderService.display(false);
      this.vital_params = result.data;
      } else {
        this.loaderService.display(false);
      }
    }, (err) => {
    //  console.log(err);
    });
  }
  public getAssesmentParameterValues(patient_id=0,assessment_id=0) {
    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id
    };
    this.loaderService.display(true);
    this.rest.getAssesmentParameterValues(postData).subscribe((result) => {
      if(result.status === 'Success') {
        this.loaderService.display(false);
      this.vital_values = result.data;
      } else {
        this.vital_values = result.data;
        this.loaderService.display(false);
      }
    }, (err) => {
     // console.log(err);
    });
  }
  public OnApplyNotify(event)
  {
    //console.log('event',event)
    this.save_notify = event
    this.saveNotify.emit(this.save_notify)
  }
  public getPreviousComplaints(page = 0) {
    const limit = 2;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      search_text : this.formatDateTime(this.dateVal),
      assessment_id : this.assessment_id,
      patient_id : this.patient_id,
      complaint_id : this.complaint_id,
      start : this.start,
      limit : this.limit,
    };
    this.loaderService.display(true);
    this.rest2.getPreviousComplaints(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
    if (result['status'] === 'Success') {
      this.complaint_list = result['data'];
      this.collection = result['total_count'];
      const i = this.complaint_list.length;
      this.index = i + 5;
    } else {
      this.complaint_list = result['data'];
    }
  });
  }
  getPrevioussearchlist() {

    const limit = 100;
    this.start = 0;
    this.limit = limit;
    if(this.dateVal == '')
    {
      this.limit = 2
    }
    const post2Data = {
      search_text : this.formatDateTime(this.dateVal),
      assessment_id : this.assessment_id,
      patient_id : this.patient_id,
      complaint_id : this.complaint_id,
      start : this.start,
      limit : this.limit,
      timeZone: Moment.tz.guess()
    };
    this.loaderService.display(true);
    this.rest2.getPreviousComplaints(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
      this.status = result['status'];
     if (result['status'] === 'Success') {
      this.complaint_list = result['data'];
      this.collection = result['total_count'];
      const i = this.complaint_list.length;
      this.index = i + 5;
        this.loaderService.display(false);
     } else {
        this.loaderService.display(false);
        this.complaint_list = result['data'];
        this.collection = 0;
        this.page = 0
     }

   });
  }
public clear_search()
{
  this.dateVal = '';
  this.getPreviousComplaints()
}
  public getEvent()
  {
    // console.log(this.complaint_data)
    // console.log(this.get_complaint_data)
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
      var flag = 0
      var val = []
      if(this.get_status == 1)
      {
          if(this.complaint_data["clinical_examination"] == this.get_complaint_data["clinical_examination"] && 
          this.complaint_data["complaints"] == this.get_complaint_data["complaints"] &&
          this.complaint_data["drug_history"] == this.get_complaint_data["drug_history"] &&
          this.complaint_data["notes"] == this.get_complaint_data["notes"] &&
          this.complaint_data["past_medical_history"] == this.get_complaint_data["past_medical_history"] && 
          this.complaint_data["social_history"] == this.get_complaint_data["social_history"] &&
          this.complaint_data["summary_of_case"] == this.get_complaint_data["summary_of_case"])
          {
            this.save_notify = 0
            this.saveNotify.emit(this.save_notify)
          }
          else
          {
            this.save_notify = 1
            this.saveNotify.emit(this.save_notify)
          }
      }
      else{
        for(let data in this.complaint_data){
          //console.log(this.complaint_data[data])
          if(this.complaint_data[data] == '' || this.complaint_data[data] == null || this.complaint_data[data] == 'null')
          {
            flag = 2
          }
          else
          {
            flag = 1
          }
        }
        if(flag == 1)
        {
          var rel=0
          for(let data in this.complaint_data){
            if(this.complaint_data[data] != '' || this.complaint_data[data] != null || this.complaint_data[data] != 'null')
            {
              rel = 1
            }
          }
          if(rel == 1)
          {
            this.save_notify = 1
            this.saveNotify.emit(this.save_notify)
          }
        }
      }
      
    }
    else
    {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
    }
      
    // console.log(this.saveNotify.emit(this.save_notify))
  }
  public getComplaints() {
    const post3Data = {
      assessment_id : this.assessment_id,
      patient_id : this.patient_id
    };
    this.loaderService.display(true);
    this.rest2.getComplaints(post3Data).subscribe((result: {}) => {
    if (result['status'] === 'Success') {
      this.loaderService.display(false);
      this.complaintget_list = result['data'];
      this.get_status = 1
      this.complaint_id = this.complaintget_list.CHIEF_COMPLAINT_ID;
      for (const val of this.labels) {
        this.complaint_data[val.key_name]  = this.complaintget_list[val.key_name.toUpperCase()];
        this.get_complaint_data[val.key_name]  = this.complaintget_list[val.key_name.toUpperCase()];
        this.changedata(val.key_name)
      }
      //this.data_copy = this.isEmpty()
    } else {
     // console.log(result)
      this.complaint_data = result;
      this.complaint_id = 0
      this.get_status = 0
    }
  });
  this.loaderService.display(false);

  }
  public isEmptyObject(obj) {
    
    return Object.keys(obj).length;
  }
  public Complaints(val)
  {
    if(val == 1)
    {
      if(Object.keys(this.complaint_data).length > 2)
      {
        if(this.complaint_data["clinical_examination"] != "" || this.complaint_data["clinical_examination"] != null )
        {
          this.copy_complaint_data.clinical_examination = this.complaint_data["clinical_examination"]
        }
        if(this.complaint_data["complaints"] != "" || this.complaint_data["complaints"] != null )
        {
          this.copy_complaint_data.complaints = this.complaint_data["complaints"]
        }
        if(this.complaint_data["drug_history"] != "" || this.complaint_data["drug_history"] != null )
        {
          this.copy_complaint_data.drug_history = this.complaint_data["drug_history"]
        }
        if(this.complaint_data["notes"] != "" || this.complaint_data["notes"] != null )
        {
          this.copy_complaint_data.notes = this.complaint_data["notes"]
        }
        if(this.complaint_data["past_medical_history"] != "" || this.complaint_data["past_medical_history"] != null )
        {
          this.copy_complaint_data.past_medical_history = this.complaint_data["past_medical_history"]
        }
        if(this.complaint_data["social_history"] != "" || this.complaint_data["social_history"] != null )
        {
          this.copy_complaint_data.social_history = this.complaint_data["social_history"]
        }
        if(this.complaint_data["summary_of_case"] != "" || this.complaint_data["summary_of_case"] != null )
        {
          this.copy_complaint_data.summary_of_case = this.complaint_data["summary_of_case"]
        }
        // this.copy_complaint_data = {
        //   clinical_examination : this.complaint_data["clinical_examination"],
        //   complaints : this.complaint_data["complaints"],
        //   drug_history : this.complaint_data["drug_history"],
        //   notes : this.complaint_data["notes"],
        //   past_medical_history : this.complaint_data["past_medical_history"],
        //   social_history : this.complaint_data["social_history"],
        //   summary_of_case : this.complaint_data["summary_of_case"],
        // }
      }
      if(Object.keys(this.copy_complaint_data).length > 0)
      {
        this.notifier.notify("info","Chief complaint data copied")
      }
      
    }
    else
    {
      // if(Object.keys(this.copy_complaint_data).length == 0)
      // {

      // }
      this.complaint_data = {}
      this.complaint_data = {
        clinical_examination : this.copy_complaint_data["clinical_examination"],
        complaints : this.copy_complaint_data["complaints"],
        drug_history : this.copy_complaint_data["drug_history"],
        notes : this.copy_complaint_data["notes"],
        past_medical_history : this.copy_complaint_data["past_medical_history"],
        social_history : this.copy_complaint_data["social_history"],
        summary_of_case : this.copy_complaint_data["summary_of_case"],
      }
        if(Object.keys(this.copy_complaint_data).length > 0 && Object.keys(this.complaint_data).length > 2)
        {
          this.notifier.notify("info","Chief complaint data added")
          this.save_notify = 1
          this.saveNotify.emit(this.save_notify)
        }
    }
    
    
  }
  public CopyPreviousComplaints(data)
  {
    this.complaint_data = {}
    this.complaint_data = {
        clinical_examination : data["CLINICAL_EXAMINATION"],
        complaints : data["COMPLAINTS"],
        drug_history : data["DRUG_HISTORY"],
        notes : data["NOTES"],
        past_medical_history : data["PAST_MEDICAL_HISTORY"],
        social_history : data["SOCIAL_HISTORY"],
        summary_of_case : data["SUMMARY_OF_CASE"],
    }
    if(Object.keys(this.complaint_data).length > 0)
    {
      this.notifier.notify("info","Previous complaint data duplicated to current EMR  ")
    }
      
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
    if(this.dental_data.tooth_complaint.length > 0)
    {
      var rel =0;
      this.dental_data.tooth_complaint.forEach((item, index) => {
        if(this.dental_data.tooth_complaint[index] == 1)
        {
          rel =1;
        }
      });
    }
    if(rel == 1 && this.dental_data.patient_type != this.patient_type)
    {
      this.confirms(confirm)
      if(this.status==this.status)
      {
        e.preventDefault();
      }
    }
    else
    {
      this.dental_data.patient_type = this.patient_type
    }
  }
  getPopup(i,data,toothDescription)
  {   
   
    if(this.procedure == "")
    {
      this.notifier.notify('error',"Please select a procedure")
      return false;
    }
    this.tooth_index = i
    this.tooth_data = data
    if(this.dental_data.patient_type == 1)
    {
      this.open(toothDescription)
      this.tooth_number = data.universal
     
    }
    if(this.dental_data.patient_type == 2)
    {
      this.open(toothDescription)
      this.tooth_number = data.label
    }
      // document.getElementById(data).hidden = true;
      // this.upper_right[i].check = 1;
      // document.getElementById(data).hidden = false;
      // this.change = 'fa fa-circle';

  }
  getPopups(data)
  {
    // if(this.tooth_arr == 'UR'){
    //   this.upper_right[this.tooth_index].check = 0;
    // }
    // if(this.tooth_arr == 'UL'){
    //   this.upper_left[this.tooth_index].check = 0;
    // }
    // if(this.tooth_arr == 'LR'){
    //   this.lower_right[this.tooth_index].check = 0;
    // }
    // if(this.tooth_arr == 'LL'){
    //   this.lower_left[this.tooth_index].check = 0;
    // }
    // this.dental_data.procedure[this.tooth_index] = this.procedure
    // this.dental_data.color[this.tooth_index] = this.tooth_color
    // this.dental_data.tooth_complaint[this.tooth_index] = 0
    if(this.dental_data.patient_type == 1)
    {
      this.dental[this.tooth_index].check = 0;
      this.dental_data.tooth_issue[this.tooth_index] = ''
      this.dental[this.tooth_index].tooth_issue = ''
      this.dental[this.tooth_index].procedure = ''
      this.dental[this.tooth_index].color = ''
      this.notifier.notify('warning','Selected tooth is removed')
      this.getEvents()
    }
    if(this.dental_data.patient_type == 2)
    {
      this.dental_child[this.tooth_index].check = 0;
      this.dental_data.tooth_issue[this.tooth_index] = ''
      this.dental_child[this.tooth_index].tooth_issue = ''
      this.dental_child[this.tooth_index].procedure = ''
      this.dental_child[this.tooth_index].color = ''
      this.notifier.notify('warning','Selected tooth is removed')
      this.getEvents()
    }
       this.tooth_index = '';
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
    this.dental_data.procedure = []
    this.dental_data.child_tooth_value = []
    this.dental_data.child_tooth_number = []
    this.dental_data.color = []
   // this.dental_data.dental_complaint_id = []
    this.dental_data.patient_type = this.patient_type
    
    this.patient_type = 0;
    this.procedure = ''
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
      val['tooth_issue'] = ''
      val['check'] = 0
    }
    this.notifier.notify('warning',"All datas are cleared")
    if(this.dental_data.patient_type == 1)
    {
      var i=0;
      for(let val of this.dental)
      {
        this.dental_data.tooth_complaint[i] = val.check;
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
        this.dental_data.tooth_complaint[j] = val.check;
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
    this.dental_data.procedure = []
    this.dental_data.child_tooth_value = []
    this.dental_data.child_tooth_number = []
    this.dental_data.color = []
    
    this.procedure = ''
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
        this.dental_data.tooth_complaint[i] = val.check;
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
        this.dental_data.tooth_complaint[j] = val.check;
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
  selectProcedure(data)
  {
  
    this.procedure = data.procedures;
    this.tooth_color = data.color
   
  }
  saveIssue(toothDescription)
  {
   
    if(this.dental_data.tooth_issue[this.tooth_index] == '')
    {
      this.notifier.notify("error","Please enter the tooth issue")
      this.open(toothDescription)
    }
    else{
      if(this.dental_data.patient_type == 1){
        this.dental[this.tooth_index].tooth_issue =  this.dental_data.tooth_issue[this.tooth_index];
        this.dental_data.procedure[this.tooth_index] = this.procedure
        this.dental_data.color[this.tooth_index] = this.tooth_color
        this.dental_data.universal[this.tooth_index] = (this.tooth_data.universal)
        this.dental_data.palmer[this.tooth_index] = (this.tooth_data.palmer)
        this.dental_data.FDI[this.tooth_index] =  (this.tooth_data.value)
        this.dental[this.tooth_index].check = 1;
        this.dental_data.tooth_complaint[this.tooth_index] = 1
        this.dental[this.tooth_index].procedure = this.procedure
        this.dental[this.tooth_index].color = this.tooth_color
      }
      if(this.dental_data.patient_type == 2)
      {
        this.dental[this.tooth_index].tooth_issue =  this.dental_data.tooth_issue[this.tooth_index];
        this.dental_data.procedure[this.tooth_index] = this.procedure
        this.dental_data.color[this.tooth_index] = this.tooth_color
        this.dental_data.child_tooth_number[this.tooth_index] = this.tooth_data.label
        this.dental_data.child_tooth_value[this.tooth_index] = this.tooth_data.value
        this.dental_child[this.tooth_index].check = 1;
        this.dental_data.tooth_complaint[this.tooth_index] = 1
        this.dental_child[this.tooth_index].procedure = this.procedure
        this.dental_child[this.tooth_index].color = this.tooth_color
      }
    
    }
    // this.tooth_index = ''
    // console.log("this.dental[this.tooth_index] ")
    //   console.log(this.dental[this.tooth_index])
    
  }
  public saveDental()
  {
    this.dental_data.assessment_id = this.assessment_id
    this.dental_data.patient_id = this.patient_id
    this.dental_data.client_date = this.now
    this.dental_data.user_id =this.user_data.user_id
    this.dental_data.timeZone = Moment.tz.guess()
    //var rel =0
  //  var rels =0
    // this.dental_data.tooth_complaint.forEach((item, index) => {
    //   if(this.dental_data.tooth_complaint[index] = 1)
    //   {
    //     rel == 1
    //   }
    //   if(this.dental_data.tooth_complaint[index] = 0)
    //   {
    //     rels == 1
    //   }
    // });
   
    if(this.dental_data.tooth_complaint.length == 0)
    {
      this.notifier.notify('error','Please select at least one tooth')
      return false
    } 
    this.loaderService.display(true);
    this.rest2.saveDentalComplaints(this.dental_data).subscribe((result) => {
      window.scrollTo(0, 0)
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if(result['status'] == 'Success')
      {
        this.loaderService.display(false);
        this.notifier.notify( 'success',result['msg'] );
        this.getDentalComplaints();
        //this. getPreviousComplaints();
      } else {
        this.loaderService.display(false);
        this.notifier.notify( 'error',result['msg']);
      }
    }
  )}
  public getDentalComplaints() {
    // console.log(this.app_tabs)
    // if(this.app_tabs)
    //   {
    //     this.app_tabs.select('app_new_tab')
    //   }
    const post3Data = {
      assessment_id : this.assessment_id,
      patient_id : this.patient_id
    };
    this.loaderService.display(true);
    this.rest2.getDentalComplaints(post3Data).subscribe((result) => {
      this.loaderService.display(false);
    if (result['status'] === 'Success') {
      
      this.dental_list = result['data'];
      var i =0
      for (const val of this.dental_list) {
        this.dental_data.dental_complaint_id[i] = val.DENTAL_COMPLAINT_ID;
        i=i+1;
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
      for (const val of this.dental_list) {
        if(val.PATIENT_TYPE == 1)
        {
          this.dental_data.patient_type = 1
          for (const value of this.dental) {
            if(val.UNIVERSAL == value.universal)
            {
              value['check']  = 1;
              value['tooth_issue'] = val.TOOTH_ISSUE
              value['procedure'] = val.PROCEDURE
              value['color'] = val.PROCEDURE_COLOR
            }
          }
        }
        if(val.PATIENT_TYPE == 2)
        {
          this.dental_data.patient_type = 2  
          for (const values of this.dental_child) {
            if(val.CHILD_TOOTH_VALUE == values.value)
            {
              values['check']  = 1;
              values['tooth_issue'] = val.TOOTH_ISSUE
              values['procedure'] = val.PROCEDURE
              values['color'] = val.PROCEDURE_COLOR
              this.procedure = val.PROCEDURE;
              this.tooth_color = val.PROCEDURE_COLOR;
            }  
          }
        }
        
      }
     
     //  console.log(this.dental_child );
    } else {
      this.clear();
    }
    if(this.dental_data.patient_type == 1)
    {
      var i=0;
      for(let val of this.dental)
      {
        this.dental_data.tooth_complaint[i] = val.check;
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
        this.dental_data.tooth_complaint[j] = val.check;
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
  
  });
  }

  
  // public dental: { id: number, label: string,  universal :number,palmer :number,value: number,check:number,image:string}[] = [
  //   { "id": 1, "label": "tooth_11" ,"universal": 1, palmer: 8, value: 11,check:0, "image": "assets/images/1.jpg"},
  //   { "id": 2, "label": "tooth_12" ,"universal": 2, palmer: 7, value: 12,check:0, "image": "assets/images/2.jpg"},
  //   { "id": 3, "label": "tooth_13" ,"universal": 3, palmer: 6, value: 13,check:0, "image": "../../../../../../assets/images/3.jpg"},
  //   { "id": 4, "label": "tooth_14" ,"universal": 4, palmer: 5, value: 14,check:0, "image": "../../../../../../assets/images/4.jpg"},
  //   { "id": 5, "label": "tooth_15" ,"universal": 5, palmer: 4, value: 15,check:0, "image": "../../../../../../assets/images/5.jpg"},
  //   { "id": 6, "label": "tooth_16" ,"universal": 6, palmer: 3, value: 16,check:0, "image": "../../../../../../assets/images/6.jpg"},
  //   { "id": 7, "label": "tooth_17" ,"universal": 7, palmer: 2, value: 17,check:0, "image": "../../../../../../assets/images/7.jpg"},
  //   { "id": 8, "label": "tooth_18" ,"universal": 8, palmer: 1, value: 18,check:0, "image": "../../../../../../assets/images/8.jpg"},
  //   { "id": 9, "label": "tooth_21"  ,"universal": 9,  palmer: 1, value: 21,check:0, "image": "../../../../../../assets/images/9.jpg"},
  //   { "id": 10, "label": "tooth_22" ,"universal": 10, palmer: 2, value: 22,check:0, "image": "../../../../../../assets/images/10.jpg"},
  //   { "id": 11, "label": "tooth_23" ,"universal": 11, palmer: 3, value: 23,check:0, "image": "../../../../../../assets/images/11.jpg"},
  //   { "id": 12, "label": "tooth_24" ,"universal": 12, palmer: 4, value: 24,check:0, "image": "../../../../../../assets/images/12.jpg"},
  //   { "id": 13, "label": "tooth_25" ,"universal": 13, palmer: 5, value: 25,check:0, "image": "../../../../../../assets/images/13.jpg"},
  //   { "id": 14, "label": "tooth_26" ,"universal": 14, palmer: 6, value: 26,check:0, "image": "../../../../../../assets/images/14.jpg"},
  //   { "id": 15, "label": "tooth_27" ,"universal": 15, palmer: 7, value: 27,check:0, "image": "../../../../../../assets/images/15.jpg"},
  //   { "id": 16, "label": "tooth_28" ,"universal": 16, palmer: 8, value: 28,check:0, "image": "../../../../../../assets/images/16.jpg"},
  //   { "id": 17, "label": "tooth_31" ,"universal": 17,  palmer: 8, value: 38,check:0, "image": "../../../../../../assets/images/17.jpg"},
  //   { "id": 18, "label": "tooth_32","universal":  18,  palmer: 7, value: 37,check:0, "image": "../../../../../../assets/images/18.jpg"},
  //   { "id": 19, "label": "tooth_33" ,"universal": 19,  palmer: 6, value: 36,check:0, "image": "../../../../../../assets/images/19.jpg"},
  //   { "id": 20, "label": "tooth_34" ,"universal": 20,  palmer: 5, value: 35,check:0, "image": "../../../../../../assets/images/20.jpg"},
  //   { "id": 21, "label": "tooth_35" ,"universal": 21,  palmer: 4, value: 34,check:0, "image": "../../../../../../assets/images/21.jpg"},
  //   { "id": 22, "label": "tooth_36" ,"universal": 22,  palmer: 3, value: 33,check:0, "image": "../../../../../../assets/images/22.jpg"},
  //   { "id": 23, "label": "tooth_37" ,"universal": 23,  palmer: 2, value: 32,check:0, "image": "../../../../../../assets/images/23.jpg"},
  //   { "id": 24, "label": "tooth_38" ,"universal": 24,  palmer: 1, value: 31,check:0, "image": "../../../../../../assets/images/24.jpg"},
  //   { "id": 25, "label": "tooth_41" ,"universal": 25,  palmer: 1, value: 41,check:0, "image": "../../../../../../assets/images/25.jpg"},
  //   { "id": 26, "label": "tooth_42" ,"universal": 26,  palmer: 2, value: 42,check:0, "image": "../../../../../../assets/images/26.jpg"},
  //   { "id": 27, "label": "tooth_43" ,"universal": 27,  palmer: 3, value: 43,check:0, "image": "../../../../../../assets/images/27.jpg"},
  //   { "id": 28, "label": "tooth_44" ,"universal": 28,  palmer: 4, value: 44,check:0, "image": "../../../../../../assets/images/28.jpg"},
  //   { "id": 29, "label": "tooth_45" ,"universal": 29,  palmer: 5, value: 45,check:0, "image": "../../../../../../assets/images/29.jpg"},
  //   { "id": 30, "label": "tooth_46" ,"universal": 30,  palmer: 6, value: 46,check:0, "image": "../../../../../../assets/images/30.jpg"},
  //   { "id": 31, "label": "tooth_47" ,"universal": 31,  palmer: 7, value: 47,check:0, "image": "../../../../../../assets/images/31.jpg"},
  //   { "id": 32, "label": "tooth_48" ,"universal": 32,  palmer: 8, value: 48,check:0, "image": "../../../../../../assets/images/32.jpg"}
  // ];

  public upper_right: { id: number, label: string, universal :number,palmer :number,value: number,check:number }[] = [
    { "id": 1, "label": "tooth_11" ,"universal": 1, palmer: 8, value: 11,check:0},
    { "id": 2, "label": "tooth_12" ,"universal": 2, palmer: 7, value: 12,check:0},
    { "id": 3, "label": "tooth_13" ,"universal": 3, palmer: 6, value: 13,check:0},
    { "id": 4, "label": "tooth_14" ,"universal": 4, palmer: 5, value: 14,check:0},
    { "id": 5, "label": "tooth_15" ,"universal": 5, palmer: 4, value: 15,check:0},
    { "id": 6, "label": "tooth_16" ,"universal": 6, palmer: 3, value: 16,check:0},
    { "id": 7, "label": "tooth_17" ,"universal": 7, palmer: 2, value: 17,check:0},
    { "id": 8, "label": "tooth_18" ,"universal": 8, palmer: 1, value: 18,check:0},
  ];
  public upper_left: { id: number, label: string,  universal :number,palmer :number,value: number,check:number}[] = [
    { "id": 9, "label": "tooth_21"  ,"universal": 9,  palmer: 1, value: 21,check:0},
    { "id": 10, "label": "tooth_22" ,"universal": 10, palmer: 2, value: 22,check:0},
    { "id": 11, "label": "tooth_23" ,"universal": 11, palmer: 3, value: 23,check:0},
    { "id": 12, "label": "tooth_24" ,"universal": 12, palmer: 4, value: 24,check:0},
    { "id": 13, "label": "tooth_25" ,"universal": 13, palmer: 5, value: 25,check:0},
    { "id": 14, "label": "tooth_26" ,"universal": 14, palmer: 6, value: 26,check:0},
    { "id": 15, "label": "tooth_27" ,"universal": 15, palmer: 7, value: 27,check:0},
    { "id": 16, "label": "tooth_28" ,"universal": 16, palmer: 8, value: 28,check:0}
  ];
  public lower_left: { id: number, label: string, universal :number,palmer :number,value: number,check:number }[]= [
   
    { "id": 17, "label": "tooth_31" ,"universal": 17,  palmer: 8, value: 38,check:0},
    { "id": 18, "label": "tooth_32","universal":  18,  palmer: 7, value: 37,check:0},
    { "id": 19, "label": "tooth_33" ,"universal": 19,  palmer: 6, value: 36,check:0},
    { "id": 20, "label": "tooth_34" ,"universal": 20,  palmer: 5, value: 35,check:0},
    { "id": 21, "label": "tooth_35" ,"universal": 21,  palmer: 4, value: 34,check:0},
    { "id": 22, "label": "tooth_36" ,"universal": 22,  palmer: 3, value: 33,check:0},
    { "id": 23, "label": "tooth_37" ,"universal": 23,  palmer: 2, value: 32,check:0},
    { "id": 24, "label": "tooth_38" ,"universal": 24,  palmer: 1, value: 31,check:0},
   
  ];
  public lower_right: { id: number, label: string, universal :number,palmer :number,value: number,check:number}[] = [
    { "id": 25, "label": "tooth_41" ,"universal": 25,  palmer: 1, value: 41,check:0},
    { "id": 26, "label": "tooth_42" ,"universal": 26,  palmer: 2, value: 42,check:0},
    { "id": 27, "label": "tooth_43" ,"universal": 27,  palmer: 3, value: 43,check:0},
    { "id": 28, "label": "tooth_44" ,"universal": 28,  palmer: 4, value: 44,check:0},
    { "id": 29, "label": "tooth_45" ,"universal": 29,  palmer: 5, value: 45,check:0},
    { "id": 30, "label": "tooth_46" ,"universal": 30,  palmer: 6, value: 46,check:0},
    { "id": 31, "label": "tooth_47" ,"universal": 31,  palmer: 7, value: 47,check:0},
    { "id": 32, "label": "tooth_48" ,"universal": 32,  palmer: 8, value: 48,check:0}
  ];
}
