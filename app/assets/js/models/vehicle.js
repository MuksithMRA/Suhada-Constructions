import { CommonService } from "/app/assets/js/services/common_service.js";

export class Vehicle {
  vehicle_no = "";
  vehicle_class = "";
  vehicle_owner = "";
  model = "";
  license_issued = "";
  license_expiry = "";
  license_number = 0;
  doc = "";
  created = "";

  constructor(
    vehicle_no,
    vehicle_class,
    vehicle_owner,
    model,
    license_issued,
    license_expiry,
    license_number,
    doc,
    created
  ) {
    this.vehicle_no = vehicle_no;
    this.vehicle_class = vehicle_class;
    this.vehicle_owner = vehicle_owner;
    this.model = model;
    this.license_issued = license_issued;
    this.license_expiry = license_expiry;
    this.license_number = license_number;
    this.doc = doc;
    this.created = created;
  }

  static fromJSON(json) {
    return new Vehicle(
      json.vehicle_no,
      json.vehicle_class,
      json.vehicle_owner,
      json.model,
      json.license_issued,
      json.license_expiry,
      json.license_number,
      json.doc,
      json.created
    );
  }

  static toJson(vehicle) {
    return {
      vehicle_no: vehicle.vehicle_no,
      vehicle_class: vehicle.vehicle_class,
      vehicle_owner: vehicle.vehicle_owner,
      model: vehicle.model,
      license_issued: vehicle.license_issued,
      license_expiry: vehicle.license_expiry,
      license_number: vehicle.license_number,
      doc: vehicle.doc,
      created: vehicle.created,
    };
  }

  deleteVehicle() {
    let res = CommonService.delete(this.vehicle_no, "vehicles").then((res) => {
      return res;
    });

    console.log(res);
  }
  3;

  updateVehicle() {
    console.log("update");
  }
}
