import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
// const endpoint = 'http://192.168.0.11/hms/server/';
// const endpoint = 'http://wiktait.com/demo/hms/server/';
const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type':  'application/json'
  })
};

@Injectable({
  providedIn: 'root'
})

export class OpRegistrationService {


  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    const body = res;
    return body || { };
  }
  saveBillresult(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Billing/saveBillresult', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveBillresult'))
    ));
  }
  // claim process
unClaimedInvoiceList(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/nonClaimedinvoiceList', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('unClaimedInvoiuceList'))
  ));
}
updateInsuranceDetails(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/updateInsuranceDetails', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('updateInsuranceDetails'))
  ));
}

getFileContent(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/getFileContent', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('getFileContent'))
  ));
}
saveFileContent(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/saveFileContent', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('saveFileContent'))
  ));
}
public download(url){
  let headers = new HttpHeaders();
  headers = headers.set('Accept', 'application/xml');
  return this.http.get(url, { headers: headers, responseType: 'blob' }
  );
  }
XmlClaimList(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/submissionFileList', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('XmlClaimList'))
  ));
}
submittedFileList(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/submittedFileList', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('submittedFileList'))
  ));
}
searchTransactionParams(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/searchTransactionParams', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('searchTransactionParams'))
  ));
}
GetNewTransactions(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/GetNewTransactions', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('GetNewTransactions'))
  ));
}
searchNewTransactions(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/getNewRemittance', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('searchNewTransactions'))
  ));
}
GenerateSubmissionFiles(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/generateSubmissionFile', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('GenerateSubmissionFiles'))
  ));
}
testUploadSubmissionFile(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/testUploadSubmissionFile', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('testUploadSubmissionFile'))
  ));
}

UploadSubmissionFile(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/UploadSubmissionFile', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('testUploadSubmissionFile'))
  ));
}
reGenerateSubmissionXml(opData): Observable<any> {
  // console.log(opData);
  return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/reGenerateSubmissionXml', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('SubmissionXmlList'))
  ));
}
  getOpDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'OpRegistration/options', null).pipe(
      map(this.extractData));
  }
  // getOpNetworks(opdata): Observable<any> {
  //   return this.http.post(AppSettings.API_ENDPOINT + 'OpRegistration/getNetworkDetails', null).pipe(
  //     map(this.extractData));
  // }
  listCPT (): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/listCurrentProceduralCode', null).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listCurrentProceduralCode'))
    ));
  }
  listCurrentProceduralCodeforTreatment (): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/listCurrentProceduralCodeforTreatment', null).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listCurrentProceduralCodeforTreatment'))
    ));
  }
  getOpNetworks (opData): Observable<any> {
    // console.log(opData);
     return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/getNetworkDetails', JSON.stringify(opData)).pipe(
       tap((result) => 
       catchError(this.handleError<any>('getOpNetworks'))
     ));
   }
  addNewOpRegistration (opData): Observable<any> {
   // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/newRegistration', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('addNewOpRegistration'))
    ));
  }

  getPatientDetails (opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/getPatientDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getBillVarificationDetails(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/GetBillverificationDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getBillVarificationDetails'))
    ));
  }
  searchTransactions(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/searchTransactions', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('searchTransactions'))
    ));
  }
  updateBillverificationData(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/updateBillverificationData', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('updateBillverificationData'))
    ));
  }
  confirmBillverificationData(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InsuranceClaim/confirmBillverificationData', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('confirmBillverificationData'))
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
