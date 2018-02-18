import axios from 'axios';

export default class ApiService {

    getProjects() {
        return axios.get('/admin/projects');
    }

    createProject(project) {
        let self = this;

        let data = Object.keys(project).reduce((formData, key) => {
            if(key == "project_pics"){
                formData.append(key, project[key].map(img => img.id));
            }
            else{
                formData.append(key, project[key]);
            }
            
            return formData;
        }, new FormData());

        return axios.post('/admin/createproject', data).then(
            (response) => {
                return response;
            }
        );
    }

    uploadPictures(data) {
        let self = this;
        return axios.post('/admin/upload-pictures', data).then(
            (response) => {
                return response;
            }
        );
    }

    removeUploadedPicture(id) {
        return axios.post('/admin/delete-picture/'+ id).then(
            (response) => {
                console.log(response);
                return response;
            },
            (error) => {
                console.log(error);
                throw new XMLHttpRequestException();
            }
        );
    }


    updateProject(project){
        let self = this;

        let data = Object.keys(project).reduce((formData, key) => {
            if(key != "project_pics"){
                formData.append(key, project[key]);
                console.log(formData.get(key));
            }
            return formData;
        }, new FormData());

        return axios.post('/admin/update-project', data);
    }
}