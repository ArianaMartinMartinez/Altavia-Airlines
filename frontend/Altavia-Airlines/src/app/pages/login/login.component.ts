import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { TokenService } from '../../services/token.service';
import { Router, RouterLink } from '@angular/router';
import { LoaderComponent } from '../../shared/loader/loader.component';

@Component({
  selector: 'app-login',
  imports: [CommonModule, ReactiveFormsModule, RouterLink, LoaderComponent],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  loginForm: FormGroup;
  hasLoaded: boolean = true;

  error = null;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private tokenService: TokenService,
    private router: Router,
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
    });
  }

  submit() {
    if(this.loginForm.invalid) {
      this.loginForm.markAllAsTouched();
      return;
    }

    this.hasLoaded = false;
    this.authService.login(this.loginForm.value).subscribe({
      next: (rtn) => {
        const validToken = this.tokenService.handle(rtn.access_token);

        if(validToken) {
          this.authService.changeAuthStatus(true);

          this.authService.me({token: rtn.access_token}).subscribe({
            next: (rtn) => {
              this.router.navigateByUrl('/');
            },
            error: (error) => {
              console.error(error);
            },
          });
        }
      },
      error: (error) => {
        console.error(error);
        this.hasLoaded = true;
        this.handleError(error);
      },
    });
  }

  handleError(error: any) {
    this.error = error.error.error;
  }
}
