import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Flight } from '../../models/flight';
import { AuthService } from '../../services/auth.service';
import { ActivatedRoute } from '@angular/router';
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
  page: string = '';
  disabled: boolean = false;

  constructor(
    private authService: AuthService,
    private route: ActivatedRoute,
  ) {
    this.page = this.route.snapshot.url[0].path;
  }

  ngOnInit(): void {
    this.disabled = this.page === 'oldFlights' ? true : false;
    
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
