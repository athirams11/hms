import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VitalSignalsComponent } from './vital-signals.component';

describe('VitalSignalsComponent', () => {
  let component: VitalSignalsComponent;
  let fixture: ComponentFixture<VitalSignalsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VitalSignalsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VitalSignalsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
