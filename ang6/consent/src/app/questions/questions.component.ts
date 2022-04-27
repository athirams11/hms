import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import {ThemePalette} from '@angular/material/core';
import { Router, NavigationEnd } from '@angular/router';
import { SignaturePad } from 'angular2-signaturepad';
import { HostListener } from "@angular/core";
import { ActivatedRoute } from '@angular/router';
import { PatientService } from '../services';
import {MatSnackBar} from '@angular/material';
import moment from 'moment-timezone';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../class/Utils';

@Component({
  selector: 'app-questions',
  templateUrl: './questions.component.html',
  styleUrls: ['./questions.component.css']
})
export class QuestionsComponent implements OnInit {

	title = 'General Consent';
	title_ar = 'الموافقة العامة';
  	lang = 1;
	@ViewChild('signdiv') signdiv: ElementRef;
  	@ViewChild('signardiv') signardiv: ElementRef;
	@ViewChild(SignaturePad) signaturePad: SignaturePad;
	signaturePadOptions = { // passed through to szimek/signature_pad constructor
	    'minWidth': 1,
	    'canvasWidth': 400,
	    'canvasHeight': 200,
	    'backgroundColor':'#e9ecef'
	};

	questions :any
					
	@HostListener('window:resize', ['$event'])
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
	public patient_options: any = [''];
	public patient_nodata: any = [];
  public patients: any = [];
	getScreenSize(event?) {
			this.scrHeight = window.innerHeight;
			this.scrWidth = window.innerWidth;
			//console.log(this.scrHeight, this.scrWidth);
	}
  constructor(private router: Router,public rest: PatientService,public snackBar: MatSnackBar) { 
	this.getScreenSize();
  }

