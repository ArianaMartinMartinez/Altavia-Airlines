import { Component } from '@angular/core';
import { FlightsFilterComponent } from "../../shared/flights-filter/flights-filter.component";
import { RouterLink } from '@angular/router';
import { FlightCardComponent } from '../../shared/flight-card/flight-card.component';

@Component({
  selector: 'app-flights',
  imports: [FlightsFilterComponent, RouterLink, FlightCardComponent],
  templateUrl: './flights.component.html',
  styleUrl: './flights.component.css'
})
export class FlightsComponent {

}
