import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { RadiologyRoutingModule } from './radiology-routing.module';
import { RadiologyComponent } from './radiology.component';
import { PageHeaderModule } from './../../shared';

@NgModule({
  declarations: [RadiologyComponent],
  imports: [
    CommonModule,
    RadiologyRoutingModule,
    NgbModule,
    PageHeaderModule,
    NgxLoadingModule
  ]
})
export class RadiologyModule { }