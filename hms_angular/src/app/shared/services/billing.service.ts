import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';

@Injectable({
  providedIn: 'root'
})
export class BillingService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    const body = res;
    return body || { };
  }
// claim process
unClaimedInvoiuceList(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/invoiceList', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('unClaimedInvoiuceList'))
  ));
}

  assessmentListByDate (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/assessmentListByDate', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('assessmentListByDate'))
    ));
  }

  assessmentListByDatefordept (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/assessmentListByDatefordept', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('assessmentListByDatefordept'))
    ));
  }

  getPatientDetails (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/getPatientDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }

  getPendingAmount (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/getPendingAmount', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPendingAmount'))
    ));
  }
  getPatientCptRate (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/getPatientCptRate', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientCptRate'))
    ));
  }
  savePatientBill (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/savePatientBill', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savePatientBill'))
    ));
  }
  saveLabInvestigationResults (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/saveLabInvestigationResults', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveLabInvestigationResults'))
    ));
  }
  saveInvestigationByCashier (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/saveInvestigationByCashier', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveInvestigationByCashier'))
    ));
  }
  deleteInvestigationByCashier(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/deleteInvestigationByCashier', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('deleteInvestigationByCashier'))
    ));
  }
  getLabInvestigationResults (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/getLabInvestigationResults', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getLabInvestigationResults'))
    ));
  }
  generatePatientBill (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/generatePatientBill', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('generatePatientBill'))
    ));
  }
  getBillByAssessment (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/getBillByAssessment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getBillByAssessment'))
    ));
  }
  get_lab_investigation (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/getLabInvestigation', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getLabInvestigation'))
    ));
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
