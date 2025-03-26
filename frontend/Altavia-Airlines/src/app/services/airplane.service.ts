import { Injectable } from '@angular/core';
import { environment } from '../../environment/environment';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Airplane } from '../models/airplane';

@Injectable({
  providedIn: 'root'
})
export class AirplaneService {
  private url = environment.apiUrl;

  constructor(private http: HttpClient) { }

  getAllAirplanes(token: any): Observable<Airplane[]> {
    return this.http.get<Airplane[]>(`${this.url}/airplanes`, {params: token});
  }

  getAirplaneById(id: string, token: any): Observable<any> {
    return this.http.get(`${this.url}/airplanes/${id}`, {params: token});
  }

  createNewAirplane(data: any): Observable<Airplane> {
    return this.http.post<Airplane>(`${this.url}/airplanes`, data);
  }

  editAirplane(id: string, data: any): Observable<Airplane> {
    return this.http.put<Airplane>(`${this.url}/airplanes/${id}`, data);
  }

  deleteAirplane(id: string, token: any): Observable<any> {
    return this.http.delete(`${this.url}/airplanes/${id}`, {params: token});
  }
}
