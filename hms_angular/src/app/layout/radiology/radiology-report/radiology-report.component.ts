import { Component, OnInit, Input, ElementRef } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import { DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
import { AppSettings } from './../../../app.settings';
import { NgbModal, ModalDismissReasons, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../../../shared/class/Utils';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import { ViewChild } from '@angular/core';
@Component({
  selector: 'app-radiology-report',
  templateUrl: './radiology-report.component.html',
  styleUrls: ['./radiology-report.component.scss']
})
export class RadiologyReportComponent implements OnInit {
  @ViewChild('file') myInputVariable: ElementRef;
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() listStatus: any = [];
  public image: any;
  private modalRef: NgbModalRef;
  private modalRefs: NgbModalRef;
  public p = 50;
  public now = new Date();
  public date: any;
  public collection: any = '';
  fileName: any = [];
  page = 1;
  public appdata = {
    dateval: new Date(),
    patient_no: '',
    search: '',
  };
  filelist: any;
  public appdatas = {
    patient_number: '',
    patient_no: '',
    patient_name: '',
    phno: '',
    remarks: '',
    document: [],
    radiology_id: 0,
    visit_id: '',
    procedure_code_id: '',
    user_id: ''
  };

  user_rights: any;
  user: any;
  notifier: NotifierService;
  public collection_list: any = [];
  public splitted: any = [];
  start: number;
  limit: number;
  public get_collection: any = [];
  closeResult: string;
  constructor(private loaderService: LoaderService,
    public notifierService: NotifierService,
    private modalService: NgbModal,
    public rest: ConsultingService) {
    this.notifier = notifierService;
  }


  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getCollection();
    //this. appdata.dateval = moment(new Date()).format('YYYY-MM-DD')
    this.appdata.dateval = defaultDateTime();
  }

  public format_date(time) {
    return formatDate(time)
  }

  public formatDateTime (data) {
    return formatDateTime(data);
}


  public getToday() {
    return moment(new Date()).format('YYYY-MM-DD')
  }

  public filePath(filename) {
    var path = "";
    path = AppSettings.API_ENDPOINT + AppSettings.RADIOLOGY_UPLOAD_PATH + filename;

    return path;
  }


  public removeattachment(attach_id, i) {

    if (attach_id != 0) {
      var postData = {
        attach_id: attach_id
      }
      this.loaderService.display(true);
      this.rest.removeradiofile(postData).subscribe((result) => {
        this.loaderService.display(false);
        if (result.status == "Success") {
          this.notifier.notify("success", result.message)
          this.getCollection();
          this.checkfile();
        }
        else {
          this.notifier.notify("error", result.message)
        }
      }, (err) => {
        console.log(err);
      });
    }
  }


  public checkfile() {
    const post2Data = {
      radiology_id: this.appdatas.radiology_id
    };
    this.loaderService.display(true);
    this.rest.getattachradio(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.filelist = result['data'];
      }
      this.loaderService.display(false);
    });
  }

  public addattachment(data) {
    console.log(data);
    this.appdatas.patient_number = data.OP_REGISTRATION_NUMBER;
    this.appdatas.patient_no = data.OP_REGISTRATION_ID;
    this.appdatas.patient_name = data.FIRST_NAME;
    this.appdatas.phno = data.MOBILE_NO;
    this.appdatas.remarks = data.REMARKS;
    this.appdatas.visit_id = data.PATIENT_VISIT_LIST_ID;
    this.appdatas.procedure_code_id = data.CURRENT_PROCEDURAL_CODE_ID;
    this.appdatas.radiology_id = data.RADIOLOGY_ID;
    this.appdatas.user_id = this.user.user_id;
    const post2Data = {
      radiology_id: this.appdatas.radiology_id
    };
    this.loaderService.display(true);
    this.rest.getattachradio(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.filelist = result['data'];
      }
      this.loaderService.display(false);
    });
  }


  public getCollection(page = 0) {
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.start = starting;
    this.limit = limit;
    const post2Data = {
      start: this.start,
      limit: this.limit,
      timeZone: moment.tz.guess(),
      dateval: this.formatDateTime (this.appdata.dateval), 
      patient_no: this.appdata.patient_no,
    };
    this.loaderService.display(true);
    this.rest.searchradiology(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.collection_list = result['data'];
        this.collection = result['total_count'];
      }
      this.loaderService.display(false);
    });
  }

  private open(content) {
    this.modalRef = this.modalService.open(content, { ariaLabelledBy: 'modal-basic-title', size: 'lg', windowClass: "col-md-12" });

    this.modalRef.result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }


  selectFiles($event, upload_file) {
    if ($event.target.files && $event.target.files[0]) {
      var filesAmount = $event.target.files.length;
      for (let i = 0; i < filesAmount; i++) {
        this.fileName.push($event.target.files[i])
        var reader = new FileReader();
        reader.onload = (event: any) => {
          var image = event.target.result
          var splitted = image.split(",");
          //this.appdatas.document[i] = sliptted[1];
          this.appdatas.document[i] = splitted[1];
        }
        reader.readAsDataURL($event.target.files[i]);
      }
    }
    upload_file.value = ""
  }
  public attachcollection() {
    if (this.appdatas.document.length < 0) {
      this.notifier.notify('error', 'Please Select File!');
    }
    else {
      this.loaderService.display(true);
      this.rest.attachradiology(this.appdatas).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify('success', result['msg']);
          this.getCollection();
          this.checkfile();
          this.appdatas.document = [];
          this.fileName = [];
          this.myInputVariable.nativeElement.value = "";
          //this.appdatas.document='';

        } else {
          this.loaderService.display(false);
          this.notifier.notify('error', ' Failed');
        }
      });
    }

  }
  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
  }

  readThis(inputValue: any): void {
    var file: File = inputValue.files[0];
    var myReader: FileReader = new FileReader();

    myReader.onloadend = (e) => {
      this.image = myReader.result;

    }
    myReader.readAsDataURL(file);
  }
  public getSearchlist(page = 0) {
    const limit = 100;
    this.start = 0;
    this.limit = limit;
    const post2Data = {
      start: this.start,
      limit: this.limit,
      timeZone: moment.tz.guess(),
      dateval: this.formatDateTime (this.appdata.dateval), 
      patient_no: this.appdata.patient_no,
    };
    this.loaderService.display(true);
    this.rest.searchradiology(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.collection_list = result['data'];
        this.collection = result['total_count'];
      }
      else {
        this.collection_list = result['data'];
      }
      this.loaderService.display(false);
    });
  }

}
