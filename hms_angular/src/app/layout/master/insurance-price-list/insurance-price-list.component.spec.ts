import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InsurancePriceListComponent } from './insurance-price-list.component';

describe('InsurancePriceListComponent', () => {
  let component: InsurancePriceListComponent;
  let fixture: ComponentFixture<InsurancePriceListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InsurancePriceListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InsurancePriceListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
