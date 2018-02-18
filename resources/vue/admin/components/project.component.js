import ThumbComponent from '../components/thumb.component';
import Project from '../models/project';
import ApiService from '../services/api.service'; 
import Vue from 'vue';


var ProjectComponent = Vue.component('new-project', {
    template:
        `<div>
            <div class="col-sm-12">
                <div class="well">
                    <div class="row">
                        <span v-for="image in projectMain.project_pics">
                            <thumb :image="image"></thumb>
                        </span>

                    </div>
                </div>
                <input type="file" class="hidden" :id="upload" name="pics[]" multiple v-on:change="uploadImages"/>
                <div class="col-sm-12" v-if="editMode">
                    <button type="button" class="btn btn-default btn-lg btn-block" style="margin-bottom: 2%;" v-on:click='uploadToProject'>Upload More</button>
                </div>
                
            </div>
            <div class="col-sm-12">
                <div class="well">
                    <form>
                        <div class="form-group">
                            <p>Project Title</p>
                            <div class="well"  v-if="!editMode">
                                <p>{{projectMain.title}}</p>
                            </div>
                            <input type="text" class="form-control dashboard-inputs" id="projectTitle" v-model='projectMain.title' placeholder="Project Title" v-if="editMode" >
                        </div>
                        <div class="form-group">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" v-bind:class="{active: visible}">
                                <a  v-on:click.stop.prevent="changeTextareas(true)">
                                    English
                                </a>
                            </li>
                            <li role="presentation" v-bind:class="{active: !visible}">
                                <a  v-on:click.stop.prevent="changeTextareas(false)">
                                    Serbian
                                </a>
                            </li>

                        </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" v-bind:class="{active: visible}" v-show="visible">
                                    <textarea class="form-control dashboard-inputs" rows="3" style=" resize: none;" v-model="projectMain.aboutenglish" v-if="editMode"></textarea>
                                    <div class="well" v-if="!editMode">
                                        <p >{{projectMain.aboutenglish}}</p>
                                    </div>
                                </div>
                                <div class="tab-pane" v-bind:class="{active: !visible}" v-show="!visible">
                                    <textarea class="form-control dashboard-inputs" rows="3" style=" resize: none;" v-model="projectMain.about" v-if="editMode"></textarea>
                                    <div class="well" v-if="!editMode">
                                        <p >{{projectMain.about}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-lg btn-block" style="margin-bottom: 2%;" v-if="editMode" v-on:click="saveEdited">Save</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-lg btn-block" style="margin-bottom: 2%;" v-if="editMode" v-on:click="editModeSwitch(false)">Cancel</button>
                        </div>                        
                        <button type="button" class="btn btn-success btn-lg btn-block" style="margin-bottom: 2%;" v-if="!editMode" v-on:click="editModeSwitch(true)">Edit</button>
                    </form>
                    <div class="clearfix" style="clear:both"></div>
                </div>
            </div>
        </div>`,
    props:{
        project: {}
    },
    data:function(){
        return {
            projectMain:this.project, 
            projectBackup:new Project(),
            api: new ApiService(),        
            editMode: false,
            visible:true
        };
    },
    computed:{
        upload:{
            get(){
                return "upload-"+ this.projectMain.id;
            },
            set(){}           
        }
    },
    components:{
        ThumbComponent
    },
    methods:{
        uploadImages(e){
            let images = Array.from(e.target.files); // from FileList object to array
            let data = new FormData();
            let form = images.reduce((data, img) => {
                data.append(img.name, img);
                return data;
            }, data);
            let size = Array.from(form).reduce((sum, el) => sum += el[1].size, 0);
            let newSize = this.$parent.bytesToSize(size);
            data.append("project_id", this.projectMain.id);
            if(size < 1000000) {
                this.$parent.processingAlert("It is uploading");
                this.api.uploadPictures(data).then( 
                    response => {
                        console.log(response);
                        this.projectMain.project_pics = this.projectMain.project_pics ? this.projectMain.project_pics.concat(response.data) : [];
                        this.$parent.showProcessing = false;
                        this.$parent.showSuccess(`Succesfully document uploaded ${newSize} `);

                    }).catch(error => {
                        this.$parent.processing = false;
                        this.$parent.handlerError(error);
                        this.$parent.showProcessing = false;
                    });
            }
            else{
                this.$parent.handlerError({message:`You uploaded ${newSize} the limiti is  1MB`});
            }
        },
        handleError(message){
            console.log(message);
            this.$parent.handlerError(message);
        },
        uploadToProject:function(){
            $("#"+this.upload).click();
            
        },
        changeTextareas(bool){
            this.visible = bool;
        },
        editModeSwitch(bool){
            this.editMode = bool;
            if(bool){
                this.projectBackup = new Project(this.projectMain.title,
                                                this.projectMain.aboutenglish,
                                                this.projectMain.about,
                                                this.projectMain.project_pics);
            } else{
                this.projectMain.title = this.projectBackup.title;          
                this.projectMain.about = this.projectBackup.about;
                this.projectMain.aboutenglish = this.projectBackup.aboutenglish;
                
            }
        },
        saveEdited(){
            this.editMode = false;
            this.projectBackup = new Project();
            this.api.updateProject(this.projectMain).then(response => {
                    console.log(response);
            }).catch( 
            error => {
                var errors = JSON.parse(JSON.stringify(error.response.data));
                errors.message = Object.keys(errors).map((el) => errors[el]).join(", ");
                this.handleError(errors);
            });
        }

    }
});

export {ProjectComponent as default};