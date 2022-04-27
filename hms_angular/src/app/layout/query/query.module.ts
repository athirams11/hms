import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import {DatePipe} from '@angular/common';
import { PageHeaderModule } from  './../../shared';
import { ListTableComponent } from './list-table/list-table.component';
import { QueryRoutingModule } from './query-routing.module';
import { QueryComponent } from './query.component';
import { PatientQueryComponent } from './patient-query/patient-query.component';
import { AppointmentListComponent } from './appointment-list/appointment-list.component';

@NgModule({
  declarations: [QueryComponent, AppointmentListComponent, PatientQueryComponent, ListTableComponent],
  imports: [
    CommonModule,
    QueryRoutingModule,
    PageHeaderModule,
    NgbModule,
    FormsModule
  ],
  providers:[DatePipe]
})
export class QueryModule { }
