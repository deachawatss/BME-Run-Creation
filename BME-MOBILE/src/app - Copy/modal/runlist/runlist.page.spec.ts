import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RunlistPage } from './runlist.page';

describe('RunlistPage', () => {
  let component: RunlistPage;
  let fixture: ComponentFixture<RunlistPage>;

  beforeEach(async(() => {
    fixture = TestBed.createComponent(RunlistPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
