import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NewAirplaneComponent } from './new-airplane.component';

describe('NewAirplaneComponent', () => {
  let component: NewAirplaneComponent;
  let fixture: ComponentFixture<NewAirplaneComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NewAirplaneComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NewAirplaneComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
