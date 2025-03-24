import { Component, OnInit } from '@angular/core';
import { FlightsFilterComponent } from "../../../shared/flights-filter/flights-filter.component";
import { RouterLink } from '@angular/router';
import { FlightCardComponent } from '../../../shared/flight-card/flight-card.component';
import { Flight } from '../../../models/flight';
import { FlightService } from '../../../services/flight.service';
import { CommonModule } from '@angular/common';
import { LoaderComponent } from '../../../shared/loader/loader.component';
import { AuthService } from '../../../services/auth.service';
import { TokenService } from '../../../services/token.service';
import { BookingService } from '../../../services/booking.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-old-flights',
  imports: [FlightsFilterComponent, RouterLink, FlightCardComponent, CommonModule, LoaderComponent],
  templateUrl: './old-flights.component.html',
  styleUrl: './old-flights.component.css'
})
export class OldFlightsComponent implements OnInit {
  flightsList: Flight[] = [];
  hasLoaded: boolean = false;
  bookedFlightsList: string[] = [];
  loggedIn: boolean = false;
  isAdmin: boolean = false;

  constructor(
    private flightService: FlightService,
    private bookingService: BookingService,
    private authService: AuthService,
    private tokenService: TokenService,
  ) {
    this.getFlights();
  }

  ngOnInit(): void {
    this.authService.authStatus.subscribe({
      next: (rtn) => {
        this.loggedIn = rtn;
        if(this.loggedIn) {
          this.getUserRole();
          if(!this.isAdmin) {
            this.getBookings();
          }
        }
      },
      error: (error) => {
        console.error(error);
      }
    });
  }

  getFlights() {
    this.flightService.getAllPastFlights().subscribe({
      next: (rtn) => {
        this.flightsList = rtn;
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
  }

  getUserRole() {
    const token = {
      token: this.tokenService.get(),
    }

    this.authService.me(token).subscribe({
      next: (rtn) => {
        this.isAdmin = rtn.role === 'admin';
      },
      error: (error) => {
        console.error(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    });
  }

  getBookings() {
    const token = {
      token: this.tokenService.get(),
    }
    this.bookingService.getUserBookings(token).subscribe({
      next: (rtn) => {
        if (rtn.next_booking) {
          this.bookedFlightsList.push((rtn.next_booking.id).toString());
        }
  
        if (rtn.future_bookings && rtn.future_bookings.length > 0) {
          rtn.future_bookings.forEach((flight: Flight) => {
            this.bookedFlightsList.push((flight.id).toString());
          });
        }
  
        if (rtn.past_bookings && rtn.past_bookings.length > 0) {
          rtn.past_bookings.forEach((flight: Flight) => {
            this.bookedFlightsList.push((flight.id).toString());
          });
        }
      },
      error: (error) => {
        console.error(error);
      }
    });
  }

  deleteFlight(id: string) {
    const token = {
      token: this.tokenService.get(),
    }
    this.flightService.deleteFlight(id, token).subscribe({
      next: (rtn) => {
        Swal.fire({
          icon: "success",
          text: rtn.message,
        });

        setTimeout(() => {
          window.location.reload();
        }, 2000);
      },
      error: (error) => {
        console.error(error);
      }
    });
  }
}
