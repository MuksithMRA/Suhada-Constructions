export class Util{
    static showAlert(parent, message, type){
        if(type == 'success'){
            parent.innerHTML = `<div class="alert alert-success">${message}</div>`;
        }else if(type == 'error'){
            parent.innerHTML = `<div class="alert alert-danger">${message}</div>`;
        }else if(type == 'warning'){
            parent.innerHTML = `<div class="alert alert-warning">${message}</div>`;
        }else{
            parent.innerHTML = `<div class="alert alert-primary">${message}</div>`;
        }
    }
}