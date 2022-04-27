import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { NgbDropdownModule } from '@ng-bootstrap/ng-bootstrap';
// import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { LayoutRoutingModule } from './layout-routing.module';
import { LayoutComponent } from './layout.component';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { HeaderComponent } from './components/header/header.component';
import { NgxLoadingModule, ngxLoadingAnimationTypes } from 'ngx-loading';
// import { Globals } from './globals'


@NgModule({
    imports: [
        CommonModule,
        LayoutRoutingModule,
        TranslateModule,
        NgbDropdownModule,
        NgxLoadingModule,
        FormsModule,
        NgbModule
                // provider: [
        //     // ... other global providers
        //     Globals // so do not provide it into another components/services if you want it to be a singleton
        //   ]
    ],
    declarations: [LayoutComponent, SidebarComponent, HeaderComponent],
     bootstrap:    [ SidebarComponent ]
})
export class LayoutModule {}
