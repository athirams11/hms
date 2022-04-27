import { NgModule } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from './../../../shared';
import { DataListingModule } from './../../../shared/modules/data-listing/data-listing.module';
import { AppointmentRoutingModule } from './appointment-routing.module';
import { AppointmentComponent } from './appointment.component';
import { DrScheduleDateComponent } from './dr-schedule-date/dr-schedule-date.component';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
@NgModule({
  declarations: [AppointmentComponent, DrScheduleDateComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    TextMaskModule,
    AppointmentRoutingModule,
    PageHeaderModule,
    DataListingModule,
    NgxLoadingModule,
    FormsModule
  ],
  exports: [],
  providers: [DatePipe]
})
export class AppointmentModule { }
