import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { AppSettings } from 'src/app/app.settings';
import { DatePipe } from '@angular/common';
import { LoaderService, ReportService, ExcelService} from 'src/app/shared';
import { Router } from '@angular/router';
import moment from 'moment-timezone';
import * as XLSX from 'xlsx';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from 'src/app/shared/class/Utils';
import { ExportAsService, ExportAsConfig ,SupportedExtensions} from 'ngx-export-as';
import { SelectDropDownModule } from 'ngx-select-dropdown';
import { ModalDismissReasons, NgbModalOptions, NgbModal} from '@ng-bootstrap/ng-bootstrap';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import { FormsModule } from '@angular/forms';
import { resource } from 'selenium-webdriver/http';
@Component({
  selector: 'app-medical-report',
  templateUrl: './medical-report.component.html',
  styleUrls: ['./medical-report.component.scss']
})
export class MedicalReportComponent implements OnInit {
	private notifier: NotifierService;
 	public fromdate = new Date(); 
  public todate = new Date(); 
 	public p_number = ''; 
 	public settings = AppSettings;
  public status: any;
  public patient_list : any = [0];
  public collection: any = '';
   public p_no: '';
    searching = false;
    searchFailed = false;
   	constructor( private exportAsService: ExportAsService, private modalService: NgbModal,private loaderService : LoaderService,private router: Router, public rest:ReportService,notifierService: NotifierService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
  	this.fromdate = defaultDateTime();
    this.todate = defaultDateTime();
  	//this.pdfexport();
  }

public getToday(): string 
  {
    return new Date().toISOString().split('T')[0]
  }

  /*public getPatient()
  {
    if(this.p_number.length > 2)
    {
      this.getPatientList();
    }
  }*/

  public set_item($event) {
    const item = $event.item;
    this.p_number = item.OP_REGISTRATION_NUMBER;
   }

   public getno($event) {
    this.p_no = $event;
   }


cptsearch = (text$: Observable<string>) =>
 text$.pipe(
   debounceTime(500),
    distinctUntilChanged(),

    tap(() => this.searching = true),
    switchMap(term =>
     this.rest.getPatientList(term).pipe(

       tap(() => this.searchFailed = false),

       catchError(() => {
         this.searchFailed = true;
         return of(['']);
       })

       )

   ),
   tap(() => this.searching = false)

 )
  formatter = (x: {NAME: String, OP_REGISTRATION_NUMBER: String}) => x.OP_REGISTRATION_NUMBER;
  /*public getPatientList(page=0)
  {
    
    const postData = {
      
      search_text:this.p_number
    };
    this.loaderService.display(true);
    this.rest.getPatientList(postData).subscribe((result) => {
      if(result.status == "Success")
      {
        this.status = result['status'];
        this.loaderService.display(false);
        this.patient_list = result.data;
        this.collection=result.total_count
      }
      else
      {
         this.status = result['status'];
        this.patient_list = [];
        this.collection = 0;
       
        this.loaderService.display(false);
      }
    }, (err) => {
      console.log(err);
    });
  }*/

  public pdfexport() {
   if((this.p_number=== '' || this.p_number == null || this.p_number.length < 10) && (this.p_no!= '' || this.p_no != null || this.p_no.length >= 10)) {
     this.p_number =this.p_no
   }
  	 const post3Data = {
      timeZone: moment.tz.guess(),
      patient_id : this.p_number,
      fromdate : this.fromdate,
      todate : this.todate
    };
    if (this.p_number === '' || this.p_number == null || (this.p_number.length < 10)) {
      this.notifier.notify( 'error', 'Invalid Patient Number!' );
    } 
    else {
      console.log(this.p_number);
      this.loaderService.display(true);
      this.rest.downloadmedicalPdf(post3Data).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        //const FileSaver = require('file-saver');
        window.open(this.settings.API_ENDPOINT+result.data);
        //const pdfUrl = this.settings.API_ENDPOINT+result.data;
        //const pdfName = result.filename;
        //FileSaver.saveAs(pdfUrl, pdfName);
        this.p_no = "";
        this.p_number = "";
        this.fromdate = defaultDateTime();
        this.todate = defaultDateTime();
      }
      else{
        this.notifier.notify("error",result.message)
      }
    })
    }
  }
}
