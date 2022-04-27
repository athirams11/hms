import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { PageHeaderModule } from  './../../../shared';
import { NgxLoadingModule }  from 'ngx-loading';
import {NgxPaginationModule} from 'ngx-pagination';

import { CorporateCompanyRoutingModule } from './corporate-company-routing.module';
import { CorporateCompanyComponent } from './corporate-company.component';

@NgModule({
  declarations: [CorporateCompanyComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    PageHeaderModule,
    NgxLoadingModule,
    NgxPaginationModule,
    CorporateCompanyRoutingModule
  ]
})
export class CorporateCompanyModule { }
