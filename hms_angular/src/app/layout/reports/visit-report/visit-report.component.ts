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
  selector: 'app-visit-report',
  templateUrl: './visit-report.component.html',
  styleUrls: ['./visit-report.component.scss']
})
export class VisitReportComponent implements OnInit {
	private notifier: NotifierService;
	public visitdate = new Date(); 
	public settings = AppSettings;
	public visit_list: any = [];
  constructor(private exportAsService: ExportAsService, private modalService: NgbModal,private loaderService : LoaderService,private router: Router, public rest:ReportService,notifierService: NotifierService) {
  this.notifier = notifierService;
   }

  ngOnInit() {
  	this.visitdate = defaultDateTime();
  	this.getVisitListByDate();
  }
  public term = ''
  public getToday(): string 
  {
    return new Date().toISOString().split('T')[0]
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  public formatTime (time) {
    return  formatTime(time);
  }
  public getVisitListByDate() {
    const sendJson = {
      dateVal : this.formatDateTime (this.visitdate),
      timeZone: moment.tz.guess()
    };
    
    this.rest.getVisitListByDate(sendJson).subscribe((result) => {
      this.loaderService.display(false);
      if (result.status === 'Success') {
        this.visit_list = result.data;
      } else {
        this.visit_list = [];
      }
    }, (err) => {
      console.log(err);
    });
  }

  public pdfexport($id) {
   
  	 const post3Data = {
      timeZone: moment.tz.guess(),
      patient_id : $id,
      visitdate : this.visitdate
    };
    
      this.loaderService.display(true);
      this.rest.downloadmedicalPdf_visitdate(post3Data).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        window.open(this.settings.API_ENDPOINT+result.data);
        
      }
      else{
        this.notifier.notify("error",result.message)
      }
    })
    
  }
}
