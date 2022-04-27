import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { NgModule, enableProdMode } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import {NgxLoadingComponent, NgxLoadingModule, ngxLoadingAnimationTypes,NgxLoadingService }  from 'ngx-loading';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { AuthGuard } from './shared';
import { BasicAuthInterceptor } from './shared/helper';
import { UserIdleModule } from 'angular-user-idle';
import { LoaderService, AppointmentService, OpVisitService, DrConsultationService, OpRegistrationService, PatientQueryService } from './shared';
import { NotifierModule, NotifierOptions } from 'angular-notifier';
import { LayoutComponent } from './layout/layout.component';
import { SelectDropDownModule } from 'ngx-select-dropdown';
// import { CreditInvoiceComponent } from './layuot/claim-process/insurance/credit-invoice/credit-invoice.component';
//import {NgxSpinnerService,NgxSpinnerModule} from 'ngx-spinner';
// import { SharedComponent } from './shared/shared.component';
// import { ModulesComponent } from './shared/modules/modules.component';

// AoT requires an exported function for factories
export const createTranslateLoader = (http: HttpClient) => {
    /* for development
    return new TranslateHttpLoader(
        http,
        '/start-angular/SB-Admin-BS4-Angular-6/master/dist/assets/i18n/',
        '.json'
    ); */
    return new TranslateHttpLoader(http, './assets/i18n/', '.json');
};
const customNotifierOptions: NotifierOptions = {
    position: {
          horizontal: {
              position: 'middle',
              distance: 12
          },
          vertical: {
              position: 'top',
              distance: 12,
              gap: 10
          }
      },
    theme: 'material',
    behaviour: {
      autoHide: 3000,
      onClick: 'hide',
      onMouseover: 'pauseAutoHide',
      showDismissButton: true,
      stacking: 4
    },
    animations: {
      enabled: true,
      show: {
        preset: 'slide',
        speed: 300,
        easing: 'ease'
      },
      hide: {
        preset: 'fade',
        speed: 300,
        easing: 'ease',
        offset: 50
      },
      shift: {
        speed: 300,
        easing: 'ease'
      },
      overlap: 150
    }
  };
const customLoaderOptions = {
    animationType: ngxLoadingAnimationTypes.wanderingCubes,
    backdropBackgroundColour: 'rgba(0,0,0,0.7)', 
    backdropBorderRadius: '4px',
    primaryColour: '#dd0031', 
    secondaryColour: '#006ddd', 
    tertiaryColour: '#fff',
    fullScreenBackdrop:true
};
enableProdMode();
@NgModule({
    imports: [
        CommonModule,
        BrowserModule,
        UserIdleModule.forRoot({idle: 300, timeout: 1, ping: 120}),
        BrowserAnimationsModule,
        HttpClientModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: createTranslateLoader,
                deps: [HttpClient]
            }
        }),
        NotifierModule.withConfig(customNotifierOptions),
        NgxLoadingModule.forRoot(customLoaderOptions),
        AppRoutingModule,SelectDropDownModule
      

    ],
    declarations: [AppComponent],
    providers: [HttpClient,AuthGuard, { provide: HTTP_INTERCEPTORS, useClass: BasicAuthInterceptor, multi: true }, LoaderService, AppointmentService, OpVisitService, DrConsultationService, OpRegistrationService, PatientQueryService,LayoutComponent,NgxLoadingService],
    
    bootstrap: [AppComponent]
})
export class AppModule {}
