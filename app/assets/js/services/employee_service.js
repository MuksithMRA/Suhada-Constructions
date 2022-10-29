import { Enviorment } from "/app/assets/js/env.js";
import { Employee } from "/app/assets/js/models/employee.js";
import { Storage } from "/app/assets/js/services/storage.js";
import { Util } from "/app/assets/js/utils/util.js";

export class EmployeeService {
  static async getEmployees() {
    return await fetch(Enviorment.API_URL + "/employees", {
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
          let employees = [];
          data["employees"].forEach((employee) => {
            employees.push(Employee.fromJSON(employee));
          });
          return employees;
        }
        return [];
      });
  }

  static async getEmployee(id) {
    return await fetch(Enviorment.API_URL + "/employees/" + id, {
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
          return Employee.fromJSON(data["employee"]);
        }
        return null;
      });
  }

  static async addEmployee(parent, employee) {
    return await Storage.uploadFile(employee.doc).then(async (data) => {
      if (data["status"] == 200) {
        employee.doc = Enviorment.API_URL + "/docs/" + data["file_name"];
        return await fetch(Enviorment.API_URL + "/employees", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(employee),
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
