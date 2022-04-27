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
  selector: 'app-general',
  templateUrl: './general.component.html',
  styleUrls: ['./general.component.css']
})
export class GeneralComponent implements OnInit {
  title = 'Dental Consent';
  	title_ar = 'الموافقة على الأسنان';

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


    condetions = [
      {
        en:"<strong>1. Treatment Plan</strong> \nI understand the recommended treatment and my financial responsibility as explained to me.\nI understand that by signing this consent I am in no way obligated to any treatment. I also acknowledge that during treatment it may be necessary to change or add procedures because of conditions found while working on the teeth that were not discovered during examination. For example, root canal therapy following routine restorative procedures.",
        ar:"أفهم العلاج الموصى به ومسؤوليتي المالية كما هو موضح لي. أفهم أنه من خلال التوقيع على هذه الموافقة ، لست ملزماً بأي شكل من الأشكال بأي علاج. وأقر أيضًا أنه قد يكون من الضروري أثناء العلاج تغيير أو إضافة إجراءات بسبب الحالات التي وجدت أثناء العمل على الأسنان التي لم يتم اكتشافها أثناء الفحص. على سبيل المثال ،علاج قناة الجذر بعد إجراء حشوة محافظة روتينية"
      },
      {
        en:"<strong>2. Drug and Medications</strong> \nI understand that antibiotics, analgesics and other medications can cause allergic reactions such as redness and swelling tissue, pain, itching, vomiting and/or anaphylactic shock.",
        ar:"الأدوية والاستطبابات:\n أفهم أن المضادات الحيوية والمسكنات والأدوية الأخرى يمكن أن تسبب تفاعلات حساسية مثل الاحمرار وتورم الأنسجة والألم والحكة والقيء و / أو صدمة الحساسية"
      },
      {
        en:"<strong>3. Extractions</strong> \nAlternatives to removal of teeth have been explained to me (root canal therapy, crown and bridge procedures, periodontal therapy, etc.)\nI understand removing teeth does not always remove the infection, if present, and may be necessary to have further treatment. \nI understand the risks involved in having teeth removed, some of which are pain, swelling, spread of infection, dry socket, loss of feeling in my teeth, lips, tongue and surrounding tissue (paresthesia) that can last for an indefinite period of time, or fractured jaw. \nI understand I may need further treatment by a specialist if complications arise during or following treatment, the cost of which is my responsibility.",
        ar:"تم شرح بدائل إزالة الأسنان (علاج قناة الجذر ، وإجراءات التاج والجسور ، علاج اللثة ، إلخ.) أفهم أن إزالة الأسنان لا تزيل دائمًا الالتهاب او الانتان ، إذا كانت موجودة ، وقد تكون هناك ضرورة لمزيد من العلاجات . أتفهم المخاطر التي تنطوي عليها إزالة الأسنان ، وبعضها الألم ، والتورم ، وانتشار الالتهاب ، والجيب الجاف ، وفقدان الإحساس في أسناني ، والشفاه ، واللسان والأنسجة المحيطة (تشوش الحس) التي يمكن أن تستمر لفترة غير محددة من الوقت ، أو كسر في الفك. أفهم أنني قد أحتاج إلى مزيد من العلاج من قبل أخصائي إذا ظهرت مضاعفات أثناء العلاج أو بعده ، وتكون تكلفتها هي مسؤوليتي"
      },
      {
        en:"<strong>4. Crown's, Bridges, Veneers</strong> \nI understand that sometimes it is not possible to match the color of natural teeth exactly with artificial teeth.\nI further understand that I may be wearing temporary crowns, which come off easily and that I must be careful to ensure that they are kept on until the permanent crown is delivered.\nI realize the final opportunity to make changes (shape of, fit, size and color) will be before cementation. It is also my responsibility to return for permanent cementation within 20 days from tooth preparation. Excessive delays may allow for tooth movement. This may necessitate a remake of the crown or bridge.\nI understand there will be additional charges for remakes due to my delaying permanent cementation.",
        ar:"أتفهم أنه في بعض الأحيان لا يمكن مطابقة لون الأسنان الطبيعية بالأسنان الاصطناعية تمامًا.\nأتفهم أيضًا أنني قد أضع  تيجانًا مؤقتة ، والتي تنزع بسهولة وأنني يجب أن أكون حريصًا على ضمان الاحتفاظ بها حتى يتم تسليم التاج الدائم.\nأدرك أن الفرصة الأخيرة لإجراء تغييرات (الشكل والملاءمة والحجم واللون) ستكون قبل الإلصاق النهائي.\nمن مسؤوليتي أيضًا أن أعود للتثبيت الدائم في غضون 20 يومًا من إعداد الأسنان. قد يسمح التأخير المفرط بحركة الأسنان. قد يتطلب هذا إعادة تصنيع التاج أو الجسر. أفهم أنه ستكون هناك رسوم إضافية على عمليات إعادة التصنيع بسبب تأخري في الإسمنت الدائم."
      },
      {
        en:"<strong>5. Endodontic Therapy</strong> \nI realize there is no guarantee that root canal treatment will save my tooth, and that complications can occur from the treatment, and that occasionally root canal filling material may extend through the tooth which does not necessarily affect the success of the treatment. \nI understand that endodontic files and reamers are very fine instruments and stresses and defects in their manufacture can cause them to separate during use.\nI understand that occasionally additional surgical procedures may be necessary following root canal treatment (apicoectomy). I understand that the tooth may be lost in spite of all efforts to restore it.",
        ar:"أدرك أنه لا يوجد ضمان بأن علاج قناة الجذر سينقذ أسناني ، وأن المضاعفات يمكن أن تحدث من العلاج ، وأن مادة حشو قناة الجذر قد تمتد أحيانًا من خلال السن مما لا يؤثر بالضرورة على نجاح العلاج .\nأتفهم أن أدوات علاج الأقنية اللبية هي أدوات دقيقة جدًا ، ويمكن للضغوط والعيوب في تصنيعها أن تتسبب في انفصالها أثناء الاستخدام. أتفهم أنه قد يلزم أحيانًا إجراء عمليات جراحية إضافية بعد علاج قناة الجذر (عملية قطع ذروة الجذر). أفهم أن السن قد يفقد بالرغم من كل الجهود المبذولة لاستعادته.\n"
      },
      {
        en:"<strong>6. Periodontal Disease</strong> \nI understand that I have been diagnosed with a serious condition, causing gum and bone inflammation and/or loss and that the result could lead to the loss of teeth. Alternative treatments have been explained to me, including gum surgery, tooth extraction and/or replacement.",
        ar:"أتفهم أنه قد تم تشخيص حالتي بحالة جدية حرجة ، مما تسبب في التهاب و / أو فقدان اللثة والعظام وأنها بالنتيجة قد تؤدي إلى فقدان الأسنان.\nشرحت لي العلاجات البديلة ، بما في ذلك جراحة اللثة ، وقلع الأسنان و / أو استبدالها."
      },
      {
        en:"<strong>7. Fillings</strong> \nI understand that care must be exercised in chewing on filling teeth, especially during the first 24 hours to avoid breakage. \nI understand that a more extensive restorative procedure than originally diagnosed may be required due to additional or extensive decays \nI understand that significant sensitivity is a common after effect of newly placed fillings.",
        ar:"أتفهم أنه يجب توخي الحذر عند المضغ على حشو الأسنان ، خاصة خلال الـ 24 ساعة الأولى لتجنب الكسر.\nأتفهم أن إجراء علاجات ترميمية أكثر شمولاً مما تم تشخيصه في الأصل قد تكون مطلوبًة تبعا لاصابات او نخور إضافية\nاتفهم أن الحساسية الشديدة هي أمر شائع بعد عمل الحشوات الموضوعة حديثًا."
      },
      {
        en:"<strong>8. Partials and Dentures</strong> \nI understand the wearing of partials/dentures is difficult. Sore spots, altered speech, and difficulty in eating are common problems. Immediate dentures (placement of dentures immediately after extractions) may be painful. Immediate dentures may require considerable adjusting and several relines. A permanent reline will be needed at a later date. This is not included in the denture fee. \nI understand that it is my responsibility to return for delivery of my partial/denture \nI understand that failure to keep my delivery appointment may result in poorly fitted dentures. If a remake is I understand that dentistry is not an exact science and that, therefore, reputable practitioners cannot property guarantee results. I acknowledge that no guarantee or assurance has been made by anyone regarding the dental treatment, which I have requested and authorized.",
        ar:"أتفهم أن ارتداء الأجزاء الجزئية / أطقم الأسنان أمر صعب. تعتبر البقع المؤلمة ، وتغيير الكلام ، وصعوبة تناول الطعام من المشاكل الشائعة. قد تكون أطقم الأسنان الفورية (وضع أطقم الأسنان بعد الاستخراج مباشرة) مؤلمة. قد تتطلب أطقم الأسنان الفورية تعديلًا كبيرًا وعدة علاجات. سوف تكون هناك حاجة إلى مقوم دائم في وقت لاحق. لم يتم تضمين هذا في رسوم طقم الأسنان. أنا أتفهم أنه من مسؤوليتي العودة لتسليم بلدي\n جزئي / أسنان. أفهم أن الفشل في الاحتفاظ بموعد التسليم قد يؤدي إلى أطقم أسنان سيئة التركيب. إذا كان طبعة جديدة\n مطلوب بسبب تأخري لأكثر من 30 يومًا ، يمكن تكبد رسوم إضافية\n أتفهم أن طب الأسنان ليس علمًا دقيقًا ، وبالتالي ، فإن الممارسين ذوي السمعة الطيبة لا يمكنهم ضمان النتائج. أقر بأنه لم يتم تقديم أي ضمان أو تأكيد من قِبل أي شخص فيما يتعلق بعلاج الأسنان ، الذي طلبته وسمح به"
      }
    ];
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
    @HostListener('window:resize', ['$event'])
	
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
    public getPatientDetails($event) {
      //console.log(this.opnumber);

      this.opnumber = this.patient_nodata.OP_REGISTRATION_NUMBER;
      if(this.opnumber != ''){
        var sendJson = {
          op_number: this.opnumber,
          dateVal : formatDateTime (this.dateVal),
          selected_options:{
            condetions: this.condetions
          },
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
      type:1,
      lang:this.lang,
      
      timeZone: moment.tz.guess(),
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
    if(this.patient_visit == null  ){
      this.openSnackBar('Invalid visit for patient', 'Error')
      return;
    }
    this.rest.saveGenConsentDetails(formData).subscribe((result) => {
        
        if (result.status == 'Success') {
          this.patient_data = null;
          this.signaturePad.clear()
          this.opnumber = ''
          //this.loaderService.display(false);
          this.openSUccessSnackBar(result.msg, '')
          this.accept_terms = false;
          

        } else {
          this.openSnackBar('No patient / Visit found', 'Failed')
          this.patient_data = null;
          
        }
      }, (err) => {
        console.log(err);
      });

  }


}
