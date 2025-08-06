import { ComponentFixture, TestBed } from '@angular/core/testing';
import { InventoryCountSearchPage } from './inventory-count-search.page';

describe('InventoryCountSearchPage', () => {
  let component: InventoryCountSearchPage;
  let fixture: ComponentFixture<InventoryCountSearchPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(InventoryCountSearchPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
