import { Enviorment } from "/app/assets/js/env.js";
import { Project } from "/app/assets/js/models/project.js";
import { CommonService } from "/app/assets/js/services/common_service.js";

export class ProjectService {
  static async getAllProject() {
    return await fetch(Enviorment.API_URL + "/projects", {
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
          let projects = [];
          data["projects"].forEach((project) => {
            projects.push(Project.fromJSON(project));
          });
          return projects;
        }
        return [];
      });
  }

  static async addProject(parent, project) {
    return CommonService.add(parent, project);
  }
}
