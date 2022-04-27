import { Component, OnInit } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LoaderService} from '../../../../shared';
import { OpRegistrationService, DoctorsService, NursingAssesmentService } from '../../../../shared';
import { BillingService } from 'src/app/shared/services/billing.service';
import moment from 'moment-timezone';
import { Moment } from 'moment';
import { AppSettings } from 'src/app/app.settings';
import { formatTime, formatDateTime, formatDate } from '../../../../shared/class/Utils';
@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.scss']
})
export class SearchComponent implements OnInit {

  public search={
    fromDateval: new Date(),
    toDateval: new Date(),
    transactionid:'',
    transactionStatus:'',
    direction:'',
    file_name:'',
    minRecordCount:'',
    maxRecordCount:''
  }
  public user_rights: any = {};
  public user_data: any;
  public search_list : any = [];
  public direction_list=[];
  public transactionid_list=[];
  public transactionStatus_list=[];
  public file_data: any;
  public status: any;
  public start = 0;
  public userTz: string;
  public activationDate:Date;
  time: any;
  constructor(  public bill: BillingService, public nas: NursingAssesmentService, public Doc: DoctorsService, private loaderService: LoaderService, public rest: OpRegistrationService , public notifier: NotifierService ) {
  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.search.fromDateval = new Date(this.search.toDateval.getFullYear(), this.search.toDateval.getMonth(), 1);
    this.searchTransactions();
    this.searchTransactionParams();
    this.time = moment.tz.guess();
  }
  public searchTransactionParams() {
    const postData = {};
  this.loaderService.display(true);
  this.rest.searchTransactionParams(postData).subscribe((result: {}) => {
  // this.status = result['status'];
   if (result['transactionID'] != ''&& result['transactionStatus'] != ''&& result['direction'] != '') {
      this.loaderService.display(false);
      this.transactionid_list =result['transactionID'];
      this.transactionStatus_list=result['transactionStatus'];
      console.log("this.transactionStatus_list "+this.transactionStatus_list);
      
      this.direction_list=result['direction'];
   }else{ 
      this.loaderService.display(false);
   }
  });
  }
  public searchTransactions() {
    console.log(" searchTransactions   ");
    
    const postData = {
      direction:this.search.direction,
      transactionID:this.search.transactionid,
      transactionStatus:this.search.transactionStatus,
      transactionFileName:this.search.file_name,
      transactionFromDate:this.formatDate(this.search.fromDateval),
      transactionToDate:this.formatDate(this.search.toDateval),
      minRecordCount:this.search.minRecordCount ,
      maxRecordCount:this.search.maxRecordCount,
      timeZone:this.time
    };
  this.loaderService.display(true);
  this.rest.searchTransactions(postData).subscribe((result: {}) => {
  this.status = result['status'];
   if (result['status'] == 'Success') {
    //  this. clear_searchFilter();
      this.loaderService.display(false);
      this.notifier.notify( 'success', ' Success', result['status']);
      console.log(result);
      this.search_list = result['message']['File'];
      // this.notDownloadedfile_list = result['message']['File'];
      // console.log('this.notDownloadedfile_list  ' + this.notDownloadedfile_list);
      // this.notDownloadedfile_colletion = this.notDownloadedfile_list.length;
      // console.log('collection size' + this.notDownloadedfile_colletion);
      // this.loaderService.display(false);
  
   }else{
    this.notifier.notify( 'error',' '+ result['status']);
    // this. clear_searchFilter();
      // this.GetNewTransactions();
      // this.claim_flag = this.claim_collection;
      // console.log('collection size' + this.collection);
      this.loaderService.display(false);
   }
  });
  }
  public filePath(filename)
  {
    var path = "";
    path  = AppSettings.API_ENDPOINT+AppSettings.XML_FILE_PATH + filename;
    return path;
  }
   public formatDate (date) {
    return  formatDate(date);
  }
  public clear_searchFilter(){
  this.search={
    toDateval: new Date(),
    fromDateval: new Date(this.search.toDateval.getFullYear(), this.search.toDateval.getMonth(), 1),
    transactionid:'',
    transactionStatus:'',
    direction:'',
    file_name:'',
    minRecordCount:'',
    maxRecordCount:''
  }
  console.log(" clear_searchFilter");
  
  }
}
