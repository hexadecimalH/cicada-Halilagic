$('#exampleModal').on('show.bs.modal', function (event) {
    var modal = $(this)
    var button = $(event.relatedTarget)
    var title = button.data('title') 

    modal.find('.mainPic').attr('src','img/projects/interior/');
    var imgSrc = modal.find('.mainPic').attr('src') + button.data('project');
    modal.find('.mainPic').attr('src',imgSrc+"1.jpg")
    
    var modalHeadline = modal.find('.modal-title').html(); 
    modal.find('.modal-title').html = modalHeadline;
    modal.find('.title').text(title);
    modal.find('#right').attr('onclick','changePic('+ button.data('length')+',"right")');
    modal.find('#left').attr('onclick','changePic('+ button.data('length')+',"left")');
});
function changePic(num,side){
    var mainPic = $('.mainPic');
    var urlString = $(mainPic).attr('src');
    var numOfPic = parseInt(urlString.substring(urlString.length-5,urlString.length-4));
    var urlTemplate = urlString.substring(0,urlString.length - 5);
    var nextPic = 1;
    if(numOfPic <= num + 1){
        var nextPic = (side == "right") ? (numOfPic == num + 1 ) ? 1 : numOfPic + 1  : (numOfPic == 1 ) ? num + 1 : numOfPic -1;
    }
    $(mainPic).attr('src',urlTemplate +nextPic + ".jpg" );
}
function newTabOpen(address){
    window.open(address);
}
function showMenuBar(){
    var display = $('.hiddenBar').css('display');
    (display == 'block')? $('.hiddenBar').slideUp() : $('.hiddenBar').slideDown();
    // $('.hiddenBar').css('display', (display == 'block')? 'none':'block');
    
}