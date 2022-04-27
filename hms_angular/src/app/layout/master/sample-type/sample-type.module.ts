import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SampleTypeRoutingModule } from './sample-type-routing.module';
import { SampleTypeComponent } from './sample-type.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  declarations: [SampleTypeComponent],
  imports: [
    CommonModule,
    SampleTypeRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule
  ]
})
export class SampleTypeModule { }
