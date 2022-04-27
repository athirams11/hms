import { Component, OnInit, HostListener, Input, Output, EventEmitter, NgModule, ViewChild, OnChanges, SimpleChanges } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { FormControl, FormBuilder } from '@angular/forms';
import { NgxLoadingComponent } from 'ngx-loading';
import { LoaderService} from '../../../../shared';
import moment from 'moment-timezone';
import { OpRegistrationService, DoctorsService, NursingAssesmentService } from '../../../../shared';
import { formatTime, formatDateTime, formatDate } from '../../../../shared/class/Utils';
@Component({
  selector: 'app-new-remittence',
  templateUrl: './new-remittence.component.html',
  styleUrls: ['./new-remittence.component.scss']
})
export class NewRemittenceComponent implements OnInit {

  public status: any;
  public notDownloadedfile_list: any = [];
  public notDownloadedfile_colletion;
  p = 10;
  pc = 100;
  public claim_flag;
  public toDateval = new Date();
  public submitedFile_list: any = [];
  public fromDateval =new Date(this.toDateval.getFullYear(), this.toDateval.getMonth(), 1);
  time: any;
  constructor( public nas: NursingAssesmentService, public Doc: DoctorsService, private loaderService: LoaderService, private modalService: NgbModal, public rest: OpRegistrationService , public notifier: NotifierService ) {
  }
  ngOnInit() {
    this.time = moment.tz.guess();
  }
  public formatDateTime (data) {
    return formatDateTime(data);
}

  public GetNewTransactions() {
    const postData = {
  };
  this.loaderService.display(true);
  this.rest.GetNewTransactions(postData).subscribe((result: {}) => {
  this.status = result['status'];
   if (result['status'] == 'Success') {
      // console.log(result);
      this.loaderService.display(false);
      this.notDownloadedfile_list = result['message']['File'];
      // console.log('this.notDownloadedfile_list  ' + this.notDownloadedfile_list);
      this.notDownloadedfile_colletion = this.notDownloadedfile_list.length;
      // console.log('collection size' + this.notDownloadedfile_colletion);
      this.loaderService.display(false);
  
   }else{
      // this.GetNewTransactions();
      // this.claim_flag = this.claim_collection;
      // console.log('collection size' + this.collection);
      this.loaderService.display(false);
   }
  });
  }
  public searchNewTransactions() {
    
    const postData = {
      transactionFromDate:formatDateTime(this.fromDateval),
      transactionToDate:formatDateTime(this.toDateval),
      timeZone:this.time
  };
  this.loaderService.display(true);
  this.rest.searchNewTransactions(postData).subscribe((result: {}) => {
  this.status = result['status'];
   if (result['status'] == 'Success') {
      // console.log(result);
      this.loaderService.display(false);
      this.notDownloadedfile_list = result['message']['File'];
      // console.log('this.notDownloadedfile_list  ' + this.notDownloadedfile_list);
      this.notDownloadedfile_colletion = this.notDownloadedfile_list.length;
      // console.log('collection size' + this.notDownloadedfile_colletion);
      this.loaderService.display(false);
  
   }else{
      // this.GetNewTransactions();
      // this.claim_flag = this.claim_collection;
      // console.log('collection size' + this.collection);
      this.loaderService.display(false);
   }
  });
  }
  
}
