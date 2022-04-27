import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { PatientQueryService } from './patient-query.service';

describe('PatientQueryService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [PatientQueryService]}));

  it('should be created', () => {
    const service: PatientQueryService = TestBed.get(PatientQueryService);
    expect(service).toBeTruthy();
  });
});
