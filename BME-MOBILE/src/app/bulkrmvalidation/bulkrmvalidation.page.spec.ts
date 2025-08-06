import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BulkrmvalidationPage } from './bulkrmvalidation.page';

describe('BulkrmvalidationPage', () => {
  let component: BulkrmvalidationPage;
  let fixture: ComponentFixture<BulkrmvalidationPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(BulkrmvalidationPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
