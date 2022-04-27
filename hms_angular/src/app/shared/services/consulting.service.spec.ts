import { TestBed } from '@angular/core/testing';
import { ConsultingService } from './consulting.service';

describe('ConsultingService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    }));

  it('should be created', () => {
    const service: ConsultingService = TestBed.get(ConsultingService);
    expect(service).toBeTruthy();
  });
});
