import { Enviorment } from "/app/assets/js/env.js";

export class CommonService {
  static async delete(id, table) {
    return await fetch(Enviorment.API_URL + "/" + table + "/" + id, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data["status"] == 200) {
          return true;
        }
        return false;
      });
  }
}
