import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from './../../../shared';
import { DataListingModule } from './../../../shared/modules/data-listing/data-listing.module';
import { DrAppointmentRoutingModule } from './dr-appointment-routing.module';
import { DrAppointmentComponent } from './dr-appointment.component';
import { NgxLoadingModule }  from 'ngx-loading';
import { DatePipe } from '@angular/common';
@NgModule({
  declarations: [DrAppointmentComponent],
  imports: [
    CommonModule,
    CommonModule,
    NgbModule,
    FormsModule,
    TextMaskModule,
    PageHeaderModule,
    DataListingModule,
    NgxLoadingModule,
    FormsModule,
    DrAppointmentRoutingModule
  ],
  providers: [
    DatePipe
  ]
})
export class DrAppointmentModule { }

