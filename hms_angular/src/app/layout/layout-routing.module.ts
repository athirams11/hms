import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LayoutComponent } from './layout.component';
import { AuthGuard } from '.././shared';
const routes: Routes = [
    {
        path: '',
        component: LayoutComponent,
        children: [
            { path: '', redirectTo: 'dashboard', pathMatch: 'prefix' },
            //{ path: 'consent', loadChildren: './dashboards/dashboards.module#DashboardModule' },
            // { path: 'dashboard', loadChildren: './dashboard/dashboard.module#DashboardModule' },
            // { path: 'charts', loadChildren: './charts/charts.module#ChartsModule' },
            // { path: 'tables', loadChildren: './tables/tables.module#TablesModule' },
            // { path: 'forms', loadChildren: './form/form.module#FormModule' },
            // { path: 'bs-element', loadChildren: './bs-element/bs-element.module#BsElementModule' },
            // { path: 'grid', loadChildren: './grid/grid.module#GridModule' },
            { path: 'components', loadChildren: './bs-component/bs-component.module#BsComponentModule' },
            { path: 'dashboard', loadChildren: './dashboard/dashboard.module#DashboardModule', canActivate: [AuthGuard], data: { level: 1, path: '/dashboard' } },
            { path: 'transaction', loadChildren: './transaction/transaction.module#TransactionModule', canActivate: [AuthGuard], data: { level: 1, path: '/transaction' } },
            // { path: 'laboratory', loadChildren: './laboratory/laboratory.module#LaboratoryModule', canActivate: [AuthGuard], data: { level: 1, path: '/laboratory' } },
            // { path: 'radiology', loadChildren: './radiology/radiology.module#RadiologyModule', canActivate: [AuthGuard], data: { level: 1, path: '/radiology' } },
            { path: 'master', loadChildren: './master/master.module#MasterModule', canActivate: [AuthGuard], data: { level: 1, path: '/master' } },
            { path: 'query', loadChildren: './query/query.module#QueryModule', canActivate: [AuthGuard], data: { level: 1, path: '/query' } },
            { path: 'settings', loadChildren: './settings/settings.module#SettingsModule', canActivate: [AuthGuard], data: { level: 1, path: '/settings' } },
            // { path: 'claim-process', loadChildren: './claim-process/claim-process.module#ClaimProcessModule', canActivate: [AuthGuard], data: { level: 1, path: '/claim-process' } },
            { path: 'reports', loadChildren: './reports/reports.module#ReportsModule', canActivate: [AuthGuard], data: { level: 1, path: '/reports' } },
            //{ path: 'dashboards', loadChildren: './dashboards/dashboards.module#DashboardModule' },
            //{ path: 'dashboards', loadChildren: './dashboards/dashboards.module#DashboardModule' }
        ]
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class LayoutRoutingModule { }
