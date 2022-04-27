import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { DoctorsService } from './doctors.service';

describe('DoctorsService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [DoctorsService]}));

  it('should be created', () => {
    const service: DoctorsService = TestBed.get(DoctorsService);
    expect(service).toBeTruthy();
  });
});
