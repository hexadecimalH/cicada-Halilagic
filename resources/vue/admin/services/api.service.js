import axios from 'axios';

export default class ApiService {

    getProjects() {
        return axios.get('/admin/projects')
    };

    createProject(project) {
        let self = this;

        let data = Object.keys(project).reduce((formData, key) => {
            formData.append(key, project[key]);
            return formData;
        }, new FormData());

        return axios.post('/admin/createproject', data).then(
            (response) => {
                console.log(response);
                // console.log(self.$parent);
                return response;
            }
        );
    };

    uploadPictures(data) {
        let self = this;
        return axios.post('/admin/upload-pictures', data).then(
            (response) => {
                return response;
            }
        );
    }

    removeUploadedPicture(data) {
        let self = this;
        var formData = new FormData();
        formData.append("url", data);
        return axios.post('/admin/delete-picture', formData).then(
            (response) => {
                return response;
            },
            (error) => {
                throw new XMLHttpRequestException();

                return error;
            }
        );
    }
}