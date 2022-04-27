import { Component, OnInit,Input } from '@angular/core';
import { routerTransition } from '../../../router.animations';
import { NotifierService } from 'angular-notifier';
import { DoctorsService, ConsultingService } from '../../../shared/services';
import { LayoutComponent} from './../.././layout.component';
import { LoaderService } from '../../../shared';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
@Component({
  selector: 'app-medicine',
  templateUrl: './medicine.component.html',
  styleUrls: ['./medicine.component.scss','../master.component.scss'],
  animations: [routerTransition()],
})
export class MedicineComponent implements OnInit {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  public now = new Date();
  public date:any;
  public  user_rights: any = {};
  public medi_data : any = [];
  public  myfile: any;
  public splitted : any = [];
  public medicine_id:"";
  public index:number;
  fileName :any;
  start: number;
  limit: number;
  p = 50;
  public collection :any= '';
  page = 1;
  public user_data : any ={};
  public medicine_data : any =
  {
    ddc_code :'',
    trade_name :'',
    scientific_code :'',
    scientific_name : '',
    ingredient_strength:'',
    dosage_from_package:'',
    route_of_admin:'',
    user_id:'',
    package_price :'',
    granular_unit : '',
    manufacturer : '',
    registered_owner : '',
    source : '',
    search_medicine:''
  }
  loading :boolean;
  notifier: NotifierService;
  loader:LayoutComponent;
  status: any;
 constructor(private loaderService:LoaderService ,public rest:DoctorsService,public rest2:ConsultingService,notifierService: NotifierService,) {
  this.notifier = notifierService;
}
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.getMedicineList();
    this.medicine_data.user_id = this.user_data.user_id;
    this.formatDateTime ();
  }
  public saveMedicine() {
     if(this.medicine_data.ddc_code== "")
    {
      this.notifier.notify( 'error', 'Please Enter DDC code !' );
    } else if(!(this.medicine_data.trade_name))
    {
      this.notifier.notify( 'error', 'Please Enter the trade name!' );
    } else if(this.medicine_data.scientific_code == "")
    {
      this.notifier.notify( 'error', 'Please Enter scientific code!' );
    } else if(!(this.medicine_data.scientific_name))
    {
      this.notifier.notify( 'error', 'Please Enter the scientific name!' );
    } else if(!(this.medicine_data.ingredient_strength))
    {
      this.notifier.notify( 'error', 'Please Enter ingredient strength!' );
    } else if(!(this.medicine_data.package_price))
    {
      this.notifier.notify( 'error', 'Please Enter package price!' );
    } else if(!(this.medicine_data.granular_unit))
    {
      this.notifier.notify( 'error', 'Please Enter granular unit!' );
    } else {
      let postData = {
        user_id :this.user_data.user_id,
        medicine_id:this.medicine_id,
        ddc_code:this.medicine_data.ddc_code,
        trade_name:this.medicine_data.trade_name,
        scientific_code:this.medicine_data.scientific_code,
        scientific_name:this.medicine_data.scientific_name,
        ingredient_strength :this.medicine_data.ingredient_strength,
        dosage_from_package :this.medicine_data.dosage_from_package,
        route_of_admin :this.medicine_data.route_of_admin,
        package_price :this.medicine_data.package_price,
        granular_unit :this.medicine_data.granular_unit,
        manufacturer :this.medicine_data.manufacturer,
        registered_owner :this.medicine_data.registered_owner,
        source:this.medicine_data.source,
        client_date:this.date
        }
       // console.log("dsff"+postData)
      this.loaderService.display(true);
      this.rest.saveMedicine(postData).subscribe((result) => {
        window.scrollTo(0, 0)
        if(result["status"] === "Success")
        {
          this.loaderService.display(false);
          this.notifier.notify( 'success','Medicine details saved successfully..!' );
          this.getMedicinelist();
          this.clearMedicine();
          this.index =result.data_id;
        } else {
          this.notifier.notify( 'error',' Failed' );
          this.loaderService.display(false);
        }
      })
    }
  }
  public getMedicinelist() {
    let post2Data = {
      medicine_id: ""
    }
    this.loaderService.display(true);
    this.rest.getMedicinelist(post2Data).subscribe((result: {}) => {
      this.loaderService.display(false);
     if(result['status'] == 'Success') {
        this.medi_data = result['data'];
       
     }
   });
  }
  public clearMedicine() {
    this.medicine_data = {
      ddc_code :'',
      trade_name :'',
      scientific_code :'',
      scientific_name : '',
      ingredient_strength:'',
      dosage_from_package:'',
      route_of_admin:'',
      user_id:'',
      package_price :'',
      granular_unit : '',
      manufacturer : '',
      registered_owner : '',
      source : '',
      search_medicine:''
   }
  }
  public editMedicine(meddata) {
    this.medicine_data.ddc_code             = meddata.DDC_CODE;
    this.medicine_data.trade_name           = meddata.TRADE_NAME;
    this.medicine_data.scientific_code      = meddata.SCIENTIFIC_CODE;
    this.medicine_data.scientific_name      = meddata.SCIENTIFIC_NAME;
    this.medicine_data.ingredient_strength  = meddata.INGREDIENT_STRENGTH;
    this.medicine_data.dosage_from_package  = meddata.DOSAGE_FORM_PACKAGE;
    this.medicine_data.route_of_admin       = meddata.ROUTE_OF_ADMIN;
    this.medicine_data.package_price        = meddata.PACKAGE_PRICE;
    this.medicine_data.granular_unit        = meddata.GRANULAR_UNIT;
    this.medicine_data.manufacturer         = meddata.MANUFACTURER;
    this.medicine_data.registered_owner     = meddata.REGISTERED_OWNER;
    this.medicine_data.source               = meddata.SOURCE;
    this.medicine_id                        = meddata.MEDICINE_ID;
    window.scrollTo(0, 0);
  }
  exportAsXLSX($event: any):void {
    let file = $event.target.files[0];
    this.fileName = file.name;
    if (this.validateFile(this.fileName))
    {
      this.readThis($event.target);
      this.notifier.notify( 'success', 'Click save to upload the document' );
    } else {
       this.notifier.notify( 'error', 'Documet is not in the expected format' );
    }
  }
  public saveExel() {
    this.index=this.index+1;
    this.splitted = this.myfile.split(",");
    let postData = {
      assessment_id:this.assessment_id,
      patient_id:this.patient_id,
      base64_file_str : this.splitted[1],
      file_name : this.fileName,
      refer_id :1,
      module_id :41,
      date:this.date
    }
    this.rest.saveDocuments(postData).subscribe((result) => {
      window.scrollTo(0, 0)
      if(result["status"] == "Success")
      {
        this.notifier.notify( 'success',' Success', result.response );
        this.clearMedicine();
      } else {
        this.notifier.notify( 'error',' Failed' );
      }
    })
  }
  validateFile(name: String) {
    const ext = name.substring(name.lastIndexOf('.') + 1);
   // console.log('extension '+ext);

    if (ext === 'xls') {
        return true;
    } else if (ext === 'xlsx') {
      return true;
    } else if (ext === 'xlsm') {
      return true;
    } else if (ext === 'xlsb') {
      return true;
    } else if (ext === 'xltx') {
      return true;
    } else if (ext === 'xltm') {
      return true;
    } else if (ext === 'xlt') {
      return true;
    } else if (ext === 'xml') {
      return true;
    } else if (ext === 'xlam') {
      return true;
    } else if (ext === 'xla') {
      return true;
    } else if (ext === 'xlw') {
      return true;
    } else if (ext === 'xlr') {
      return true;
    } else {
        return false;
    }
  }
  readThis(inputValue: any): void {
    const file: File = inputValue.files[0];
    const myReader: FileReader = new FileReader();
    myReader.onloadend = (e) => {
      this.myfile = myReader.result;
    };
    myReader.readAsDataURL(file);
  }
  public getMedicineList(page = 0) {
    const limit = 50;
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const postData = {
      start : this.start,
      limit : this.limit,
      medicine_id : '0'
    };
    this.loaderService.display(true);
    // window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest2.listMedicine(postData).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
        this.medi_data = result['data'];
        this.collection = result['total_count'];
        this.loaderService.display(false);
      } else {
          this.loaderService.display(false);
          this.collection = 0;
      }
    });
  }
  getsearchlist(_page = 0) {
    const limit = 50;
    this.start = 0;
    this.limit = limit;
    const postData = {
      start : this.start,
      limit : this.limit,
      search_text : this.medicine_data.search_medicine,
      medicine_id : '0'
    };
    this.loaderService.display(true);
    // window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest2.listMedicine(postData).subscribe((result: {}) => {
      if (result['status'] === 'Success') {
          this.medi_data = result['data'];
          this.collection = result['total_count'];
          const i = this.medi_data.length;
          this.index = i + 5;
          this.status = result['status'];
          this.loaderService.display(false);
      } else {
          this.loaderService.display(false);
          this.collection = 0;
          this.status = result['status'];
      }
    });
  }

  public formatDateTime () {
    if (this.now ) {
      this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }

  public clear_search() {
    if (this.medicine_data.search_medicine !== '') {
    this.clearMedicine();
    this.getMedicineList();
    }
  }
  model: any;
  searching = false;
  searchFailed = false;

  medicine_search = (text$: Observable<string>) =>
  text$.pipe(
  debounceTime(500),
  distinctUntilChanged(),
  tap(() => this.searching = true),
  switchMap(term =>
    this.rest2.medicine_search(term).pipe(
    tap(() => this.searchFailed = false),
    catchError(() => {
      this.searchFailed = true;
      return of(['']);
      })
    )
  ),
  tap(() => this.searching = false)
  )
  formatter = (x: {TRADE_NAME: String, MEDICINE_ID: Number, SCIENTIFIC_CODE: String, SCIENTIFIC_NAME: String, DDC_CODE: String }) => x.TRADE_NAME;
}