  	ngOnInit() {
		this.getDropdowns();
		var width = this.signdiv.nativeElement.offsetWidth;
		
		if(width < 800){

			this.signaturePadOptions.canvasWidth =  width-30;
		this.signaturePadOptions.canvasHeight = width/2;
		}
		this.init_values()
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
	changeLang(lang){
		this.lang = lang;
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
	init_values(){
		this.questions = [
			{remark:'',need_remark:false,is_option:false,ques:'Is your general health good?',ques_ar:'أية مشكلة بالصحة عموما؟',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Are you presently under medical care?',ques_ar:'هل تتبع أية عناية طبية؟',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Have you ever had a serious illness or operation?',ques_ar:'هل تعرضت لمشكلة صحية خطيرة؟',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Are you taking any medications, including anticoagulants? ',ques_ar:'أية أدوية أو مميعات دم؟ هل تتناول',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:true,ques:'Do you have/had any of the following:',ques_ar:'هل بأي من الأمراض التالية؟:',ans:'',
				options:[
					{ques:'Anemia?',ar:'فقر دم',ans:''},
					{ques:'Heart, heart valve problems or rheumatic fever? ',ar:' مشاكل قلبية صمام',ans:''},
					{ques:'High blood pressure?',ar:'ارتفاع ضغط الدم',ans:''},
					{ques:'Hemophilia, thalassemia, or a tendency to bleed?',ar:'هيموفيليا أو تلاسيميا أو قابلية النزف',ans:''},
					{ques:'Jaundice?',ar:'يرقان',ans:''},
					{ques:'Asthma?',ar:'ربو',ans:''},
					{ques:'Diabetes?',ar:'سكري',ans:''},
					{ques:'Epilepsy?',ar:'شلل',ans:''},
					{ques:'Hepatitis or HIV?',ar:'التهاب كبد أو ايدز',ans:''},
					{ques:'Liver, kidney or thyroid problems?',ar:'الكلية أو الغدة الدرقية',ans:''},
				]
			},
			{remark:'',need_remark:false,is_option:false,ques:'Are you pregnant or a nursing mother?',ques_ar:'هل أنت حامل أو مرضع؟',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Have you got any allergies? [latex, medicine, food, iodine, others] ',ques_ar:'هل اصبت بأي تحسس لأي مادة:لاتكس أو أي دواء أو طعام أو....',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Have you ever had a serious reaction to an antibiotic, such as penicillin?',ques_ar:'هل تعرضت لأية صدمة تحسس لأي مضاد حيوي مثل البنيسيلين؟',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Are you or your family sensitive to any anaesthesia drugs?',ques_ar:'هل تعرضت للتحسس للتخدير',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Do you smoke?',ques_ar:'هل أنت مدخن؟',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Do you have any medical problems or special needs not mentioned?',ques_ar:'أية مشاكل طبية أو احتياجات خاصة لم تذكر؟',ans:'',options:[]},
			{remark:'',need_remark:false,is_option:false,ques:'Have you ever fainted during dental work?',ques_ar:'هل أصبت بالدوار خلال معالجات الأسنان سابقا؟',ans:'',options:[]},
			{remark:'',need_remark:true,is_option:false,ques:'When was your last dental visit?',ques_ar:'متى آخر زيارة لك لعيادة الأسنان؟ ',ans:'',options:[]},
			{remark:'',need_remark:true,is_option:false,ques:'Is there anything else we should be aware of, before attending to your dental needs?',ques_ar:'هل تريد اخبارنا بأي شيء آخر يجب معرفته قبل البدء بالعلاج؟',ans:'',options:[]},
			//{is_option:false,ques:'Is the patient fit for Dental Procedure?',ques_ar:'هل المريض جاهز لتلقي علاج الأسنان',ans:'',options:[]},
]
	}
	public getPatientDetails($event) {
		//console.log(this.opnumber);
		this.opnumber = this.patient_nodata.OP_REGISTRATION_NUMBER;
		if(this.opnumber != ''){
		
		var sendJson = {
		op_number: this.opnumber,
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
	configer = {
        displayKey: "OP_REGISTRATION_NUMBER",
        search: true, 
        height: '200px',
        placeholder: 'Select patient number',
        limitTo: 239, 
        moreText: '.........', 
        noResultsFound: 'No results found!',
        searchPlaceholder: 'Search',
        searchOnKey: 'OP_REGISTRATION_NUMBER'
      }

      public getDropdowns() {
        this.patients = [];
        const myDate = new Date();
        var sendJson = {
          dateVal : formatDateTime (this.dateVal),
          timeZone: moment.tz.guess(),
          };
          this.rest.getPatientLists(sendJson).subscribe((data) => {
          if (data['status'] === 'Success') {
              this.patients = data['data'];
              // this.patient_nodata = this.patients;
              for (let index = 0; index < this.patients.length; index++) {
                if (this.patients[index].PATIENT_ID == this.opnumber) {
                  this.patient_nodata = this.patients[index];
                } 
              }
              
          }
        });
      }

	public saveConsent(){
		console.log(this.questions);
		var formData = {
		  sign : this.signaturePad.toDataURL(),
		  patient_visit: this.patient_visit,
		  dateVal: this.dateVal,
		  opnumber: this.opnumber,
		  lang:this.lang,
		  type:3,
		  selected_options:{
			questions: this.questions
		  },
		  timeZone: moment.tz.guess(),
		}
		if(this.accept_terms == false){
		  this.openSnackBar('Please accept terms', 'Error')
		  return;
		}
		var questions_checked: boolean = true
		var isolationtype_checked = false
		var toccSymptoms_checked = false
		this.questions.forEach(function (value) {
		 
		  if(value.is_option==false && value.ans === ''){
			questions_checked = false;
			return
		  }
		  else if(value.is_option==true){
			value.options.forEach(function (v) {
				if(v.ans === ''){
					questions_checked = false;
					console.log(v);
					return
				  }
			});
		  }
		}); 
		if(questions_checked !== true){
		  this.openSnackBar('Please answer all options' , 'Error')
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
			  this.init_values()
	
			} else {
			  this.openSnackBar('No patient / Visit found', 'Failed')
			  this.patient_data = null;
			  
			}
		  }, (err) => {
			console.log(err);
		  });
	
	}

}
