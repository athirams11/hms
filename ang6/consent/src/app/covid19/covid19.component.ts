import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import {ThemePalette} from '@angular/material/core';
import { Router, NavigationEnd } from '@angular/router';
import { SignaturePad } from 'angular2-signaturepad';
import { HostListener } from "@angular/core";
import { ActivatedRoute } from '@angular/router';
import {MatBottomSheet, MatBottomSheetRef} from '@angular/material';
import { TermsComponent } from '../terms/terms.component'; 
import { PatientService } from '../services';
import {MatSnackBar} from '@angular/material';
import moment from 'moment-timezone';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../class/Utils';
@Component({
  selector: 'app-covid19',
  templateUrl: './covid19.component.html',
  styleUrls: ['./covid19.component.css']
})
export class Covid19Component implements OnInit {

  	@ViewChild('pagediv')

  	pagediv: ElementRef;

	@ViewChild(SignaturePad) signaturePad: SignaturePad;
  maxDate = new Date();
	signaturePadOptions = { // passed through to szimek/signature_pad constructor
	    'minWidth': 1,
	    'canvasWidth': 400,
	    'canvasHeight': 200,
	    'backgroundColor':'#e9ecef'
	};

	scrHeight:any;
    scrWidth:any;
    patient_data:any = null;
    patient_dob:any;
    patient_name:any;
    patient_no: any;
    patient_address: any;
    patient_nationality: any;
    patient_gender: any;
    patient_nationalid: any;
    p_number:any;
    patient_visit:any;
    visit_id:any;
    institution_name:any;
    dateVal = new Date();
    accept_terms = true;
    opnumber = '';
	@HostListener('window:resize', ['$event'])
	
    getScreenSize(event?) {
          this.scrHeight = window.innerHeight;
          this.scrWidth = window.innerWidth;
          //console.log(this.scrHeight, this.scrWidth);
    }
  	title = 'Checklist for Symptoms and TOCC';
  	influensas: any[] = [
	  	{ name: 'Fever', selected: false, sub: false, fields:[] }, 
	  	{ name: 'cough', selected: false, sub: false, fields:[] }, 
	  	{ name: 'Sore throat', selected: false, sub: false, fields:[] }, 
	  	{ name: 'Shortness of breath', selected: false, sub: false, fields:[] }, 
	  	{ name: 'Diarrhea and/or vomiting', selected: false, sub: false, fields:[] }, 
	  	{ name: 'None of above', selected: false, sub: false, fields:[] }, 
	  	{ name: 'Information cannot be obtained', selected: false, sub: false, fields:[] }
	];

  	isolationtype: any[] = [
	  	{name: 'Droplet Precaution', selected: false,  sub: false}, 
	  	{name: 'Contact Precautions', selected: false,  sub: false}, 
	  	{name: 'Airtorne Precautions', selected: false,  sub: false}, 
	  	{name: 'Nil', selected: false,  sub: false}
  	];
  	toccSymptoms: any[] = [
  		{name: 'History of recent Travel to the affected areas', selected: false, sub: true, fields:{from:'',to:'',area:''}},
		{name: 'High risk Occupation .(e.g, laboratory workers, healthcare workers, wild animals related work)', selected: false, sub: false, fields:[]},
		{name: 'History of unprotected Contact with: a) Human case confirmed with CDVID-19, OR b) Consumption of wild animals in areas known to have CDVID-19 infection',  selected: false, sub: false, fields:[]}, 
		{name: 'Clustering of infruenza.blie illness `pneumonia (>=2 affected persons)',  selected: false, sub: false, fields:[]},
		{name: 'None of above', selected: false, sub: false, fields:[]}
	];

  	constructor(private router: Router, private bottomSheet: MatBottomSheet,public rest: PatientService,public snackBar: MatSnackBar) { 
  		
  		this.getScreenSize();
  	}

  	ngOnInit() {
  		
  		var width = this.pagediv.nativeElement.offsetWidth;
      
  		if(width < 800){

	   		this.signaturePadOptions.canvasWidth =  width-30;
	  		this.signaturePadOptions.canvasHeight = width/2;
  		}
  	
  		 
  		//this.dateVal = defaultDateTime();
  	}
    

  	ngAfterViewInit() {
   		this.signaturePad.clear()
   		
  		//this.signaturePadOptions.canvasHeight = ((80 / 100) * this.scrWidth)/2;
  	}

  	drawComplete(signaturePad) {
    // will be notified of szimek/signature_pad's onEnd event
    	//console.log(this.toccSymptoms);

  	}

  	drawClear() {
	    this.signaturePad.clear()
  	}

  	drawStart() {
    // will be notified of szimek/signature_pad's onBegin event
    	//console.log('begin drawing');
  	}

