import { TestBed } from '@angular/core/testing';

import { ClaimProcessService } from './claim-process.service';

describe('ClaimProcessService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ClaimProcessService = TestBed.get(ClaimProcessService);
    expect(service).toBeTruthy();
  });
});
