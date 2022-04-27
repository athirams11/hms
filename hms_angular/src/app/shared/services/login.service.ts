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
export class LoginService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  
  checkCredentials (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Login/checkCredentials', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('checkCredentials'))
    ));
  }
  getAccessTypes(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Login/getAccessTypes',null).pipe(
      map(this.extractData));
  }
  getInstitution(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'InstitutionManagement/listInstitution', null).pipe(
      map(this.extractData));
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
