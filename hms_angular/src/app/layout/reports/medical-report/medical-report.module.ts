import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { PageHeaderModule } from  './../../../shared';
import { NgxLoadingModule }  from 'ngx-loading';
import { LoaderService } from '../../../shared';
import { NgxPaginationModule } from 'ngx-pagination';
import { MedicalReportRoutingModule } from './medical-report-routing.module';
import { MedicalReportComponent } from './medical-report.component';
import { Ng2SearchPipeModule } from 'ng2-search-filter';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';
import { NgxPrintModule } from 'ngx-print';
import { ExportAsModule } from 'ngx-export-as';
import { SelectDropDownModule } from 'ngx-select-dropdown'
@NgModule({
  declarations: [MedicalReportComponent],
  imports: [
    FormsModule,
    PageHeaderModule,
    NgxLoadingModule,
    NgxPaginationModule,
    Ng2SearchPipeModule,
    NgbPaginationModule,
    NgxPrintModule,
    ExportAsModule,
    SelectDropDownModule,
    CommonModule,
    MedicalReportRoutingModule,
    NgbModule
  ]
})
export class MedicalReportModule { }
