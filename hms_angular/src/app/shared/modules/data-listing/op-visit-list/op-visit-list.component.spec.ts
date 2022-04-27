import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OpVisitListComponent } from './op-visit-list.component';

describe('OpVisitListComponent', () => {
  let component: OpVisitListComponent;
  let fixture: ComponentFixture<OpVisitListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OpVisitListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OpVisitListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
