import { Component, OnInit,Input } from '@angular/core';
import { formatTime, formatDateTime, formatDate } from './../../../../../shared/class/Utils';
@Component({
  selector: 'app-investigative-procedure',
  templateUrl: './investigative-procedure.component.html',
  styleUrls: ['./investigative-procedure.component.scss']
})
export class InvestigativeProcedureComponent implements OnInit {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() vital_values: any = [];

  constructor() { }

  ngOnInit() {
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

}
