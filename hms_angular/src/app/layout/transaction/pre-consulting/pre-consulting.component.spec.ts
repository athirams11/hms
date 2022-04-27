import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PreConsultingComponent } from './pre-consulting.component';

describe('PreConsultingComponent', () => {
  let component: PreConsultingComponent;
  let fixture: ComponentFixture<PreConsultingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PreConsultingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PreConsultingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
