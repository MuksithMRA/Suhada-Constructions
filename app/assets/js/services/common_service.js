import { Enviorment } from "/app/assets/js/env.js";
import { Storage } from "/app/assets/js/services/storage.js";
import { Util } from "/app/assets/js/utils/util.js";

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

  static async add(parent, dataObject) {
    return await Storage.uploadFile(dataObject.doc).then(async (data) => {
      if (data["status"] == 200) {
        dataObject.doc = Enviorment.API_URL + "/docs/" + data["file_name"];
        return await fetch(Enviorment.API_URL + "/" + dataObject.table, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(dataObject),
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

  static async update(parent, dataObject) {
    console.log(JSON.stringify(dataObject));
    return await fetch(Enviorment.API_URL + "/" + dataObject.table, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(dataObject),
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data["response"]["status"] == 200) {
          Util.showAlert(parent, data["response"]["message"], "success");
          return true;
        }
        Util.showAlert(parent, data["response"]["message"], "error");
        return false;
      });
  }
}
