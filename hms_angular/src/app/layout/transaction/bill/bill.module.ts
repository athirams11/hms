import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule , ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from '../../../shared';
import { DataListingModule } from '../../../shared/modules/data-listing/data-listing.module';
import { Ng2SearchPipeModule } from 'ng2-search-filter';
import {NgxPrintModule} from 'ngx-print';
import {Nl2BrPipeModule} from 'nl2br-pipe';

import { BillRoutingModule } from './bill-routing.module';
import { BillComponent } from './bill.component';
import { BillPatientComponent } from './bill-patient/bill-patient.component';
import { InvoiceDuplicateComponent } from './invoice-duplicate/invoice-duplicate.component';
@NgModule({
  declarations: [BillComponent, BillPatientComponent, InvoiceDuplicateComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    TextMaskModule,
    PageHeaderModule,
    DataListingModule,
    CommonModule,
    NgxPrintModule,
    ReactiveFormsModule,
    Ng2SearchPipeModule,
    Nl2BrPipeModule,
    BillRoutingModule
  ]
})
export class BillModule { }



