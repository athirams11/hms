import { Component, OnInit, Input, EventEmitter, Output, SimpleChanges, ViewChild, ElementRef } from '@angular/core';
import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LoaderService, NursingAssesmentService, ConsultingService } from 'src/app/shared';
import { DatePipe } from '@angular/common';
import { Router } from '@angular/router';
import { NotifierService } from 'angular-notifier';
import { defaultDateTime, formatTime, formatDateTime, formatDate, formatByDate, getTimeZone} from 'src/app/shared/class/Utils';
import { jsPDF } from "jspdf";
import html2canvas from 'html2canvas';
import { ExportAsConfig, SupportedExtensions, ExportAsService } from 'ngx-export-as';
import { AppSettings } from 'src/app/app.settings';
@Component({
  selector: 'app-sick-leave-certificate',
  templateUrl: './sick-leave-certificate.component.html',
  styleUrls: ['./sick-leave-certificate.component.scss']
})
export class SickLeaveCertificateComponent implements OnInit {
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() doctor_id = 0 ;
  @Input() selected_visit: any = [];
  @Input() save_notify: number ;
  @Output() saveNotify = new EventEmitter();
  public config: ExportAsConfig = {
    type: 'pdf',
    elementIdOrContent: 'certificate',
    options: {
      jsPDF: {
        orientation: 'portrait'
      }
    }
  };
  public sickLeave = {
    patient_id : 0,
    assessment_id  : 0,
    user_id : 0,
    sickReason : "",
    duration : 0,
    patient_sick_leave_id : 0,
    sickFromdate : new Date(),
    sickTodate :new Date(),
    issueddate : new Date(),
    client_date : this.formatDateTime(new Date()),
    date : defaultDateTime(),
    timeZone: getTimeZone(),
  }
  public gender : any = [ "Female","Male"]
  public gender_sur : any = [ "Ms.","Mr."]  
  notifier: NotifierService;
  user_data: any;
  todaysDate = defaultDateTime();
  public now = defaultDateTime();
  public sick_data: any = [];
  public institution = JSON.parse(localStorage.getItem('institution'));
  public logo_path = JSON.parse(localStorage.getItem('logo_path'));
  closeResult: string;
  public sick_certificate_data: any = [];
  public settings = AppSettings;
  previous_sick_id: number;
  previous_assessment_id: number;
  previous_patient_id: number;
  constructor(
    private exportAsService: ExportAsService,
    private modalService: NgbModal,
    private loaderService: LoaderService, 
    public datepipe: DatePipe, 
    private router: Router, 
    public rest2: NursingAssesmentService, 
    public rest: ConsultingService, 
    notifierService: NotifierService) {
    this.notifier = notifierService;
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.getNumberOfDays()
  }

