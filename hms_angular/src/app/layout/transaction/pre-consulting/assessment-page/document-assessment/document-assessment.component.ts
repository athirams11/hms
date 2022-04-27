import { Component, OnInit, Input,NgModule,ViewChild, OnChanges, SimpleChanges, Output, EventEmitter } from '@angular/core';
import { NursingAssesmentService,ConsultingService} from './../../../../../shared/services'
import { AppSettings } from './../../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import {DatePipe, CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { NgxLoadingComponent }  from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { BrowserModule } from '@angular/platform-browser';
import * as moment from 'moment';
import Moment from 'moment-timezone';
import { flattenStyles } from '@angular/platform-browser/src/dom/dom_renderer';
import { LoaderService } from '../../../../../shared';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../../../shared/class/Utils';
// import {require} from 'lodash' ;
declare var require: any;

@Component({
  selector: 'app-document-assessment',
  templateUrl: './document-assessment.component.html',
  styleUrls: ['./document-assessment.component.scss']
})
@NgModule({
  imports: [CommonModule]
})
export class DocumentAssessmentComponent implements OnInit, OnChanges {
  
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0; 
  @Input() selected_visit: any = [];
  todaysDate = new Date();
  @ViewChild('ngxLoading') ngxLoadingComponent: NgxLoadingComponent;
  @Input() consultation_id: number = 0;
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  public document_form_values : any = [];
  private notifier: NotifierService;
  public module_id : number;
  public refer_id : number;
  public file_name : string;
  public doc_type : string;
  public loading = false;
  public doc_date : string;
  public doc_description : string;
  public base64_file_str : string;
  public fileName : any ;
  public image : any;
  public date :  Date;
  dateVal = new Date();
  public myWar : any ;
  public splitted : any = [];
  public user_rights : any ={};
  public user_data : any ={};
  public  user : any = {};
  public documents_data = {
   document : '',
   document_date : new Date(),
   document_type : '',
   document_description : '',
   user_id:0
  }
  docs_list : any = [];
  type_list: any;
  current_date :any;
  
  
 constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:NursingAssesmentService,public rest:ConsultingService,notifierService: NotifierService)
  {
    this.notifier = notifierService;
   }
  
  ngOnInit() {
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.documents_data.document_date = defaultDateTime();
    this.dateVal = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.documents_data.user_id = this.user_data.user_id
     this.getDocuments();
     this.get_type();
    // this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  ngOnChanges(changes: SimpleChanges): void {
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.documents_data.document_date = defaultDateTime();
    this.dateVal = defaultDateTime();
    this.todaysDate = defaultDateTime();
    this.documents_data.user_id = this.user_data.user_id
    this.getDocuments();
     this.get_type();
  }
  // today: Date = new Date();
  // selectedFile = null;
  getFileDetails($event) {
    let file = $event.target.files[0];
    this.fileName = file.name;
    // let file = event.target.files[0];
    // let filename = Event.target.files[0].name;
        // let name = file.value;
       //let file = event.target.files[0].name;
       this.readThis($event.target);
      //  console.log("file");
      //  console.log(file);
      // var filename = fileInput.target.file.name;
      // console.log(event);
      
    }
//     (fileInput: Event){
//     let file = fileInput.target.files[0];
//     let fileName = file.name;
// }
    

readThis(inputValue: any): void {
  // console.log("inputValue");
  // console.log(inputValue);
  var file:File = inputValue.files[0];
  var myReader:FileReader = new FileReader();

  myReader.onloadend = (e) => {
    this.image = myReader.result;
  }
  myReader.readAsDataURL(file);
  
//console.log(this.splitted)
}
public MethodName($scope)
{
    $scope.date = new Date();
}
public getEvent()
{
  this.save_notify = 1
  this.saveNotify.emit(this.save_notify)
}
 
public documentsCheck()
{
  //console.log(this.documents_data);
  if(this.documents_data.document  == ''|| this.documents_data.document == null)
  {
    this.notifier.notify( 'error', 'Please select a document !' );  
    return false;
  }
 if(this.documents_data.document_date == null)
  {
    this.notifier.notify( 'error', 'Please select date !' );  
    return false;
  }
  
  else if (this.documents_data.document_type == '')
   {
     this.notifier.notify( 'error', 'Please enter documet type !' );  
     return false;
   }
  else if(this.documents_data.document_description == '')
  {
    this.notifier.notify( 'error', 'Please enter document description !' );  
    return false;
  } 
  else
  {
    // console.log(this.documents_data);
    this.splitted = this.image.split(","); 
    var postData = {
      assessment_id:this.assessment_id,
      patient_id:this.patient_id,
       file_name : this.fileName,
       base64_file_str : this.splitted[1],
      refer_id : this.assessment_id,
      module_id :17,
      doc_date : this.documents_data.document_date,
      client_date: this.documents_data.document_date,
      doc_type : this.documents_data.document_type,
      doc_description : this.documents_data.document_description,
      user_id : this.documents_data.user_id,
      timeZone: Moment.tz.guess()
    }

  // this.loading = true;
  this.loaderService.display(true);
    this.rest2.saveDocuments(postData).subscribe((result) => {
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      //this.router.navigate(['/product-details/'+result._id]);
      //console.log(result);
      
      window.scrollTo(0, 0)
      if(result["status"] == "Success")
      {
        // this.loading = false;
        this.loaderService.display(false);
        this.notifier.notify( 'success', "Document details saved successfully..!" ); 

        this.getDocuments();
        this.clearForm();

      }
      else{
        // this.loading = false;
        this.loaderService.display(false);
        this.notifier.notify( 'error',' Failed' );
      }
    }) 
  }
}
public clearForm()
  {
    this.documents_data = {
      
      document : "",
      document_date: defaultDateTime(),
      document_description:'',
      document_type : '',
      user_id : this.user_data.user_id
      
    }
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
  public documentPath (filename)
  {
    var path = '';
    path  = AppSettings.API_ENDPOINT+AppSettings.DOCUMENT_UPLOAD_PATH + filename;

    return path;
  }

  public getDocuments()
  {
    let post2Data = {
      module_id :17,
      patient_id:this.patient_id
    }
    // this.loading = true;
    this.loaderService.display(true);
   this.rest2.getDocuments(post2Data).subscribe((result: {}) => {
     if(result['status'] == 'Success')
     {

        //console.log(result);
        this.docs_list = result['data'];
        // this.loading = false;
        this.loaderService.display(false);

     } else {
      // this.loading = false;
      this.loaderService.display(false);
      this.docs_list = []
     }

   });
  }
 

  public get_type() {
    const postData = {
      master_id : 10
    };
    this.rest.get_Documenttype(postData).subscribe((result) => {
      if (result['status'] === 'Success') {
        this.type_list = result['master_list'];
        //console.log('master_list  =' + result);
      } else {
        this.type_list = [];
      }
    }, (err) => {
      console.log(err);
    });
  }


}
