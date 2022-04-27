import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { NursingAssesmentService } from './nursing-assesment.service';

describe('NursingAssesmentService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [NursingAssesmentService]}));

  it('should be created', () => {
    const service: NursingAssesmentService = TestBed.get(NursingAssesmentService);
    expect(service).toBeTruthy();
  });
});
