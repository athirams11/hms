import { Component, OnInit,Input,NgModule,ViewChild,Output,EventEmitter, SimpleChanges, OnChanges } from '@angular/core';
import { ConsultingService,NursingAssesmentService} from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from './../../../../../shared/class/Utils';
import { AppSettings } from './../../../../../app.settings';
import {DatePipe, CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import { FormGroup, FormControl } from '@angular/forms';
import * as moment from 'moment';
import { NgxLoadingComponent }  from 'ngx-loading';
import { LoaderService } from '../../../../../shared';
@Component({
  selector: 'app-file-upload',
  templateUrl: './file-upload.component.html',
  styleUrls: ['./file-upload.component.scss']
})
export class FileUploadComponent implements OnInit,OnChanges {

  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() consultation_id: number = 0;
  @Input() selected_visit: any = [];
  todaysDate = defaultDateTime(); 
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  notifier: NotifierService;
  public loading = false;
  public document_form_values : any = [];
  public module_id : number;
  public refer_id : number;
  public file_name : string;
  public doc_type : string;
  public doc_date : string;
  public doc_description : string;
  public base64_file_str : string;
  public fileName : any ;
  public image : any;
  public date = defaultDateTime();
  dateVal = defaultDateTime();
  public myWar : any ;
  public splitted : any = [];
  public user_rights : any ={};
  public user_data : any ={};
  public  user : any = {};
  
  public documents_data = {
   document : '',
   document_date : defaultDateTime(),
   document_type : '',
   document_description : '',
  }
  docs_list : any = [];
  type_list: any = [];

  constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:ConsultingService,public rest:NursingAssesmentService,notifierService: NotifierService)
  {
    this.notifier = notifierService;
   }

   ngOnInit() {
    // this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
      this.get_type();
     this.getDocuments();
    // this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  ngOnChanges(changes: SimpleChanges) {
    this.get_type();
    this.getDocuments();
  }
  public getEvent()
  {
    if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
    {
      if(this.documents_data.document != '' || this.documents_data.document_description != '' ||
      this.documents_data.document_type != '')
      {
        this.save_notify = 1
        this.saveNotify.emit(this.save_notify)
      }
      else
      {
        this.save_notify = 0
        this.saveNotify.emit(this.save_notify)
      }
      
    }
    else
    {
      this.save_notify = 0
      this.saveNotify.emit(this.save_notify)
    }
    // console.log(this.saveNotify.emit(this.save_notify))
  }
  // today: Date = new Date();
  // selectedFile = null;
  getFileDetails($event) {
    let file = $event.target.files[0];
    this.fileName = file.name;
       this.readThis($event.target);
      
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

readThis(inputValue: any): void {
  var file:File = inputValue.files[0];
  var myReader:FileReader = new FileReader();

  myReader.onloadend = (e) => {
    this.image = myReader.result;
    
  }
  myReader.readAsDataURL(file);
}
public MethodName($scope)
{
    $scope.date = new Date();
}
 
 
  public documentsCheck()
{
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
     this.notifier.notify( 'error', 'Please select documet type !' );  
     return false;
   }
  else if(this.documents_data.document_description == '')
  {
    this.notifier.notify( 'error', 'Please enter document description !' );  
    return false;
  } 
  else
  {
    this.splitted = this.image.split(","); 
   // console.log("this.splitted  "+this.splitted );
    
    var postData = {
      assessment_id:this.assessment_id,
      patient_id:this.patient_id,
       file_name : this.fileName,
       base64_file_str : this.splitted[1],
      refer_id : this.assessment_id,
      module_id :17,
      doc_date : defaultDateTime(),
      timeZone : getTimeZone(),
      client_date: new Date(),
      doc_type : this.documents_data.document_type,
      doc_description : this.documents_data.document_description
      // test_methode : AppSettings.NURSING_ASSESMENT
    }

    // this.loading = true;
    this.loaderService.display(true);
    this.rest.saveDocuments(postData).subscribe((result) => {
      
      window.scrollTo(0, 0)
      this.save_notify = 2
      this.saveNotify.emit(this.save_notify)
      if(result["status"] == "Success")
      {
        // this.loading = false;
        this.loading = false;this.loaderService.display(false);
        this.notifier.notify( 'success','Document details saved successfully..!' ); 
        this.getDocuments();
        this.clearForm();

      }
      else{
        // this.loading = false;
        this.loading = false;this.loaderService.display(false);
        this.notifier.notify( 'error',' Failed' );
      }
    }) 
  }
}
public clearForm()
  {
    this.documents_data = {
      
      document : "",
      document_date: new Date(),
      document_description:'',
      document_type : ''
      
    }
  }

  public filePath(filename)
  {
    var path = "";
    path  = AppSettings.API_ENDPOINT+AppSettings.DOCUMENT_UPLOAD_PATH + filename;
    
    return path;
  }

  public getDocuments()
  {
    var post2Data = {
      module_id :17,
      patient_id:this.patient_id
    }
    // this.loading = true;
    this.loading = false;this.loaderService.display(true);
   this.rest.getDocuments(post2Data).subscribe((result: {}) => {
     if(result['status'] == 'Success')
     {
      // this.loading = false;
      this.loaderService.display(false);
        this.docs_list = result['data'];
        
       
     }
    //  this.loading = false;
    this.loaderService.display(false);
     
   });
  }
  public get_type()
  {
    let postData = {
      master_id : 10
    }
    this.rest2. get_filetype(postData).subscribe((result) => {
      if(result['status'] == 'Success') {
        this.type_list = result['master_list'];
      } else {
        this.type_list = [];
      }
    }, (err) => {
     // console.log(err);
    });
  }

}
