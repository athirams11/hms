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
import { concat } from 'rxjs';
@Component({
  selector: 'app-radiology-report',
  templateUrl: './radiology-report.component.html',
  styleUrls: ['./radiology-report.component.scss']
})
export class RadiologyReportComponent implements OnInit,OnChanges {

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
     this.getDocuments();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.getDocuments();
  }
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
  public filePath(filename)
  {
    var path = "";
    
    path  = AppSettings.API_ENDPOINT+AppSettings.RADIOLOGY_UPLOAD_PATH  + filename;
    
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
   this.rest.getradiologyReports(post2Data).subscribe((result: {}) => {
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
}
