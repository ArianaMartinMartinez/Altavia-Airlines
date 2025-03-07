import { Component, Input } from '@angular/core';
import { Flight } from '../../models/flight';

@Component({
  selector: 'app-flight-card',
  imports: [],
  templateUrl: './flight-card.component.html',
  styleUrl: './flight-card.component.css'
})
export class FlightCardComponent {
  @Input() flight!: Flight;
}
