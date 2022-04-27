import { NgModule } from '@angular/core';
import { CommonModule,DatePipe } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { PageHeaderModule } from './../../../shared';
import { DataListingModule } from './../../../shared/modules/data-listing/data-listing.module';
import { OpVisitEntryRoutingModule } from './op-visit-entry-routing.module';
import { OpVisitEntryComponent } from './op-visit-entry.component';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { TextMaskModule } from 'angular2-text-mask';
import { SelectDropDownModule } from 'ngx-select-dropdown'

@NgModule({
  declarations: [OpVisitEntryComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    ReactiveFormsModule,
    OpVisitEntryRoutingModule,
    PageHeaderModule,
    DataListingModule,
    TextMaskModule, 
    SelectDropDownModule,
    NgxLoadingModule
  ],
  providers:[DatePipe]
})
export class OpVisitEntryModule { }
