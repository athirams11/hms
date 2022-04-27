import { NgModule,Input,Output} from '@angular/core';
import { CommonModule } from '@angular/common';
import { DiagnosisComponent } from './diagnosis.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { DiagnosisRoutingModule } from './diagnosis-routing.module';
import { PageHeaderModule } from  './../../../shared';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
@NgModule({
  declarations: [DiagnosisComponent],
  imports: [
    CommonModule,
    DiagnosisRoutingModule,
    FormsModule,
    NgbModule,
    PageHeaderModule,
    NgxLoadingModule
    ]
})
export class DiagnosisModule { }
