import { Component, OnInit, Input, ViewChild, NgModule, SimpleChanges, OnChanges } from '@angular/core';
import {DatePipe, CommonModule } from '@angular/common';
import { NursingAssesmentService, ConsultingService } from './../../../../../shared/services';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import { AppSettings } from './../../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import { Router } from '@angular/router';
import { NgxLoadingComponent }  from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import { LoaderService } from '../../../../../shared';
import * as moment from 'moment';
@Component({
  selector: 'app-special-comments',
  templateUrl: './special-comments.component.html',
  styleUrls: ['./special-comments.component.scss']
})
export class SpecialCommentsComponent implements OnInit, OnChanges {
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() vital_values: any = [];
  @Input() selected_visit: any = [];
  todaysDate = new Date();
  public loading = false;
  public now = new Date();
  public date: any;
  @ViewChild('ngxLoading') ngxLoadingComponent: NgxLoadingComponent;
  public comments_data = {
    specialComments : '',
    previousComments: ''
    };
    user_data : any;
   public comment_arr: any = [];
   public previous_comment_arr: any = [];
  notifier: NotifierService;
  special_list = {
    special_comment: ''
  };
  // loading: boolean;




  constructor(public datepipe: DatePipe, private router: Router, private loaderService: LoaderService, public rest2: ConsultingService, notifierService: NotifierService) {
    this.notifier = notifierService;
   }


  ngOnInit() {
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.date = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.getSpecialcomments();
    this.getPreviousSpecialcomments();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.getSpecialcomments();
  }
  public save_specialComments() {
    const postData = {
      assessment_id: this.assessment_id,
      patient_id: this.patient_id,
      special_comment: this.comments_data.specialComments,
      client_date: new Date(),
      date: defaultDateTime(),
      user_id : this.user_data.user_id,
      timeZone : getTimeZone()
       };

      //  this.loading = true;
      this.loaderService.display(true);
    this.rest2.save_specialComments(postData).subscribe((result) => {
      // this.router.navigate(['/product-details/'+result._id]);
      // console.log(result);

      window.scrollTo(0, 0);
      if (result['status'] === 'Success') {
        // this.loading = false;
        this.loaderService.display(false);
        this.notifier.notify( 'success', ' Success', result.response );
        this.getSpecialcomments();
        this.clearComments();

      } else {
        // this.loading = false;
        this.loaderService.display(false);
        this.notifier.notify( 'error', ' Failed' );
      }
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

  public getSpecialcomments() {
    const post2Data = {
      assessment_id: this.assessment_id,
      patient_id: this.patient_id

    };
    // this.loading = true;
    this.loaderService.display(true);
   this.rest2.getSpecialcomments(post2Data).subscribe((result: {}) => {
     if (result['status'] === 'Success') {

      this.loaderService.display(false);
        //console.log(result);
        // this.special_list.special_comment = result['data.SPECIAL_COMMENT'];
        this.comment_arr = result['data'];
        this.comments_data.specialComments = this.comment_arr.SPECIAL_COMMENT;
        // console.log("comment_array_data "+this.comment_arr.SPECIAL_COMMENT);

      // console.log("this is the special commement "+this.special_list.special_comment);
     } else {
      this.comments_data.specialComments = result['data'];
     }

    this.loaderService.display(false);
   });
  }

  public  getPreviousSpecialcomments() {
    const post2Data = {
      assessment_id : this.assessment_id,
      patient_id : this.patient_id,
    };
    // this.loading = true;
    this.loaderService.display(true);
    this.rest2.getPreviousSpecialcomments(post2Data).subscribe((result: {}) => {

    if (result['status'] === 'Success') {
      // this.loading = false;
      this.loaderService.display(false);
      this.previous_comment_arr = result['data'];
      this.comments_data.previousComments = this.previous_comment_arr.SPECIAL_COMMENT;
    } else {
      this.comments_data.previousComments = result['data'];
    }
  });
  this.loaderService.display(false);
  }

  public clearComments() {
    this.comments_data = {

      specialComments : '',
      previousComments: this.comments_data.previousComments
   };
  }
}
