export default class Project {
    constructor(title = "", eng = "", srb = "", pics = []){
        this.title = title;
        this.aboutenglish = eng;  
        this.about = srb;
        this.project_pics = pics;
    }
}