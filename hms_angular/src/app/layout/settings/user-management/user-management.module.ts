import { NgModule } from '@angular/core';
import { CommonModule,DatePipe } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from './../../../shared';
import { UserManagementRoutingModule } from './user-management-routing.module';
import { UserManagementComponent } from './user-management.component';

@NgModule({
  declarations: [UserManagementComponent],
  imports: [
    NgbModule,
    CommonModule,
    FormsModule,
    TextMaskModule,
    PageHeaderModule,
    UserManagementRoutingModule
  ]
})
export class UserManagementModule { }
