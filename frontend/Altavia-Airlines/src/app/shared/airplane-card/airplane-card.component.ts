import { Component, Input } from '@angular/core';
import { Airplane } from '../../models/airplane';

@Component({
  selector: 'app-airplane-card',
  imports: [],
  templateUrl: './airplane-card.component.html',
  styleUrl: './airplane-card.component.css'
})
export class AirplaneCardComponent {
  @Input() airplane!: Airplane;
}
