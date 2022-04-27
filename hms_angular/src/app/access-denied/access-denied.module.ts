import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { AccessDeniedRoutingModule } from './access-denied-routing.module';
import { AccessDeniedComponent } from './access-denied.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule,
    AccessDeniedRoutingModule
  ],
  declarations: [AccessDeniedComponent]
})
export class AccessDeniedModule { }
