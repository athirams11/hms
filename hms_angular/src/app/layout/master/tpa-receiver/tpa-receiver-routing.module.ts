import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { TpaReceiverComponent } from './tpa-receiver.component';
const routes: Routes = [
  {
    path: '', component: TpaReceiverComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TpaReceiverRoutingModule { }
