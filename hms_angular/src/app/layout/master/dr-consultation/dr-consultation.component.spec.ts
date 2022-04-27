import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DrConsultationComponent } from './dr-consultation.component';

describe('DrConsultationComponent', () => {
  let component: DrConsultationComponent;
  let fixture: ComponentFixture<DrConsultationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DrConsultationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DrConsultationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
