import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DentalInvestigationComponent } from './dental-investigation.component';

describe('DentalInvestigationComponent', () => {
  let component: DentalInvestigationComponent;
  let fixture: ComponentFixture<DentalInvestigationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DentalInvestigationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DentalInvestigationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
