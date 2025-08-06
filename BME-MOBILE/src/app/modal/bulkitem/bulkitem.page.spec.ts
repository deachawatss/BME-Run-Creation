import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BulkitemPage } from './bulkitem.page';

describe('BulkitemPage', () => {
  let component: BulkitemPage;
  let fixture: ComponentFixture<BulkitemPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(BulkitemPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
