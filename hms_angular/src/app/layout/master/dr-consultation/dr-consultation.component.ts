import { Component, OnInit } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';
import { routerTransition } from '../../../router.animations';
import { DrConsultationService } from '../../../shared/services'
import { NotifierService } from 'angular-notifier';
import * as moment from 'moment';
import { LoaderService } from '../../../shared';
@Component({
  selector: 'app-dr-consultation',
  templateUrl: './dr-consultation.component.html',
  styleUrls: ['./dr-consultation.component.scss','../master.component.scss'],
  animations: [routerTransition()],
  //providers: [DrConsultationService]
})
export class DrConsultationComponent implements OnInit {
  private notifier: NotifierService;
  public now = new Date(); public client_date:any;
  public drSchedule = {
    user_id:1,
    schedule_id:0,
    dr_name:"",
    dr_id:"",
    sel_spec_in:"",
    avg_con_t:"20",
    time_table: {
        sun :{
        slot_1: {
          from :"",
          to: ""
        },
        slot_2: {
          from :"",
          to: ""
        },
        slot_3: {
          from :"",
          to: ""
        },
        de_select : true
      },
      mon :{
        slot_1: {
          from :"",
          to: ""
        },
        slot_2: {
          from :"",
          to: ""
        },
        slot_3: {
          from :"",
          to: ""
        },
        de_select : true
      },
      tue :{
        slot_1: {
          from :"",
          to: ""
        },
        slot_2: {
          from :"",
          to: ""
        },
        slot_3: {
          from :"",
          to: ""
        },
        de_select : true
      },
      wed :{
        slot_1: {
          from :"",
          to: ""
        },
        slot_2: {
          from :"",
          to: ""
        },
        slot_3: {
          from :"",
          to: ""
        },
        de_select : true
      },
      thu :{
        slot_1: {
          from :"",
          to: ""
        },
        slot_2: {
          from :"",
          to: ""
        },
        slot_3: {
          from :"",
          to: ""
        },
        de_select : true
      },
      fri :{
        slot_1: {
          from :"",
          to: ""
        },
        slot_2: {
          from :"",
          to: ""
        },
        slot_3: {
          from :"",
          to: ""
        },
        de_select : true
      },
      sat :{
        slot_1: {
          from :"",
          to: ""
        },
        slot_2: {
          from :"",
          to: ""
        },
        slot_3: {
          from :"",
          to: ""
        },
        de_select : true
      }
    }

  }
  specialized_in : any = [];
  public doctors_list : any = [];
  public success_message = "";
  public failed_message = "";
  public loading = false;
  public enable_action = true;
  public user_rights : any ={};
  public user_data : any ={};
  public doc_data : any =[];
  dropdownSettings = {};
  constructor(private loaderService:LoaderService ,public rest:DrConsultationService, public datepipe: DatePipe, private router: Router, private route: ActivatedRoute,notifierService: NotifierService) { 
    
    this.notifier = notifierService;
  }
  
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.getDropdowns(); 
    const id = +this.route.snapshot.paramMap.get('id');
    const type = this.route.snapshot.paramMap.get('type');
    this.enable_action = true;
    if(type == "view")
    {
      this.enable_action = false
    }
    if(id != null && id != 0)
    {
      this.getScheduleById(id);
    }
    this.dropdownSettings = {
      singleSelection: true,
      idField: 'DOCTORS_ID',
      textField: 'DOCTORS_NAME',
      itemsShowLimit: 5,
      allowSearchFilter: true
    };

  }
  onItemSelect(item: any) {
    //console.log(item);
    this.drSchedule.dr_name = item.DOCTORS_NAME; 
    this.drSchedule.dr_id = item.DOCTORS_ID ;
    this.getDoctorsSpecialized();
  }
  onSelectAll(items: any) {
    //console.log(items);
  }
  public getScheduleById(id)
  {
    this.loaderService.display(true);
    var sendData = {
      id : id
    };
    this.rest.getScheduleById(sendData).subscribe((result) => {
      //this.router.navigate(['/product-details/'+result._id]);
      //console.log(result);
      window.scrollTo(0, 0);
      if(result["status"] == "Success")
      {
        this.loaderService.display(false);
        var value = result.data;
        this.drSchedule.dr_name = value.DOCTORS_NAME;
        this.drSchedule.dr_id = value.DOCTORS_ID;
        this.doc_data = value.DOCTOR_DETAILS;
       // console.log(value);
        this.drSchedule.sel_spec_in = value.SPECIALIZED_IN;
        this.drSchedule.avg_con_t = value.AVG_CONS_TIME;
        this.drSchedule.schedule_id = +value.DOCTORS_SCHEDULE_ID;
        if(value.sun)
        {
          this.drSchedule.time_table.sun.de_select = false;
          if(value.sun.slot_1)
          {
            this.drSchedule.time_table.sun.slot_1.from = moment(value.sun.slot_1.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.sun.slot_1.to = moment(value.sun.slot_1.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.sun.slot_2)
          {
            this.drSchedule.time_table.sun.slot_2.from = moment(value.sun.slot_2.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.sun.slot_2.to = moment(value.sun.slot_2.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.sun.slot_3)
          {
            this.drSchedule.time_table.sun.slot_3.from = moment(value.sun.slot_3.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.sun.slot_3.to = moment(value.sun.slot_3.to, "HH:mm.ss").format("HH:mm");
          }
        }

        if(value.mon)
        {
          this.drSchedule.time_table.mon.de_select = false;
          if(value.mon.slot_1)
          {  
            this.drSchedule.time_table.mon.slot_1.from = moment(value.mon.slot_1.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.mon.slot_1.to = moment(value.mon.slot_1.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.mon.slot_2)
          {
            this.drSchedule.time_table.mon.slot_2.from = moment(value.mon.slot_2.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.mon.slot_2.to = moment(value.mon.slot_2.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.mon.slot_3)
          {
            this.drSchedule.time_table.mon.slot_3.from = moment(value.mon.slot_3.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.mon.slot_3.to = moment(value.mon.slot_3.to, "HH:mm.ss").format("HH:mm");
          }
        }
        if(value.tue)
        {
          this.drSchedule.time_table.tue.de_select = false;
          if(value.tue.slot_1)
          {   
            this.drSchedule.time_table.tue.slot_1.from = moment(value.tue.slot_1.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.tue.slot_1.to = moment(value.tue.slot_1.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.tue.slot_2)
          {
            this.drSchedule.time_table.tue.slot_2.from = moment(value.tue.slot_2.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.tue.slot_2.to = moment(value.tue.slot_2.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.tue.slot_3)
          {
            this.drSchedule.time_table.tue.slot_3.from = moment(value.tue.slot_3.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.tue.slot_3.to = moment(value.tue.slot_3.to, "HH:mm.ss").format("HH:mm");
          }
        }
        if(value.wed)
        {
          this.drSchedule.time_table.wed.de_select = false;

          if(value.wed.slot_1)
          {
            this.drSchedule.time_table.wed.slot_1.from = moment(value.wed.slot_1.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.wed.slot_1.to = moment(value.wed.slot_1.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.wed.slot_2)
          {
            this.drSchedule.time_table.wed.slot_2.from = moment(value.wed.slot_2.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.wed.slot_2.to = moment(value.wed.slot_2.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.wed.slot_3)
          {
            this.drSchedule.time_table.wed.slot_3.from = moment(value.wed.slot_3.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.wed.slot_3.to = moment(value.wed.slot_3.to, "HH:mm.ss").format("HH:mm");
          }
        }
        if(value.thu)
        {
          this.drSchedule.time_table.thu.de_select = false;

          if(value.thu.slot_1)
          {
            this.drSchedule.time_table.thu.slot_1.from = moment(value.thu.slot_1.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.thu.slot_1.to = moment(value.thu.slot_1.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.thu.slot_2)
          {
            this.drSchedule.time_table.thu.slot_2.from = moment(value.thu.slot_2.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.thu.slot_2.to = moment(value.thu.slot_2.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.thu.slot_3)
          {
            this.drSchedule.time_table.thu.slot_3.from = moment(value.thu.slot_3.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.thu.slot_3.to = moment(value.thu.slot_3.to, "HH:mm.ss").format("HH:mm");
          }
        }
        if(value.fri)
        {
          this.drSchedule.time_table.fri.de_select = false;

          if(value.fri.slot_1)
          {
            this.drSchedule.time_table.fri.slot_1.from = moment(value.fri.slot_1.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.fri.slot_1.to = moment(value.fri.slot_1.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.fri.slot_2)
          {
            this.drSchedule.time_table.fri.slot_2.from = moment(value.fri.slot_2.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.fri.slot_2.to = moment(value.fri.slot_2.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.fri.slot_3)
          {
            this.drSchedule.time_table.fri.slot_3.from = moment(value.fri.slot_3.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.fri.slot_3.to = moment(value.fri.slot_3.to, "HH:mm.ss").format("HH:mm");
          }
        }
        if(value.sat)
        {
          this.drSchedule.time_table.sat.de_select = false;

          if(value.sat.slot_1)
          {
            this.drSchedule.time_table.sat.slot_1.from = moment(value.sat.slot_1.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.sat.slot_1.to = moment(value.sat.slot_1.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.sat.slot_2)
          {
            this.drSchedule.time_table.sat.slot_2.from = moment(value.sat.slot_2.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.sat.slot_2.to = moment(value.sat.slot_2.to, "HH:mm.ss").format("HH:mm");
          }
          if(value.sat.slot_3)
          {
            this.drSchedule.time_table.sat.slot_3.from = moment(value.sat.slot_3.from, "HH:mm.ss").format("HH:mm");
            this.drSchedule.time_table.sat.slot_3.to = moment(value.sat.slot_3.to, "HH:mm.ss").format("HH:mm");
          }
        }
        
      }
      else
      {
        this.failed_message = "<strong>Failed </strong> to get schedule data"
        this.loaderService.display(false);

      }
      
      //MessageBox.show(this.dialog, `Hello, World!`);
    }, (err) => {
      console.log(err);
    });
  }
  public addNewOpRegistration() {
    if(this.drSchedule.dr_name == '' || this.drSchedule.dr_name == null)
    {
      //window.scrollTo(0, 0);
      //this.failed_message = "Invalid <strong>Doctor Name </strong> "
      this.notifier.notify( 'error', 'Please Select Doctor!' );
    }
    else if(this.drSchedule.sel_spec_in == '' || this.drSchedule.sel_spec_in == null)
    {
      //window.scrollTo(0, 0);
      //this.failed_message = "Invalid <strong>Specialized In</strong> "
      this.notifier.notify( 'error', 'Please Select Specialized In!' );
    }
    else if(this.drSchedule.avg_con_t == '' || this.drSchedule.avg_con_t == '0' || this.drSchedule.avg_con_t == null)
    {
      //window.scrollTo(0, 0);
      //this.failed_message = "Invalid <strong>Avg. Consult Time </strong> "
      this.notifier.notify( 'error', 'Invalid Avg. Consult Time!' );
    }
    else if(this.drSchedule.time_table.sun.de_select == true &&
      this.drSchedule.time_table.mon.de_select == true && 
      this.drSchedule.time_table.tue.de_select == true && 
      this.drSchedule.time_table.wed.de_select == true && 
      this.drSchedule.time_table.thu.de_select == true && 
      this.drSchedule.time_table.fri.de_select == true && 
      this.drSchedule.time_table.sat.de_select == true )
    {
      //console.log(this.drSchedule.avg_con_t);
      //window.scrollTo(0, 0);
      //this.failed_message = "Selected <strong>shcedule days</strong> must be more than Zero"
      this.notifier.notify( 'error', 'Please enter schedule for atleast one day.!' );
    }
    else
    {
      this.loaderService.display(true);
      this.drSchedule.user_id = this.user_data.user_id;
      this.rest.addNewSchedule(this.drSchedule).subscribe((result) => {
        //this.router.navigate(['/product-details/'+result._id]);
        //console.log(result);
        window.scrollTo(0, 0);
        if(result["status"] == "Success")
        {
          this.loaderService.display(false);
          if(result.response)
          {
            //this.success_message = result.response;
            this.notifier.notify( 'success', result.response );
          }
          else{
              //this.success_message = "<strong>Data </strong> saved successfully...";
              this.notifier.notify( 'success', 'Data saved successfully...' );
          }
          setTimeout(() => {
              this.router.navigate(['master/dr-schedule-list']);
              //window.location.reload();
              
            }, 2000);
            this.getDropdowns();  
            this.drSchedule = {
              user_id:this.user_data.user_id,
              schedule_id:0,
              dr_name:"",
              dr_id:"",
              sel_spec_in:"",
              avg_con_t:"20",
              time_table: {
                  sun :{
                  slot_1: {
                    from :"",
                    to: ""
                  },
                  slot_2: {
                    from :"",
                    to: ""
                  },
                  slot_3: {
                    from :"",
                    to: ""
                  },
                  de_select : true
                },
                mon :{
                  slot_1: {
                    from :"",
                    to: ""
                  },
                  slot_2: {
                    from :"",
                    to: ""
                  },
                  slot_3: {
                    from :"",
                    to: ""
                  },
                  de_select : true
                },
                tue :{
                  slot_1: {
                    from :"",
                    to: ""
                  },
                  slot_2: {
                    from :"",
                    to: ""
                  },
                  slot_3: {
                    from :"",
                    to: ""
                  },
                  de_select : true
                },
                wed :{
                  slot_1: {
                    from :"",
                    to: ""
                  },
                  slot_2: {
                    from :"",
                    to: ""
                  },
                  slot_3: {
                    from :"",
                    to: ""
                  },
                  de_select : true
                },
                thu :{
                  slot_1: {
                    from :"",
                    to: ""
                  },
                  slot_2: {
                    from :"",
                    to: ""
                  },
                  slot_3: {
                    from :"",
                    to: ""
                  },
                  de_select : true
                },
                fri :{
                  slot_1: {
                    from :"",
                    to: ""
                  },
                  slot_2: {
                    from :"",
                    to: ""
                  },
                  slot_3: {
                    from :"",
                    to: ""
                  },
                  de_select : true
                },
                sat :{
                  slot_1: {
                    from :"",
                    to: ""
                  },
                  slot_2: {
                    from :"",
                    to: ""
                  },
                  slot_3: {
                    from :"",
                    to: ""
                  },
                  de_select : true
                }
              }
          
            }
        }
        else
        {
          if(result.response)
          {
            //this.failed_message = result.response;
            this.notifier.notify( 'error', result.response );
          }
          else{
            //this.failed_message = "<strong>Failed </strong> to saved the details";
            this.notifier.notify( 'error', 'Failed to saved the details!' );
           
          }
          this.loaderService.display(false);
        }
        
        //MessageBox.show(this.dialog, `Hello, World!`);
      }, (err) => {
        console.log(err);
      });
    }
    this.loaderService.display(false);
  }
  handleSelected($event,text) {
    if ($event.target.checked === true) {
    // Handle your code
      switch(text) { 
        case 'sun': { 
          //statements; 
          this.drSchedule.time_table.sun.de_select = false;
            if(this.drSchedule.time_table.sun.slot_1.from == "") {this.drSchedule.time_table.sun.slot_1.from=this.drSchedule.time_table.mon.slot_1.from}
            if(this.drSchedule.time_table.sun.slot_1.to == "") {this.drSchedule.time_table.sun.slot_1.to=this.drSchedule.time_table.mon.slot_1.to}
            if(this.drSchedule.time_table.sun.slot_2.from == "") {this.drSchedule.time_table.sun.slot_2.from=this.drSchedule.time_table.mon.slot_2.from}
            if(this.drSchedule.time_table.sun.slot_2.to == "") {this.drSchedule.time_table.sun.slot_2.to=this.drSchedule.time_table.mon.slot_2.to;}
            if(this.drSchedule.time_table.sun.slot_3.from == "") {this.drSchedule.time_table.sun.slot_3.from=this.drSchedule.time_table.mon.slot_3.from}
            if(this.drSchedule.time_table.sun.slot_3.to == "") {this.drSchedule.time_table.sun.slot_3.to=this.drSchedule.time_table.mon.slot_3.to}
            //console.log(text);
          break; 
        } 
        case 'mon': { 
          // statements;
          this.drSchedule.time_table.mon.de_select = false; 
          if(this.drSchedule.time_table.mon.slot_1.from == "") {this.drSchedule.time_table.mon.slot_1.from=this.drSchedule.time_table.sun.slot_1.from}
          if(this.drSchedule.time_table.mon.slot_1.to == "") {this.drSchedule.time_table.mon.slot_1.to=this.drSchedule.time_table.sun.slot_1.to}
          if(this.drSchedule.time_table.mon.slot_2.from == "") {this.drSchedule.time_table.mon.slot_2.from=this.drSchedule.time_table.sun.slot_2.from}
          if(this.drSchedule.time_table.mon.slot_2.to == "") {this.drSchedule.time_table.mon.slot_2.to=this.drSchedule.time_table.sun.slot_2.to}
          if(this.drSchedule.time_table.mon.slot_3.from == "") {this.drSchedule.time_table.mon.slot_3.from=this.drSchedule.time_table.sun.slot_3.from;}
          if(this.drSchedule.time_table.mon.slot_3.to == "") {this.drSchedule.time_table.mon.slot_3.to=this.drSchedule.time_table.sun.slot_3.to}
          //console.log(text);
          break;
        }
        case 'tue': {
          // statements;
          this.drSchedule.time_table.tue.de_select = false;
          if(this.drSchedule.time_table.tue.slot_1.from == "") {this.drSchedule.time_table.tue.slot_1.from=this.drSchedule.time_table.mon.slot_1.from;}
          if(this.drSchedule.time_table.tue.slot_1.to == "") {this.drSchedule.time_table.tue.slot_1.to=this.drSchedule.time_table.mon.slot_1.to;}
          if(this.drSchedule.time_table.tue.slot_2.from == "") {this.drSchedule.time_table.tue.slot_2.from=this.drSchedule.time_table.mon.slot_2.from;}
          if(this.drSchedule.time_table.tue.slot_2.to == "") {this.drSchedule.time_table.tue.slot_2.to=this.drSchedule.time_table.mon.slot_2.to;}
          if(this.drSchedule.time_table.tue.slot_3.from == "") {this.drSchedule.time_table.tue.slot_3.from=this.drSchedule.time_table.mon.slot_3.from;}
          if(this.drSchedule.time_table.tue.slot_3.to == "") {this.drSchedule.time_table.tue.slot_3.to=this.drSchedule.time_table.mon.slot_3.to;}
          //console.log(text);
          break;
        }
        case 'wed': {
          // statements;
          this.drSchedule.time_table.wed.de_select  =false;
          if(this.drSchedule.time_table.wed.slot_1.from == "") {this.drSchedule.time_table.wed.slot_1.from=this.drSchedule.time_table.tue.slot_1.from;}
          if(this.drSchedule.time_table.wed.slot_1.to == '') {this.drSchedule.time_table.wed.slot_1.to=this.drSchedule.time_table.tue.slot_1.to}
          if(this.drSchedule.time_table.wed.slot_2.from == '') {this.drSchedule.time_table.wed.slot_2.from=this.drSchedule.time_table.tue.slot_2.from}
          if(this.drSchedule.time_table.wed.slot_2.to == '') {this.drSchedule.time_table.wed.slot_2.to=this.drSchedule.time_table.tue.slot_2.to}
          if(this.drSchedule.time_table.wed.slot_3.from == '') {this.drSchedule.time_table.wed.slot_3.from=this.drSchedule.time_table.tue.slot_3.from}
          if(this.drSchedule.time_table.wed.slot_3.to == '') {this.drSchedule.time_table.wed.slot_3.to=this.drSchedule.time_table.tue.slot_3.to}
         // console.log(text);
          break;
        }
        case 'thu': {
          // statements;
          this.drSchedule.time_table.thu.de_select  = false;
          if(this.drSchedule.time_table.thu.slot_1.from == '') {this.drSchedule.time_table.thu.slot_1.from=this.drSchedule.time_table.wed.slot_1.from;}
          if(this.drSchedule.time_table.thu.slot_1.to == '') {this.drSchedule.time_table.thu.slot_1.to=this.drSchedule.time_table.wed.slot_1.to;}
          if(this.drSchedule.time_table.thu.slot_2.from == '') {this.drSchedule.time_table.thu.slot_2.from=this.drSchedule.time_table.wed.slot_2.from;}
          if(this.drSchedule.time_table.thu.slot_2.to == '') {this.drSchedule.time_table.thu.slot_2.to=this.drSchedule.time_table.wed.slot_2.to;}
          if(this.drSchedule.time_table.thu.slot_3.from == '') {this.drSchedule.time_table.thu.slot_3.from=this.drSchedule.time_table.wed.slot_3.from;}
          if(this.drSchedule.time_table.thu.slot_3.to == '') {this.drSchedule.time_table.thu.slot_3.to=this.drSchedule.time_table.wed.slot_3.to;}
          //console.log(text);
          break;
        }
        case 'fri': {
          // statements;
          this.drSchedule.time_table.fri.de_select =false;
          if(this.drSchedule.time_table.fri.slot_1.from == '') {this.drSchedule.time_table.fri.slot_1.from=this.drSchedule.time_table.thu.slot_1.from;}
          if(this.drSchedule.time_table.fri.slot_1.to == '') {this.drSchedule.time_table.fri.slot_1.to=this.drSchedule.time_table.thu.slot_1.to;}
          if(this.drSchedule.time_table.fri.slot_2.from == '') {this.drSchedule.time_table.fri.slot_2.from=this.drSchedule.time_table.thu.slot_2.from;}
          if(this.drSchedule.time_table.fri.slot_2.to == '') {this.drSchedule.time_table.fri.slot_2.to=this.drSchedule.time_table.thu.slot_2.to;}
          if(this.drSchedule.time_table.fri.slot_3.from == '') {this.drSchedule.time_table.fri.slot_3.from=this.drSchedule.time_table.thu.slot_3.from;}
          if(this.drSchedule.time_table.fri.slot_3.to == '') {this.drSchedule.time_table.fri.slot_3.to=this.drSchedule.time_table.thu.slot_3.to;}
          //console.log(text);
          break;
        }
        case 'sat': {
          // statements;
          this.drSchedule.time_table.sat.de_select  =false;
          if(this.drSchedule.time_table.sat.slot_1.from == '') {this.drSchedule.time_table.sat.slot_1.from=this.drSchedule.time_table.fri.slot_1.from;}
          if(this.drSchedule.time_table.sat.slot_1.to == '') {this.drSchedule.time_table.sat.slot_1.to=this.drSchedule.time_table.fri.slot_1.to;}
          if(this.drSchedule.time_table.sat.slot_2.from == '') {this.drSchedule.time_table.sat.slot_2.from=this.drSchedule.time_table.fri.slot_2.from;}
          if(this.drSchedule.time_table.sat.slot_2.to == '') {this.drSchedule.time_table.sat.slot_2.to=this.drSchedule.time_table.fri.slot_2.to;}
          if(this.drSchedule.time_table.sat.slot_3.from == '') {this.drSchedule.time_table.sat.slot_3.from=this.drSchedule.time_table.fri.slot_3.from;}
          if(this.drSchedule.time_table.sat.slot_3.to == '') {this.drSchedule.time_table.sat.slot_3.to = this.drSchedule.time_table.fri.slot_3.to; }
         // console.log(text);
          break;
        }
        default: {
          // statements;
         // console.log(text);
          break;
        }
      }

    } else {
      switch (text) {
        case 'sun': {
          // statements;
          this.drSchedule.time_table.sun.de_select = true;
          break;
        }
        case 'mon': {
          // statements;
          this.drSchedule.time_table.mon.de_select = true;
          break;
        }
        case 'tue': {
          // statements;
          this.drSchedule.time_table.tue.de_select = true;
          break;
        }
        case 'wed': {
          // statements;
          this.drSchedule.time_table.wed.de_select = true;
          break;
        }
        case 'thu': {
          // statements;
          this.drSchedule.time_table.thu.de_select = true;
          break;
        }
        case 'fri': {
          // statements;
          this.drSchedule.time_table.fri.de_select = true;
          break;
        }
        case 'sat': {
          // statements;
          this.drSchedule.time_table.sat.de_select = true;
          break;
        }
        default: {
          // statements;
          console.log(text);
          break;
        }
      }
    }
 }
 public getDoctorsSpecialized() {
   const doctor_id = this.drSchedule.dr_id;
   const postData = {doctor_id : doctor_id};
  this.rest.getDoctorsSpecialized(postData).subscribe((data: {}) => {
    if (data['status'] === 'Success') {
        this.specialized_in = data['data'];
    }

  });
 }
 public getDropdowns() {
  this.loaderService.display(true);

  this.rest.getDropdowns().subscribe((data: {}) => {
    if (data['status'] === 'Success') {
      this.loaderService.display(false);

      // console.log(data);
      if (data['specialized_in']['status'] === 'Success') {
        this.specialized_in = data['specialized_in']['data'];
      }

      if (data['doctors']['status'] === 'Success') {
        this.doctors_list = data['doctors']['data'];
      }


    }
    this.loaderService.display(false);
  });
 }
 public formatDateTime () {
   if (this.now ) {
     this.client_date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
   }
 }

}
