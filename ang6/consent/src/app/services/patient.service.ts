import { Injectable } from '@angular/core';
import { HttpErrorResponse, HttpResponse } from '@angular/common/http';

import { Observable, throwError, of } from 'rxjs';
import { catchError, retry, map, tap } from 'rxjs/operators';

import { HttpClient, HttpHeaders } from '@angular/common/http';

const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type':  'application/json'
  })
};
//const rootUrl = 'http://localhost/HMS/hms/server/';
 const rootUrl = 'http://35.163.47.246/hms_his/server/';
 //const rootUrl ='https://wiktait.com/demo/hms/server/';
const cpt_path = rootUrl + 'Querys/getPatientList';
@Injectable({
  providedIn: 'root'
})
export class PatientService {


  constructor(private http: HttpClient) { }

  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
	
  	getInstitution(): Observable<any> {
    	return this.http.post(rootUrl + 'consent/getInstitution',null).pipe(
      	map(this.extractData));
  	}
  	getPatientDetails (opData): Observable<any> {
	    //console.log(opData);
	    return this.http.post<any>(rootUrl + 'consent/getPatientDetails', JSON.stringify(opData)).pipe(
	      tap((result) => 
	      catchError(this.handleError<any>('getPatientDetails'))
	    ));
  	}
	saveConsentDetails (opData): Observable<any> {
	   // console.log(opData);
	    return this.http.post<any>(rootUrl + 'consent/newConsent', JSON.stringify(opData)).pipe(
	      tap((result) => 
	      catchError(this.handleError<any>('saveConsentDetails'))
	    ));
	}
	saveGenConsentDetails (opData): Observable<any> {
	   // console.log(opData);
	    return this.http.post<any>(rootUrl + 'consent/newGeneralConsent', JSON.stringify(opData)).pipe(
	      tap((result) => 
	      catchError(this.handleError<any>('saveGeneralConsentDetails'))
	    ));
	}

	getPatientLists (opData): Observable<any> {
		// console.log(opData);
		 return this.http.post<any>(rootUrl + 'Querys/getPatientList_bydate',  JSON.stringify(opData)).pipe(
		   tap((result) => 
		   catchError(this.handleError<any>('getPatientList_bydate'))
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
