import { Component, OnInit, Input } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
//import * as moment from 'moment';
import moment from 'moment-timezone';
import { NotifierService } from 'angular-notifier';
import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LoaderService, NursingAssesmentService } from './../../../services';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../class/Utils';
import { AppSettings } from 'src/app/app.settings';
@Component({
  selector: 'app-op-visit-list',
  templateUrl: './op-visit-list.component.html',
  styleUrls: ['./op-visit-list.component.scss']
})
export class OpVisitListComponent implements OnInit {
  @Input() visit_list: any;
  public user_rights: any = {};
  public user_id = 0;
  public dateVal  = new Date();
  private notifier: NotifierService;
  @Input() page: any = 'Visit';
  term : any
  closeResult: string;
  user_data: any;
  cancel_reason : string;
  cancelled_id = 0
  public settings = AppSettings;
  text: string;
// <<<<<<< HEAD
//   constructor(public datepipe: DatePipe, private router: Router, public rest2: NursingAssesmentService,private modalService: NgbModal,notifierService: NotifierService) { 
//     this.notifier = notifierService;
//   }
  constructor(private loaderService: LoaderService, 
    public datepipe: DatePipe, private router: Router, public rest2: NursingAssesmentService,private modalService: NgbModal,notifierService: NotifierService) { 
    this.notifier = notifierService;
  }


  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.dateVal = defaultDateTime();
    this.user_id =this.user_data.user_id;
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
  
  public startAssesment(visit) {
    if (visit !== '' && visit != null) {
      const postData = {
        visit_id : visit.PATIENT_VISIT_LIST_ID,
        user_id : this.user_id,
        p_id : visit.OP_REGISTRATION_ID,
        clint_date : this.formatDateTime (new Date()),
        // date : this.formatDateTime (defaultDateTime()),
        date : defaultDateTime(),
        timeZone : moment.tz.guess(),
        time : this.formatDateTime (new Date())
      };
      this.rest2.startAssesment(postData).subscribe((result) => {
        if (result.status === 'Success') {

        // console.log(result.data);
        this.router.navigate(['transaction/pre-consulting']);
          // window.location.reload();
        } else {
            this.notifier.notify( 'error', result.message ); 
          }
      }, (err) => {
        console.log(err);
      });
    }
  }
  public pdfexport(visit,val=0,p_number=0) {
    if (visit !== '' && visit != null) {
      const postData = {
        visit_id : visit.PATIENT_VISIT_LIST_ID,
        user_id : this.user_id,
        p_id : visit.OP_REGISTRATION_ID,
        clint_date : this.formatDateTime (new Date()),
        type: val,
        p_number: p_number,
        date : defaultDateTime(),
        timeZone : moment.tz.guess(),
        time : this.formatDateTime (new Date())
      };
      this.loaderService.display(true);
      this.rest2.downloadgeneralconsent(postData).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
        //const FileSaver = require('file-saver');
        window.open(this.settings.API_ENDPOINT+result.data);
        
      }
      else{
        this.notifier.notify("error",result.message)
      }
    })
    }
  }
  public showTpa(visit)
  {
    if(visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null)
    {
      if(visit.insurance != false){
        var coin = []
        var rest = ''
        if( visit.insurance.OP_INS_IS_ALL == 1)
        {
          rest = visit.insurance.OP_INS_ALL_VALUE+' '+visit.insurance.OP_INS_ALL_TYPE
        }
        else
        {
          if(visit.insurance.co_ins && visit.insurance.co_ins.length > 0){
            var i = 0
            for(let data of visit.insurance.co_ins)
            {
              coin[i] = '\n            '+data.COIN_NAME + '  :  ' + data.COIN_VALUE+ ' ' +data.COIN_VALUE_TYPE+ ' '  
              i=i+1
            }
          }
        }
        var tpa ="TPA :  ";
        var company ="Company :  ";
        var Network ="Network :  ";
        var Deductable ="Deductable :  ";
        var restall ="\nRest of all :  ";
        var coins ="\nCo-Ins  :";
        if(coin.length == 0)
        {
          coins = ''
        }
        if(visit.insurance.OP_INS_IS_ALL == 0)
        {
          restall = '';
          rest = ''
        }
        this.text = 'TPA  :  ' + visit.insurance.TPA_ECLAIM_LINK_ID + ' - ' + visit.insurance.TPA_NAME + '\n' +
        'Company  :  ' + visit.insurance.INSURANCE_PAYERS_ECLAIM_LINK_ID + ' - ' + visit.insurance.INSURANCE_PAYERS_NAME + '\n' +
        'Network  :  ' + visit.insurance.INS_NETWORK_NAME + '\n' +
        'Deductable  :  ' + visit.insurance.OP_INS_DEDUCTIBLE + ' ' + visit.insurance.OP_INS_DEDUCT_TYPE +
        restall +''+ rest + 
        coins +''+ coin;
      }
    }
  }
  public hideTpa(visit)
  {
    if(visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null)
    {
      this.text = ''
    }
  }
  public cancelVisit()
  {
    if(this.cancelled_id != 0 && this.cancelled_id != null && this.cancel_reason != "" && this.cancel_reason != null)
    {
      var postData = {
        visit_id :  this.cancelled_id,
        cancel_reason : this.cancel_reason,
        user_id :this.user_data.user_id
      }
      this.rest2.cancelVisit(postData).subscribe((result) => {
        this.cancelled_id = 0;
        this.cancel_reason = "";
        if(result.status == "Success")
        {
          //  this.router.navigate(['transaction/pre-consulting']);
          this.notifier.notify( 'success', result.message );
        }
        else
        {
          this.notifier.notify( 'error', result.message );
        }
      }, (err) => {
        console.log(err);
      });
    }
    else{

    }
  }
  public popcancelVisit(visit_id)
  {
    this.cancelled_id = visit_id;
  }
  
  private open(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }
  private opened(content) {
    this.modalService.open(content, {ariaLabelledBy: 'modal-basic-title', size: 'lg', windowClass:'col-md-10', }).result.then((result) => {
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
}
