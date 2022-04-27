import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {ClaimProcessComponent} from './claim-process.component';
import { AuthGuard } from '../.././shared';
const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: '', component : ClaimProcessComponent
      },
      { path: 'insurance', loadChildren: './insurance/insurance.module#InsuranceModule', canActivate: [AuthGuard], data: {level : 2, path: '/insurance'} }


    ],
    runGuardsAndResolvers: 'always',
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ClaimProcessRoutingModule { }
