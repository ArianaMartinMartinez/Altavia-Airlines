import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { LoaderComponent } from '../../../shared/loader/loader.component';
import { TokenService } from '../../../services/token.service';
import { Router } from '@angular/router';
import { AirplaneService } from '../../../services/airplane.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-new-airplane',
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './new-airplane.component.html',
  styleUrl: './new-airplane.component.css'
})
export class NewAirplaneComponent {
  newAirplaneForm: FormGroup;

  constructor(
    private fb: FormBuilder,
    private tokenService: TokenService,
    private airplaneService: AirplaneService,
    private router: Router,
  ) {
    this.newAirplaneForm = this.fb.group({
      name: ['', Validators.required],
      seats: ['', [Validators.required, Validators.min(1)]],
    });
  }

  submit() {
    if(this.newAirplaneForm.invalid) {
      this.newAirplaneForm.markAllAsTouched();
      return;
    }

    if(this.newAirplaneForm.valid) {
      const token = {
        token: this.tokenService.get(),
      }
      const data = {...this.newAirplaneForm.value, ...token};

      this.airplaneService.createNewAirplane(data).subscribe({
        next: (rtn) => {
          Swal.fire({
            icon: "success",
            title: "Airplane created successfully",
          });

          setTimeout(() => {
            this.router.navigateByUrl('/airplanes')
          }, 3000);
        },
        error: (error) => {
          console.error(error);
          Swal.fire({
            icon: "error",
            title: "Oh no!",
            text: error.error.message,
          });
        } 
      });
    }
  }
}
