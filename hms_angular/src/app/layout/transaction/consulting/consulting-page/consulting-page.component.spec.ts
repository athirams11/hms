import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ConsultingPageComponent } from './consulting-page.component';

describe('ConsultingPageComponent', () => {
  let component: ConsultingPageComponent;
  let fixture: ComponentFixture<ConsultingPageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ConsultingPageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ConsultingPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
