import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ItemrundataPage } from './itemrundata.page';

describe('ItemrundataPage', () => {
  let component: ItemrundataPage;
  let fixture: ComponentFixture<ItemrundataPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(ItemrundataPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
