import { Component, OnInit, Input, EventEmitter, Output } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { AppointmentService,OpVisitService, LoaderService } from './../../../services'
import Moment from 'moment-timezone';
import { NotifierService } from 'angular-notifier';
@Component({
  selector: 'app-appointment-list',
  templateUrl: './appointment-list.component.html',
  styleUrls: ['./appointment-list.component.scss'],
  providers:[AppointmentService]
})
export class AppointmentListComponent implements OnInit {


  @Input() appoinment_list: any;
  @Output() onEdit = new EventEmitter();
  @Input() filter: any;
  @Input() page: any = "Appointment";
  public user_rights : any = {};
  public user_data : any = {};
  @Input() appointmentStatus :any = []
  timeZone: any;
  term : any;
  private notifier: NotifierService;
  text: string;
  constructor(private loaderService: LoaderService, 
    public datepipe: DatePipe,private router: Router, 
    public rest : AppointmentService, public rest2:OpVisitService,
    notifierService: NotifierService) {
      this.notifier = notifierService;
    }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.timeZone = Moment.tz.guess();
   
  }
  public showTpa(visit)
  {
    if(visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null)
    {
      if(visit.insurance != false){
        var coin = []
        var rest = ''
        if( visit.insurance.OP_INS_IS_ALL == 1)
        {
          rest = visit.insurance.OP_INS_ALL_VALUE+' '+visit.insurance.OP_INS_ALL_TYPE
        }
        else
        {
          if(visit.insurance.co_ins && visit.insurance.co_ins.length > 0){
            var i = 0
            for(let data of visit.insurance.co_ins)
            {
              coin[i] = '\n            '+data.COIN_NAME + '  :  ' + data.COIN_VALUE+ ' ' +data.COIN_VALUE_TYPE+ ' '  
              i=i+1
            }
          }
        }
        var tpa ="TPA :  ";
        var company ="Company :  ";
        var Network ="Network :  ";
        var Deductable ="Deductable :  ";
        var restall ="\nRest of all :  ";
        var coins ="\nCo-Ins  :";
        if(coin.length == 0)
        {
          coins = ''
        }
        if(visit.insurance.OP_INS_IS_ALL == 0)
        {
          restall = '';
          rest = ''
        }
        this.text = 'TPA  :  ' + visit.insurance.TPA_ECLAIM_LINK_ID + ' - ' + visit.insurance.TPA_NAME + '\n' +
        'Company  :  ' + visit.insurance.INSURANCE_PAYERS_ECLAIM_LINK_ID + ' - ' + visit.insurance.INSURANCE_PAYERS_NAME + '\n' +
        'Network  :  ' + visit.insurance.INS_NETWORK_NAME + '\n' +
        'Deductable  :  ' + visit.insurance.OP_INS_DEDUCTIBLE + ' ' + visit.insurance.OP_INS_DEDUCT_TYPE +
        restall +''+ rest + 
        coins +''+ coin;
      }
    }
  }
  public hideTpa(visit)
  {
    if(visit.OP_REGISTRATION_TYPE == 1 && visit.PATIENT_TYPE == null || visit.PATIENT_TYPE == 1 && visit.PATIENT_TYPE != null)
    {
      this.text = ''
    }
  }
  public getStatus()
  {
    if(this.appoinment_list.length > 0)
    {
      this.appointmentStatus = []
      var i=0
      for(let data of this.appoinment_list)
      {
          this.appointmentStatus[i] = data.APPOINTMENT_STATUS
          i=i+1
      }
    }
  }
  public formatTime (time)
  {
    var retVal = "";
    if(time != "")
    {
      retVal =  moment(time, "HH:mm.ss").format("hh:mm a");
    }
    return  retVal;
  }
  public formatDate (date)
  {
    var retVal = "";
    if(date != "")
    {
      var d  =new Date(date)
      retVal =  this.datepipe.transform(d, 'dd-MM-yyyy') //.toDateString();
    }
    return  retVal;
  }
  public cancelAppointment(app_id)
  {
    if(app_id != "" && app_id != null)
    {
      var postData = {
        app_id : app_id
      }
      this.loaderService.display(true);
      this.rest.cancelAppointment(postData).subscribe((result) => {
        this.loaderService.display(false);
        if(result.status == "Success")
        {
    
        // console.log(result.data);
        //this.router.navigate(['transaction/op-new-registration']);
        if(this.page == 'Visit')
        {
          this.router.navigate(['transaction/op-visit-entry']);
        }
        if(this.page == 'Appointment')
        {
          this.router.navigate(['transaction/appointment']);
        }
         // window.location.reload();
        }
        else
        {

        }
      }, (err) => {
        console.log(err);
      });
    }
    // this.getStatus()
  }
  public editAppointment(appointment)
  {
    if(appointment != "" && appointment != null)
    {
      this.onEdit.emit(appointment);
      
     /* this.rest2.addVisit(postData).subscribe((result) => {
        if(result.status == "Success")
        {
    
        // console.log(result.data);
        this.router.navigate(['transaction/op-visit-entry']);
          //window.location.reload();
        }
        else
        {

        }
      }, (err) => {
        console.log(err);
      });*/
    }
  }
  public addVisit(appointment)
  {
    if(appointment != "" && appointment != null)
    {
      var postData = {
        op_number : appointment.PATIENT_NO,
        p_id : appointment.OP_REGISTRATION_ID,
        date :  new Date(),
        time : new Date().getHours() + ':' + new Date().getMinutes() + ':'+  new Date().getSeconds(),
        appointment_id : appointment.APPOINTMENT_ID,
        department : appointment.SPECIALIZED_IN,
        visit_doctor : appointment.DOCTOR_ID,
        visit_doctor_name : appointment.DOCTORS_NAME,
        visit_reason : "",
        timeZone : this.timeZone,
        user_id : this.user_data.user_id
      }
      this.rest2.addVisit(postData).subscribe((result) => {
        if(result.status == "Success")
        {
    
        // console.log(result.data);
        this.router.navigate(['transaction/op-visit-entry']);
          //window.location.reload();
        }
        else
        {

        }
      }, (err) => {
        console.log(err);
      });
    }
  }
  public changeAppointmentStatus(app_id,i)
  {
   
    if(app_id != "" && app_id != null && this.appointmentStatus[i] > 1)
    {
      var postData = {
        app_id : app_id,
        appointment_status : this.appointmentStatus[i]
      }
      this.loaderService.display(true);
      this.rest.changeAppointmentStatus(postData).subscribe((result) => {
        this.loaderService.display(false);
        if(result.status == "Success")
        {
          // this.getStatus()
          // this.router.navigate(['transaction/appointment']);
          this.notifier.notify("success",result.message)
        }
        else
        {
          this.notifier.notify("error",result.message)
        }
        this.appointmentStatus = []
      }, (err) => {
        console.log(err);
      });
    }
    // this.getStatus()
  }
}
