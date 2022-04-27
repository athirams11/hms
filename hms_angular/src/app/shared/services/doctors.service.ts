import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
@Injectable({
  providedIn: 'root'
})
@Injectable({
  providedIn: 'root'
})
export class DoctorsService {
  medicine_search(term: string) {
    throw new Error("Method not implemented.");
  }

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  getDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Doctors/options',null).pipe(
      map(this.extractData));
  }
  addNewDoctor (opData): Observable<any> {
    // console.log(opData);
     return this.http.post<any>(AppSettings.API_ENDPOINT + 'Doctors/addNewDoctor', JSON.stringify(opData)).pipe(
       tap((result) => 
       catchError(this.handleError<any>('addNewDoctor'))
     ));
   }
  getDoctorList(postData): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Doctors/getDoctorList',JSON.stringify(postData)).pipe(
      map(this.extractData));
  }
  save_diagnosis (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Diagnosis/saveDiagnosis', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savecomplaints'))
    ));
  }
  getDiagnosisList(postdata): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Diagnosis/listDiagnosis',null).pipe(
      map(this.extractData));
  }
  getDiagnosis(postdata): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Diagnosis/getDiagnosis',null).pipe(
      map(this.extractData));
  }
  saveMedicine (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + '/Medicine/saveMedicine', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savecomplaints'))
    ));
  }
  getMedicinelist(postdata): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Medicine/listMedicine',null).pipe(
      map(this.extractData));
  }
  saveCPT (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/saveCurrentProceduralCode', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savecomplaints'))
    ));
  }
  // getCPTlist(postdata): Observable<any> {
  //   return this.http.post(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/listCurrentProceduralCode',null).pipe(
  //     map(this.extractData));
  // }
  getCPTlist(postdata): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/listCurrentProceduralCode', JSON.stringify(postdata)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getcpt'))
    ));
    
  }
  getCPT(postdata): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/getCurrentProceduralCode',JSON.stringify(postdata)).pipe(
      map(this.extractData));
  }
  getcpt_group(): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/options',null).pipe(
      map(this.extractData));
  }
  saveLab_investigation (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/saveLabInvestigation', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveLabInvestigation'))
    ));
  }
  saveDentalInvestigation (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/saveDentalInvestigation', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveDentalInvestigation'))
    ));
  }
  saveInvestigation (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/saveInvestigation', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveInvestigation'))
    ));
  }
  get_lab_investigation (opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/getLabInvestigation',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getLabInvestigation'))
    ));
  }
  getDentalInvestigation (opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/getDentalInvestigation',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getDentalInvestigation'))
    ));
  }
  deleteInvestigation (opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/deleteInvestigation',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('deleteInvestigation'))
    ));
  }
  getlabinvestigation (opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/getLabInvestigation',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getLabInvestigation'))
    ));
  }
  getbillStatus(opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/getbillStatus',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getbillStatus'))
    ));
  }
  saveProcedurecode (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'ProcedureCode/saveProcedureCode', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savecomplaints'))
    ));
  }
  getProcedurecode(postdata): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + '/ProcedureCode/listProcedureCode',null).pipe(
      map(this.extractData));
  }

  saveDocuments (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'FileManage/saveFile', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
    ));
  }

  //For dental activities

  saveLab_investigation_dental (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/saveLabInvestigation', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveLabInvestigation'))
    ));
  }
  saveInvestigation_dental (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/saveInvestigation', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveInvestigation'))
    ));
  }
  get_lab_investigation_dental (opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/getLabInvestigation',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getLabInvestigation'))
    ));
  }
  deleteInvestigation_dental (opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/deleteInvestigation',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('deleteInvestigation'))
    ));
  }
  getbillStatus_dental(opData) : Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'LabInvestigation/getbillStatus',JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getbillStatus'))
    ));
  }
   //To handle error
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
