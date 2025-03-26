import { Component } from '@angular/core';
import { Airplane } from '../../models/airplane';
import { AirplaneService } from '../../services/airplane.service';
import { TokenService } from '../../services/token.service';
import { CommonModule } from '@angular/common';
import { LoaderComponent } from '../../shared/loader/loader.component';
import { AirplaneCardComponent } from '../../shared/airplane-card/airplane-card.component';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-airplanes',
  imports: [CommonModule, LoaderComponent, AirplaneCardComponent, RouterLink],
  templateUrl: './airplanes.component.html',
  styleUrl: './airplanes.component.css'
})
export class AirplanesComponent {
  airplanesList: Airplane[] = [];
  hasLoaded: boolean = false;

  constructor(
    private airplaneService: AirplaneService,
    private tokenService: TokenService,
  ) {
    this.getAirplanes();
  }

  getAirplanes() {
    const token = {
      token: this.tokenService.get(),
    }

    this.airplaneService.getAllAirplanes(token).subscribe({
      next: (rtn) => {
        this.airplanesList = rtn;
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
