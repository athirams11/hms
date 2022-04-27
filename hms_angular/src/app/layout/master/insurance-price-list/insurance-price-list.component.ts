import { Component, OnInit, ɵConsole } from '@angular/core';
import { LoaderService, OpRegistrationService, ConsultingService } from 'src/app/shared';
import { NotifierService } from 'angular-notifier';
import { debounceTime, distinctUntilChanged, tap, switchMap, catchError } from 'rxjs/operators';
import { Observable, of } from 'rxjs';
import * as moment from 'moment';
@Component({
  selector: 'app-insurance-price-list',
  templateUrl: './insurance-price-list.component.html',
  styleUrls: ['./insurance-price-list.component.scss']
})
export class InsurancePriceListComponent implements OnInit {
  private notifier: NotifierService;
  public tpa_options:any;
  public tpa_receiver: any ;
  public networks: any = [];
  public tpa_data:any=[];
  public get_rate : any = [];
  public insurance_price_list : any=[];
  public price_data : any ={
    cpt_rate_id : '',
    tpa_receiver :'',
    network : '',
    cpt : '',
    rate : '',
    cpt_code : '',
    client_date: new Date(),
    search : '',
    active_status: 1
  }
  p = 50;
  public collection: number = 0;
  page = 1;
  model: any;
    searching = false;
    searchFailed = false;
  public index: number;
  public start: number = 0;
  public limit: any;
  public status: any;
  public current_procedural_code_id  : number =0;
  public laboratory_allias_data : string = '';
  public cptlist_data: any = [];
  public user_rights : any ;
  public user: any;
  public now = new Date(); 
  public date: any;
  cpt_list: any;
  cpt_options: any;
  cpt_data: any;
  constructor(private loaderService: LoaderService, public rest: OpRegistrationService ,public notifierService:NotifierService, public list:ConsultingService) {
    this.notifier = notifierService;
  }
  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getDropdowns();
    this.listInsurancePrice();
    this.getCptlist();
  }
  public saveInsurancePrice() {
    if(this.price_data.tpa_receiver== "")
    {
      this.notifier.notify( 'error', 'Please select TPA receiver Name!' );
    } else if(this.price_data.network === "") {
      this.notifier.notify( 'error', 'Please select network !' );
    } else if(this.price_data.cpt == "") {
      this.notifier.notify( 'error', 'Please select CPT name!' );
    } else if(this.price_data.rate == "") {
      this.notifier.notify( 'error', 'Please Enter Insurance price!' );
    
    } else {
    const postData = {
      cpt_rate_id :this.price_data.cpt_rate_id,
      user_id : this.user.user_id,
      tpa_id : this.price_data.tpa_receiver,
      network_id : this.price_data.network,
      cpt_id : this.price_data.cpt,
      cpt_code : this.price_data.cpt_code,
      cpt_rate : this.price_data.rate,
      active_status : this.price_data.active_status,
      client_date : this.formatDateTime(this.now)
     // client_date: this.date
      };
    //  console.log("postdata"+JSON.stringify(postData));
      this.loaderService.display(true);
     this.list.saveInsurancePrice(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.price_data.cpt_rate_id = result.data_id;
          this.notifier.notify( 'success' , 'Insurance price saved successfully..!' );
          this.listInsurancePrice();
          this.clearForm();
          this.getDropdowns();
          this.getCptlist();
        } else {
          this.loaderService.display(false);
          this.notifier.notify( 'error', result['msg'] );
        }
      });
    }
  }
  public selectStatus(val) {
    this.price_data.active_status = val;
    //console.log("this.payment_mode  "+this.payment_mode);
  }
  public validateNumber(event) {
    const keyCode = event.keyCode;  
    // console.log(keyCode)  
    const excludedKeys = [8, 37, 39, 46];   
     if (!((keyCode > 48 && keyCode < 57) ||
      (keyCode >= 96 && keyCode <= 105 ) ||  ( keyCode == 37  ) || 
      ( keyCode == 110  ) || ( keyCode == 190  ) ||  (excludedKeys.includes(keyCode)))) {
      event.preventDefault();
    }
  }
  public getCptlist() {
    const myDate = new Date();
    this.loaderService.display(true);
    this.rest.listCurrentProceduralCodeforTreatment().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        {
          this.cpt_list = data['data'];
          this.cpt_options=this.cpt_list;
          for (let index = 0; index < this.cpt_options.length; index++) {
            if (this.cpt_options[index].CURRENT_PROCEDURAL_CODE_ID == this.price_data.cpt) {
              this.cpt_data = this.cpt_options[index];
            }
          }
        }
    
      }
    });
  }
  public setDropdown(){
   
    for (let index = 0; index < this.cpt_list.length; index++) {
      if (this.cpt_list[index].CURRENT_PROCEDURAL_CODE_ID == this.price_data.cpt) {
        this.cpt_data = this.cpt_list[index];
        break;
      }
    } 
  }
      
  public getDropdowns() {
    this.loaderService.display(true);
    this.rest.getOpDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        if (data['tpa_receiver']['status'] === 'Success') {
          this.tpa_receiver = data['tpa_receiver']['data'];
          this.tpa_options = this.tpa_receiver;
          for (let index = 0; index < this.tpa_options.length; index++) {
            if (this.tpa_options[index].TPA_ID === this.price_data.tpa_receiver) {
              this.tpa_data = this.tpa_options[index];
              //console.log(this.invoice_data)
            }
          }
        }
      }
    });
  }
  public setTPA(){
   
    for (let index = 0; index < this.tpa_receiver.length; index++) {
      if (this.tpa_receiver[index].TPA_ID == this.price_data.tpa_receiver) {
        this.tpa_data = this.tpa_receiver[index];
        break;
      }
    }
}
    public getNetworks(data) {
      this.networks=['']
      const opdata = {
       tpa_id :data  
        };
      this.loaderService.display(true);
      this.rest.getOpNetworks(opdata).subscribe((result) => {
        this.loaderService.display(false);
        if (result['status'] === 'Success') {
        this.networks = result['data'];
        }
  
      });
    }
    public getInsNetwork(data_id) {
      this.networks=['']
      const opdata = {
       tpa_id : data_id
        };
      this.loaderService.display(true);
      this.rest.getOpNetworks(opdata).subscribe((result) => {
        this.loaderService.display(false);
        if (result['status'] === 'Success') {
        this.networks = result['data'];
        }
  
      });
    }
    public formatDateTime (data) {
      if (data) {
        data = moment(data, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y HH:MM:ss');
        return data;
      }
    }
    getTpa(tpa_data){
      //console.log("this.country_data  "+JSON.stringify(this.tpa_data));
      // console.log(this.tpa_data.TPA_ID)
      // console.log(this.tpa_data)
      if(this.tpa_data)
      {

        this.price_data.tpa_receiver = this.tpa_data.TPA_ID;
         this.getNetworks(this.price_data.tpa_receiver)
      }
     }
     getCpt(){
      // console.log("this.country_data  "+JSON.stringify(this.country_data));
       this.price_data.cpt = this.cpt_data.CURRENT_PROCEDURAL_CODE_ID;
       this.price_data.cpt_code = this.cpt_data.PROCEDURE_CODE;
     }
     public listInsurancePrice(page= 0) {

      const limit = 50;
      const starting = page * limit;
      this.start = starting;
      this.limit = limit;
      const postData = {
        start : this.start,
        limit : this.limit,
      };
      this.loaderService.display(true);
     this.list.listInsurancePrice(postData).subscribe((result: {}) => {

       if (result['status'] === 'Success') {
        this.loaderService.display(false);
          this.insurance_price_list = result['data'];
          this.collection = result['total_count'];
          const i = this.insurance_price_list.length;
        this.index = i + 5;
       }
       this.loaderService.display(false);
     });
    }

    public listSearchPrice(page= 0) {
      const limit = 100;
      this.start = 0;
      this.limit = limit;
      const postData = {
        start : this.start,
        limit : this.limit,
        search_text : this.price_data.search,
      };
        this.loaderService.display(true);
        this.list.listInsurancePrice(postData).subscribe((result: {}) => {
        this.status = result['status'];
        if (result['status'] === 'Success') 
        {
          this.loaderService.display(false);
          this.insurance_price_list = result['data'];
          this.collection = result['total_count'];
          const i = this.insurance_price_list.length;
          this.index = i + 5;
          this.status = result['status'];
        } 
        else {
          this.loaderService.display(false);
          this.collection = 0;
          this.status = result['status'];
          //console.log('status' + this.status);
        }   
       });
    }

    public set_item($event) {
      const item = $event.item;
      // this.laboratory_data.laboratory_alliasname[i] = $event.item.PROCEDURE_CODE_ALIAS_NAME
      this.price_data.cpt = item.CURRENT_PROCEDURAL_CODE_ID;
      this.price_data.cpt_code = item.PROCEDURE_CODE;
      this.laboratory_allias_data = item.PROCEDURE_CODE_NAME;
     // this.getCpt(item, i);
     }
  config = {
    displayKey:"TPA", //if objects array passed which key to be displayed defaults to description
    search:true, //true/false for the search functionlity defaults to false,
    height: 'auto', //height of the list so that if there are more no of items it can show a scroll defaults to auto. With auto height scroll will never appear
    placeholder:'Select TPA / Receiver', // text to be displayed when no item is selected defaults to Select,
   // customComparator: ()=>{}, // a custom function using which user wants to sort the items. default is undefined and Array.sort() will be used in that case,
    limitTo: 10, // a number thats limits the no of options displayed in the UI similar to angular's limitTo pipe
    moreText: '.........', // text to be displayed whenmore than one items are selected like Option 1 + 5 more
    noResultsFound: 'No results found!', // text to be displayed when no items are found while searching
    searchPlaceholder:'Search' ,// label thats displayed in search input,
    searchOnKey: 'TPA' // key on which search should be performed this will be selective search. if undefined this will be extensive search on all keys
    }
    configs = {
      displayKey:"CPT", //if objects array passed which key to be displayed defaults to description
      search:true, //true/false for the search functionlity defaults to false,
      height: 'auto', //height of the list so that if there are more no of items it can show a scroll defaults to auto. With auto height scroll will never appear
      placeholder:'Select CPT code / Name', // text to be displayed when no item is selected defaults to Select,
     // customComparator: ()=>{}, // a custom function using which user wants to sort the items. default is undefined and Array.sort() will be used in that case,
      limitTo: 10, // a number thats limits the no of options displayed in the UI similar to angular's limitTo pipe
      moreText: '.........', // text to be displayed whenmore than one items are selected like Option 1 + 5 more
      noResultsFound: 'No results found!', // text to be displayed when no items are found while searching
      searchPlaceholder:'Search' ,// label thats displayed in search input,
      searchOnKey: 'CPT' // key on which search should be performed this will be selective search. if undefined this will be extensive search on all keys
      }
    public editInsurancerate(data) {
      const post2Data = {
        cpt_rate_id: data.CPT_RATE_ID
      };

      this.loaderService.display(true);
      this.list.getInsurancePrice(post2Data).subscribe((result: {}) => {
        if (result['status'] === 'Success') {
         this.loaderService.display(false);
         this.get_rate = result['data'];
        
        // this.appdata.tpa_id = this.get_network.TPA_ID;
         this.tpa_data = this.get_rate.TPA_ECLAIM_LINK_ID +' - '+ this.get_rate.TPA_NAME;
         this.cpt_data = this.get_rate.CURRENT_PROCEDURAL_CODE+ ' - '+this.get_rate.PROCEDURE_CODE_NAME;
         this.price_data.cpt_rate_id = this.get_rate.CPT_RATE_ID;
         this.price_data.tpa_receiver = this.get_rate.TPA_ID;
         this.price_data.network = this.get_rate.NETWORK_ID;
         this.price_data.cpt = this.get_rate.CURRENT_PROCEDURAL_CODE_ID;
         this.price_data.rate = this.get_rate.CPT_RATE;
         this.price_data.cpt_code = this.get_rate.CURRENT_PROCEDURAL_CODE;
         this.price_data.active_status = this.get_rate.STATUS
        // this.laboratory_allias_data = this.get_rate.PROCEDURE_CODE_NAME;
         this.setDropdown();
         this.setTPA();
        this.getInsNetwork(this.get_rate.TPA_ID);
        // console.log("price"+JSON.stringify(this.laboratory_allias_data));
        }
        this.loaderService.display(false);
      });

      window.scrollTo(0, 0);
    }


  public clearForm() {
    this.price_data = {
      cpt_rate_id : '',
      tpa_receiver :'',
      network : '',
      cpt : '',
      rate : '',
      cpt_code : '',
      active_status : 1,
      client_date: '',
     };
     this.tpa_data = [''];
     this.cpt_data = [''];
  }

public clear_search() { if (this.price_data.search !== '') {
  this.price_data.search = '' ;
  this.listInsurancePrice(1);
  this.editInsurancerate(1);
  this.status = '';
}
}
    cptsearch = (text$: Observable<string>) =>
 text$.pipe(
   debounceTime(500),
    distinctUntilChanged(),

    tap(() => this.searching = true),
    switchMap(term =>
     this.list.cptsearch(term).pipe(

       tap(() => this.searchFailed = false),

       catchError(() => {
         this.searchFailed = true;
         return of(['']);
       })

       )

   ),
   tap(() => this.searching = false)

 )
 formatter = (x: {PROCEDURE_CODE_NAME: String, CURRENT_PROCEDURAL_CODE_ID: Number, PROCEDURE_CODE_CATEGORY: Number, PROCEDURE_CODE: String }) => x.PROCEDURE_CODE_NAME;

}

