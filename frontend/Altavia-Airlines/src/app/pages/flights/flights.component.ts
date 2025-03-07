import { Component } from '@angular/core';
import { FlightsFilterComponent } from "../../shared/flights-filter/flights-filter.component";
import { RouterLink } from '@angular/router';
import { FlightCardComponent } from '../../shared/flight-card/flight-card.component';
import { Flight } from '../../models/flight';
import { FlightService } from '../../services/flight.service';
import { CommonModule } from '@angular/common';
import { LoaderComponent } from '../../shared/loader/loader.component';

@Component({
  selector: 'app-flights',
  imports: [FlightsFilterComponent, RouterLink, FlightCardComponent, CommonModule, LoaderComponent],
  templateUrl: './flights.component.html',
  styleUrl: './flights.component.css'
})
export class FlightsComponent {
  flightsList: Flight[] = [];
  hasLoaded: boolean = false;

  constructor(private flightService: FlightService) {
    this.getFlights();
  }

  getFlights() {
    this.flightService.getAllFutureFlights().subscribe({
      next: (rtn) => {
        this.flightsList = rtn;
        console.log(this.flightsList);
      },
      error: (error) => {
        console.log(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    });
  }

  filterFlights(filteredFlights: Flight[]) {
    this.flightsList = filteredFlights;
    console.log(this.flightsList);
  }
}
