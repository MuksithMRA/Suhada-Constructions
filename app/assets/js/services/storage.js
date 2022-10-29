import { Enviorment } from "/app/assets/js/env.js";

export class Storage {
  static async uploadFile(formData) {
    return await fetch(Enviorment.APP + "/upload_file.php", {
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
