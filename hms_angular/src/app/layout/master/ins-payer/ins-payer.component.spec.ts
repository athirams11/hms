import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InsPayerComponent } from './ins-payer.component';

describe('InsPayerComponent', () => {
  let component: InsPayerComponent;
  let fixture: ComponentFixture<InsPayerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InsPayerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InsPayerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
