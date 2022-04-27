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
const cpt_path = AppSettings.API_ENDPOINT + 'Querys/getPatientList';
@Injectable({
  providedIn: 'root'
})
export class ReportService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  listCashReport(opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Report/listCashReport', JSON.stringify(opData)).pipe(
      tap((result) => catchError(this.handleError<any>('listCashReport'))
    ))
  }
  downloadmedicalPdf(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Report/downloadmedicalPdf', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('downloadmedicalPdf'))
    ));
  }

  downloadmedicalPdf_visitdate(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Report/downloadmedicalPdf_visitdate', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('downloadmedicalPdf'))
    ));
  }

getVisitListByDate (postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpVisitEntry/getVisitListByDate', JSON.stringify(postData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  
   getPatientList(term: string) {
    if (term === '') {
      return of([]);
    }


    return this.http
      .post<any>(cpt_path, JSON.stringify( {search_text:  term, limit: 50, start: 1})).pipe(
        map(response => response['data'])
      );
  }

  /* getPatientList(postData): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Querys/getPatientList',JSON.stringify(postData)).pipe(
      map(this.extractData));
  }*/
  
  listCreditReport(opData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Report/listCreditReport', JSON.stringify(opData)).pipe(
      tap((result) => catchError(this.handleError<any>('listCreditReport'))
    ))
  }
  getTPA(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'OpRegistration/options', null).pipe(
      map(this.extractData));
  }
  listDoctorList(postData): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Doctors/getDoctorList',JSON.stringify(postData)).pipe(
      map(this.extractData));
  }
  getUserList(): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserManagement/getUserList', null).pipe(
       tap((result) => catchError(this.handleError<any>('getUserList'))
     ));
  }
  saveCorporateCompany(postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CorporateCompany/saveCorporateCompany', JSON.stringify(postData)).pipe(
      tap((result) => catchError(this.handleError<any>('saveCorporateCompany'))
    ))
  }
  listCorporateCompany(postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CorporateCompany/listCorporateCompany', JSON.stringify(postData)).pipe(
      tap((result) => catchError(this.handleError<any>('listCorporateCompany'))
    ))
  }
  getCorporateCompany(postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CorporateCompany/getCorporateCompany', JSON.stringify(postData)).pipe(
      tap((result) => catchError(this.handleError<any>('getCorporateCompany'))
    ))
  }
  getBill(postData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Report/getBill', JSON.stringify(postData)).pipe(
      tap((result) => catchError(this.handleError<any>('getBill'))
    ))
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
