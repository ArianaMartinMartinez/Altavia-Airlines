import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class TokenService {
  constructor() { }

  handle(token: string) {
    this.set(token);
    return this.loggedIn();
  }

  set(token: string) {
    localStorage.setItem('token', token);
  }

  get() {
    return localStorage.getItem('token');
  }

  remove() {
    localStorage.removeItem('token');
  }

  isValid() {
    const token = this.get();
    if(token) {
      const payload = this.payload(token);

      if(payload) {
        const isIssuerValid = (payload.iss === 'http://127.0.0.1:8000/api/auth/login') ? true : false;
        const isNotExpired = payload.exp > Math.floor(Date.now() / 1000);

        return isIssuerValid && isNotExpired;
      }
    }

    return false;
  }

  payload(token: string) {
    const payload = token.split('.')[1];
    return this.decode(payload);
  }

  decode(payload: any) {
    return JSON.parse(atob(payload));
  }

  loggedIn() {
    return this.isValid();
  }
}
