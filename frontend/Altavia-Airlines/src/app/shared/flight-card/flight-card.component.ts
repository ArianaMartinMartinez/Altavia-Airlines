import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Flight } from '../../models/flight';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-flight-card',
  imports: [CommonModule, RouterLink],
  templateUrl: './flight-card.component.html',
  styleUrl: './flight-card.component.css'
})
export class FlightCardComponent implements OnInit {
  @Input() flight!: Flight;
  @Input() isBooked?: boolean;
  @Input() disabled: boolean = false;
  @Input() isAdmin: boolean = false;

  @Output() flightBooked: EventEmitter<string> = new EventEmitter<string>();
  @Output() flightDeleted: EventEmitter<string> = new EventEmitter<string>();

  loggedIn: boolean = false;

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
  }

  booked(id: number) {
    this.flightBooked.emit(id.toString());
  }

  deleteFlight(id: number) {
    this.flightDeleted.emit(id.toString());
  }
}
