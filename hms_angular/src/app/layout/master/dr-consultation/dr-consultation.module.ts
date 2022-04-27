import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import {DatePipe} from '@angular/common';
import { DrConsultationRoutingModule } from './dr-consultation-routing.module';
import { DrConsultationComponent } from './dr-consultation.component';
import { PageHeaderModule } from  './../../../shared';
import { NgMultiSelectDropDownModule } from 'ng-multiselect-dropdown';
import {NgxLoadingModule} from 'ngx-loading'
import { from } from 'rxjs';
import { HttpClientModule } from '@angular/common/http';
@NgModule({
  declarations: [DrConsultationComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    PageHeaderModule,
    NgMultiSelectDropDownModule,
    DrConsultationRoutingModule,
    NgxLoadingModule,
    HttpClientModule
  ],
  providers:[DatePipe]
})
export class DrConsultationModule { }
