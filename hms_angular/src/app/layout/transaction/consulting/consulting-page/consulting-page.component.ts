import { Component, OnInit, Input, SimpleChanges, OnChanges, Output, EventEmitter } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { NursingAssesmentService, OpVisitService} from '../../../../shared/services'
import { formatTime, formatDateTime, formatDate } from '../../../../shared/class/Utils';
import { NotifierService } from 'angular-notifier';
import { AppSettings } from 'src/app/app.settings';
import moment from 'moment-timezone';
import { NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-consulting-page',
  templateUrl: './consulting-page.component.html',
  styleUrls: ['./consulting-page.component.scss']
})
export class ConsultingPageComponent implements OnInit,OnChanges {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() selected_visit: any = [];
  @Input() user_id: number = 0;
  @Output() onEvent = new EventEmitter();
  @Input() save_notify: number = 0;
  @Output() finishAssessment = new EventEmitter();
  @Output() showDiscount = new EventEmitter();
  // @Input() visit_list: any = [];
  public blood_sugar : any = [];
  public settings = AppSettings;
  public assessment_menu_list: any = [];
  public loading = false;
  public menu_list: any = [];
  public  visit_list: any = [];
  private notifier: NotifierService;
  public user_rights : any ={};
  public vital_params : any = [];
  public vital_values : any = [];
  public vital_form_values : any = [];
  public dateVal = new Date();
  public assessment_entry_id : number = 0;
  selectedUserTab: any;
  UserTab: any;
  assesment_list: any;
  time: any;
  selectedTab: any;
  closeResult: string;
  doctor_department: any;
  department: number;

  
  constructor(private modalService: NgbModal,public datepipe: DatePipe,private router: Router, public opv: OpVisitService,public rest2:NursingAssesmentService,notifierService: NotifierService) { 
    this.notifier = notifierService;
  }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
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
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id); 
    this.get_sub_modules();
    this.getBloodSugarReport(); 
    this.time = moment.tz.guess();
   // console.log(this.save_notify)
  }
  ngOnChanges(changes: SimpleChanges) {
    // changes.prop contains the old and the new value...
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id); 
    this.getBloodSugarReport(); 
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
  public setAssessment(id)
  {
    this.assessment_id = id;
    //console.log("assessment-id=="+id)
  }
  public setPatient(id)
  {
    this.patient_id = id;
  }
  
  public get_sub_modules()
  {
      var postData = {
        module_id : AppSettings.DOCTOR_ASSESMENT_MENU
      }
      this.loading = true;
      this.rest2.get_sub_modules(postData).subscribe((result) => {
        if(result["status"] == "Success")
        {
          this.loading = false;
          this.menu_list = result["menu_list"];
       //   console.log(this.menu_list);
          this.UserTab = this.menu_list["1"];
          if(this.UserTab.MENUS["0"] )
          {
            this.selectedUserTab = this.UserTab.MENUS["0"];
          }   
        }
        else
        {
          this.menu_list = [];
        }
      }, (err) => {
     //   console.log(err);
      });
     
  }
  public getEvent($event)
  {
    console.log($event)
    this.onEvent.emit()
  }
  public getAssesmentParameterValues(patient_id=0,assessment_id=0)
  {
    //this.patient_id = patient_id;assessment_id
    //this.assessment_id = assessment_id;assessment_id
    //console.log("Assessement ID;"+this.assessment_id);
    //console.log("patient ID;"+this.patient_id);
      var postData = {
        patient_id : this.patient_id,
        assessment_id : this.assessment_id
      }
      this.loading = true;
      this.rest2.getAssesmentParameterValues(postData).subscribe((result) => {
        if(result.status == "Success")
        {
          this.loading = false;
       // console.log(result.data);
        this.vital_values = result.data;
        //this.router.navigate(['transaction/pre-consulting']);
          //window.location.reload();
        }
        else
        {
          this.vital_values = result.data;
        }
      }, (err) => {
       // console.log(err);
      });
    
  }
  public getVisitListByDate()
  {
    var sendJson = {
      dateVal : this.dateVal,
      timeZone:this.time
    }
    this.loading = true;
    this.opv.getVisitListByDate(sendJson).subscribe((result) => {
      if(result.status == "Success")
      {
        //console.log(result.data);
        this.loading = false;
        this.visit_list = result.data;
        
      }
     
      else
      {
        this.loading = false;
        this.visit_list = [];
      }
    }, (err) => {
   //   console.log(err);
    });
  }
  public getBloodSugarReport() {
    const postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id
    }
    this.rest2.getBloodSugarReport(postData).subscribe((result) => {
      if(result.status == "Success") {
        this.blood_sugar = result.data
      } 
      else {
        this.blood_sugar = result.data
      }
    }, (err) => {
      console.log(err);
    });

  }
  public change(UserTab)
  {
    if(UserTab==this.UserTab)
      this.UserTab=0;
    else
      this.UserTab=UserTab;
  }
  public tabchange(selectedTab,content)
  {
   // console.log(this.save_notify)
    this.selectedTab = selectedTab
    if(selectedTab==this.UserTab)
      this.selectedUserTab=0;
    else
    if(this.save_notify == 1)
    {
      this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',size: 'sm',centered:true}).result.then((result) => {
        this.closeResult = `Closed with: ${result}`;
      }, (reason) => {
        this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      });
     
    }
    else
    {
      this.selectedUserTab=selectedTab;
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
  public changetab()
  {
      this.selectedUserTab=this.selectedTab;
      this.save_notify = 0
  }
  public OnApplyNotify(event)
  {
   // console.log('event',event)
    this.save_notify = event
  }
  public OnFinish()
  {
    this.finishAssessment.emit(1)
  }
  public ShowDiscount()
  {
    this.showDiscount.emit(1)
  }
}