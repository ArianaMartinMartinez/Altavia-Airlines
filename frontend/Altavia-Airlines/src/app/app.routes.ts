import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { FlightsComponent } from './pages/flights/flights.component';
import { OldFlightsComponent } from './pages/old-flights/old-flights.component';
import { LoginComponent } from './pages/login/login.component';

export const routes: Routes = [
    {
        path: '',
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
        path: 'login',
        component: LoginComponent,
    }
];
