import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SettingsComponent } from './settings.component';
import { AuthGuard } from '../.././shared';
const routes: Routes = [
  {
      path: '', 
      children: [
        {
          path: '', component: SettingsComponent
        },
        { path: 'user-access', loadChildren: './user-access/user-access.module#UserAccessModule', canActivate: [AuthGuard], data: {level : 2,path: '/user-access'} },
        { path: 'user-management', loadChildren: './user-management/user-management.module#UserManagementModule', canActivate: [AuthGuard], data: {level : 2,path: '/user-management'} },
        { path: 'institution', loadChildren: './institution/institution.module#InstitutionModule', canActivate: [AuthGuard], data: {level : 2,path: '/institution'} },
        { path: 'user-management', loadChildren: './user-management/user-management.module#UserManagementModule', canActivate: [AuthGuard], data: {level : 2,path: '/user-management'} },


      ],
      runGuardsAndResolvers: 'always',
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SettingsRoutingModule { }
