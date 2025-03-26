import { RouterLink, Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { FlightsComponent } from './pages/flights/flights.component';
import { OldFlightsComponent } from './pages/flights/old-flights/old-flights.component';
import { LoginComponent } from './pages/login/login.component';
import { RegisterComponent } from './pages/register/register.component';
import { BookingsComponent } from './pages/bookings/bookings.component';
import { AuthGuard } from './guards/auth.guard';
import { FlightDetailComponent } from './pages/flights/admin/flight-detail/flight-detail.component';
import { NewFlightComponent } from './pages/flights/admin/new-flight/new-flight.component';
import { EditFlightComponent } from './pages/flights/admin/edit-flight/edit-flight.component';
import { AirplanesComponent } from './pages/airplanes/airplanes.component';

export const routes: Routes = [
    {
        path: 'home',
        component: HomeComponent,
    },
    {
        path: 'flights',
        component: FlightsComponent,
    },
    {
        path: 'oldFlights',
        component: OldFlightsComponent,
    },
    {
        path: 'flight-detail/:id',
        component: FlightDetailComponent,
        canActivate: [AuthGuard],
        data: { role: 'admin' },
    },
    {
        path: 'new-flight',
        component: NewFlightComponent,
        canActivate: [AuthGuard],
        data: { role: 'admin' },
    },
    {
        path: 'edit-flight/:id',
        component: EditFlightComponent,
        canActivate: [AuthGuard],
        data: { role: 'admin' },
    },
    {
        path: 'login',
        component: LoginComponent,
    },
    {
        path: 'register',
        component: RegisterComponent,
    },
    {
        path: 'my-bookings',
        component: BookingsComponent,
        canActivate: [AuthGuard],
    },
    {
        path: 'airplanes',
        component: AirplanesComponent,
        canActivate: [AuthGuard],
        data: { RouterLink: 'admin' },
    },
    {
        path: '',
        redirectTo: 'home',
        pathMatch: 'full',
    },
    {
        path: '**',
        redirectTo: 'home',
        pathMatch: 'full',
    }
];
