import { ComponentFixture, TestBed } from '@angular/core/testing';
import { InventoryCountPage } from './inventory-count.page';

describe('InventoryCountPage', () => {
  let component: InventoryCountPage;
  let fixture: ComponentFixture<InventoryCountPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(InventoryCountPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
