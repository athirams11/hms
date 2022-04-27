import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PatientQueryComponent } from './patient-query.component';

describe('PatientQueryComponent', () => {
  let component: PatientQueryComponent;
  let fixture: ComponentFixture<PatientQueryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PatientQueryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PatientQueryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
