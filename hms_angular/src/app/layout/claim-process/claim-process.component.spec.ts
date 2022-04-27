import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ClaimProcessComponent } from './claim-process.component';

describe('ClaimProcessComponent', () => {
  let component: ClaimProcessComponent;
  let fixture: ComponentFixture<ClaimProcessComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ClaimProcessComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ClaimProcessComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
