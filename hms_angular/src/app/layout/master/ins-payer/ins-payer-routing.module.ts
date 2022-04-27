import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { InsPayerComponent } from './ins-payer.component';

const routes: Routes = [
  {
    path: '', component: InsPayerComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class InsPayerRoutingModule { }
