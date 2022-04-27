import { Component, OnInit, HostListener, Input, Output, EventEmitter, NgModule, ViewChild, OnChanges, SimpleChanges } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService} from '../../../../shared';
import * as moment from 'moment';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { OpRegistrationService, DoctorsService, NursingAssesmentService } from '../../../../shared';
import { AppSettings } from 'src/app/app.settings';
import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { formatTime, formatDateTime, formatDate } from '../../../../shared/class/Utils';
import { saveAs } from 'file-saver'
import { Subscriber } from 'rxjs';
import { interval, observable, Subscription } from 'rxjs';

@Component({
  selector: 'app-claim-xml',
  templateUrl: './claim-xml.component.html',
  styleUrls: ['./claim-xml.component.scss']
})
export class ClaimXmlComponent implements OnInit {

  @Input() id: string;
  @Output() pageChange: EventEmitter<number>;
  public xml_file = {
    file_id: '',
    file_name: '',
    file_content : '',
    date:this.formatDateTime(new Date())
  };
  public subscription: Subscription;
  source = interval(5000);
  public f=0;
  public start = 0;
  public limit = 100;
  public claim_checkall;
  public unclaimed_list: any = {};
  public claimXml_list: any = [];
  public claim_flag;
  public claim_collectiom;
  public claim_collection: any = '';
  public collection: any = '';
  public status: any;
  public page = 1;
  public file_path:any;
  pc = 100;
  public user_rights: any = {};
  public user_data: any;
  closeResult: string;
  // public file_data=[];
  public file_data: any;
  constructor( private http: HttpClient,public nas: NursingAssesmentService, public Doc: DoctorsService, private loaderService: LoaderService, private modalService: NgbModal, public rest: OpRegistrationService , public notifier: NotifierService ) {
  }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.xmlClaimInvoiceList();
    this.subscription = this.source.subscribe(x => this.xmlClaimInvoiceList(this.page,1));

  }
  ngOnDestroy() {
    if (this.subscription) {  
      this.subscription.unsubscribe();
   }
  }
  private open(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title', size: 'lg', windowClass: 'col-md-12', }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason == ModalDismissReasons.BACKDROP_CLICK) {
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
  public xmlClaimInvoiceList(page = 0,f = 0) {
    const limit = 100;
    // this.page=page;
    const start = page * limit;
    this.start = start;
    this.limit = limit;
    // this.claimXml_list = [];
    const post2Data = {
    limit: this.limit,
    start: this.start
};
  if (f==0) {
    this.loaderService.display(true);
  } 
  this.rest.XmlClaimList(post2Data).subscribe((result: {}) => {
  this.status = result['status'];
   if (result['status'] === 'Success') {
   //   console.log(result);
      this.loaderService.display(false);
      this.claimXml_list = result['data'];
      this.claim_collection = result['count'];
      this.file_path = result['path'];
      this.claim_flag = this.claim_collection;
     // console.log(" this.file_path "+ this.file_path);
            // this.claim_check_box_flag = 0;
      // this.checkall === 0;
      // this.claim_dissable(this.claim_flag);
      // console.log('collection size' + this.collection);

      this.loaderService.display(false);

   }
   this.claim_flag = this.claim_collection;
  // console.log('collection size' + this.collection);
   this.loaderService.display(false);
 });
}
// public select_claim(event, i) {
//   console.log('event ' + event.SUBMISSION_FILE_ID);
//   let counter = 0;
//   // this.ins_company = event.OP_INS_PAYER;
//   // this.billing_id = event.BILLING_ID;
//   this.tpa_id = event.SUBMISSION_FILE_ID;
//   for (let index = 0; index < this.claimXml_list.length; index++) {
//     if (this.tpa_id === this.claimXml_list[index].SUBMISSION_FILE_ID && this.claimXml_list[index].check === 1) {
//       this.claimXml_list[index]['check'] = 0;
//       console.log('this.claimXml_list[index]  ' + JSON.stringify(this.claimXml_list[index]));
//       }
//     //    else if ( this.tpa_id === this.claimXml_list[index].SUBMISSION_FILE_ID ) {
//     //   // && this.claimXml_list[index].check !== 1
//     //   this.claimXml_list[index]['check'] = 1;
//     //   console.log('claimXml_list ' + JSON.stringify(this.claimXml_list[index]));

//     // }

//     // else if (this.tpa_id === this.claimXml_list[index].SUBMISSION_FILE_ID && this.claimXml_list[index].check === 1) {
//     // this.claimXml_list[index]['check'] = 0;
//     // console.log('this.claimXml_list[index]  ' + JSON.stringify(this.claimXml_list[index]));
//     // console.log('111111111111');

//     // }
//     // console.log('check val' + this.claimXml_list[index].check);
//     if (this.claimXml_list[index].check === 1) { counter ++; }
//   }

//   // console.log('check count' + counter);
//   if (counter === 0) {
//     // console.log('check counter' + JSON.stringify(this.claimXml_list));
//     this.tpa_id = 0;
//     // this.ins_company = 0;
//   }
// // check the api for geting
//   }
 public sendfile(list) {
    const postData = {
      file_id: list.SUBMISSION_FILE_ID,
      submission_date: new Date(),
      user_id : this.user_data.user_id,
      client_date: new Date()
    };
    this.loaderService.display(true);
    this.rest.testUploadSubmissionFile(postData).subscribe((result: {}) => {
    this.status = result['status'];
    if (result['status'] === 'Success') {
      this.notifier.notify( 'success',  result['message']+'\n'+result['errorReport']);
      this.xmlClaimInvoiceList();
      this.loaderService.display(false);
    } else {
      this.loaderService.display(false);
      this.xmlClaimInvoiceList();
      this.notifier.notify( 'error', result['message']);
   }
  });
 }
 public send_productionfile(list) {
  const postData = {
    file_id: list.SUBMISSION_FILE_ID,
    submission_date: new Date(),
    user_id : this.user_data.user_id,
    client_date: new Date()
  };
  this.loaderService.display(true);
  this.rest.UploadSubmissionFile(postData).subscribe((result: {}) => {
  this.status = result['status'];
  if (result['status'] === 'Success') {
    this.notifier.notify( 'success', result['message']);
    this.xmlClaimInvoiceList();
    this.loaderService.display(false);
  } else {
    this.loaderService.display(false);
    this.xmlClaimInvoiceList();
    this.notifier.notify( 'error', result['message']);
 }
});
}
  // public claim_selectAll() {
  //   // console.log('this.check_box_flag ' + this.check_box_flag);
  //   if (this.claim_check_box_flag === 0) {
  //     for (let index = 0; index < this.claimXml_list.length; index++) {
  //       this.claimXml_list[index]['check'] = 1;
  //       console.log(' this.claimXml_list' + JSON.stringify(this.claimXml_list));
  //     }
  //     this.claim_checkall = 1;
  //     this.claim_check_box_flag = 1;
  //   } else {
  //        this.claim_checkall = '';
  //        this.claim_check_box_flag = 0;
  //       //  console.log('else falg');
  //        for (let index = 0; index < this.claimXml_list.length; index++) {
  //         this.claimXml_list[index]['check'] = 0;
  //         // console.log(' this.claimXml_list[index][check]' + this.claimXml_list[index]['check']);
  //       }

  //   }
  // }


public reSubmissioniFile(data) {

  const post2Data = {
    submissin_file_id: data.SUBMISSION_FILE_ID,
    client_date: new Date()
};
this.loaderService.display(true);
this.rest.reGenerateSubmissionXml(post2Data).subscribe((result: {}) => {
this.status = result['status'];
 if (result['status'] === 'Success') {
  this.xmlClaimInvoiceList();
  this.notifier.notify( 'success', result['message']);
  this.loaderService.display(false);
 }
 else{
 this.loaderService.display(false);
 this.xmlClaimInvoiceList();
}
});
}

public getFileContent(data) {

  const postData = {
    file_id: data.SUBMISSION_FILE_ID
};
this.loaderService.display(true);
this.rest.getFileContent(postData).subscribe((result: {}) => {
this.status = result['status'];
 if (result['status'] === 'Success') {
  // this.notifier.notify( 'success', ' Success', result['status']);
  this.file_data = result;

  // console.log('file_data ' + JSON.stringify(this.file_data));

  this.xml_file.file_id = this.file_data.file_id;
  this.xml_file.file_name = this.file_data.file_name;
  this.xml_file.file_content = this.file_data.file_content;
  var format = require('xml-formatter');
  var xml = this.xml_file.file_content;
  var options = {indentation: '  ', stripComments: true, collapseContent: true, lineSeparator: '\n'};
  var formattedXml = format(xml,options);
  this.xml_file.file_content = formattedXml;
  this.loaderService.display(false);
 } else {
 this.loaderService.display(false);
//  this.notifier.notify( 'error', ' Error', result['message']);
 }
});
}

public saveFileContent() {
  const postData = {
    file_id: this.xml_file.file_id,
    file_content: this.xml_file.file_content
  };
  this.loaderService.display(true);
  this.rest.saveFileContent(postData).subscribe((result: {}) => {
  this.status = result['status'];
    if (result['status'] == 'Success') {
      this.notifier.notify( 'success',  result['message']);
      this.loaderService.display(false);
    } else {
    this.loaderService.display(false);
    this.notifier.notify( 'error',  result['message']);
    }
  });
}
// public filePath(filename)
// {
//   var path = "";
//   console.log(" this.file_path+filename  "+ this.file_path+filename);
  
//   path  = this.file_path+filename;
//   return path;
// }
public download(url,file_name){
  this.rest.download(url).subscribe((result: {}) => {
    this.status = result['status'];
    if (this.status !='') {
      saveAs(result, file_name);
      this.notifier.notify( 'success', ' Downloading ');
    }
   else{
  //   console.log(" error error error ");
     
    this.notifier.notify( 'error', 'page not found');
   }
    });
}

}
