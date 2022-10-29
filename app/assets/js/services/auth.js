import { StorageKeys } from "/app/assets/js/utils/storage_keys.js";
import { Enviorment } from "/app/assets/js/env.js";
import { Util } from "/app/assets/js/utils/util.js";

export class Auth {
  static async login(user, parent) {
    return await fetch(Enviorment.API_URL + "/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(user),
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data["response"]["status"] == 200) {
          console.log(data);
          localStorage.setItem(StorageKeys.TOKEN, data["user"]["id"]);
          localStorage.setItem(StorageKeys.USER_TYPE, data["user"]["type"]);
          window.location.href = Enviorment.APP + "/index.html";
          return data;
        } else {
          Util.showAlert(parent, data["response"]["message"], "error");
          return data;
        }
      });
  }

  static logout() {
    localStorage.removeItem(StorageKeys.TOKEN);
    localStorage.removeItem(StorageKeys.USER);
    localStorage.removeItem(StorageKeys.USER_TYPE);
    window.location.href = Enviorment.APP + "/login.html";
  }

  static isAuthenticated() {
    return localStorage.getItem(StorageKeys.TOKEN) ? true : false;
  }
}
