import { Enviorment } from "/app/assets/js/env.js";

export class DashboardService {
  static async getDashboardData() {
    return await fetch(Enviorment.API_URL + "/dashboardData", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        
        return data;
      });
  }
}