  ngOnInit() {
    this.getNumberOfDays()
    this.getPatientSickLeave(0,0,'')
    this.listPatientSickLeave()
  }
  ngOnChanges(changes: SimpleChanges) {
    this.sickLeave.patient_sick_leave_id = 0
    this.getNumberOfDays()
    this.getPatientSickLeave(0,0,'')
    this.listPatientSickLeave()
  }
  exportAs(type: SupportedExtensions, opt?: string) {
    console.log(this.config)
    this.exportAsService.save(this.config, this.sick_certificate_data.CERTIFICATE_NUMBER+'_'+ new Date().getTime() ).subscribe(() => {
      // save started
    });
  }
  public getToday(): string 
  {
    return new Date().toISOString().split('T')[0]
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
  public formatByDate (data) {
    return formatByDate(data);
}
downloadFile() {

    const postData = {
      assessment_id : this.previous_assessment_id,
      patient_id : this.previous_patient_id,
      patient_sick_leave_id : this.previous_sick_id
    };
  this.loaderService.display(true);
  this.rest.downloaSickleavePdf(postData).subscribe((result) => {
    this.loaderService.display(false);
    if (result['status'] === 'Success') {
      const FileSaver = require('file-saver');
      //  window.open(this.settings.API_ENDPOINT+result.data);
      const pdfUrl = this.settings.API_ENDPOINT+result.data;
      const pdfName = result.filename+'_'+ new Date().getTime()+'.pdf';
      FileSaver.saveAs(pdfUrl, pdfName);
    }
  })
}
  public getNumberOfDays()
  {
    if(this.sickLeave.sickTodate != null && this.sickLeave.sickFromdate != null)
    {
      var from = new Date(this.sickLeave.sickTodate)
      var to = new Date(this.sickLeave.sickFromdate)
      // To calculate the time difference of two date               
      var duration = from.getTime() - to.getTime();                                                                                       
      // To calculate the no. of days between two dates 
      this.sickLeave.duration =  Math.round(duration / (1000 * 3600 * 24)); 
    }
    else
    {
      this.sickLeave.duration = 0
    }
  }
  public savePatientSickLeave() 
  {
    if(this.sickLeave.sickReason === '') 
    {
      this.notifier.notify( 'error', 'Please enter sick leave reason!' );
    } 
    else if(this.sickLeave.issueddate === null) 
    {
      this.notifier.notify( 'error', 'Please enter issued date!' );
    } 
    else if(this.sickLeave.sickFromdate === null) 
    {
      this.notifier.notify( 'error', 'Please enter from date!' );
    } 
    else if(this.sickLeave.sickTodate === null) 
    {
      this.notifier.notify( 'error', 'Please enter to date!' );
    } 
    else {
      this.sickLeave.user_id = this.user_data.user_id
      this.sickLeave.patient_id = this.patient_id
      this.sickLeave.assessment_id = this.assessment_id
      this.sickLeave.issueddate = this.sickLeave.issueddate
      this.loaderService.display(true);
      this.rest.savePatientSickLeave(this.sickLeave).subscribe((result) => {
        this.loaderService.display(false);
        this.save_notify = 2
        this.saveNotify.emit(this.save_notify)
        if (result.status == 'Success') 
        {
          this.sickLeave.patient_sick_leave_id = result.data_id
          this.notifier.notify( 'success', 'Sick leave details saved successfully..!' );
          this.getPatientSickLeave(0,0,'')
          this.listPatientSickLeave()
        } 
        else 
        {
          this.notifier.notify( 'error', result.msg );
        }
      });
    }
  }
  public getPatientSickLeave(patient = 0,sick = 0,value) {
    
    var patient_id = 0
    var sick_id = 0
    var assessment_id = 0
    if(patient == 0 && sick == 0)
    {
      this.previous_patient_id = this.patient_id
      this.previous_assessment_id = this.assessment_id
      this.previous_sick_id = this.sickLeave.patient_sick_leave_id
      patient_id = this.patient_id,
      assessment_id = this.assessment_id,
      sick_id = this.sickLeave.patient_sick_leave_id
    }
    else
    {
      this.previous_patient_id = patient
      this.previous_assessment_id = 0
      this.previous_sick_id = sick
      patient_id = patient,
      assessment_id = 0,
      sick_id = sick
    }
    const postData = {
      assessment_id : assessment_id,
      patient_id : patient_id,
      patient_sick_leave_id : sick_id
    };

    this.loaderService.display(true);
    this.rest.getPatientSickLeave(postData).subscribe((result: {}) => {
      this.loaderService.display(false);
      if (result['status'] == 'Success') 
      {
        this.sick_certificate_data = result['data'];
        // console.log(this.sick_certificate_data  )
        const sick_data = result['data'];
        this.sickLeave.sickReason = sick_data.SICK_REASON;
        this.sickLeave.sickFromdate = sick_data.FROM_DATE;
        this.sickLeave.sickTodate = sick_data.TO_DATE;
        this.sickLeave.issueddate = sick_data.ISSUED_DATE;
        this.sickLeave.duration = sick_data.DURATION;
        this.sickLeave.patient_sick_leave_id = sick_data.PATIENT_SICK_LEAVES_ID;
        if(patient != 0 && sick!= 0)
        {
          this.open(value);
        }
      } 
      else 
      {
        this.clear_sick()
      }
    });
  }
  public listPatientSickLeave() {
    const postData = {
      patient_id : this.patient_id,
    };
    this.loaderService.display(true);
    this.rest.listPatientSickLeave(postData).subscribe((result: {}) => {
      this.loaderService.display(false);
      if(result['status'] == 'Success') 
      {
        this.sick_data = result['data'];
      } 
      else 
      {
        this.sick_data = []
      }
    });
  }
  public clear_sick()
  {
    this.sickLeave.sickReason = ""
    this.sickLeave.sickTodate = new Date()
    this.sickLeave.sickTodate = new Date()
    this.sickLeave.issueddate = new Date()
    this.sickLeave.duration = 0
  }
  public open(content)
  {
    this.modalService.open(content, {size: 'lg',windowClass: 'modal-ip',centered:true}).result.then((result) => {
      // this.modalService.open(content, {size: 'lg',centered:true}).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return  `with: ${reason}`;
    }
  }}
