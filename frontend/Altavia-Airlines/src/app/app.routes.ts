import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { FlightsComponent } from './pages/flights/flights.component';
import { OldFlightsComponent } from './pages/old-flights/old-flights.component';
import { LoginComponent } from './pages/login/login.component';
import { RegisterComponent } from './pages/register/register.component';
import { BookingsComponent } from './pages/bookings/bookings.component';
import { AuthGuard } from './auth.guard';

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
    /*{
        path: 'flight-details',
        component: ,
        canActivate: [AuthGuard],
        data: { role: 'admin' },
    },*/
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
