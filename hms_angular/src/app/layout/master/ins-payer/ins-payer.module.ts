import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { InsPayerRoutingModule } from './ins-payer-routing.module';
import { InsPayerComponent } from './ins-payer.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';


@NgModule({
  declarations: [InsPayerComponent],
  imports: [
    CommonModule,
    InsPayerRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule
  ]
})
export class InsPayerModule { }
