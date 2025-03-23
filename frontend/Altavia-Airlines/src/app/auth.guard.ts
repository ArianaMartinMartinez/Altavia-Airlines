import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from './services/auth.service';
import { TokenService } from './services/token.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {
  loggedIn: boolean = false;

  constructor(private authService: AuthService, private tokenService: TokenService, private router: Router) {
    this.authService.authStatus.subscribe({
      next: (rtn) => {
        this.loggedIn = rtn;
      },
      error: (error) => {
        console.error(error);
      }
    });
  }

  canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
    const requiredRole = next.data['role'];
    if (this.loggedIn) {
      const token = {
        token: this.tokenService.get(),
      }
      let role: string = 'user';
      this.authService.me(token).subscribe({
        next: (rtn) => {
          role = rtn.role;
        },
        error: (error) => {
          console.error(error);
        }
      });
      if(requiredRole && role!==requiredRole){
        return false;
      }
      return true;
    }

    this.router.navigate(['login']);
    return false;
  }
}
