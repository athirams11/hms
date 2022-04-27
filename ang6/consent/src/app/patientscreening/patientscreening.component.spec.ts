import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PatientscreeningComponent } from './patientscreening.component';

describe('PatientscreeningComponent', () => {
  let component: PatientscreeningComponent;
  let fixture: ComponentFixture<PatientscreeningComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PatientscreeningComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PatientscreeningComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
