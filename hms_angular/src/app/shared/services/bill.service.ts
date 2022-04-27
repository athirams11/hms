import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';

const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type':  'application/json'
  })
};

const cpt_path = AppSettings.API_ENDPOINT + 'CurrentProceduralCode/listCurrentProceduralCodeforTreatment';

@Injectable({
  providedIn: 'root'
})

export class BillService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    const body = res;
    return body || { };
  }

  cptsearch(term: string,department:number) {
    if (term === '') {
      return of([]);
    }


    return this.http
      .post<any>(cpt_path, JSON.stringify( {procedure_code_category : department , search_text:  term, limit: 50, current_procedural_code_id : '0', start: 1})).pipe(
        map(response => response['data'])
      );
  }
  assessmentListByDatefordept (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/assessmentListByDatefordept', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('assessmentListByDatefordept'))
    ));
  }

  getPatientDetails (opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/getPatientDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }

  getPendingAmount (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/getPendingAmount', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPendingAmount'))
    ));
  }
  getPatientCptRate (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/getPatientCptRate', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientCptRate'))
    ));
  }
  savePatientBill (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/savePatientBill', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savePatientBill'))
    ));
  }
  savediscount (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/savediscount', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savediscount'))
    ));
  }
  saveLabInvestigationResults (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/saveLabInvestigationResults', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveLabInvestigationResults'))
    ));
  }
  saveInvestigationByCashier (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/saveInvestigationByCashier', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveInvestigationByCashier'))
    ));
  }
  deleteInvestigationByCashier(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/deleteInvestigationByCashier', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('deleteInvestigationByCashier'))
    ));
  }
  getLabInvestigationResults (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/getLabInvestigationResults', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getLabInvestigationResults'))
    ));
  }
  generatePatientBill (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/generatePatientBill', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('generatePatientBill'))
    ));
  }
  getBillByAssessment (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/getBillByAssessment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getBillByAssessment'))
    ));
  }

  getBill(postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Report/getBill', JSON.stringify(postData)).pipe(
      tap((result) => catchError(this.handleError<any>('getBill'))
    ))
  }
  getLabInvestigation (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Bill/getLabInvestigation', JSON.stringify(opData)).pipe(
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
