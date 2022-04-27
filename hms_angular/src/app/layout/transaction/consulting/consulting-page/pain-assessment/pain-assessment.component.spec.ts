import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PainAssessmentComponent } from './pain-assessment.component';

describe('PainAssessmentComponent', () => {
  let component: PainAssessmentComponent;
  let fixture: ComponentFixture<PainAssessmentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PainAssessmentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PainAssessmentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
