import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RMvalidationPage } from './rmvalidation.page';

describe('RMvalidationPage', () => {
  let component: RMvalidationPage;
  let fixture: ComponentFixture<RMvalidationPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(RMvalidationPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
