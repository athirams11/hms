import { Component, OnInit } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LoaderService} from '../../../../shared';
import moment from 'moment-timezone';
import { OpRegistrationService, DoctorsService, NursingAssesmentService } from '../../../../shared';
import { BillingService } from 'src/app/shared/services/billing.service';
import { AppSettings } from 'src/app/app.settings';
import { formatTime, formatDateTime, formatDate } from '../../../../shared/class/Utils';
@Component({
  selector: 'app-submitted-files',
  templateUrl: './submitted-files.component.html',
  styleUrls: ['./submitted-files.component.scss']
})
export class SubmittedFilesComponent implements OnInit {

  public start = 0;
  public limit = 100;
  public datval:Date;
  public status: any;
  public submitedFile_collection: any = '';
  public submitedFile_list: any = [];
  public submit_flag;
  public claim_flag;
  public user_rights: any = {};
  public user_data: any;
  closeResult: string;
  page=1;
  pc=100;
  public claim_collection: any = '';
  
  public submited_files = {
    fromDateval: new Date(),
    toDateval: new Date(),
    search_text: '',
   };
  time: any;
  public reporterror: any = [];
  constructor(  public bill: BillingService, public nas: NursingAssesmentService, public Doc: DoctorsService, private loaderService: LoaderService, private modalService: NgbModal, public rest: OpRegistrationService , public notifier: NotifierService ) {
  }
  
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.submited_files.fromDateval = new Date(this.submited_files.toDateval.getFullYear(), this.submited_files.toDateval.getMonth(), 1);
    this.time = moment.tz.guess();
    this.submittedFileList();
  }


  public formatDate (date) {
    return  formatDate(date);
  }
  public formatDateTime (data) {
    return formatDateTime(data);
}
  public submittedFileList(page= 0) {
    const limit = 100;
    const start = page * limit;
    this.start = start;
    this.limit = limit;
    // this.claimXml_list = [];
    const postData = {
    search_text: this.submited_files.search_text,
    limit: this.limit,
    start: this.start,
    submission_upload_from: this.formatDateTime(this.submited_files.fromDateval),
    submission_upload_to: this.formatDateTime(this.submited_files.toDateval),
    timeZone:this.time
  };
  this.loaderService.display(true);
  this.rest.submittedFileList(postData).subscribe((result: {}) => {
  this.status = result['status'];
   if (result['status'] == 'Success') {
      // console.log(result);
      this.loaderService.display(false);
      this.submitedFile_list = result['data'];
     // console.log(" 00000000 this.submitedFile_list 0000000 -> "+ this.submitedFile_list);    
      this.submitedFile_collection = this.submitedFile_list.length;
      this.submit_flag = this.submitedFile_collection;
      // console.log('collection size' + this.collection);
  
      this.loaderService.display(false);
  
   }
   else{
    this.claim_flag = this.claim_collection;
    //  console.log('collection size' + this.collection);
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

  public clear_search() {
    this.submited_files.search_text ='';
  }
  private open(content,list) {
    if(list["SUBMISSION_ERROR_DETAILS"] != null || list["SUBMISSION_ERROR_DETAILS"] != '' || list["SUBMISSION_ERROR_DETAILS"] != 'null')
    {
      this.reporterror = list
      this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title',size:'lg' ,centered: true,windowClass:"col-md-12"}).result.then((result) => {
        this.closeResult = `Closed with: ${result}`;
      }, (reason) => {
        this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
      });
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
}
