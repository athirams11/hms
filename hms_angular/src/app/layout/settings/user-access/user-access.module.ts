import { NgModule } from '@angular/core';
import { CommonModule,DatePipe } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from './../../../shared';

import { UserAccessRoutingModule } from './user-access-routing.module';
import { UserAccessComponent } from './user-access.component';

@NgModule({
  declarations: [UserAccessComponent],
  imports: [
    NgbModule,
    CommonModule,
    FormsModule,
    TextMaskModule,
    PageHeaderModule,
    UserAccessRoutingModule
  ]
})
export class UserAccessModule { }
