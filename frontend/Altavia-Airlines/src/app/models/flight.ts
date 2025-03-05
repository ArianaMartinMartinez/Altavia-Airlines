import { Airplane } from "./airplane";
import { City } from "./city";

export interface Flight {
    id: number;
    date: Date;
    price: number;
    airplane: Airplane;
    departure: City;
    arrival: City;
}
