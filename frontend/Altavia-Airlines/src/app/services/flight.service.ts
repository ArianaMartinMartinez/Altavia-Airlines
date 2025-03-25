import { Injectable } from '@angular/core';
import { Flight } from '../models/flight';
import { HttpClient } from '@angular/common/http';
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

  getAllPastFlights(): Observable<Flight[]> {
    return this.http.get<Flight[]>(`${this.url}/flightsPast`);
  }

  getFutureFilteredFlights(filters: any): Observable<any> {
    return this.http.get(`${this.url}/filterFutureFlights`, {params: filters});
  }

  getPastFilteredFlights(filters: any): Observable<any> {
    return this.http.get(`${this.url}/filterPastFlights`, {params: filters});
  }

  getFlightById(id: string, token: any): Observable<any> {
    return this.http.get(`${this.url}/flights/${id}`, {params: token});
  }

  createNewFlight(data: any): Observable<Flight> {
    return this.http.post<Flight>(`${this.url}/flights`, data);
  }

  editFlight(id: string, data: any): Observable<Flight> {
    return this.http.put<Flight>(`${this.url}/flights/${id}`, data);
  }

  deleteFlight(id: string, token: any): Observable<any> {
    return this.http.delete(`${this.url}/flights/${id}`, {params: token});
  }
}
