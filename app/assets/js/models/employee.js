import { CommonService } from "/app/assets/js/services/common_service.js";

CommonService;
export class Employee {
  id = 0;
  name = "";
  designation = "";
  contact = "";
  doc = "";
  created = "";
  table = "employees";

  constructor(id, name, designation, contact, doc, created) {
    this.id = id;
    this.name = name;
    this.designation = designation;
    this.contact = contact;
    this.doc = doc;
    this.created = created;
  }

  static toJson(employee) {
    return {
      id: employee.id,
      name: employee.name,
      designation: employee.designation,
      contact: employee.contact,
      doc: employee.doc,
      created: employee.created,
    };
  }

  static fromJSON(json) {
    return new Employee(
      json.id,
      json.name,
      json.designation,
      json.contact,
      json.doc,
      json.created
    );
  }

  deleteEmployee() {
    CommonService.delete(this.id, this.table).then((res) => {
      if (res) {
        location.reload();
      }
    });
  }
  
  updateEmployee(parent) {
    let res = CommonService.update(parent, this).then((res) => {
      return res;
    });
  }
}
