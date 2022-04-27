import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import {ThemePalette} from '@angular/material/core';
import { Router, NavigationEnd } from '@angular/router';
import { SignaturePad } from 'angular2-signaturepad';
import { HostListener } from "@angular/core";
import { ActivatedRoute } from '@angular/router';
import {MatBottomSheet, MatBottomSheetRef} from '@angular/material';
import { TermsComponent } from './terms/terms.component'; 
import { PatientService } from './services';
import {MatSnackBar} from '@angular/material';
import moment from 'moment-timezone';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './class/Utils';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})


export class AppComponent {
	
    institution_name:any;
    dateVal = new Date();
    accept_terms = true;
    opnumber = '';
	
  	constructor(private router: Router,public rest: PatientService) { 
  		
  		
  	}

  	ngOnInit() {
  		
  		
      this.getInstitution();
  	
  	}
    getInstitution(){
      this.rest.getInstitution().subscribe((result) => {
        
        if (result.status == 'Success') {
          const institution_details = result.data;
             
            this.institution_name = institution_details.INSTITUTION_NAME
           
        } else {
         // this.openSnackBar('No Institution found', 'Failed')
         // this.patient_data = null;
          
        }
      }, (err) => {
        console.log(err);
      });
    }
	

}


