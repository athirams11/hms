import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { InsurancePriceListRoutingModule } from './insurance-price-list-routing.module';
import { InsurancePriceListComponent } from './insurance-price-list.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';
import { SelectDropDownModule } from 'ngx-select-dropdown'
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';


import { pipe } from '@angular/core/src/render3';


@NgModule({
  declarations: [InsurancePriceListComponent],
  imports: [
    CommonModule,
    InsurancePriceListRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule,
    SelectDropDownModule,
    NgbModule,
    
  ]
})
export class InsurancePriceListModule { }
