import { Component, OnInit, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
import { AppSettings } from './../../../app.settings';
import {NgbModal, ModalDismissReasons,NgbModalRef} from '@ng-bootstrap/ng-bootstrap';
import { formatTime, formatDateTime, formatDate,defaultDateTime } from '../../../shared/class/Utils';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';

@Component({
  selector: 'app-test-result',
  templateUrl: './test-result.component.html',
  styleUrls: ['./test-result.component.scss']
})
export class TestResultComponent implements OnInit {
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() listStatus :any = [];
  public image : any;
  private modalRef: NgbModalRef;
  private modalRefs: NgbModalRef;
  public p = 50;
  public now = new Date();
  public date: any;
  public collection: any = '';
  public types: any = [];
  public fileName : any ;
  page = 1;
  public appdata = {
   mrno: '',
   patient_no:'',
   sample_type:'',
   test:'',
   search: '',
  };

  public appdatas = {
    mrno: '',
    patient_no:'',
    patient_name:'',
    collection_type:1,
    status:'',
    remarks: '',
    attach_id:'',
    document: '',
    document_type : '',
   document_description : '',
   filepath : '',
  sample_id : 0,
   };

  user_rights: any;
  user: any;
  notifier: NotifierService;
  public collection_list: any = [];
  public splitted : any = [];
  start: number;
  limit: number;
  public p_no: '';
  searching = false;
  searchFailed = false;
  public get_collection: any = [];
  public type_list : any = []; 
  public test_list : any = [];
  public collected_date: any;
  public Collection_id: '';
  public status: string;
  closeResult: string;
  constructor(private loaderService: LoaderService ,
     public notifierService: NotifierService, 
     private modalService: NgbModal, 
     public rest: ConsultingService)
      {
        this.notifier = notifierService;
      }


  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getCollection();
    this.formatDateTime ();
    this.getdroptest(); 
  }
  
  public format_date(time)
  {
    return formatDate(time)
  }
 
  public selectStatus(val) {
    this.appdatas.collection_type = val;
  }
  public getStatus()
  {
    if(this.collection_list.length > 0)
    {
      this.listStatus = []
      var i=0
      for(let data of this.collection_list)
      {
          this.listStatus[i] = data.CURRENT_STATUS
          i=i+1
      }
    }
  }

  public getToday() 
  {
    return moment(new Date()).format('YYYY-MM-DD')
 }

 public filePath(sampleid,filename)
  {
    var path = "";
    var sample="/";
    path  = AppSettings.API_ENDPOINT+AppSettings.LABRESULT_UPLOAD_PATH + sampleid + sample + filename;
    
    return path;
  }
public getdroptest() {
  const myDate = new Date();
  this.loaderService.display(true);
  this.rest.getdroptest().subscribe((data: {}) => {
    this.loaderService.display(false);
    if (data['status'] === 'Success') {
      if (data['test_list']['status'] === 'Success') {
        this.test_list = data['test_list']['data'];
      }
      if (data['type_list']['status'] === 'Success') {
        this.type_list = data['type_list']['data'];
      }
    }
  });
}


public addattachment(data) {
  this.appdatas.mrno = data.MR_NO;
  this.appdatas.patient_no = data.OP_REGISTRATION_NUMBER;
  this.appdatas.patient_name = data.FIRST_NAME;
  this.appdatas.status = data.CURRENT_STATUS;
  this.appdatas.attach_id = data.SAMPLE_ID;
  this.appdatas.remarks = data.RESULT_REMARKS;
  this.appdatas.filepath = data.RESULT_FILE_NAME;
  this.appdatas.sample_id = data.SAMPLE_ID;
}

public removeattachment(sample_id,i)
  {
   
    if(sample_id != 0)
    {
      var postData = {
        sample_id : sample_id
      }
      this.loaderService.display(true);
      this.rest.removefile(postData).subscribe((result) => {
        this.loaderService.display(false);
        if(result.status == "Success")
        {
          this.notifier.notify("success",result.message)
          this.appdatas.filepath = "";
          this.getCollection();
        }
        else
        {
          this.notifier.notify("error",result.message)
        }
      }, (err) => {
        console.log(err);
      });
    }
  }

public changeStatus(sample_id,i)
  {
   
    if(sample_id != "" && sample_id != null && this.listStatus[i] > 0)
    {
      var postData = {
        sample_id : sample_id,
        status : this.listStatus[i]
      }
      this.loaderService.display(true);
      this.rest.changeStatus(postData).subscribe((result) => {
        this.loaderService.display(false);
        if(result.status == "Success")
        {
          this.notifier.notify("success",result.message)
        }
        else
        {
          this.notifier.notify("error",result.message)
        }
        this.listStatus = []
      }, (err) => {
        console.log(err);
      });
    }
  }


  public getCollection(page= 0) {
    this.status = '';
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      collection_id: '',
      start: this.start,
      limit : this.limit,
      //search_text: this.appdata.search,
      mrno: this.appdata.mrno,
      patient_no:this.appdata.patient_no,
      sample_type:this.appdata.sample_type,
      test:this.appdata.test,
    };
    this.loaderService.display(true);
   this.rest.searchCollection(post2Data).subscribe((result: {}) => {
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.collection_list = result['data'];
        this.collection = result['total_count'];
     }
     this.loaderService.display(false);
   });
  }

  private open(content) {
    this.modalRef = this.modalService.open(content,{ariaLabelledBy: 'modal-basic-title',size: 'lg' ,windowClass:"col-md-12"});
    
    this.modalRef.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  getFileDetails($event) {
    let file = $event.target.files[0];
    this.fileName = file.name;
       this.readThis($event.target);
      
    }
  public attachcollection() {
    if (this.appdatas.status === '') {
      this.notifier.notify( 'error', 'Please Select Status!' );
    } 
    else if (this.appdatas.document === '') {
      this.notifier.notify( 'error', 'Please Select File!' );
    } 
    else {
      //console.log(this.image);
      this.splitted = this.image.split(","); 
    const postData = {
      attach_id: this.appdatas.attach_id,
      user_id: this.user.user_id,
      mrno: this.appdatas.mrno,
      patient_no: this.appdatas.patient_no,
      status: this.appdatas.status,
      collection_type : this.appdatas.collection_type,
      remarks : this.appdatas.remarks,
      files : this.splitted[1],
      doc_type : this.appdatas.document_type,
      doc_description : this.appdatas.document_description
      };
      this.loaderService.display(true);
      this.rest.attachcollection(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify( 'success' , 'Attachment saved successfully..!' );
          this.appdatas.filepath = result['data']['file'];
          // this.appdatas.remarks="";
          // this.appdatas.collection_type=1;
           this.appdatas.document='';
          // this.appdatas.status='';
          this.getCollection();

        } else {
          this.loaderService.display(false);
          this.notifier.notify( 'error', ' Failed' );
        }
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

  readThis(inputValue: any): void {
    var file:File = inputValue.files[0];
    var myReader:FileReader = new FileReader();
  
    myReader.onloadend = (e) => {
      this.image = myReader.result;
      
    }
    myReader.readAsDataURL(file);
  }
public getSearchlist(page= 0) {
  const limit = 100;
    this.start = 0;
    this.limit = limit;
    const post2Data = {
      collection_id: '',
      start: this.start,
      limit : this.limit,
      //search_text: this.appdata.search,
      mrno: this.appdata.mrno,
      patient_no:this.appdata.patient_no,
      sample_type:this.appdata.sample_type,
      test:this.appdata.test,
    };
    this.loaderService.display(true);
   this.rest.searchCollection(post2Data).subscribe((result: {}) => {
     this.status = result['status'];
     if (result['status'] === 'Success') {
      this.loaderService.display(false);
        this.collection_list = result['data'];
        this.collection = result['total_count'];
     }
     else{
      this.collection_list = result['data'];
     }
     this.loaderService.display(false);
   });
}

  
  public clear_search() {
  if (this.appdata.search !== '') {
 // this.clearForm();
  this.status = '';
  // this.getCPTlist();
}
}
public formatDateTime () {
    if (this.now ) {
      this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
}
