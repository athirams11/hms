import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SampleTypeComponent } from './sample-type.component';

describe('SampleTypeComponent', () => {
  let component: SampleTypeComponent;
  let fixture: ComponentFixture<SampleTypeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SampleTypeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SampleTypeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
