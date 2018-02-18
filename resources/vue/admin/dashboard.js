import ProjectComponent from './components/project.component';
import ThumbComponent from './components/thumb.component';
import ApiService from './services/api.service';
import Project from './models/project';
import {alert} from 'vue-strap';

import Vue from 'vue';
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

var app = new Vue({
    el: '#dashboard',
    components: {
        VeeValidate,
        ProjectComponent,
        ThumbComponent,
        alert
    },
    data: {
        showError: false,
        showProcessing: false,
        errorMessage: '',
        message: '',
        successMessage:'',
        success: false,
        api: new ApiService(),
        visible: true,
        projects: [],
        project: new Project(),
        uploadedPics: []

    },
    methods: {
        upload() {
            let upload = document.getElementById("upload");
            upload.click();

        },
        handlerError(error) {
            this.showError = true;
            this.errorMessage = error.message;
            let code = error.code;
            setTimeout(() => this.showError = false, 5000);
        },
        processingAlert(message) {
            this.showProcessing = true;
            this.message = message;
            // setTimeout(() => this.showProcessing = false, 5000);
        },
        showSuccess(message) {
            this.success = true;
            this.successMessage = message;
            setTimeout(() => this.success = false, 5000);
        },
        uploadImages(e) {
            console.log(this);
            let images = Array.from(e.target.files); // from FileList object to array
            let data = new FormData();
            let form = images.reduce((data, img) => {
                data.append(img.name, img);
                return data;
            }, data);
            let size = Array.from(form).reduce((sum, el) => sum += el[1].size, 0);
            let newSize = this.bytesToSize(size);
            if(size < 1000000) {
                this.processingAlert("It is uploading");
                this.api.uploadPictures(data).then( 
                    response => {
                        console.log(response);
                        this.uploadedPics = this.uploadedPics ? this.uploadedPics.concat(response.data) : [];
                        this.showProcessing = false;
                        this.showSuccess(`Succesfully document uploaded ${newSize} `);

                    }).catch(error => {
                        this.processing = false;
                        this.handlerError(error);
                        this.showProcessing = false;
                    });
            }
            else{
                this.handlerError({message:`You uploaded ${newSize} the limiti is  1MB`});
            }
        },
        getProjects() {
            this.api.getProjects().then(response => {
                console.log(response);
                this.projects = response.data;

            }).catch(error => {
                handlerError(error);
            });
        },
        clear() {
            console.log("Hellouu hellou");
        },
        bytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) return '0 Byte';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
        submit(){
            this.$validator.validateAll()
            .then((res) => {
                if(res){
                    this.createProject();
                }
                else{
                    var errorMessages = this.errors.all().join("\n");
                    this.handlerError({message:errorMessages});
                }
            });
        },
        createProject(){
            this.project.project_pics = this.uploadedPics;
            this.api.createProject(this.project).then(
                response => {
                    console.log(response);
                    // this.projects.push(response.data);
                    // this.project = new Project();
                    // this.uploadedPics = [];
                    // $("#upload").val('');
                    // this.errors.clear();
                }).catch( 
                error => {
                    var errors = JSON.parse(JSON.stringify(error.response.data));
                    console.log(errors, error);
                    errors.message = Object.keys(errors).map((el) => errors[el]).join(", ");
                    this.handlerError(errors);
                });
        }
    },
    mounted() {
        this.getProjects();
        this.showError = false;
        this.showProcessing = false;
        // this.$validator.dictionary.container.en.
        // console.log(this.$validator);
    },
    delimiters: ['${', '}']

});