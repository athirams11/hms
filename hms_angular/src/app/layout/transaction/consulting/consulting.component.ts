import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { routerTransition } from '../../../router.animations';
import { NursingAssesmentService, OpVisitService } from '../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../../../shared/class/Utils';
import { Router, NavigationEnd } from '@angular/router';
import { DatePipe } from '@angular/common';
import moment from 'moment-timezone';
import { ModalDismissReasons, NgbModal, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { Directive, HostBinding, HostListener } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { interval, observable, Subscription } from 'rxjs';
import Moment from 'moment-timezone';
import { LoaderService } from '../../../shared/services';
@Component({
  selector: 'app-consulting',
  templateUrl: './consulting.component.html',
  styleUrls: ['./consulting.component.scss'],
  animations: [routerTransition()],
  providers: [ DatePipe,OpVisitService]
})
export class ConsultingComponent implements OnInit,OnDestroy{
  
  @Input() visit_list: any = [];
  @Output() onEvent = new EventEmitter();
  @Input() finish :  number = 0;
  @Input() discount :  number = 0;
  public user_rights : any ={};
  public loading = false;
  navigationSubscription;
  public assessment_menu_list : any = [];
  public dateVal = new Date();
  public assessment_id : number;
  public patient_id: number;
  public selected_visit : any ={};
  public assesment_list: any[];
  todaysDate = new Date();
  public user:any=[''];
  public user_id:any;
  public user_group:any;
  public subscription: Subscription;
  source = interval(10000);
  public f=0;
  current_date : any;
  @Input() page: any = "Visit";
  notifier: any;
  time: any;
  text: string;
  term : any;
  public popoverTitle: string = '';
  public popoverMessage: string = 'Are you sure the assessment completed?';
  public confirmClicked: boolean = false;
  public cancelClicked: boolean = false;
  visit_details: any = [];
  private modalRef: NgbModalRef;
  private modalRefs: NgbModalRef;
  discountDetails: any;
  constructor(private modalService: NgbModal,
    public loaderService: LoaderService,public opv: OpVisitService,
    public nas: NursingAssesmentService,notifierService: NotifierService, 
    public datepipe: DatePipe,private router: Router) {
  //   this.navigationSubscription = this.router.events.subscribe((e: any) => {
   this.notifier = notifierService;
  //     if (e instanceof NavigationEnd) {
  //      this.initialiseInvites();
  //    }
  //  });
   }
closeResult: string;
visit_time : any;
selectedGroup: any;
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.user_id =this.user.user_id;
    this.user_group=this.user.user_group;
    this.dateVal = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.getVisitListByDate();
    this.getAssesmentListByDate();
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
        if (e instanceof NavigationEnd) {
         this.initialiseInvites();
       }
     }); 
    //  this.getallVisitDetails()   
     this.time = moment.tz.guess();
     this.subscription = this.source.subscribe(x => this.getAssesmentListByDate(1));
  }
  initialiseInvites() {
    this.getVisitListByDate();
    this.getAssesmentListByDate();
    // this.subscription = this.source.subscribe(x => this.getAssesmentListByDate(1));

  }
  ngOnDestroy() {
    // avoid memory leaks here by cleaning up after ourselves. If we  
    // don't then we will continue to run our initialiseInvites()   
    // method on every navigationEnd event.
    if (this.navigationSubscription) {  
       this.navigationSubscription.unsubscribe();
    }
    if (this.subscription) {  
      this.subscription.unsubscribe();
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

  public setAssessment(visit)
  {
    this.selected_visit = visit; 
    this.assessment_id = visit.NURSING_ASSESSMENT_ID;
    this.patient_id = visit.PATIENT_ID;
  }
  public setPatient(id)
  {
    this.patient_id = id;
    this.getallVisitDetails();
  }
  public getVisitListByDate()
  {
    var sendJson = {
      dateVal : defaultDateTime(),
      timeZone: moment.tz.guess(),
      user_group:this.user_group,
      user_id:this.user_id,
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
      console.log(err);
    });
  }
  public getAssesmentListByDate(f=0)
  {
   // console.log("this.user.user_id "+this.user_id);
   //console.log("this.user_group  -> "+this.user_group +"      this.user_id  -> "+this.user_id);
    var sendJson = {
      dateVal : this.formatDateTime(this.dateVal),
      user_group:this.user_group,
      user_id:this.user_id,
      timeZone: moment.tz.guess()
      }
      if (f==0) {
        this.loading=true;
      }
    this.nas.getDoctorAssesmentListByDate(sendJson).subscribe((result) => {
      if(result.status == "Success")
      {
        //console.log(result.data);
        this.loading = false;
        this.assesment_list = result.data;
        for(let assessment of this.assesment_list)
        {
          if(assessment.PATIENT_VISIT_LIST_ID != null && assessment.NURSING_ASSESSMENT_ID == null  && assessment.BILL_STATUS == null  && assessment.STAT == null)
          assessment["ACTION"] = "Arrived"
          else if(assessment.NURSING_ASSESSMENT_ID != null &&  assessment.BILL_STATUS == null  && assessment.STAT == null)
          assessment["ACTION"] = "Nursing Assessment Started"
          else if(assessment.STAT == 1 && assessment.BILL_STATUS == null && assessment.DOCTOR_STAT == null)
          assessment["ACTION"] = "Nursing Assessment Completed"
          else if(assessment.STAT == 1 && assessment.BILL_STATUS == null && assessment.DOCTOR_STAT == 1)
          assessment["ACTION"] = "Doctor Assessment Completed"
          // else if(assessment.BILL_STATUS == 0)
          // assessment["ACTION"] = "Billing Payment Pending"
          else if(assessment.BILL_STATUS == 1)
          assessment["ACTION"] = "Billing Completed"
          // else if(assessment.BILL_GENERATED == 1)
          // assessment["ACTION"] = "Billing Payment Completed"
        }
      }
      else
      {
        this.assesment_list = [];
      }
    }, (err) => {
      console.log(err);
    });
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
  public getToday(): string 
  {
    return new Date().toISOString().split('T')[0]
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
   
    this.modalRefs =  this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',size: 'sm',centered:true})
    
    this.modalRefs.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private notification(content) {
   
    this.modalRefs =  this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',centered:true})
    
    this.modalRefs.result.then((result) => {
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
  public getNextAssessmentDetails(visit)
  {
      var sendJson = {
        assessment_id : visit.NURSING_ASSESSMENT_ID,
        patient_id : visit.PATIENT_ID
      }
      this.nas.getNextAssessmentDetails(sendJson).subscribe((result) => {
        if(result.status == "Success")
        {
          this.selected_visit = result.data
          this.assessment_id = this.selected_visit.NURSING_ASSESSMENT_ID
          this.patient_id = this.selected_visit.PATIENT_ID
        }
        else
        {
          this.notifier.notify( 'error', 'No next assessments!' );
        }
      }, (err) => {
        console.log(err);
      });
  }
  public getPreviousAssessmentDetails(visit)
  {
   
    var sendJson = {
      assessment_id : visit.NURSING_ASSESSMENT_ID,
      patient_id : visit.PATIENT_ID
    }
    this.nas.getPreviousAssessmentDetails(sendJson).subscribe((result) => {
      if(result.status == "Success")
      {
        this.selected_visit = result.data
        this.assessment_id = this.selected_visit.NURSING_ASSESSMENT_ID
        this.patient_id = this.selected_visit.PATIENT_ID
      }
      else
      {
        this.notifier.notify( 'error', 'No previous assessments!' );
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getAssessmentDetails()
  {
    // console.log(visit)
    // console.log(this.selectedGroup)
    var sendJson = {
      assessment_id :this.assessment_id,
      patient_id : this.patient_id
    }
    //this.loaderService.display(true);
    this.nas.getAssessmentDetails(sendJson).subscribe((result) => {
      //this.loaderService.display(false);
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
      
      this.nas.getallVisitDetails(sendJson).subscribe((result) => {
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
    this.completeDoctorAssesment(visit)
  }
   public completeDoctorAssesment(visit,val = 0)
  {
    let sendJson = {
      assessment_id : visit.NURSING_ASSESSMENT_ID,
      date: defaultDateTime(),
      timeZone : Moment.tz.guess(),
    };
    this.loaderService.display(true);
    this.nas.completeDoctorAssesment(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if(result.status == "Success")
      {
        if(val == 1)
        {
            this.notifier.notify("success","Doctor assessment completed successfully..!")
        }
        this.getAssesmentListByDate();
        if(this.modalRefs)
          this.modalRefs.close();
        if(this.modalRef)
          this.modalRef.close();
      } else {
       this.onEvent.emit();
      }
    }, (err) => {
      console.log(err);
    });
  }
  public onFinishAssessment(event,content)
  {
    this.finish = event
    // console.log(this.finish)
    if(this.finish == 1)
    {
      this.confirm(content)
    }
    
  }
  public getDiscountTreatmentDetails(discount)
  {
    let sendJson = {
      visit_id : this.selected_visit.VISIT_ID
    }
    this.nas.getDiscountTreatmentDetails(sendJson).subscribe((result) => {
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
  public onshowDiscount(event,content)
  {
    this.discount = event
    if(this.discount == 1)
    {
      this.getDiscountTreatmentDetails(content)
      // this.notification(content)
    }
    
  }
}

