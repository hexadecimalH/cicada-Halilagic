var $headings = $(".head");
//Slider function
function slide(side){
    clearInterval(sliderInterval);
    var $headings = $(".head");
    var index = 0;
    $.each($headings,function(i,val){
        var className = $(val).attr("class");
        var max = $(".head").length;
        if(!className.includes('hidden')){
            if(!(i == max - 1)){
                index = (side.includes('right'))? i + 1 : (i == 0) ? max - 2  : i - 1;
            }
            $(val).fadeOut();
            $(val).addClass('hidden');
        }
    });
    $($headings[index]).fadeIn();
    $($headings[index]).removeClass('hidden');
    sliderInterval = window.setInterval(function(){slide("right")},3000);
}
// new Tab opening function
function newTabOpen(address){
    window.open(address);
}
//project photo change 
function changeProjPhoto(){
    var photos = $(".proj");
    var max = photos.length;
    var index = 0;
    $.each(photos,function(i,val){
        var className = $(val).attr("class");
        if(!className.includes('hidden')){
            if(!(i == max - 1)){
                index = i + 1;
            }
            $(val).fadeOut(1500);
            $(val).addClass('hidden');
        }
    });
    $(photos[index]).fadeIn(1000);
    $(photos[index]).removeClass('hidden');
    $(photos[index]).css("display","block");
    $(photos[index]).css("margin","auto");
}
//smooth scroll function
$(function() {
  $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});
// show hide menu bar function
function showMenuBar(){
    var display = $('.hiddenBar').css('display');
    (display == 'block')? $('.hiddenBar').slideUp() : $('.hiddenBar').slideDown();
}

//function for printing message after sending PHPMailer
if($('#hiddenMessage').text() == "Your e-mail has been succesfully sent. We will contact you as soon as possible. "){
    $('#myModal').modal('show');
    $('#modalTxt').text($('#hiddenMessage').text());   
    $('#titleModal').text('Thank you for contacting us');
    $('#titleDivModal').css('background-color','#22394C');
}
else if($('#hiddenMessage').text() == "We are sorry! There must be some technical issues, please try contacting us again or directly to our e-mail address <b>info@hcg.rs</b>"){
    $('#myModal').modal('show');
    $('#modalTxt').text($('#hiddenMessage').text());   
    $('#titleModal').text('Thank you for contacting us');
    $('#titleDivModal').css('background-color','red');
}
// setting intervals 
var projectPhotoInterval = window.setInterval(function(){changeProjPhoto()},5000);
var sliderInterval = window.setInterval(function(){slide("right")},3000);

// setting the first visible
$(document).ready(function(){
    var firstPic = $('.proj').first();
    $(firstPic).removeClass('hidden');
});