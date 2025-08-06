import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AddbulkitemPage } from './addbulkitem.page';

describe('AddbulkitemPage', () => {
  let component: AddbulkitemPage;
  let fixture: ComponentFixture<AddbulkitemPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(AddbulkitemPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
