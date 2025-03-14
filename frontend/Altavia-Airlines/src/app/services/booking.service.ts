import { Injectable } from '@angular/core';
import { environment } from '../../environment/environment';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BookingService {
  private url = environment.apiUrl;

  constructor(private http: HttpClient) { }

  getUserBookings(token: any): Observable<any> {
    return this.http.get(`${this.url}/bookings`, {params: token});
  }

  bookFlight(id: number, token: Object) {
    return this.http.post(`${this.url}/book/${id}`, token);
  }

  cancelFlight(id: number, token: Object) {
    return this.http.post(`${this.url}/cancel/${id}`, token);
  }
}
