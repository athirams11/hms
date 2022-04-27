import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import {DatePipe} from '@angular/common';
import { routerTransition } from '../../../router.animations';
import { OpVisitService, NursingAssesmentService} from '../../../shared';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../../../shared/class/Utils';
import { LoaderService } from '../../../shared';
import moment from 'moment-timezone';
import { interval, observable, Subscription } from 'rxjs';
@Component({
  selector: 'app-pre-consulting',
  templateUrl: './pre-consulting.component.html',
  styleUrls: ['./pre-consulting.component.scss','../transaction.component.scss'],
  animations: [routerTransition()],
  providers: [ OpVisitService]
})
export class PreConsultingComponent implements OnInit, OnDestroy {
  public dateTime: Date;
  public user_rights: any = {};
  navigationSubscription;
  public visit_list: any = [];
  public subscription: Subscription;
  source = interval(5000);
  public f=0;
  public loading = false;
  public assesment_list: any = [];
  public current_date : any = "";
  public user:any=[''];
  public user_id:any;
  public user_group:any;
  dateVal = new Date();
  time: any;
  constructor(private loaderService: LoaderService, public opv: OpVisitService, public nas: NursingAssesmentService, public datepipe: DatePipe, private router: Router) {
    // this.navigationSubscription = this.router.events.subscribe((e: any) => {
    //   // If it is a NavigationEnd event re-initalise the component
    //   if (e instanceof NavigationEnd) {
    //     this.initialiseInvites();
    //   }
    // });
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.dateVal = defaultDateTime();

    this.user_id =this.user.user_id;
    this.user_group=this.user.user_group;
   // console.log("this.user_group  -> "+this.user_group +"      this.user_id  -> "+this.user_id);
    this.getVisitListByDate();
    this.getAssesmentListByDate();
    this.time = moment.tz.guess();
    this.subscription = this.source.subscribe(x => this.getVisitListByDate(1),);
    this.subscription = this.source.subscribe(x => this.getAssesmentListByDate(1),);
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      // If it is a NavigationEnd event re-initalise the component
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
  }
  
  initialiseInvites() {
    this.getVisitListByDate();
    this.getAssesmentListByDate();
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
  public getToday(): string {
    return new Date().toISOString().split('T')[0];
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

  public getVisitListByDate(f=0) {
    const sendJson = {
      dateVal : this.formatDateTime (this.dateVal),
      timeZone: moment.tz.guess(),
      user_group:this.user_group,
      user_id:this.user_id,
    };
    if (f==0) {
      this.loaderService.display(true);
    }
    
    this.opv.getVisitListByDate(sendJson).subscribe((result) => {
      if (result.status === 'Success') {
        this.loaderService.display(false);
        // console.log(result.data);
        this.visit_list = result.data;
        for(let visit of this.visit_list)
        {
          if(visit.VISIT_STATUS == 1 && visit.BILL_STATUS == null  && visit.STAT == null)
          visit["ACTION"] = "Nursing Assessment Started"
          else if(visit.VISIT_STATUS == 1 && visit.STAT == 1 && visit.BILL_STATUS == null && visit.DOCTOR_STAT == null)
          visit["ACTION"] = "Nursing Assessment Completed"
          else if(visit.VISIT_STATUS == 2)
          visit["ACTION"] = "Visit cancelled"
          else if(visit.VISIT_STATUS == 1 && visit.BILL_STATUS == null && visit.DOCTOR_STAT == 1 && visit.STAT == 1)
          visit["ACTION"] = "Doctor Assessment Completed"
          else if(visit.VISIT_STATUS == 1 && visit.BILL_STATUS == 1)
          visit["ACTION"] = "Billing Completed"
        }
      } else {
        this.loaderService.display(false);
        this.visit_list = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getAssesmentListByDate(f=0) {
    const sendJson = {
      dateVal : this.formatDateTime(this.dateVal),
      timeZone: moment.tz.guess(),
      user_group:this.user_group,
      user_id:this.user_id,
    };
    if (f==0) {
      this.loaderService.display(true);
    }    this.nas.getAssesmentListByDate(sendJson).subscribe((result) => {
      if (result.status === 'Success') {
        this.loaderService.display(false);
        // console.log(result.data);
        this.assesment_list = result.data;
      } else {
        this.loaderService.display(false);
        this.assesment_list = [];
      }
    }, (err) => {
      console.log(err);
    });
  }

}
