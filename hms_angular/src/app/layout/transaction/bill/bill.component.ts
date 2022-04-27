import { Component, OnInit, Input } from '@angular/core';
import { routerTransition } from '../../../router.animations';
import { NursingAssesmentService, OpVisitService, LoaderService, DoctorsService, BillService } from '../../../shared'
import { Router, NavigationEnd } from '@angular/router';
import { DatePipe } from '@angular/common';
import moment from 'moment-timezone';
import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { NotifierService } from 'angular-notifier';
import { formatTime, formatDateTime, formatDate, defaultDateTime, getTimeZone } from '../../../shared/class/Utils';
import { interval, observable, Subscription } from 'rxjs';

@Component({
  selector: 'app-bill',
  templateUrl: './bill.component.html',
  styleUrls: ['./bill.component.scss'],
  animations: [routerTransition()],
  providers: [DatePipe, OpVisitService]
})
export class BillComponent implements OnInit {


  @Input() visit_list: any = [];
  public subscription: Subscription;
  source = interval(5000);
  public f = 0;
  public user_rights: any = {};
  public loading = false;
  navigationSubscription;
  public assessment_menu_list: any = [];
  public dateVal = defaultDateTime();
  public assessment_id: number;
  public patient_id: number;
  public selected_visit: any = {};
  public assesment_list: any = [];
  public patient_details: any = [];
  public gender: any = ["Female", "Male"]
  public gender_sur: any = ["Ms.", "Mr."]
  @Input() page: any = "Visit";
  term: any;
  notifier: any;
  invoice_list: any;
  time: any;
  changeText = false;
  text: any;
  discountDetails: any;
  user: any;
  constructor(private loaderService: LoaderService,
    public Doc: DoctorsService,
    private modalService: NgbModal,
    public bill: BillService,
    public nas: NursingAssesmentService,
    notifierService: NotifierService,
    public datepipe: DatePipe,
    private router: Router) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      this.notifier = notifierService;
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
  }
  closeResult: string;
  ngOnInit() {

    // console.log(this.user)
    this.getAssesmentListByDate();
    this.get_investigation();
    this.time = moment.tz.guess();
    this.subscription = this.source.subscribe(x => this.getAssesmentListByDate(1));

  }
  initialiseInvites() {
    this.get_investigation();
    this.getAssesmentListByDate();
  }
  ngOnDestroy() {
    // avoid memory leaks here by cleaning up after ourselves. If we  
    // don't then we will continue to run our initialiseInvites()   
    // method on every navigationEnd event.
    if (this.navigationSubscription) {
      this.navigationSubscription.unsubscribe();
    }
    if (this.subscription) {
      this.subscription.unsubscribe();
    }
  }
  public showTpa(visit) {
    if (visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null) {
      if (visit.insurance != false) {
        var coin = []
        var rest = ''
        if (visit.insurance.OP_INS_IS_ALL == 1) {
          rest = visit.insurance.OP_INS_ALL_VALUE + '' + visit.insurance.OP_INS_ALL_TYPE
        }
        else {
          if (visit.insurance.co_ins && visit.insurance.co_ins.length > 0) {
            var i = 0
            for (let data of visit.insurance.co_ins) {
              coin[i] = '\n            ' + data.COIN_NAME + '  :  ' + data.COIN_VALUE + ' ' + data.COIN_VALUE_TYPE + ' '
              i = i + 1
            }
          }
        }
        var tpa = "TPA :  ";
        var company = "Company :  ";
        var Network = "Network :  ";
        var Deductable = "Deductable :  ";
        var restall = "\nRest of all :  ";
        var coins = "\nCo-Ins :";
        if (coin.length == 0) {
          coins = ''
        }
        if (visit.insurance.OP_INS_IS_ALL == 0) {
          restall = '';
          rest = ''
        }
        this.text = 'TPA  :  ' + visit.insurance.TPA_ECLAIM_LINK_ID + ' - ' + visit.insurance.TPA_NAME + '\n' +
          'Company  :  ' + visit.insurance.INSURANCE_PAYERS_ECLAIM_LINK_ID + ' - ' + visit.insurance.INSURANCE_PAYERS_NAME + '\n' +
          'Network  :  ' + visit.insurance.INS_NETWORK_NAME + '\n' +
          'Deductable  :  ' + visit.insurance.OP_INS_DEDUCTIBLE + ' ' + visit.insurance.OP_INS_DEDUCT_TYPE +
          restall + '' + rest +
          coins + '' + coin;
      }
    }
  }
  public hideTpa(visit) {
    if (visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null) {
      this.text = ''
    }
  }
  public setAssessment(visit) {
    this.selected_visit = visit;
    this.assessment_id = visit.NURSING_ASSESSMENT_ID;
  }
  public setPatient(id) {
    this.patient_id = id;
  }
  public open(content) {
    this.modalService.open(content, { ariaLabelledBy: 'modal-basic-title', size: 'lg', windowClass: "col-md-12" }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private notification(content) {
    this.modalService.open(content, { ariaLabelledBy: 'modal-basic-title', centered: true }).result.then((result) => {
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
      return `with: ${reason}`;
    }
  }
  public getAssesmentListByDate(f = 0) {
    var sendJson = {
      dateVal: formatDateTime(this.dateVal),
      timeZone: moment.tz.guess(),
      department: this.user.department
    }
    if (f == 0) {
      this.loaderService.display(true);
    } this.bill.assessmentListByDatefordept(sendJson).subscribe((result) => {
      if (result.status == "Success") {
        //console.log(result.data);
        this.loaderService.display(false);
        this.assesment_list = result["data"];
        this.assesment_list = this.assesment_list.filter(a => a.STAT == '1')
        for (let visit of this.assesment_list) {
          if (visit.GENERATED == 1)
            visit["ACTION"] = "Payment Completed"
          if (visit.GENERATED != 1 )
            visit["ACTION"] = "Pending"
          if (visit.BILL_STATUS == 1 && visit.GENERATED != 1 )
            visit["ACTION"] = "Processing"
        }
      }
      else {
        this.assesment_list = [];
        this.loaderService.display(false);
      }
    }, (err) => {
      console.log(err);
    });
  }
  public formatTime(time) {
    return formatTime(time);
  }
  public formatDate(date) {
    return formatDate(date);
  }
  public formatDateTime(data) {
    return formatDateTime(data);
  }
  public getToday(): string {
    return new Date().toISOString().split('T')[0]
  }
  public get_investigation() {
    var postData = {
      patient_id: this.patient_id,
      assessment_id: this.assessment_id,
    }
    this.loaderService.display(true);
    this.Doc.get_lab_investigation(postData).subscribe((result) => {
      if (result.status == "Success") {
        this.loaderService.display(false);
        this.invoice_list = result.data.LAB_INVESTIGATION_DETAILS

      }
      else {
        this.loaderService.display(false);
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getNextAssessmentDetails(visit) {
    var sendJson = {
      assessment_id: visit.NURSING_ASSESSMENT_ID,
      patient_id: visit.PATIENT_ID
    }

    this.nas.getNextAssessmentDetails(sendJson).subscribe((result) => {
      if (result.status == "Success") {
        this.selected_visit = result.data
        this.assessment_id = this.selected_visit.NURSING_ASSESSMENT_ID
        this.patient_id = this.selected_visit.PATIENT_ID
      }
      else {
        this.notifier.notify('error', 'No next assessments!');
      }
    }, (err) => {
      console.log(err);
    });
  }
  public getPreviousAssessmentDetails(visit) {
    var sendJson = {
      assessment_id: visit.NURSING_ASSESSMENT_ID,
      patient_id: visit.PATIENT_ID
    }

    this.nas.getPreviousAssessmentDetails(sendJson).subscribe((result) => {
      if (result.status == "Success") {
        this.selected_visit = result.data
        this.assessment_id = this.selected_visit.NURSING_ASSESSMENT_ID
        this.patient_id = this.selected_visit.PATIENT_ID
      }
      else {
        this.notifier.notify('error', 'No previous assessments!');
      }
    }, (err) => {
      console.log(err);
    });
  }
  print(): void {
    let printContents, popupWin;
    printContents = document.getElementById('print-section').innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html>
        <head>
          <title>Prescription</title>
          <style>
          
          
          </style>
        </head>
    <body onload="window.print();window.close()">${printContents}</body>
      </html>`
    );
    popupWin.document.close();
  }
  public getDiscountTreatmentDetails(discount) {
    let sendJson = {
      visit_id: this.selected_visit.VISIT_ID
    }
    this.nas.getDiscountTreatmentDetails(sendJson).subscribe((result) => {
      if (result.status === 'Success') {
        this.discountDetails = result.data;
        this.notification(discount)
      }
      else {
        this.discountDetails = [];
      }
    });
  }
}