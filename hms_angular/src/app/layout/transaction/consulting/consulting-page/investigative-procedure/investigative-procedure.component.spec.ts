import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InvestigativeProcedureComponent } from './investigative-procedure.component';

describe('InvestigativeProcedureComponent', () => {
  let component: InvestigativeProcedureComponent;
  let fixture: ComponentFixture<InvestigativeProcedureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InvestigativeProcedureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InvestigativeProcedureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
