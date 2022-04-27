import { Component, OnInit, Input, OnChanges, SimpleChanges, Output, EventEmitter } from '@angular/core';
import { NursingAssesmentService } from './../../../../../shared/services'
import { formatTime, formatDateTime, formatDate, defaultDateTime } from './../../../../../shared/class/Utils';
import { AppSettings } from'./../../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from './../../../../../shared';
import Moment from 'moment-timezone';
import { iif } from 'rxjs';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { exists } from 'fs';

@Component({
  selector: 'app-pain-assessment',
  templateUrl: './pain-assessment.component.html',
  styleUrls: ['./pain-assessment.component.scss']
})
export class PainAssessmentComponent implements OnInit {


    @Input() assessment_id: number = 0;
    @Input() patient_id: number = 0;
    @Input() selected_visit: any = [];
    @Input() save_notify: number ;
    @Output() saveNotify = new EventEmitter();
    public todaysDate = defaultDateTime();
    public settings = AppSettings;
    notifier: NotifierService;
    user_rights: any;
    public pain_rate: { id: number, label: string, value: number,check:number,image:string,select:string}[] = [
      { 
        "id": 1, 
        "label": "No Hurt" , 
        value: 0,
        check:0, 
        "image": "assets/images/PainAssessment0.jpeg",
        "select": "assets/images/PainAssessmentSelect0.jpeg"
      },
      { 
        "id": 2,
        "label": "Hurts Little Bit" , 
        value: 2,
        check:0, 
        "image": "assets/images/PainAssessment2.jpeg",
        "select": "assets/images/PainAssessmentSelect2.jpeg"
      },
      { 
        "id": 3, 
        "label": "Hurts Little More" , 
        value: 4,
        check:0, 
        "image": "assets/images/PainAssessment4.jpeg",
        "select": "assets/images/PainAssessmentSelect4.jpeg"
      },
      { 
        "id": 4, 
        "label": "Hurts Even More" , 
        value: 6,
        check:0, 
        "image": "assets/images/PainAssessment6.jpeg",
        "select": "assets/images/PainAssessmentSelect6.jpeg"
      },
      { 
        "id": 5, 
        "label": "Hurts Whole Lot" , 
        value: 8,
        check:0, 
        "image": "assets/images/PainAssessment8.jpeg",
        "select": "assets/images/PainAssessmentSelect8.jpeg"
      },
      { 
        "id": 6, 
        "label": "Hurts Worst" , 
        value: 10,
        check:0, 
        "image": "assets/images/PainAssessment10.jpeg",
        "select": "assets/images/PainAssessmentSelect10.jpeg"
      },
     
    ];
    public pain_rate_user: { id: number, label: string, value: number,check:number,image:string,select:string}[] = [
      { 
        "id": 1, 
        "label": "No Hurt" , 
        value: 0,
        check:0, 
        "image": "assets/images/PainAssessment0.jpeg",
        "select": "assets/images/PainAssessmentSelect0.jpeg"
      },
      { 
        "id": 2,
        "label": "Hurts Little Bit" , 
        value: 2,
        check:0, 
        "image": "assets/images/PainAssessment2.jpeg",
        "select": "assets/images/PainAssessmentSelect2.jpeg"
      },
      { 
        "id": 3, 
        "label": "Hurts Little More" , 
        value: 4,
        check:0, 
        "image": "assets/images/PainAssessment4.jpeg",
        "select": "assets/images/PainAssessmentSelect4.jpeg"
      },
      { 
        "id": 4, 
        "label": "Hurts Even More" , 
        value: 6,
        check:0, 
        "image": "assets/images/PainAssessment6.jpeg",
        "select": "assets/images/PainAssessmentSelect6.jpeg"
      },
      { 
        "id": 5, 
        "label": "Hurts Whole Lot" , 
        value: 8,
        check:0, 
        "image": "assets/images/PainAssessment8.jpeg",
        "select": "assets/images/PainAssessmentSelect8.jpeg"
      },
      { 
        "id": 6, 
        "label": "Hurts Worst" , 
        value: 10,
        check:0, 
        "image": "assets/images/PainAssessment10.jpeg",
        "select": "assets/images/PainAssessmentSelect10.jpeg"
      },
     
    ];
    public pain_assessment_data : any = []
    pain_intensity_list: any;
    pain_character_list: any;
    public pain_assessment = {
      user_id : 0,
      assessment_id : 0,
      pain_score : 1,
      pain_intensity : 0,
      pain_character : 0,
      location : "",
      frequency : "",
      duration : "",
      radiation : "",
      pain_assessment_id : 0,
      client_date : new Date(),
      date : new Date(),
      timeZone: Moment.tz.guess(),
    };
    user_data: any;
    // pain_rate_user: { id: number; label: string; value: number; check: number; image: string; select: string; }[];
    constructor(private loaderService : LoaderService, public nurse:NursingAssesmentService,notifierService: NotifierService) { 
      this.notifier = notifierService;
    }
    ngOnInit() {
      this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
      this.user_data = JSON.parse(localStorage.getItem('user'));
      this.get_pain_character();
      this.get_pain_intensity();
      this.getPainAssesments();
      this.getPainAssesment();
    }
    ngOnChanges(changes: SimpleChanges) {
      this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
      this.user_data = JSON.parse(localStorage.getItem('user'));
      this.getPainAssesments();
      this.getPainAssesment();
    }
    public selectPainRate(i)
    {
      if(formatDate(this.todaysDate) == formatDate(this.selected_visit.CREATED_TIME))
      {
        if(this.pain_rate[i].check == 0)
        {
          for(let data of this.pain_rate)
          {
            data["check"] = 0
          }
          this.pain_rate[i].check = 1
          this.pain_assessment.pain_score = this.pain_rate[i].value
        }
        else
        {
          this.pain_rate[i].check = 0
          this.pain_assessment.pain_score = 1 
        }
        this.savePainAssesment()
      }
    }
    public get_pain_intensity() {
      const postData = {
        master_id : 12
      };
      this.nurse.get_master_data(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.pain_intensity_list = result['master_list'];
        } else {
          this.pain_intensity_list = [];
        }
      });
    }
    public get_pain_character() {
      const postData = {
        master_id : 13
      };
      this.nurse.get_master_data(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.pain_character_list = result['master_list'];
        } else {
          this.pain_character_list = [];
        }
      });
    }
    public getEvent()
    {
      this.save_notify = 1
      this.saveNotify.emit(this.save_notify)
    }
    public formatTime (time) {
      return  formatTime(time);
    }
    public formatDate (date) {
      return  formatDate(date);
    }
    public formatDateTime (data) {
        return formatDateTime(data);
    }
    public savePainAssesment() {
      this.pain_assessment.user_id =  this.user_data.user_id;
      this.pain_assessment.assessment_id = this.assessment_id;
      this.pain_assessment.date = defaultDateTime();
      this.pain_assessment.timeZone = Moment.tz.guess();
      this.loaderService.display(true);
      this.nurse.savePainAssesment(this.pain_assessment).subscribe((result) => {
        this.loaderService.display(false);
        this.save_notify = 2
        this.saveNotify.emit(this.save_notify)
        if (result.status === 'Success') {
          this.notifier.notify( 'success',result.msg );
          this.pain_assessment.pain_assessment_id = result.data_id;
          this.pain_assessment.assessment_id = this.assessment_id;
          this.getPainAssesment()
        } else {
          this.notifier.notify( 'error', result.msg );
        }
  
      }, (err) => {
        this.notifier.notify( 'error', err );
      });
    }
    public getPainAssesment() {
      const postData = {
        user_id : this.user_data.user_id,
        assessment_id :  this.assessment_id,
      }
      this.loaderService.display(true);
      this.nurse.getPainAssesment(postData).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status === 'Success') {
          var i = 0
          //this.pain_assessment_data =[]
          for(let pain of result.data)
          {
            if(pain.CREATED_BY == this.user_data.user_id)
            {
              this.pain_assessment.pain_assessment_id = pain.PAIN_ASSESSMENT_ID
              for(let data of this.pain_rate)
              {
                data["check"] = 0
                if(data["value"] == pain.PAIN_SCORE)
                {
                  data["check"] = 1
                }
              }
            }
          }
        }
        else
        {
          for(let data of this.pain_rate)
          {
            data["check"] = 0
          }
        }
      });
    } 
    public getPainAssesments() {
      const postData = {
        user_id : this.user_data.user_id,
        assessment_id :  this.assessment_id,
        user_group : this.user_data.user_group
      }
      this.loaderService.display(true);
      this.nurse.getPainAssesments(postData).subscribe((result) => {
        this.loaderService.display(false);
        if(result.status === 'Success') {
          this.pain_assessment_data = result.data
          this.pain_rate_user = [
            { 
              "id": 1, 
              "label": "No Hurt" , 
              value: 0,
              check:0, 
              "image": "assets/images/PainAssessment0.jpeg",
              "select": "assets/images/PainAssessmentSelect0.jpeg"
            },
            { 
              "id": 2,
              "label": "Hurts Little Bit" , 
              value: 2,
              check:0, 
              "image": "assets/images/PainAssessment2.jpeg",
              "select": "assets/images/PainAssessmentSelect2.jpeg"
            },
            { 
              "id": 3, 
              "label": "Hurts Little More" , 
              value: 4,
              check:0, 
              "image": "assets/images/PainAssessment4.jpeg",
              "select": "assets/images/PainAssessmentSelect4.jpeg"
            },
            { 
              "id": 4, 
              "label": "Hurts Even More" , 
              value: 6,
              check:0, 
              "image": "assets/images/PainAssessment6.jpeg",
              "select": "assets/images/PainAssessmentSelect6.jpeg"
            },
            { 
              "id": 5, 
              "label": "Hurts Whole Lot" , 
              value: 8,
              check:0, 
              "image": "assets/images/PainAssessment8.jpeg",
              "select": "assets/images/PainAssessmentSelect8.jpeg"
            },
            { 
              "id": 6, 
              "label": "Hurts Worst" , 
              value: 10,
              check:0, 
              "image": "assets/images/PainAssessment10.jpeg",
              "select": "assets/images/PainAssessmentSelect10.jpeg"
            },
           
          ];
        }
        else
        {
          this.pain_assessment_data = result.data
          this.pain_rate_user = []
          
        }
      });
    }
  }