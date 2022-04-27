import { Component, OnInit, HostListener, Input, Output, EventEmitter, NgModule, ViewChild, OnChanges, SimpleChanges } from '@angular/core';
// import { NotifierService } from 'angular-notifier';
// import { ModalDismissReasons, NgbModal } from '@ng-bootstrap/ng-bootstrap';
// import { LoaderService} from '../../../shared';
// import * as moment from 'moment';
// import { OpRegistrationService, DoctorsService, NursingAssesmentService } from '../../../shared';
// import { BillingService } from 'src/app/shared/services/billing.service';
// import { AppSettings } from 'src/app/app.settings';
@Component({
  selector: 'app-insurance',
  templateUrl: './insurance.component.html',
  styleUrls: ['./insurance.component.scss']
})
export class InsuranceComponent implements OnInit {
 
  @Input() id: string;
  @Output() pageChange: EventEmitter<number>;
  constructor( ) {
  }
  ngOnInit() {
    
  }
  
}



