import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { PageHeaderModule } from './../../shared';
import { ClaimProcessRoutingModule } from './claim-process-routing.module';
import { ClaimProcessComponent } from './claim-process.component';


@NgModule({
  declarations: [ClaimProcessComponent],
  imports: [
    CommonModule,
    PageHeaderModule,
    ClaimProcessRoutingModule,
    NgxLoadingModule,
    NgbModule
  ]
})
export class ClaimProcessModule { }
