import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MedicineComponent } from './medicine.component';
import { MedicineRoutingModule } from './medicine-routing.module';
import { PageHeaderModule } from  './../../../shared';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { FormModule } from '../../form/form.module';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { NgbModal, NgbModule } from '@ng-bootstrap/ng-bootstrap';
//import { Globals } from './globals';

@NgModule({
  declarations: [MedicineComponent],
  imports: [
    CommonModule,
    MedicineRoutingModule,
    PageHeaderModule,
    FormsModule,
    NgxLoadingModule,
    NgbModule
  ],
  //providers: [ Globals ]
})
export class MedicineModule { }
