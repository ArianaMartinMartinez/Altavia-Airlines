import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';
import { FlightCardComponent } from '../../../shared/flight-card/flight-card.component';
import { Flight } from '../../../models/flight';
import { TokenService } from '../../../services/token.service';
import { BookingService } from '../../../services/booking.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-old-bookings',
  imports: [CommonModule, FlightCardComponent],
  templateUrl: './old-bookings.component.html',
  styleUrl: './old-bookings.component.css'
})
export class OldBookingsComponent {
  @Input() oldBookings!: Flight[];

  constructor(private tokenService: TokenService, private bookingService: BookingService) { }

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

        setTimeout(() => {
          window.location.reload();
        }, 2000);
      }
    });
  }
}
