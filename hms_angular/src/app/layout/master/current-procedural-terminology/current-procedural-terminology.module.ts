import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { PageHeaderModule } from  './../../../shared';
import { CurrentProceduralTerminologyRoutingModule } from './current-procedural-terminology-routing.module';
import { CurrentProceduralTerminologyComponent } from './current-procedural-terminology.component';
import { NgxLoadingModule }  from 'ngx-loading';
import { LoaderService } from '../../../shared';
import {NgxPaginationModule} from 'ngx-pagination';
@NgModule({
  declarations: [CurrentProceduralTerminologyComponent],
  imports: [
    CommonModule,
    NgbModule,
    FormsModule,
    PageHeaderModule,
    CurrentProceduralTerminologyRoutingModule,
    NgxLoadingModule,
    NgxPaginationModule
  ]
})
export class CurrentProceduralTerminologyModule { }
