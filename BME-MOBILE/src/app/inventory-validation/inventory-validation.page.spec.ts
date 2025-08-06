import { ComponentFixture, TestBed } from '@angular/core/testing';
import { InventoryValidationPage } from './inventory-validation.page';

describe('InventoryValidationPage', () => {
  let component: InventoryValidationPage;
  let fixture: ComponentFixture<InventoryValidationPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(InventoryValidationPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
