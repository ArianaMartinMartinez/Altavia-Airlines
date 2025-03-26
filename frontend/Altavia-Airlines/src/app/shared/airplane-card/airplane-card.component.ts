import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Airplane } from '../../models/airplane';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-airplane-card',
  imports: [RouterLink],
  templateUrl: './airplane-card.component.html',
  styleUrl: './airplane-card.component.css'
})
export class AirplaneCardComponent {
  @Input() airplane!: Airplane;

  @Output() airplaneDeleted: EventEmitter<string> = new EventEmitter<string>();

  deleteAirplane(id: number) {
    this.airplaneDeleted.emit(id.toString());
  }
}
