import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Flight } from '../../models/flight';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-flight-card',
  imports: [CommonModule],
  templateUrl: './flight-card.component.html',
  styleUrl: './flight-card.component.css'
})
export class FlightCardComponent implements OnInit {
  @Input() flight!: Flight;
  @Input() isBooked?: boolean;
  @Input() disabled: boolean = false;

  @Output() flightBooked: EventEmitter<string> = new EventEmitter<string>();

  loggedIn: boolean = false;
  isAdmin: boolean = false;

  constructor(private authService: AuthService) { }

  ngOnInit(): void {
    this.authService.authStatus.subscribe({
      next: (rtn) => {
        this.loggedIn = rtn;
      },
      error: (error) => {
        console.error(error);
      }
    });

    if(this.loggedIn) {
      this.checkIsAdmin();
    }
  }

  booked(id: number) {
    this.flightBooked.emit(id.toString());
  }

  checkIsAdmin() {
    this.isAdmin = (this.authService.getRole() === 'admin') ? true : false;
  }
}
