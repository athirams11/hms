import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { OpVisitService } from './op-visit.service';

describe('OpVisitService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [OpVisitService]}));

  it('should be created', () => {
    const service: OpVisitService = TestBed.get(OpVisitService);
    expect(service).toBeTruthy();
  });
});
