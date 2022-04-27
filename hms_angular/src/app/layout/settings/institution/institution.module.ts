import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from './../../../shared';
import { InstitutionRoutingModule } from './institution-routing.module';
import { InstitutionComponent } from './institution.component';
import { SelectDropDownModule } from 'ngx-select-dropdown'

@NgModule({
  declarations: [InstitutionComponent],
  imports: [
    CommonModule,
    CommonModule,
    FormsModule,
    TextMaskModule,
    PageHeaderModule,
    InstitutionRoutingModule,
    SelectDropDownModule
  ]
})
export class InstitutionModule { }
