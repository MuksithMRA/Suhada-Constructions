import { CommonService } from "/app/assets/js/services/common_service.js";
import { Enviorment } from "/app/assets/js/env.js";
import { Employee } from "/app/assets/js/models/employee.js";

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
    CommonService.add(parent, employee);
  }
}
