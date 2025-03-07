import { Injectable } from '@angular/core';
import { Flight } from '../models/flight';
import { HttpClient, HttpContext, HttpHeaders } from '@angular/common/http';
import { environment } from '../../environment/environment';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class FlightService {
  private url = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getAllFutureFlights(): Observable<Flight[]> {
    return this.http.get<Flight[]>(`${this.url}/flightsFuture`);
  }

  getFutureFilteredFlights(filters: any): Observable<any> {
    return this.http.get(`${this.url}/filterFutureFlights`, {params: filters});
  }
}
