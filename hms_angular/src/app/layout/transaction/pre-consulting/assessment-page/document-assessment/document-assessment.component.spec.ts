import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentAssessmentComponent } from './document-assessment.component';

describe('DocumentAssessmentComponent', () => {
  let component: DocumentAssessmentComponent;
  let fixture: ComponentFixture<DocumentAssessmentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DocumentAssessmentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentAssessmentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
