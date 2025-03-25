import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { LoaderComponent } from '../../../../shared/loader/loader.component';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Airplane } from '../../../../models/airplane';
import { City } from '../../../../models/city';
import { CityService } from '../../../../services/city.service';
import { AirplaneService } from '../../../../services/airplane.service';
import { TokenService } from '../../../../services/token.service';
import { FlightService } from '../../../../services/flight.service';
import Swal from 'sweetalert2';
import { Router } from '@angular/router';

@Component({
  selector: 'app-new-flight',
  imports: [CommonModule, LoaderComponent, ReactiveFormsModule],
  templateUrl: './new-flight.component.html',
  styleUrl: './new-flight.component.css'
})
export class NewFlightComponent {
  newFlightForm: FormGroup;
  hasLoaded: boolean = false;

  airplanesList: Airplane[] = [];
  citiesList: City[] = [];

  constructor(
    private fb: FormBuilder,
    private cityService: CityService,
    private airplaneService: AirplaneService,
    private tokenService: TokenService,
    private flightService: FlightService,
    private router: Router,
  ) {
    this.getCities();
    this.getAirplanes();

    this.newFlightForm = this.fb.group({
      date: ['', Validators.required],
      price: ['', [Validators.required, Validators.min(1)]],
      airplane_id: ['', Validators.required],
      departure_id: ['', Validators.required],
      arrival_id: ['', Validators.required],
    });
  }

  private getAirplanes() {
    const token = {
      token: this.tokenService.get(),
    }

    this.airplaneService.getAllAirplanes(token).subscribe({
      next: (rtn) => {
        this.airplanesList = rtn;
      },
      error: (error) =>{
        console.error(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    });
  }

  private getCities() {
    this.cityService.getAllCities().subscribe({
      next: (rtn) => {
        this.citiesList = rtn;
      },
      error: (error) => {
        console.error(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    });
  }

  submit() {
    if(this.newFlightForm.invalid) {
      this.newFlightForm.markAllAsTouched();
      return;
    }

    if(this.newFlightForm.valid) {
      const token = {
        token: this.tokenService.get(),
      }
      const data = {...this.newFlightForm.value, ...token};

      this.flightService.createNewFlight(data).subscribe({
        next: (rtn) => {
          Swal.fire({
            icon: "success",
            title: "Flight created successfully",
          });

          setTimeout(() => {
            this.router.navigateByUrl('/flights')
          }, 3000);
        },
        error: (error) => {
          console.error(error);
          Swal.fire({
            icon: "error",
            title: "Oh no!",
            text: error.error.message,
          });
        } 
      });
    }
  }
}
