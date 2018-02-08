import ApiService from '../services/api.service';
import Vue from 'vue';


var ThumbComponent = Vue.component('thumb', {
    template:
        `<a class="thumbnail" v-on:click="() => false ">
            <img v-bind:src="url" alt="...">
            <i class="fa fa-times-circle close" aria-hidden="true" v-on:click="clear"></i>
        </a>`,
    props:{
        url: ""

    },
    data:function(){
        return {
            alternative: "",
            hidden:true,
            api: new ApiService(),
        };
    },

    methods:{
        clear(){
            // this.api.removeUploadedPicture()
            // clear is removing 2 same pictures if they are uploaded
            // currently that is edge case and I will warn customer
            // TODO: implement deleting picture from server otherwise it will pile up and create a mess
            // console.log(this.url);
            this.api.removeUploadedPicture(this.url).then( response => {
                console.log(response);
            }).catch(error => {
                console.log(error);
            });
            this.$parent.project.uploadedPics = this.$parent.project.uploadedPics.filter((el) => el == this.url ? false : true);
            this.$parent.project.pics = [];
        }
    }
});

export {ThumbComponent as default};