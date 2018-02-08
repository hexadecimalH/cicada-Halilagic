import ThumbComponent from '../components/thumb.component';

import Vue from 'vue';


var ProjectComponent = Vue.component('new-project', {
    template:
        `<div>
            <div class="col-sm-12">
                <div class="well">
                    <div class="row">
                        <span v-for="image in project.project_pics">
                            <thumb :url="image.url"></thumb>
                        </span>

                    </div>
                </div>
                <input type="file" class="hidden" id="upload" name="pics[]" multiple />
                <div class="col-sm-6" v-if="editMode">
                    <button type="button" class="btn btn-default btn-lg btn-block" style="margin-bottom: 2%;">Upload More</button>
                </div>
                <div class="col-sm-6" v-if="editMode">
                    <button type="button" class="btn btn-default btn-lg btn-block"  v-on:click="$parent.upload" style="margin-bottom: 2%;">Set Thumbnail</button>
                </div>
                
            </div>
            <div class="col-sm-12">
                <div class="well">
                    <form>
                        <div class="form-group">
                            <p>Project Title</p>
                            <div class="well"  v-if="!editMode">
                                <p>{{title}}</p>
                            </div>
                            <input type="text" class="form-control dashboard-inputs" id="projectTitle" v-model='title' placeholder="Project Title" v-if="editMode">
                        </div>
                        <div class="form-group">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#english" aria-controls="english" role="tab" data-toggle="tab">English</a></li>
                                <li role="presentation"><a href="#serbian" aria-controls="serbian" role="tab" data-toggle="tab">Serbian</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="english">
                                    <textarea class="form-control dashboard-inputs" rows="3" style=" resize: none;" v-model="aboutEng" v-if="editMode"></textarea>
                                    <div class="well" v-if="!editMode">
                                        <p >{{aboutEng}}</p>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="serbian">
                                    <textarea class="form-control dashboard-inputs" rows="3" style=" resize: none;" v-model="about" v-if="editMode"></textarea>
                                    <div class="well" v-if="!editMode">
                                        <p >{{about}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-lg btn-block" style="margin-bottom: 2%;" v-if="editMode">Save</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-lg btn-block" style="margin-bottom: 2%;" v-if="editMode" v-on:click="editMode = false">Cancel</button>
                        </div>                        
                        <button type="button" class="btn btn-success btn-lg btn-block" style="margin-bottom: 2%;" v-if="!editMode" v-on:click="editMode = true">Edit</button>
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
            title: this.project.title,
            uploadedPics: this.project.project_pics,
            about: this.project.about,
            aboutEng: this.project.aboutenglish,
            editMode: false
        };
    },
    components:{
        ThumbComponent
    },
    methods:{
        upload:function(){
            console.log("hey hey")
        },

    }
});

export {ProjectComponent as default};