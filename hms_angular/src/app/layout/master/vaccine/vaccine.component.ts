import { Component, OnInit, Output, EventEmitter, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { LoaderService } from '../../../shared';
import { Observable, of } from 'rxjs';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
@Component({
  selector: 'app-vaccine',
  templateUrl: './vaccine.component.html',
  styleUrls: ['./vaccine.component.scss', '../master.component.scss']
})
export class VaccineComponent implements OnInit {
  @Output() pageChange: EventEmitter<number>;
  @Input() id: string;
  @Input() maxSize: number;
  public now = new Date(); public date: any;
  public user_rights: any = {};
  public user: any = {};
  public loading = false;
  private notifier: NotifierService;
  vaccine_age: any;
  vaccine_list: any = [];
  patient_type: any;
  immunization_id: any;
  public index: number;
  public start: any;
  public limit: any;
  public search: any;
  public i = 0;
  p: number = 50;
  public collection: any = '';
  page = 1;
  public collectionSize = '';
  public pageSize = 10;
  status: any;
  constructor(private loaderService: LoaderService, notifierService: NotifierService, public rest2: ConsultingService) {
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.list_vaccine_age();
    this.getVaccine();
    this.list_patient_type();
    this.formatDateTime();
  }
  public vaccineData: any = {
    id: 0,
    patient_type_id: 0,
    vaccine_age_id: 0,
    vaccine_name: "",
    vaccine_age: '',
    vaccine_type: '',
    vaccine_price_of_one_item: '',
    vaccine_optional: 0,
    vaccine_order: "",
    user_id: 0,
    search_vaccine: ''
  };
  public list_vaccine_age() {
    this.loaderService.display(true);
    var postData = {
      master_id: 8
    }
    this.rest2.get_master_data(postData).subscribe((result) => {
      if (result["status"] === "Success") {
        this.loaderService.display(false);
        this.vaccine_age = result["master_list"];
        this.getVaccinelist();
      }
      else {
        this.vaccine_age = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  public list_patient_type() {
    this.loaderService.display(true);
    let postData = {
      master_id: 7
    };
    this.rest2.get_master_data(postData).subscribe((result) => {
      if (result["status"] == "Success") {
        this.loaderService.display(false);
        this.patient_type = result["master_list"];
        // console.log("master_list  ="+result);
      } else {
        this.loaderService.display(false);
        this.patient_type = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  public saveVaccine() {
    if (this.vaccineData.vaccine_name == "") {
      this.notifier.notify('error', 'Please Enter Vaccine Name!');
    } else if (this.vaccineData.vaccine_age === "") {
      this.notifier.notify('error', 'Please Enter Vaccine Age!');
    } else if (this.vaccineData.vaccine_type == "") {
      this.notifier.notify('error', 'Please Enter Vaccine Type!');
    } else if (this.vaccineData.vaccine_price_of_one_item == "") {
      this.notifier.notify('error', 'Please Enter Vaccine Price!');
    } else if (this.vaccineData.vaccine_order === '') {
      this.notifier.notify('error', 'Please Enter Vaccine Order!');
    } else {
      const postData = {
        immunization_id: this.immunization_id,
        user_id: this.user,
        patient_type_id: this.vaccineData.vaccine_type,
        vaccine_name: this.vaccineData.vaccine_name,
        vaccine_age_id: this.vaccineData.vaccine_age,
        vaccine_price_of_one_item: this.vaccineData.vaccine_price_of_one_item,
        vaccine_optional: this.vaccineData.vaccine_optional,
        vaccine_order: this.vaccineData.vaccine_order,
        client_date: this.date
      };
      this.loaderService.display(true);
      this.rest2.saveImmunization(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.notifier.notify('success', 'Vaccine details saved successfully..!');
          this.getVaccine();
          this.clearForm();
        } else {
          this.loaderService.display(false);
          this.notifier.notify('error', result.msg);
        }
      });
    }
  }
  public validateNumber(event) {
    const keyCode = event.keyCode;
    // console.log(keyCode)  
    const excludedKeys = [8, 37, 39, 46];
    if (!((keyCode > 48 && keyCode < 57) ||
      (keyCode >= 96 && keyCode <= 105) || (keyCode == 37) ||
      (keyCode == 110) || (keyCode == 190) || (excludedKeys.includes(keyCode)))) {
      event.preventDefault();
    }
  }
  public getVaccine() {
    this.loaderService.display(true);
    const post2Data = {
      patient_immunization_id: 3
    };
    this.rest2.listImmunization(post2Data).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.loaderService.display(false);
        this.vaccine_list = result['data'];
      }
      this.loaderService.display(false);
    });
  }
  public checkVaccineOptional(checked) {
    if (this.vaccineData.vaccine_optional == 0) {
      this.vaccineData.vaccine_optional = 1;
    } else {
      this.vaccineData.vaccine_optional = 0;
    }
  }
  public editVaccine(vaccine_data) {
    this.immunization_id = vaccine_data.IMMUNIZATION_ID;
    this.vaccineData.vaccine_type = vaccine_data.PATIENT_TYPE_ID;
    this.vaccineData.vaccine_name = vaccine_data.VACCINE_NAME;
    this.vaccineData.vaccine_age = vaccine_data.VACCINE_AGE_ID;
    this.vaccineData.vaccine_price_of_one_item = vaccine_data.PRICE_OF_ONE_ITEM;
    this.vaccineData.vaccine_optional = vaccine_data.VACCINE_OPTIONAL;
    this.vaccineData.vaccine_order = vaccine_data.LIST_ORDER;
    window.scrollTo(0, 0);
  }
  public clearForm() {
    this.vaccineData = {
      id: 0,
      patient_type_id: 0,
      vaccine_age_id: 0,
      vaccine_name: '',
      vaccine_age: '',
      vaccine_type: '',
      vaccine_price_of_one_item: '',
      vaccine_optional: 0,
      vaccine_order: '',
      user_id: 0,
      search_vaccine: ''
    };
  }
  getVaccinelist(page = 0) {
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const postData = {
      start: this.start,
      limit: this.limit,
      immunization_id: '0'
    };
    this.loaderService.display(true);
    window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest2.listImmunization(postData).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.vaccine_list = result['data'];
        this.collection = result['total_count'];
        const i = this.vaccine_list.length;
        this.index = i + 5;
        this.loaderService.display(false);
      } else {
        this.loaderService.display(false);
        this.collection = 0;
      }
    });
  }
  getSearchlist(page = 0) {
    const limit = 100;
    this.start = 0;
    this.limit = limit;
    const postData = {
      start: this.start,
      limit: this.limit,
      search_text: this.vaccineData.search_vaccine,
      immunization_id: '0'
    };
    this.loaderService.display(true);
    window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest2.listImmunization(postData).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.vaccine_list = result['data'];
        this.collection = result['total_count'];
        const i = this.vaccine_list.length;
        this.index = i + 5;
        this.loaderService.display(false);
        this.status = result['status'];
      } else {
        this.loaderService.display(false);
        this.collection = 0;
        this.status = result['status'];
        // console.log('status' + this.status);
      }
    });
  }

  public formatDateTime() {
    if (this.now) {
      this.date = moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }

  public clear_search() {
    if (this.vaccineData.search_vaccine !== '') {
      this.clearForm();
      this.getVaccinelist();
    }
  }
  model: any;
  searching = false;
  searchFailed = false;

  vaccine_search = (text$: Observable<string>) =>
    text$.pipe(
      debounceTime(500),
      distinctUntilChanged(),
      tap(() => this.searching = true),
      switchMap(term =>
        this.rest2.vaccine_search(term).pipe(
          tap(() => this.searchFailed = false),
          catchError(() => {
            this.searchFailed = true;
            return of(['']);
          })
        )
      ),
      tap(() => this.searching = false)
    )
  formatter = (x: { VACCINE_NAME: String, IMMUNIZATION_ID: Number }) => x.VACCINE_NAME;
}
