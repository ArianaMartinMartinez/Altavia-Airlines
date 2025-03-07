import { Airplane } from "./airplane";
import { City } from "./city";

export interface Flight {
    id: number;
    date: Date;
    price: number;
    users_count: number;
    airplane: Airplane;
    departure: City;
    arrival: City;
}
