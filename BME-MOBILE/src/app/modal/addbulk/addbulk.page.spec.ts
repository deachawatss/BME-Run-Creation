import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AddbulkPage } from './addbulk.page';

describe('AddbulkPage', () => {
  let component: AddbulkPage;
  let fixture: ComponentFixture<AddbulkPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(AddbulkPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
