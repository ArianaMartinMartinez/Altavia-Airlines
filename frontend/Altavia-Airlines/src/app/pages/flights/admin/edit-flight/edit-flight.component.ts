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
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-edit-flight',
  imports: [CommonModule, LoaderComponent, ReactiveFormsModule],
  templateUrl: './edit-flight.component.html',
  styleUrl: './edit-flight.component.css'
})
export class EditFlightComponent {
  editFlightForm: FormGroup;
  hasLoaded: boolean = false;
  selectedFlight: any;

  airplanesList: Airplane[] = [];
  citiesList: City[] = [];
  idFlight: string = '';

  constructor(
    private fb: FormBuilder,
    private cityService: CityService,
    private airplaneService: AirplaneService,
    private tokenService: TokenService,
    private flightService: FlightService,
    private router: Router,
    private route: ActivatedRoute,
  ) {
    this.getCities();
    this.getAirplanes();

    this.editFlightForm = this.fb.group({
      date: ['', Validators.required],
      price: ['', [Validators.required, Validators.min(1)]],
      airplane_id: ['', Validators.required],
      departure_id: ['', Validators.required],
      arrival_id: ['', Validators.required],
    });

    const id = this.route.snapshot.paramMap.get('id');
    if(id) {
      this.idFlight = id;
      this.loadFlight(this.idFlight);
    }
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

  loadFlight(id: string) {
    const token = {
      token: this.tokenService.get(),
    }
    this.flightService.getFlightById(id, token).subscribe({
      next: (rtn) => {
        this.selectedFlight = rtn;
        this.editFlightForm.patchValue(this.selectedFlight);
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
    if(this.editFlightForm.invalid) {
      this.editFlightForm.markAllAsTouched();
      return;
    }

    if(this.editFlightForm.valid) {
      const token = {
        token: this.tokenService.get(),
      }
      const data = {...this.editFlightForm.value, ...token};

      this.flightService.editFlight(this.idFlight, data).subscribe({
        next: (rtn) => {
          Swal.fire({
            icon: "success",
            title: "Flight updated successfully",
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
