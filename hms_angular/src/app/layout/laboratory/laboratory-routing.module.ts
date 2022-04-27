import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {LaboratoryComponent} from './laboratory.component';
import { AuthGuard } from '../.././shared';
const routes: Routes = [
    {
      path: '', 
      children: [
        {
          path: '', component: LaboratoryComponent
        },
        { path: 'sample-collection', loadChildren: './sample-collection/sample-collection.module#SampleCollectionModule',canActivate: [AuthGuard],data: {level : 2,path: "/sample-collection"} },
        { path: 'test-result', loadChildren: './test-result/test-result.module#TestResultModule',canActivate: [AuthGuard],data: {level : 2,path: "/test-result"} },
      ],
      runGuardsAndResolvers: 'always',
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LaboratoryRoutingModule { }
