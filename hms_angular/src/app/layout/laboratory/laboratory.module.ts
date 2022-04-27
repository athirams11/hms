import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { LaboratoryRoutingModule } from './laboratory-routing.module';
import { LaboratoryComponent } from './laboratory.component';
import { PageHeaderModule } from './../../shared';

@NgModule({
  declarations: [LaboratoryComponent],
  imports: [
    CommonModule,
    LaboratoryRoutingModule,
    NgbModule,
    PageHeaderModule,
    NgxLoadingModule
  ]
})
export class LaboratoryModule { }