import { Component, OnInit, Input, Output,EventEmitter } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import {NgbModal, ModalDismissReasons,NgbModalRef} from '@ng-bootstrap/ng-bootstrap';
import { NursingAssesmentService, LoaderService } from '../../../../shared/services';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../../../../shared/class/Utils';
import { AppSettings } from '../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import { interval, observable, Subscription } from 'rxjs';
// import { Model } from './model';
import { UserIdleService } from 'angular-user-idle';
@Component({
  selector: 'app-nursing-assesment-list',
  templateUrl: './nursing-assesment-list.component.html',
  styleUrls: ['./nursing-assesment-list.component.scss']
})
export class NursingAssesmentListComponent implements OnInit {
  @Input() visit_list: any = [];
  @Output() onEvent = new EventEmitter();
  @Input() finish :  number = 0;
  @Input() discount :  number = 0;
  @Output() finishAssessment = new EventEmitter();
  public subscription: Subscription;
  source = interval(50000);
  private notifier: NotifierService;
  public user_rights : any ={};
  public assessment_menu_list : any = [];
  public dateVal = new Date();
  public assessment_id : number;
  public patient_id: number;
  public failed_message = '';
  public selected_visit : any ={};
  public now=new Date();
  public date:any;
  term : any;
  // public model: Model;
  // @Input() dr_schedule_list_date: any;
  public popoverTitle: string = '';
  public popoverMessage: string = 'Are you sure the assessment completed?';
  public confirmClicked: boolean = false;
  public cancelClicked: boolean = false;
  user_data : any;
  current_date :any;
  @Input() page: any = "Visit";
  private modalRef: NgbModalRef;
  private modalRefs: NgbModalRef;
  login: string;
  visit_details: any;
  discountDetails: any;
  text: string;
  constructor(private userIdle: UserIdleService,public loaderService: LoaderService,private modalService: NgbModal,public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,notifierService: NotifierService) {
    this.notifier = notifierService;
    // this.model = new Model();
  }
  closeResult: string;
  
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.login = localStorage.getItem('isLoggedin')
    const source = interval(1000);
    this.dateVal = defaultDateTime();
    this.subscription = source.subscribe(val => this.opensnack(this.login))
    //console.log(this.login)
  }
  ngOnDestroy() {
    // avoid memory leaks here by cleaning up after ourselves. If we  
    // don't then we will continue to run our initialiseInvites()   
    // method on every navigationEnd event.
    if (this.subscription) {  
       this.subscription.unsubscribe();
       
      //  this.userIdle.onTimeout().subscribe(() => {  
      //     console.log(this.userIdle.onTimeout())
      //     this.modalRef.close();
      // });
    }
    
  }
  public showTpa(visit)
  {
    if(visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null)
    {
      if(visit.insurance != false){
        var coin = []
        var rest = ''
        if( visit.insurance.OP_INS_IS_ALL == 1)
        {
          rest = visit.insurance.OP_INS_ALL_VALUE+' '+visit.insurance.OP_INS_ALL_TYPE
        }
        else
        {
          if(visit.insurance.co_ins && visit.insurance.co_ins.length > 0){
            var i = 0
            for(let data of visit.insurance.co_ins)
            {
              coin[i] = '\n            '+data.COIN_NAME + '  :  ' + data.COIN_VALUE+ ' ' +data.COIN_VALUE_TYPE+ ' '  
              i=i+1
            }
          }
        }
        var tpa ="TPA :  ";
        var company ="Company :  ";
        var Network ="Network :  ";
        var Deductable ="Deductable :  ";
        var restall ="\nRest of all :  ";
        var coins ="\nCo-Ins  :";
        if(coin.length == 0)
        {
          coins = ''
        }
        if(visit.insurance.OP_INS_IS_ALL == 0)
        {
          restall = '';
          rest = ''
        }
        this.text = 'TPA  :  ' + visit.insurance.TPA_ECLAIM_LINK_ID + ' - ' + visit.insurance.TPA_NAME + '\n' +
        'Company  :  ' + visit.insurance.INSURANCE_PAYERS_ECLAIM_LINK_ID + ' - ' + visit.insurance.INSURANCE_PAYERS_NAME + '\n' +
        'Network  :  ' + visit.insurance.INS_NETWORK_NAME + '\n' +
        'Deductable  :  ' + visit.insurance.OP_INS_DEDUCTIBLE + ' ' + visit.insurance.OP_INS_DEDUCT_TYPE +
        restall +''+ rest + 
        coins +''+ coin;
      }
    }
  }
  public hideTpa(visit)
  {
    if(visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null)
    {
      this.text = ''
    }
  }
  private open(content) {
    this.modalRef = this.modalService.open(content,{ariaLabelledBy: 'modal-basic-title',size: 'lg' ,windowClass:"col-md-12"});
    
    this.modalRef.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private confirm(content) {
    this.modalRefs = this.modalService.open(content,{ariaLabelledBy: 'modal-basic-title',size: 'sm' ,centered:true});
    
    this.modalRefs.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      this.finishAssessment.emit(0)
    });
  }
  private notification(content) {
    this.modalRefs = this.modalService.open(content,{ariaLabelledBy: 'modal-basic-title',centered:true});
    
    this.modalRefs.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      this.finishAssessment.emit(0)
    });
    }
  public opensnack(login)
  {
    if(login == "true")
    {
      // this.modalRef.close();
      
    }
    else
    {
      if(this.modalRefs && this.modalRef)
       this.modalRefs.close();
       this.modalRef.close();
      //this.modalService.dismissAll();
    }
  }
  // private open(content,data) {
  //   this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',size: 'sm', windowClass:"col-md-4"}).result.then((result) => {
  //     this.closeResult = `Closed with: ${result}`;
  //   }, (reason) => {
  //     this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
  //   });
  // }

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
  public formatDTime (data) {
    if (data) {
      data = moment(data, 'yyyy-MM-D HH:mm.ss a').format('hh:mm a');
      // dateFormat(now, "dd, mm, yyyy, h:MM:ss TT"); format('D-MM-YHH:MM:ss ');
      return data;
    }
  }
  public setAssessment(visit)
  {
    this.selected_visit = visit;
    this.assessment_id = visit.NURSING_ASSESSMENT_ID;
    // console.log("vistttt==="+visit.NURSING_ASSESSMENT_ID)

  }
  public setPatient(id)
  {
    this.patient_id = id;
    this.getallVisitDetails();
  }
  public completeAssessment(visit,val = 0)
  {
    let sendJson = {
      assessment_id : visit.NURSING_ASSESSMENT_ID,
      date: defaultDateTime(),
      timeZone : Moment.tz.guess(),
    };
    this.loaderService.display(true);
    this.rest2.completeAssessment(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if(result.status == "Success")
      {
        if(val == 1)
        {
            this.notifier.notify("success","Nursing assessment completed successfully..!")
        }
        this.onEvent.emit();
        if(this.modalRef)
          this.modalRef.close();
      } else {
        this.onEvent.emit();
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getNextAssessmentDetails(visit) {
      let sendJson = {
        assessment_id : visit.NURSING_ASSESSMENT_ID,
        patient_id : visit.PATIENT_ID
      }

      this.rest2.getNextAssessmentDetails(sendJson).subscribe((result) => {
        if(result.status == 'Success') {
          this.selected_visit = result.data;
          this.assessment_id = this.selected_visit.NURSING_ASSESSMENT_ID;
          this.patient_id = this.selected_visit.PATIENT_ID;
          // this.onEvent.emit();
        } else {
          // this.onEvent.emit();
          this.notifier.notify( 'error', 'No next assessments!' );
        }
      }, (err) => {
        console.log(err);
      });
  }
  public getPreviousAssessmentDetails(visit) {
    const sendJson = {
      assessment_id : visit.NURSING_ASSESSMENT_ID,
      patient_id : visit.PATIENT_ID
    };

    this.rest2.getPreviousAssessmentDetails(sendJson).subscribe((result) => {
      if (result.status === 'Success') {
        // this.onEvent.emit();
        this.selected_visit = result.data;
        this.assessment_id = this.selected_visit.NURSING_ASSESSMENT_ID;
        this.patient_id = this.selected_visit.PATIENT_ID;
      } else {
        // this.onEvent.emit();
        this.notifier.notify( 'error', 'No previous assessments!' );
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getAssessmentDetails(visit)
  {
    // console.log(visit)
    // console.log(this.selectedGroup)
    var sendJson = {
      assessment_id :this.assessment_id,
      patient_id : this.patient_id
    }
    this.loaderService.display(true);
    this.rest2.getAssessmentDetails(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if(result.status == "Success")
      {
        this.selected_visit = result.data
        this.assessment_id = this.selected_visit.NURSING_ASSESSMENT_ID
        this.patient_id = this.selected_visit.PATIENT_ID
      }
      else
      {
        this.notifier.notify( 'error', 'No assessments in the date!' );
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getallVisitDetails()
  {
      var sendJson = {
        patient_id : this.patient_id
      }
      
      this.rest2.getallVisitDetails(sendJson).subscribe((result) => {
        if(result.status == "Success")
        {
          this.visit_details = result.data
          
        }
        else
        {
          this.notifier.notify( 'error', 'No next assessments!' );
        }
      }, (err) => {
        console.log(err);
      });
  }
  public alertbox(visit) {
   // if (confirm("Add patient to doctor assessment")) {
      this.completeAssessment(visit)
   // } else {
     
   // }
    // document.getElementById("demo").innerHTML = txt;
  }
  public onFinishAssessment(event,content)
  {
    this.finish = event
    if(this.finish == 1)
    {
      this.confirm(content)
    }
    
  }
  public onshowDiscount(event,content)
  {
    this.discount = event
    if(this.discount == 1)
    {
      this.getDiscountTreatmentDetails(content)
      // this.notification(content)
    }
    
  }
  
  public getDiscountTreatmentDetails(discount)
  {
    let sendJson = {
      visit_id : this.selected_visit.VISIT_ID
    }
    this.loaderService.display(true);
    this.rest2.getDiscountTreatmentDetails(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if(result.status === 'Success') {
        this.discountDetails = result.data;
        this.notification(discount)
      } 
      else 
      {
        this.discountDetails = [];
      }
    });
  }
}
