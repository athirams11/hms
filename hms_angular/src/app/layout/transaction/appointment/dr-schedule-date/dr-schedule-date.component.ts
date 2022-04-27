import { Component, OnInit, Input } from '@angular/core';
import {DatePipe} from '@angular/common';
import * as moment from 'moment';
import { AppointmentService, LoaderService } from 'src/app/shared';
@Component({
  selector: 'app-dr-schedule-date',
  templateUrl: './dr-schedule-date.component.html',
  styleUrls: ['./dr-schedule-date.component.scss']
})
export class DrScheduleDateComponent implements OnInit {
  @Input() schedule_list: any;
  @Input() dr_schedule_list_date: any;
  @Input() date: any;
  alreadyBookedSlot: any;
  slot_array: any;
  constructor(public datepipe: DatePipe,
    public rest:AppointmentService,
    private loaderService : LoaderService) { }

  ngOnInit() {
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
  public getNoOfPatients (slots,avgTime)
  {
    var retVal = 0;
    var seconds = 0
    var avg = 0
    var duration : any;
    var time : any;
    if(slots.END_AT != "" && slots.START_AT != "")
    {
      duration = moment.utc(moment(slots.END_AT, "HH:mm:ss").diff(moment(slots.START_AT, "HH:mm:ss"))).format("HH:mm:ss")
      time = duration.split(':'); 
      seconds = (+time[0]) * 60 * 60 + (+time[1]) * 60 + (+time[2]); 
      avg = avgTime * 60 ; 
      retVal = seconds/avg;
    }
    return  retVal;
  }

  // public getNoOfPatients (from,to,avgTime)
  // {
  //   var retVal = "";
  //   if(from != "" && to != "")
  //   {
  //     var fromTime =  moment(from, "HH:mm.ss");
  //     var toTime =  moment(to, "HH:mm.ss");
  //     let duration = moment.duration(toTime.diff(fromTime))
  //     let diffInMs: number = duration.asMinutes() //diffInMs / 1000 / 60 / 60;
  //     let totalPatients: number = diffInMs / +avgTime;
  //     retVal = totalPatients.toString();
  //   }
  //   return  retVal;
  // }

  public formatTimes (time)
  {
    var retVal = "";
    if(time != "")
    {
      retVal =  moment(time, "HH:mm a").format("hh:mm a");
    }
    return  retVal;
  }
}
