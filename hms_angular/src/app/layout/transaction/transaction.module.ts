import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { TransactionRoutingModule } from './transaction-routing.module';
import { TransactionComponent } from './transaction.component';
import { PageHeaderModule } from './../../shared';

@NgModule({
  declarations: [TransactionComponent],
  imports: [
    CommonModule,
    TransactionRoutingModule,
    NgbModule,
    PageHeaderModule,
    NgxLoadingModule
  ]
})
export class TransactionModule { }
