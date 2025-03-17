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

  @Output() flightBooked: EventEmitter<string> = new EventEmitter<string>();

  loggedIn: boolean = false;
  @Input() disabled: boolean = false;

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
}
