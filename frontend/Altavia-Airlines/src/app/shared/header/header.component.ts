import { Component, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { TokenService } from '../../services/token.service';

@Component({
  selector: 'app-header',
  imports: [CommonModule, RouterLink],
  templateUrl: './header.component.html',
  styleUrl: './header.component.css'
})
export class HeaderComponent implements OnInit {
  loggedIn: boolean = false;
  isAdmin: boolean = false;

  constructor(
    private authService: AuthService,
    private tokenService: TokenService,
    private router: Router,
  ) { }

  ngOnInit(): void {
    this.authService.authStatus.subscribe({
      next: (rtn) => {
        this.loggedIn = rtn;
        if(this.loggedIn) { this.getUserRole(); }
      },
      error: (error) => {
        console.error(error);
      }
    });
  }

  logout() {
    const token = {
      "token": this.tokenService.get(),
    }
    
    this.authService.logout(token).subscribe({
      next: (rtn) => {
        this.tokenService.remove();
        this.authService.changeAuthStatus(false);
        this.isAdmin = false;
        this.router.navigateByUrl('/');
      },
      error: (error) => {
        console.error(error);
      },
    });
  }

  getUserRole() {
    const token = {
      token: this.tokenService.get(),
    }

    this.authService.me(token).subscribe({
      next: (rtn) => {
        this.isAdmin = rtn.role === 'admin';
      },
      error: (error) => {
        console.error(error);
      }
    });
  }
}
