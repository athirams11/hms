import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TestResultRoutingModule } from './test-result-routing.module';
import { TestResultComponent } from './test-result.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  declarations: [TestResultComponent],
  imports: [
    CommonModule,
    TestResultRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule,
    NgbModule
  ]
})
export class TestResultModule { }