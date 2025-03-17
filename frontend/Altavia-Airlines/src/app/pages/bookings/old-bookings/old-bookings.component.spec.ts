import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OldBookingsComponent } from './old-bookings.component';

describe('OldBookingsComponent', () => {
  let component: OldBookingsComponent;
  let fixture: ComponentFixture<OldBookingsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [OldBookingsComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OldBookingsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
