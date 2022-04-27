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
@Injectable({
  providedIn: 'root'
})
export class OpVisitService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  getOpDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'OpVisitEntry/options',null).pipe(
      map(this.extractData));
  }
  getPatientDetails (postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/getPatientDetails', JSON.stringify(postData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  
  getDrByDateDept (postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/getDrByDateDept', JSON.stringify(postData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  //getVisitListByDate
  getVisitListByDate (postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/getVisitListByDate', JSON.stringify(postData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getCPTBySites (postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/getCPTBySites', JSON.stringify(postData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  //updateInsuranceDetails
  updateInsuranceDetails (postData): Observable<any> {
     return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/updateInsuranceDetails', JSON.stringify(postData)).pipe(
       tap((result) => 
       catchError(this.handleError<any>('updateInsuranceDetails'))
     ));
   }
   updateCompanyDetails(postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/updateCompanyDetails', JSON.stringify(postData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('updateCompanyDetails'))
    ));
  }
   addVisit (postData): Observable<any> {
     return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/addVisit', JSON.stringify(postData)).pipe(
       tap((result) => 
       catchError(this.handleError<any>('addVisit'))
     ));
   }
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
