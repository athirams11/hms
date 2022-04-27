import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InsNetworkComponent } from './ins-network.component';

describe('InsNetworkComponent', () => {
  let component: InsNetworkComponent;
  let fixture: ComponentFixture<InsNetworkComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InsNetworkComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InsNetworkComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
