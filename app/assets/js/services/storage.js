import { Enviorment } from "/app/env.js";

export class Storage {
  static async uploadFile(formData) {
    return await fetch(Enviorment.HOST + "/app/upload_file.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        return data;
      });
  }
}