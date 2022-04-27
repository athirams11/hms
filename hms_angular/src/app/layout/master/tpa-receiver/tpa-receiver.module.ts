import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { TpaReceiverRoutingModule } from './tpa-receiver-routing.module';
import { TpaReceiverComponent } from './tpa-receiver.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';
@NgModule({
  declarations: [TpaReceiverComponent],
  imports: [
    CommonModule,
    TpaReceiverRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule
  ]
})
export class TpaReceiverModule { }
