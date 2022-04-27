import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PageHeaderModule } from  './../../../shared';
import { VaccineRoutingModule } from './vaccine-routing.module';
import { VaccineComponent } from './vaccine.component';
import { FormsModule } from '@angular/forms';
import { NgxLoadingModule }  from 'ngx-loading';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';
@NgModule({
  declarations: [VaccineComponent],
  imports: [
    CommonModule,
    VaccineRoutingModule,
    PageHeaderModule,
    FormsModule,
    NgxLoadingModule,
    NgbPaginationModule
  ]
})
export class VaccineModule { }
