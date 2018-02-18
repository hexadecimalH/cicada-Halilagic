import ApiService from '../services/api.service';
import Vue from 'vue';


var ThumbComponent = Vue.component('thumb', {
    template:
        `<a class="thumbnail" v-on:click="() => false ">
            <img v-bind:src="image.url" alt="...">
            <i class="fa fa-times-circle close" aria-hidden="true" v-on:click="clear(image.id)"></i>
        </a>`,
    props:{
        image: {}
    },
    data:function(){
        return {
            alternative: "",
            hidden:true,
            api: new ApiService(),
            imageId: this.image.id
        };
    },

    methods:{
        clear(id){

            this.api.removeUploadedPicture(id)
                .then( response => {
                    this.$parent.projectMain.project_pics = this.$parent.projectMain.project_pics.filter(pic => pic.id != id);
                }).catch( error => {
                    this.handleError(error);
                });
        },
        handleError(message){
            this.$parent.$parent.handlerError(message);
        }
    }
});

export {ThumbComponent as default};