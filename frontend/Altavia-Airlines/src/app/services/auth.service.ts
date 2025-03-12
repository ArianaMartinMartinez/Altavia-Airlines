import { inject, Injectable } from '@angular/core';
import { environment } from '../../environment/environment';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { TokenService } from './token.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private tokenService = inject(TokenService);
  private url = environment.apiUrl;

  private loggedIn = new BehaviorSubject<boolean>(this.tokenService.loggedIn());
  authStatus = this.loggedIn.asObservable();
  
  constructor(private http: HttpClient) { }

  login(form: any): Observable<any> {
    return this.http.post(`${this.url}/auth/login`, form);
  }

  logout(token: Object): Observable<any> {
    return this.http.post(`${this.url}/auth/logout`, token);
  }

  changeAuthStatus(value: boolean) {
    this.loggedIn.next(value);
  }
}
