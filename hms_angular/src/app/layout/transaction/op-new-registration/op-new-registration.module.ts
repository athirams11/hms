import { NgModule } from '@angular/core';
import { CommonModule,DatePipe } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { OpNewRegistrationComponent } from './op-new-registration.component';
import { PageHeaderModule } from './../../../shared';
import { OpNewRegistrationRoutingModule } from './op-new-registration-routing.module';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { NgMultiSelectDropDownModule } from 'ng-multiselect-dropdown';
import { SelectDropDownModule } from 'ngx-select-dropdown'

@NgModule({
  declarations: [OpNewRegistrationComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    ReactiveFormsModule,
    TextMaskModule,
    PageHeaderModule,
    OpNewRegistrationRoutingModule,
    NgxLoadingModule,
    NgMultiSelectDropDownModule.forRoot(),
    SelectDropDownModule  ],
  providers:[DatePipe]
})
export class OpNewRegistrationModule { }
