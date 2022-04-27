import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OpVisitEntryComponent } from './op-visit-entry.component';

describe('OpVisitEntryComponent', () => {
  let component: OpVisitEntryComponent;
  let fixture: ComponentFixture<OpVisitEntryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OpVisitEntryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OpVisitEntryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
