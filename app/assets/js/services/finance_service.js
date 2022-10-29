import { Enviorment } from "/app/assets/js/env.js";
import { Finance } from "/app/assets/js/models/finance.js";

export class FinanceService {
  static async getAllFinance() {
    return await fetch(Enviorment.API_URL + "/finance", {
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
          let finances = [];
          data["finances"].forEach((finance) => {
            finances.push(Finance.fromJSON(finance));
          });
          return finances;
        }
        return [];
      });
  }
}
