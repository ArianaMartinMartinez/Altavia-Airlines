import { Component, EventEmitter, Output } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, ReactiveFormsModule, ValidationErrors, Validators } from '@angular/forms';
import { CityService } from '../../services/city.service';
import { FlightService } from '../../services/flight.service';
import { City } from '../../models/city';
import { CommonModule } from '@angular/common';
import { Flight } from '../../models/flight';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-flights-filter',
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './flights-filter.component.html',
  styleUrl: './flights-filter.component.css'
})
export class FlightsFilterComponent {
  @Output() filteredFlightsEvent = new EventEmitter<Flight[]>();
  filterForm: FormGroup;
  citiesList: City[] = [];
  filteredFlights: Flight[] = [];

  hasLoaded: boolean = false;
  page: string = '';

  constructor(
    private fb: FormBuilder,
    private cityService: CityService,
    private flightService: FlightService,
    private route: ActivatedRoute,
  ) {
    this.getCities();

    this.page = this.route.snapshot.url[0].path;
    this.filterForm = this.fb.group({
      departure: [''],
      arrival: [''],
      date: ['', this.dateValidator(this.page)],
    });
  }

  private getCities() {
    this.cityService.getAllCities().subscribe({
      next: (rtn) => {
        this.citiesList = rtn;
      },
      error: (error) => {
        console.log(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    })
  }

  dateValidator(page: string) {
    return (control: AbstractControl): ValidationErrors | null => {
      const date = new Date(control.value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
  
      if (page === 'flights' && date < today) {
        return { invalidDate: true };
      }
      if (page === 'oldFlights' && date >= today) {
        return { invalidDate: true };
      }
  
      return null;
    };
  }

  submit() {
    if(this.filterForm.invalid) {
      this.filterForm.markAllAsTouched();
      return;
    }

    if(this.filterForm.valid) {
      const filters: any = {};

      if(this.filterForm.value.departure) {
        filters['departure_id'] = this.filterForm.value.departure;
      }
      if(this.filterForm.value.arrival) {
        filters['arrival_id'] = this.filterForm.value.arrival;
      }
      if(this.filterForm.value.date) {
        filters['date'] = this.filterForm.value.date;
      }

      if(this.page === 'flights') {
        this.flightService.getFutureFilteredFlights(filters).subscribe({
          next: (rtn) => {
            this.filteredFlights = rtn;
            this.filteredFlightsEvent.emit(this.filteredFlights);
          },
          error: (error) => {
            console.log(error);
          },
          complete: () => {
            this.hasLoaded = true;
          }
        });
      }

      if(this.page === 'oldFlights') {
        this.flightService.getPastFilteredFlights(filters).subscribe({
          next: (rtn) => {
            this.filteredFlights = rtn;
            this.filteredFlightsEvent.emit(this.filteredFlights);
          },
          error: (error) => {
            console.log(error);
          },
          complete: () => {
            this.hasLoaded = true;
          }
        });
      }
    }
  }
}
