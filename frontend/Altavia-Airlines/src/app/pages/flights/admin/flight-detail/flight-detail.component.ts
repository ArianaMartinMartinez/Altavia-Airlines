import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { FlightService } from '../../../../services/flight.service';
import { TokenService } from '../../../../services/token.service';
import { Flight } from '../../../../models/flight';
import { CommonModule } from '@angular/common';
import { LoaderComponent } from '../../../../shared/loader/loader.component';

@Component({
  selector: 'app-flight-detail',
  imports: [CommonModule, LoaderComponent],
  templateUrl: './flight-detail.component.html',
  styleUrl: './flight-detail.component.css'
})
export class FlightDetailComponent implements OnInit {
  flight!: any;
  hasLoaded: boolean = false;

  constructor(
    private route: ActivatedRoute,
    private flightService: FlightService,
    private tokenService: TokenService
  ) { }

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');

    if(id) {
      this.loadFlightData(id);
    }
  }

  loadFlightData(id: string) {
    const token = {
      token : this.tokenService.get(),
    }
    this.flightService.getFlightById(id, token).subscribe({
      next: (rtn) => {
        this.flight = rtn;
      },
      error: (error) => {
        console.error(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    });
  }
}
