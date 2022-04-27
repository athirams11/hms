import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CurrentProceduralTerminologyComponent } from './current-procedural-terminology.component';

describe('CurrentProceduralTerminologyComponent', () => {
  let component: CurrentProceduralTerminologyComponent;
  let fixture: ComponentFixture<CurrentProceduralTerminologyComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CurrentProceduralTerminologyComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CurrentProceduralTerminologyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
