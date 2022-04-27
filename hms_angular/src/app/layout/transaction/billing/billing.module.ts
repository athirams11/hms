import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BillingRoutingModule } from './billing-routing.module';
import { FormsModule , ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from '../../../shared';
import { DataListingModule } from '../../../shared/modules/data-listing/data-listing.module';
import { BillingComponent } from './billing.component';
import { PatientBillComponent } from './patient-bill/patient-bill.component';
import { DuplicateInvoiceComponent } from './duplicate-invoice/duplicate-invoice.component';
import { Ng2SearchPipeModule } from 'ng2-search-filter';
import {NgxPrintModule} from 'ngx-print';
import {Nl2BrPipeModule} from 'nl2br-pipe';
@NgModule({
  declarations: [BillingComponent, PatientBillComponent, DuplicateInvoiceComponent  ],
  imports: [  
    CommonModule,
    BillingRoutingModule,
    NgbModule,
    FormsModule,
    TextMaskModule,
    PageHeaderModule,
    DataListingModule,
    CommonModule,
    NgxPrintModule,
    ReactiveFormsModule,
    Ng2SearchPipeModule,
    Nl2BrPipeModule
  ]
})
export class BillingModule { }
