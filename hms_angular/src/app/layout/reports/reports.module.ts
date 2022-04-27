import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { PageHeaderModule } from  './../../shared';
import { NgxLoadingModule }  from 'ngx-loading';
import { LoaderService } from '../../shared';
import { NgxPaginationModule } from 'ngx-pagination';
import { ReportsRoutingModule } from './reports-routing.module';
import { ReportsComponent } from './reports.component';

@NgModule({
  declarations: [ReportsComponent],
  imports: [
    CommonModule,
    FormsModule,
    PageHeaderModule,
    NgxLoadingModule,
    NgxPaginationModule,
    ReportsRoutingModule
  ]
})
export class ReportsModule { }
