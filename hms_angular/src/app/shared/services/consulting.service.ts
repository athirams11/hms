import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type':  'application/json'
  })
};
const cpt_path1 = AppSettings.API_ENDPOINT + 'Querys/getPatientList';
// const WIKI_URL = 'https://en.wikipedia.org/w/api.php';
const cpt_path = AppSettings.API_ENDPOINT + 'CurrentProceduralCode/listCurrentProceduralCodeforTreatment';
const diagnosis_path = AppSettings.API_ENDPOINT + 'Diagnosis/listDiagnosis';
const medicine_path = AppSettings.API_ENDPOINT + 'Medicine/listMedicine';
const generic_path = AppSettings.API_ENDPOINT + 'Medicine/listMedicine';
const vaccine_path = AppSettings.API_ENDPOINT + 'Immunization/listImmunization';
const routeofadmin_path = AppSettings.API_ENDPOINT + '/DoctorPrescription/listRouteOfAdmin';
const PARAMS = new HttpParams({
  fromObject: {
    action: 'opensearch',
    format: 'json',
    origin: '*'
  }
});

@Injectable({
  providedIn: 'root'
})
export class ConsultingService {

  constructor(private http: HttpClient) { }
  /*search(term: string) {
    if (term === '') {
      return of([]);
    }

    return this.http
      .get(WIKI_URL, {params: PARAMS.set('search', term)}).pipe(
        map(response => response[1])
      );
  }*/
  cptsearch(term: string) {
    if (term === '') {
      return of([]);
    }


    return this.http
      .post<any>(cpt_path, JSON.stringify( {search_text:  term, limit: 50, current_procedural_code_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  cptDentalsearch(term: string ,procedure_code_category : number,dental_procedure_id : number) {
    if (term === '') {
      return of([]);
    }


    return this.http
      .post<any>(cpt_path, JSON.stringify( {
        search_text:  term, limit: 50, 
        current_procedural_code_id : '0', 
        start: 1,
        procedure_code_category : procedure_code_category,
        dental_procedure_id : dental_procedure_id
      })).pipe(
        map(response => response['data'])
      );
  }
  master_cptsearch(term: string) {
    if (term === '') {
      return of([]);
    }


    return this.http
      .post<any>(cpt_path, JSON.stringify( {search_text:  term, limit: 50, current_procedural_code_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  diagnosis_search(term: string) {
    if (term === '') {
      return of([]);
    }
    return this.http
      .post<any>(diagnosis_path, JSON.stringify( {search_text:  term, limit: 50, diagnosis_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  listCpt(postdata): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/listCurrentProceduralCode', JSON.stringify(postdata)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getcpt'))
    ));
    
  }
  medicine_search(term: string) {
    if (term === '') {
      return of([]);
    }
    return this.http
      .post<any>(medicine_path, JSON.stringify( {search_text:  term, limit: 50, medicine_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  routeofadmin_search(term: string) {
    if (term === '') {
      return of([]);
    }
    return this.http
      .post<any>(routeofadmin_path, JSON.stringify( {search_text:  term, limit: 50, routeofadmin_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  generic_search(term: string) {
    if (term === '') {
      return of([]);
    }
    return this.http
      .post<any>(generic_path, JSON.stringify( {search_text:  term, limit: 50, medicine_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  vaccine_search(term: string) {
    if (term === '') {
      return of([]);
    }
    return this.http
      .post<any>(vaccine_path, JSON.stringify( {search_text:  term, limit: 50, immunization_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  private extractData(res: Response) {
    const body = res;
    return body || { };
  }
  
  saveInsurancePrice (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsurancePrice/saveInsurancePrice', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveInsurancePrice'))
    ));
  }
  getInsurancePrice (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsurancePrice/getInsurancePrice', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getInsurancePrice'))
    ));
  }
  listInsurancePrice (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsurancePrice/listInsurancePrice', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listInsurancePrice'))
    ));
  }
  save_specialComments (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + '/PatientSpecialComment/savePatientSpecialComment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savecomplaints'))
    ));
  }
  getSpecialcomments (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientSpecialComment/getPatientSpecialComment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
      ));
      }
  getPreviousSpecialcomments (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientSpecialComment/getPreviousPatientSpecialComment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
      ));
    }
  saveComplaints (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'ChiefComplaints/saveComplaints', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savecomplaints'))
    ));
  }
  saveDentalComplaints (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DentalComplaints/saveDentalComplaints', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveDentalComplaints'))
    ));
  }
  saveDentalHistory(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DentalComplaints/saveDentalHistory', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveDentalHistory'))
    ));
  }
  saveTpa (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'TPA_reciever/saveTPA', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savetpa'))
    ));
  }
  saveType (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/saveType', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveType'))
    ));
  }

  getTypelist (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/listType', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getTypelist error'))
      ));
  }

  getType (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/getType', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getType error'))
      ));

  }


  saveTest (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/saveTest', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveTest'))
    ));
  }

  getTestlist (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/listTest', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getTestlist error'))
      ));
  }

  getTest (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/getTest', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getTest error'))
      ));

  }



  saveCollection (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/saveCollection', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveCollection'))
    ));
  }

  attachcollection (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/attachcollection', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('attachcollection'))
    ));
  }

  attachradiology (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/attachradiology', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('attachradiology'))
    ));
  }

  getCollectionlist (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/listCollection', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getCollectionlist error'))
      ));
  }

  changeStatus(params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/changeStatus', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('changeStatus'))
    ));
  }

  removefile(params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/removefile', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('removefile'))
    ));
  }

  removeradiofile(params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/removeradiofile', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('removeradiofile'))
    ));
  }

  searchCollection (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/searchCollection', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('searchCollection error'))
      ));
  }

  searchradiology (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/searchradiology', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('searchradiology error'))
      ));
  }

  getattachradio (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/getattachradio', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getattachradio'))
    ));
  }
  getOpDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'OpRegistration/options', null).pipe(
      map(this.extractData));
  }
  getPatientList(term: string) {
    if (term === '') {
      return of([]);
    }
    return this.http
      .post<any>(cpt_path1, JSON.stringify( {search_text:  term, limit: 50, start: 1})).pipe(
        map(response => response['data'])
      );
  }
  getDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Appointment/options',null).pipe(
      map(this.extractData));
  }

  getlab(opData): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Laboratory/getlab',JSON.stringify(opData)).pipe(
      map(this.extractData));
  }

  getdroptest(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Laboratory/testoptions',null).pipe(
      map(this.extractData));
  }
  getCollection (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/getCollection', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getCollection error'))
      ));

  }

  saveInsurancepayer (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Insurance_payer/saveinsurance_payers', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveInsurancepayer'))
    ));
  }
  saveNetwork (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'INS_network/saveinsnetwork', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveNetwork'))
    ));
  }
  getPreviousComplaints (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'ChiefComplaints/getPreviousComplaints', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
      ));
  }
  getTpalist (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'TPA_reciever/listTPA', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getTpalist error'))
      ));
  }
  getInspayerlist (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Insurance_payer/listinsurance_payers', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getInspayerlist error'))
      ));
  }
  getNetworklist (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'INS_network/listinsnetwork', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getNetworklist error'))
      ));

  }
  getTpa (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'TPA_reciever/getTPA', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getTpa error'))
      ));

  }
  getInscompany (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Insurance_payer/getinsurance_payers', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getInscompany error'))
      ));

  }
  getNetwork (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'INS_network/getinsnetwork', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getNetwork error'))
      ));

  }
  getComplaints (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'ChiefComplaints/getComplaints', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
      ));
  }
  getDentalComplaints(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DentalComplaints/getDentalComplaints', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDentalComplaints'))
      ));
  }
  getDentalHistory(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DentalComplaints/getDentalHistory', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDentalHistory'))
      ));
  }
  listNotAllowedProcedure(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DentalComplaints/listNotAllowedProcedure', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listNotAllowedProcedure'))
      ));
  }
  listDentalProcedure(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'DentalComplaints/listDentalProcedure',null).pipe(
      map(this.extractData));
  }
  // downloadFile(): any {
	// 	return this.http.get(AppSettings.API_ENDPOINT + 'public/uploads/pdf_name.pdf', {responseType: 'blob'});
  // } 
  downloadDentalHistoryPdf(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DentalComplaints/downloadDentalHistoryPdf', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('downloadDentalHistoryPdf'))
    ));
  }
  get_master_data (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Settings/get_master_data', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('get_master_data'))
    ));
  }
  get_filetype (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Settings/get_master_data', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>(' get_filetype'))
    ));
  }
  get_Documenttype (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Settings/get_master_data', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>(' get_Documenttype'))
    ));
  }
  get_priority (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Settings/get_master_data', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>(' get_filetype'))
    ));
  }

  savePatientDiagnosis (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientDiagnosis/savePatientDiagnosis', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('savePatientDiagnosis'))
    ));
  }
  getPatientDiagnosis (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientDiagnosis/getPatientDiagnosis', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getPatientDiagnosis'))
    ));
  }
  getPreviousDiagnosis (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientDiagnosis/getPreviousDiagnosis', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getPreviousDiagnosis'))
    ));
  }
  listDiagnosis (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Diagnosis/listDiagnosis', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('listDiagnosis'))
    ));
  }
  getDiagnosis(postdata): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Diagnosis/getDiagnosis', JSON.stringify(postdata)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listDiagnosis'))
    ));
  }
  saveReportsAndNotes (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'ReportsAndNotes/saveReportsAndNotes', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveReportsAndNotes'))
    ));
  }
  getReportsAndNotes (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'ReportsAndNotes/getReportsAndNotes', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getReportsAndNotes'))
    ));
  }
  listImmunization (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Immunization/listImmunization', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listImmunization'))
    ));
  }
  getImmunization (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Immunization/getImmunization', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getImmunization'))
    ));
  }
  saveImmunization (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Immunization/saveImmunization', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveImmunization'))
    ));
  }
  savePatientImmunization (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientImmunization/savePatientImmunization', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savePatientImmunization'))
    ));
  }
  getPatientImmunization (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientImmunization/getPatientImmunization', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientImmunization'))
    ));
  }
  listMedicine(opData): Observable<any> {
      return this.http.post<any>(AppSettings.API_ENDPOINT + 'Medicine/listMedicine', JSON.stringify(opData)).pipe(
        tap((result) => 
        catchError(this.handleError<any>('listMedicine'))
      ));
  }
  listRouteOfAdmin (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorPrescription/listRouteOfAdmin', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listRouteOfAdmin'))
    ));
  }
  
  getRouteOfAdmin(opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorPrescription/getRouteOfAdmin', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getRouteOfAdmin'))
    ));
  }
  getMedicine(opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Medicine/getMedicine', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getMedicine'))
    ));
  }
  listGenericType (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'MedicineGenericType/listGenericType', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listGenericType'))
    ));
  }
  getGenericType (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'MedicineGenericType/getGenericType', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getGenericType'))
    ));
    }
  getPrescription (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorPrescription/getPrescription', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPrescription'))
    ));
  }
  savePrescription (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorPrescription/savePrescription', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savePrescription'))
    ));
  }
  uploadToeRx (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorPrescription/generateRxFile', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('generateRxFile'))
    ));
  }
  savePatientSickLeave (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientSickLeave/savePatientSickLeave', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savePatientSickLeave'))
    ));
  }
  downloaSickleavePdf(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientSickLeave/downloaSickleavePdf', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('downloaSickleavePdf'))
    ));
  }
  getPatientSickLeave (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientSickLeave/getPatientSickLeave', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientSickLeave'))
    ));
  }
  listPatientSickLeave (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientSickLeave/listPatientSickLeave', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listPatientSickLeave'))
    ));
  }
  getPreviousPrescription (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorPrescription/getPreviousPrescription', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getPreviousPrescription'))
    ));
  }

  getCancelPrescription (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorPrescription/getCancelPrescription', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getCancelPrescription'))
    ));
  }

  getCDTByProcedureId (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DentalComplaints/getCDTByProcedureId', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getCDTByProcedureId'))
    ));
  }
  getdentalProcedure(): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/options',null).pipe(
      map(this.extractData));
  }
   // To handle error
   private handleError<T> (operation = 'operation', result?: T) {
    return (error: any): Observable<T> => {

      // TODO: send the error to remote logging infrastructure
      console.error(error); // log to console instead

      // TODO: better job of transforming error for user consumption
      console.log(`${operation} failed: ${error.message}`);

      // Let the app keep running by returning an empty result.
      return of(result as T);
    };
  }
}


