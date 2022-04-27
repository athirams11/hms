import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { SampleCollectionRoutingModule } from './sample-collection-routing.module';
import { SampleCollectionComponent } from './sample-collection.component';
import { PageHeaderModule } from 'src/app/shared';
import { NgxLoadingModule } from 'ngx-loading';
import { FormsModule } from '@angular/forms';
import { NgbPaginationModule } from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  declarations: [SampleCollectionComponent],
  imports: [
    CommonModule,
    SampleCollectionRoutingModule,
    PageHeaderModule,
    NgxLoadingModule,
    FormsModule,
    NgbPaginationModule,
    NgbModule
  ]
})
export class SampleCollectionModule { }