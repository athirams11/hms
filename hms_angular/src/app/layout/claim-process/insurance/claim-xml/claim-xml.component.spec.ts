import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ClaimXmlComponent } from './claim-xml.component';

describe('ClaimXmlComponent', () => {
  let component: ClaimXmlComponent;
  let fixture: ComponentFixture<ClaimXmlComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ClaimXmlComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ClaimXmlComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
