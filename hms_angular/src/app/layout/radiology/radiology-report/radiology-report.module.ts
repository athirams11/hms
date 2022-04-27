import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { RadiologyReportRoutingModule } from './radiology-report-routing.module';
import { RadiologyReportComponent } from './radiology-report.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  declarations: [RadiologyReportComponent],
  imports: [
    CommonModule,
    RadiologyReportRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule,
    NgbModule
  ]
})
export class RadiologyReportModule { }