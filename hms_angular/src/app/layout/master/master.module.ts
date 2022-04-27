import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import {NgxPaginationModule} from 'ngx-pagination';
import { MasterRoutingModule } from './master-routing.module';
import { MasterComponent } from './master.component';
//import { ProcedureCodeComponent } from './procedure-code/procedure-code.component';
//import { ProcedureCodeComponent } from './procedure-code/procedure-code.component';
// import { MedicineComponent } from './medicine/medicine.component';
// import { DiagnosisComponent } from './diagnosis/diagnosis.component';

@NgModule({
  declarations: [MasterComponent],
  imports: [
    CommonModule,
    MasterRoutingModule,
    NgbModule,
    NgxPaginationModule
  ]
})
export class MasterModule { }
