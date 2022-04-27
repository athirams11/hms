import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DashboardsRoutingModule } from './dashboards-routing.module';
import { DashboardsComponent } from './dashboards.component';
import { NgbModule, NgbDropdownModule } from '@ng-bootstrap/ng-bootstrap';
import { NgxPaginationModule } from 'ngx-pagination';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { TranslateModule } from '@ngx-translate/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { TextMaskModule } from 'angular2-text-mask';
import { NgMultiSelectDropDownModule } from 'ng-multiselect-dropdown';
import { HttpClientModule } from '@angular/common/http';
import { ChartsModule } from 'ng2-charts';  
//import { DoughnutChartComponent, PieChartComponent, BarChartComponent } from 'angular-d3-charts'; // this is needed!
 

@NgModule({
  declarations: [DashboardsComponent],
  imports: [
    CommonModule,
    DashboardsRoutingModule,
    TranslateModule,
    NgbDropdownModule,
    NgxPaginationModule,
    NgbModule,
    PageHeaderModule,
    FormsModule,
    TextMaskModule,
    ReactiveFormsModule,
    NgMultiSelectDropDownModule,
    NgxLoadingModule,
    HttpClientModule,
    ChartsModule
  ]
})
export class DashboardsModule { }
