import { ComponentFixture, TestBed } from '@angular/core/testing';
import { PreweighValidationPage } from './preweigh-validation.page';

describe('PreweighValidationPage', () => {
  let component: PreweighValidationPage;
  let fixture: ComponentFixture<PreweighValidationPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(PreweighValidationPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
