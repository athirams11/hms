import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import {DatePipe} from '@angular/common';
import { PageHeaderModule } from  './../../../shared';
import { DrScheduleListRoutingModule } from './dr-schedule-list-routing.module';
import { DrScheduleListComponent } from './dr-schedule-list.component';
import { ListTableComponent } from './list-table/list-table.component';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';

@NgModule({
  declarations: [DrScheduleListComponent, ListTableComponent],
  imports: [
    CommonModule,
    NgbModule,
    DrScheduleListRoutingModule,
    FormsModule,
    PageHeaderModule,
    NgxLoadingModule
  ],
  providers:[DatePipe]
})
export class DrScheduleListModule { }
