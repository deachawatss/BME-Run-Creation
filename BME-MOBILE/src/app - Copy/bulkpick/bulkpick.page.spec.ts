import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BulkpickPage } from './bulkpick.page';

describe('BulkpickPage', () => {
  let component: BulkpickPage;
  let fixture: ComponentFixture<BulkpickPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(BulkpickPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
