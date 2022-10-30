import { CommonService } from "/app/assets/js/services/common_service.js";
export class Project {
  id = 0;
  project_name = "";
  category = "";
  location = "";
  doc = "";
  created = "";
  table = "projects";

  constructor(id, project_name, category, location, doc, created) {
    this.id = id;
    this.project_name = project_name;
    this.category = category;
    this.location = location;
    this.doc = doc;
    this.created = created;
  }

  static toJson(project) {
    return {
      id: project.id,
      project_name: project.project_name,
      category: project.category,
      location: project.location,
      doc: project.doc,
      created: project.created,
    };
  }

  static fromJSON(json) {
    return new Project(
      json.id,
      json.project_name,
      json.category,
      json.location,
      json.doc,
      json.created
    );
  }

  deleteProject() {
    let res = CommonService.delete(this.id, "projects").then((res) => {
      return res;
    });

    console.log(res);
  }

  updateProject(parent) {
    let res = CommonService.update(parent, this).then((res) => {
      return res;
    });
  }
}
