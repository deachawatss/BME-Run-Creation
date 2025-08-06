import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BatchtransferPage } from './batchtransfer.page';

describe('BatchtransferPage', () => {
  let component: BatchtransferPage;
  let fixture: ComponentFixture<BatchtransferPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(BatchtransferPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
