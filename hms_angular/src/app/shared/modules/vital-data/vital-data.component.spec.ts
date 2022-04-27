import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VitalDataComponent } from './vital-data.component';

describe('VitalDataComponent', () => {
  let component: VitalDataComponent;
  let fixture: ComponentFixture<VitalDataComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VitalDataComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VitalDataComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
