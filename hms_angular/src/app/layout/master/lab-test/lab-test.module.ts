import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { LabTestRoutingModule } from './lab-test-routing.module';
import { LabTestComponent } from './lab-test.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  declarations: [LabTestComponent],
  imports: [
    CommonModule,
    LabTestRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule
  ]
})
export class LabTestModule { }