import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OldFlightsComponent } from './old-flights.component';

describe('OldFlightsComponent', () => {
  let component: OldFlightsComponent;
  let fixture: ComponentFixture<OldFlightsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [OldFlightsComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OldFlightsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
