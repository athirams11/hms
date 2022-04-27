import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PageHeaderModule } from './../../../shared';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { InsuranceRoutingModule } from './insurance-routing.module';
import { InsuranceComponent } from './insurance.component';
import { FormsModule } from '@angular/forms';
import { ClaimXmlComponent } from './claim-xml/claim-xml.component';
import { SubmittedFilesComponent } from './submitted-files/submitted-files.component';
import { SearchComponent } from './search/search.component';
import { NewRemittenceComponent } from './new-remittence/new-remittence.component';
import { CreditInvoiceComponent } from './credit-invoice/credit-invoice.component';
import {Nl2BrPipeModule} from 'nl2br-pipe';
@NgModule({
  declarations: [InsuranceComponent,ClaimXmlComponent, SubmittedFilesComponent, SearchComponent, NewRemittenceComponent,CreditInvoiceComponent],
  imports: [
    CommonModule,
    PageHeaderModule,
    NgbModule,
    InsuranceRoutingModule,
    Nl2BrPipeModule,
    FormsModule
  ]
})
export class InsuranceModule { }