    openSnackBar(message: string, action: string) {
        this.snackBar.open(message, action, {
          duration: 3000,
        });
      }
    openSUccessSnackBar(message: string, action: string) {
        this.snackBar.open(message, action, {
          duration: 3000,
          panelClass:'text-success'
        });
      }
    public getPatientDetails() {
      //console.log(this.opnumber);
      if(this.opnumber.trim() != ''){
        
        var sendJson = {
          op_number: this.opnumber.trim(),
          dateVal : formatDateTime (this.dateVal),
          timeZone: moment.tz.guess(),
        };
        //this.loaderService.display(true);
        this.rest.getPatientDetails(sendJson).subscribe((result) => {
          
          if (result.status == 'Success') {
            const patient_details = result.data;
               this.p_number = this.opnumber;
              //console.log(patient_details);
              this.patient_data = patient_details.patient_data
              this.patient_dob = this.patient_data.DOB
              this.patient_no = this.patient_data.MOBILE_NO
              this.patient_address = this.patient_data.ADDRESS
              this.patient_nationality = this.patient_data.NATIONALITY_NAME
              this.patient_gender = (this.patient_data.GENDER ==1)?'Mr':'Ms';
              this.patient_nationalid = this.patient_data.NATIONAL_ID
              this.patient_name = this.patient_data.FIRST_NAME + ' ' + this.patient_data.MIDDLE_NAME + ' ' + this.patient_data.LAST_NAME
              this.patient_visit = this.patient_data.PATIENT_VISIT_LIST_ID 
            //this.loaderService.display(false);
          } else {
            this.openSnackBar('No patient / Visit found', 'Failed')
            this.patient_data = null;
            
          }
        }, (err) => {
          console.log(err);
        });
      }
  }
  public saveConsent(){
    var formData = {
      sign : this.signaturePad.toDataURL(),
      patient_visit: this.patient_visit,
      dateVal: this.dateVal,
      opnumber: this.opnumber,
      type:2,
      selected_options:{
        influensas: this.influensas,
        isolationtype: this.isolationtype,
        toccSymptoms: this.toccSymptoms
      },
      timeZone: moment.tz.guess(),
    }
    if(this.accept_terms == false){
      this.openSnackBar('Please accept terms', 'Error')
      return;
    }
    var influensas_checked = false
    var isolationtype_checked = false
    var toccSymptoms_checked = false
    this.influensas.forEach(function (value) {
     
      if(value.selected == true){
        influensas_checked = true
      }
    }); 
    if(influensas_checked == false){
      this.openSnackBar('Please select an option from Influenza-like illness symptoms' , 'Error')
      return;
    }
    this.toccSymptoms.forEach(function (value) {
      
      if(value.selected == true){
        toccSymptoms_checked = true
      }
    }); 
    if(toccSymptoms_checked == false){
      this.openSnackBar('Please select an option from TOCC: 14 days before onset of symptoms' , 'Error')
      return;
    }
    this.isolationtype.forEach(function (value) {
      
      if(value.selected == true){
        isolationtype_checked = true
      }
    }); 
    if(isolationtype_checked == false){
      this.openSnackBar('Please select an option from Isolation Precautions required' , 'Error')
      return;
    }
    
    if(this.signaturePad.isEmpty()){

      this.openSnackBar('Please sign the consent', 'Error')
      return;
    }
    //console.log(this.signaturePad.toDataURL() )
    if(this.opnumber == ''){
      this.openSnackBar('Invalid op number', 'Error')
      return;
    }
    if(this.patient_visit == null){
      this.openSnackBar('Invalid visit for patient', 'Error')
      return;
    }
    this.rest.saveConsentDetails(formData).subscribe((result) => {
        
        if (result.status == 'Success') {
          this.patient_data = null;
          this.signaturePad.clear()
          this.opnumber = ''
          //this.loaderService.display(false);
          this.openSUccessSnackBar(result.msg, '')
          this.accept_terms = false;
          this.influensas = [
                { name: 'Fever', selected: false, sub: false, fields:[] }, 
                { name: 'cough', selected: false, sub: false, fields:[] }, 
                { name: 'Sore throat', selected: false, sub: false, fields:[] }, 
                { name: 'Shortness of breath', selected: false, sub: false, fields:[] }, 
                { name: 'Diarrhea and/or vomiting', selected: false, sub: false, fields:[] }, 
                { name: 'None of above', selected: false, sub: false, fields:[] }, 
                { name: 'Information cannot be obtained', selected: false, sub: false, fields:[] }
            ];
          this.isolationtype = [
                {name: 'Droplet Precaution', selected: false,  sub: false}, 
                {name: 'Contact Precautions', selected: false,  sub: false}, 
                {name: 'Airtorne Precautions', selected: false,  sub: false}, 
                {name: 'Nil', selected: false,  sub: false}
              ];
          this.toccSymptoms = [
                {name: 'History of recent Travel to the affected areas', selected: false, sub: true, fields:{from:'',to:'',area:''}},
                {name: 'High risk Occupation .(e.g, laboratory workers, healthcare workers, wild animals related work)', selected: false, sub: false, fields:[]},
                {name: 'History of unprotected Contact with: a) Human case confirmed with CDVID-19, OR b) Consumption of wild animals in areas known to have CDVID-19 infection',  selected: false, sub: false, fields:[]}, 
                {name: 'Clustering of infruenza.blie illness `pneumonia (>=2 affected persons)',  selected: false, sub: false, fields:[]},
                {name: 'None of above', selected: false, sub: false, fields:[]}
            ];

        } else {
          this.openSnackBar('No patient / Visit found', 'Failed')
          this.patient_data = null;
          
        }
      }, (err) => {
        console.log(err);
      });

  }


}
