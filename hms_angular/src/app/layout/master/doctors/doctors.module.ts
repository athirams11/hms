import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { DoctorsRoutingModule } from './doctors-routing.module';
import { DoctorsComponent } from './doctors.component';
import { PageHeaderModule } from  './../../../shared';
import { NgMultiSelectDropDownModule } from 'ng-multiselect-dropdown';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
@NgModule({
  declarations: [DoctorsComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    PageHeaderModule,
    NgMultiSelectDropDownModule,
    DoctorsRoutingModule,
    NgxLoadingModule
  ]
})
export class DoctorsModule { }
