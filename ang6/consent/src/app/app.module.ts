import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import { SignaturePadModule } from 'angular2-signaturepad';
import { MatRadioModule, MatButtonModule, MatIconModule, MatDividerModule, MatSnackBarModule, MatToolbarModule, MatTabsModule,MatBottomSheetModule,MatNativeDateModule,MatDatepickerModule,MatInputModule,MatCheckboxModule,MatFormFieldModule,MatListModule,MatCardModule,MatGridListModule} from '@angular/material';
import { TermsComponent } from './terms/terms.component'; 
import { BasicAuthInterceptor } from './helper';
import { RouterModule, Routes } from '@angular/router';
import { GeneralComponent } from './general/general.component';
import { AppRoutingModule } from './app-routing.module';
import { Covid19Component } from './covid19/covid19.component';
import { QuestionsComponent } from './questions/questions.component';
import { PatientscreeningComponent } from './patientscreening/patientscreening.component';
import {Nl2BrPipeModule} from 'nl2br-pipe';
import { DashboardComponent } from './dashboard/dashboard.component';
import { SelectDropDownModule } from 'ngx-select-dropdown'

@NgModule({
  declarations: [
    AppComponent, TermsComponent, GeneralComponent, Covid19Component, QuestionsComponent, PatientscreeningComponent, DashboardComponent
  ],
  imports: [
  RouterModule.forRoot([]),
    Nl2BrPipeModule,
	  FormsModule, ReactiveFormsModule,
	  MatCheckboxModule,
    MatRadioModule,
	  MatFormFieldModule,
	  MatGridListModule,
	  MatCardModule,
	  MatInputModule,
	  MatDatepickerModule,
	  MatNativeDateModule,
	  MatBottomSheetModule,
	  MatTabsModule,
	  MatToolbarModule,
    MatDividerModule,
    MatSnackBarModule,
    MatListModule,
    MatIconModule,
    MatButtonModule,
    BrowserModule,
    HttpClientModule,
    BrowserAnimationsModule,
    SignaturePadModule,
    SelectDropDownModule,
    AppRoutingModule
  ],
  providers: [MatDatepickerModule,{provide: MAT_DATE_LOCALE, useValue: 'en-GB'},HttpClient, { provide: HTTP_INTERCEPTORS, useClass: BasicAuthInterceptor, multi: true }],
  bootstrap: [AppComponent],
  entryComponents: [TermsComponent],
})
export class AppModule { }
