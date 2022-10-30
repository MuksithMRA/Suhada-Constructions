import { Enviorment } from "/app/assets/js/env.js";
import { Vehicle } from "/app/assets/js/models/vehicle.js";

export class VehicleService {
  static async getAllVehicle() {
    return await fetch(Enviorment.API_URL + "/vehicles", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data["response"]["status"] == 200) {
          let vehicles = [];
          data["vehicles"].forEach((vehicle) => {
            vehicles.push(Vehicle.fromJSON(vehicle));
          });
          return vehicles;
        }
        return [];
      });
  }
}
