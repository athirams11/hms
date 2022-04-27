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
export class ModuleService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    const body = res;
    return body || { };
  }
  getModules(sentData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Modules/getModules', JSON.stringify(sentData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('checkCredentials'))
      ));
  }
  getModulesNotification(sentData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'modules/getNotificationCount', JSON.stringify(sentData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('checkCredentials'))
      ));
  }
  getModuleSummary(sentData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'modules/getModuleSummary', JSON.stringify(sentData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getModuleSummary'))
      ));
  }
  changePassword(sentData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserManagement/changePassword', JSON.stringify(sentData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('changePassword'))
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
