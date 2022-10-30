import { Enviorment } from "/app/assets/js/env.js";
import { Finance } from "/app/assets/js/models/finance.js";
import { Storage } from "/app/assets/js/services/storage.js";
import { Util } from "/app/assets/js/utils/util.js";

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

  static async addFinance(parent, finance) {
    return await Storage.uploadFile(finance.doc).then(async (data) => {
      if (data["status"] == 200) {
        finance.doc = Enviorment.API_URL + "/docs/" + data["file_name"];
        return await fetch(Enviorment.API_URL + "/finance", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(finance),
        })
          .then((response) => {
            return response.json();
          })
          .then((data) => {
            if (data["response"]["status"] == 201) {
              Util.showAlert(parent, data["response"]["message"], "success");
              return true;
            }
            Util.showAlert(parent, data["response"]["message"], "error");
            return false;
          });
      } else {
        Util.showAlert(parent, data["message"], "warning");
        return false;
      }
    });
  }
}
