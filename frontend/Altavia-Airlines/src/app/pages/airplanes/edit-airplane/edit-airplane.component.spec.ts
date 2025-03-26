import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditAirplaneComponent } from './edit-airplane.component';

describe('EditAirplaneComponent', () => {
  let component: EditAirplaneComponent;
  let fixture: ComponentFixture<EditAirplaneComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EditAirplaneComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EditAirplaneComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
