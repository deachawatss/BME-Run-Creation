import { ComponentFixture, TestBed } from '@angular/core/testing';
import { PreweighInventoryPage } from './preweigh-inventory.page';

describe('PreweighInventoryPage', () => {
  let component: PreweighInventoryPage;
  let fixture: ComponentFixture<PreweighInventoryPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(PreweighInventoryPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
