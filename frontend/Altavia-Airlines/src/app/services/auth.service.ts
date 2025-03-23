import { inject, Injectable } from '@angular/core';
import { environment } from '../../environment/environment';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, tap } from 'rxjs';
import { TokenService } from './token.service';
import { User } from '../models/user';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private tokenService = inject(TokenService);
  private url = environment.apiUrl;

  private loggedIn = new BehaviorSubject<boolean>(this.tokenService.loggedIn());
  authStatus = this.loggedIn.asObservable();
  
  constructor(private http: HttpClient) { }

  login(form: Object): Observable<any> {
    return this.http.post(`${this.url}/auth/login`, form).pipe(
      tap((response: any) => {
        if(response.access_token) {
          this.tokenService.set(response.access_token);
          this.changeAuthStatus(true);
        }
      })
    )
  }

  logout(token: Object): Observable<any> {
    return this.http.post(`${this.url}/auth/logout`, token);
  }

  register(form: Object): Observable<any> {
    return this.http.post(`${this.url}/auth/register`, form);
  }

  me(token: Object): Observable<User> {
    return this.http.post<User>(`${this.url}/auth/me`, token);
  }

  changeAuthStatus(value: boolean) {
    this.loggedIn.next(value);
  }
}
