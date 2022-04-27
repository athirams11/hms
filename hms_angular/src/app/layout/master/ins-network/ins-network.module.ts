import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { InsNetworkRoutingModule } from './ins-network-routing.module';
import { InsNetworkComponent } from './ins-network.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';
import { SelectDropDownModule } from 'ngx-select-dropdown'
@NgModule({
  declarations: [InsNetworkComponent],
  imports: [
    CommonModule,
    InsNetworkRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule,
    SelectDropDownModule
  ]
})
export class InsNetworkModule { }
