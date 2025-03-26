import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { LoaderComponent } from '../../../shared/loader/loader.component';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { TokenService } from '../../../services/token.service';
import { ActivatedRoute, Router } from '@angular/router';
import { AirplaneService } from '../../../services/airplane.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-edit-airplane',
  imports: [CommonModule, LoaderComponent, ReactiveFormsModule],
  templateUrl: './edit-airplane.component.html',
  styleUrl: './edit-airplane.component.css'
})
export class EditAirplaneComponent {
  editAirplaneForm: FormGroup;
  hasLoaded: boolean = false;
  selectedAirplane: any;

  idAirplane: string = '';

  constructor(
    private fb: FormBuilder,
    private airplaneService: AirplaneService,
    private tokenService: TokenService,
    private router: Router,
    private route: ActivatedRoute,
  ) {
    this.editAirplaneForm = this.fb.group({
      name: ['', Validators.required],
      seats: ['', [Validators.required, Validators.min(1)]],
    });

    const id = route.snapshot.paramMap.get('id');
    if(id) {
      this.idAirplane = id;
      this.loadAirplane(this.idAirplane);
    }
  }

  loadAirplane(id: string) {
    const token = {
      token: this.tokenService.get(),
    }

    this.airplaneService.getAirplaneById(id, token).subscribe({
      next: (rtn) => {
        this.selectedAirplane = rtn;
        this.editAirplaneForm.patchValue(this.selectedAirplane);
      },
      error: (error) => {
        console.error(error);
      },
      complete: () => {
        this.hasLoaded = true;
      }
    });
  }

  submit() {
    if(this.editAirplaneForm.invalid) {
      this.editAirplaneForm.markAllAsTouched();
      return;
    }

    if(this.editAirplaneForm.valid) {
      const token = {
        token: this.tokenService.get(),
      }
      const data = {...this.editAirplaneForm.value, ...token};

      this.airplaneService.editAirplane(this.idAirplane, data).subscribe({
        next: (rtn) => {
          Swal.fire({
            icon: "success",
            title: "Airplane updated successfully",
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
