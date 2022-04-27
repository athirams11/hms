import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { InsNetworkComponent } from './ins-network.component';
const routes: Routes = [
  {
    path: '', component: InsNetworkComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class InsNetworkRoutingModule { }
