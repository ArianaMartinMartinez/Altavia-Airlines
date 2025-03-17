import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { LoaderComponent } from '../../shared/loader/loader.component';
import { FlightCardComponent } from '../../shared/flight-card/flight-card.component';
import { FutureBookingsComponent } from './future-bookings/future-bookings.component';
import { OldBookingsComponent } from './old-bookings/old-bookings.component';
import { BookingService } from '../../services/booking.service';
import { TokenService } from '../../services/token.service';
import { Flight } from '../../models/flight';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-bookings',
  imports: [CommonModule, LoaderComponent, FlightCardComponent, FutureBookingsComponent, OldBookingsComponent],
  templateUrl: './bookings.component.html',
  styleUrl: './bookings.component.css'
})
export class BookingsComponent implements OnInit {
  hasLoaded: boolean = false;
  nextBooking!: Flight;
  futureBookings!: Flight[];
  oldBookings!: Flight[];

  futureFlightsLoaded: boolean = true;

  constructor(private bookingService: BookingService, private tokenService: TokenService) { }

  ngOnInit(): void {
    this.getBookings();
  }

  getBookings() {
    const token = {
      token: this.tokenService.get(),
    }
    this.bookingService.getUserBookings(token).subscribe({
      next: (rtn) => {
          this.nextBooking = rtn.next_booking;
          this.futureBookings = rtn.future_bookings;
          this.oldBookings = rtn.past_bookings;
      },
      error: (error) => {
        console.error(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    });
  }

  setBooking(id: string) {
    this.cancelFlight(Number(id));

    setTimeout(() => {
      window.location.reload();
    }, 2000);
  }

  private cancelFlight(id: number) {
    const token = {
      token: this.tokenService.get(),
    }
    this.bookingService.cancelFlight(id, token).subscribe({
      next: (rtn: any)=> {
        Swal.fire({
          icon: "success",
          title: rtn.message,
        });

        setTimeout(() => {
          window.location.reload();
        }, 2000);
      },
      error: (error) => {
        if(error.error.message) {
          Swal.fire({
            icon: "error",
            title: "Oh no!",
            text: error.error.message,
          });
        }
        console.error(error);
      }
    });
  }

  loadFutureBookings() {
    this.futureFlightsLoaded = true;
  }

  loadOldBookings() {
    this.futureFlightsLoaded = false;
  }
}
