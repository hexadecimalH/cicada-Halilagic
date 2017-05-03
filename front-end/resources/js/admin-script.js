// function that shows editing tools
function toEditMode($e){
    $e.preventDefault();
    toggleTextarea($e.target);
    hidePallete($e.target);
}
//function for hiding toggling buttons shown/hidden hidden/shown
function hidePallete(button){
    var buttons = $(button).siblings('.hidden');
    $(buttons).removeClass('hidden');
    $(button).addClass('hidden');
}

function toggleTextarea(button){
    var form = $(button).parent().parent();
    var p = $(form).find('p').first();
    $(p).toggleClass('hidden');
    $(p).next().toggleClass('hidden');
}

//function for cancel button without saving changes
function cancelChanges($e){
    $e.preventDefault();
    var text =  $($e.target).parent().prev().prev().text();
    $($e.target).parent().prev().val(text);
    var notHiddenButtons = $($e.target).parent().find('button').not('.hidden');
    hidePallete(notHiddenButtons);
    toggleTextarea($e.target);
}

// function saving changes of an edited text 
function saveChanges($e){
    $e.preventDefault();
    var button = $e.target;
    var text = $($e.target).parent().prev().val();
    var data = new FormData();
    data.append('text',text);
    var projId = $(button).data('id');  
    $.ajax({
        url: "/update-about/"+projId,
        type: 'POST',
        data: data,
        cache: false,
        contentType: false,
        processData: false
    }).done(function(data){
        console.log(data);
    });
    var notHiddenButtons = $(button).parent().find('button').not('.hidden');
    hidePallete(notHiddenButtons);
    var form = $(button).parent().parent();
    var p = $(form).find('p').first();
    $(p).text(text);
    toggleTextarea(button);
}


function sendRequest($picId, button){
    $.post('/delete/'+$picId)
        .done(function(data){
            thumb = $(button).parent().parent().parent().parent();
            $(thumb).fadeOut();
        });
}
//function for deleting Project picture
$(document).on('click', 'a.delete', function($e){ 
    $e.preventDefault();
    var projectId = $(this).data('proj-id');
    var picId = $(this).data('pic-id')
    sendRequest(picId, this);
} );
//function for adding new Project Pic
$(document).on('click', 'button.add', function(event){
    var title = $(this).data('title');
    projectId = $(this).data('proj-id');
    $('.modal-title').text(title);
    $("#myModal").modal('show');
});
$('#createProject').click(function(){
    var title = $('#title').val();
    var about = $('#about').val();
    var data = new FormData();
    data.append('title', title);
    data.append('about', about);
     $.ajax({
        url: "/project",
        type: 'POST',
        data: data,
        contentType: false,
        processData: false
    }).done(function(data){
        projectId = data.id;
        createPannel(data);
        $('#myModal').modal('show');
    });
});
var projectId = null;
//function for trigering upload input with bootstrap button
$(document).on('click', '.upload', function(event){
    event.preventDefault();
    $('#upload').click();
});
$('.upload-project').click(function(event){
    event.preventDefault();
    $('#upload-project').click();
});

$('#upload').change(function(event){
    var names = [];
    var data = new FormData();
    for (var i = 0; i < $(this).get(0).files.length; ++i) {
        names.push($(this).get(0).files[i].name);
        data.append('pic-'+i, $(this).get(0).files[i]);
    }
    $('.upload-span').text(names.join(', '));
    var form = $(this).parent().parent().parent();
    var action = $(form).attr('action');
     $.ajax({
        url: "/upload/"+projectId,
        type: 'POST',
        data: data,
        cache: false,
        contentType: false,
        processData: false
    }).done(function(data){
        images = data;
        createThumbs(data);
    });
});
function createThumbs(data){
    $.each(data, function(i, image){
        var container = createThumbnail(image);
        $('#modal-panel').append(container);
    });
}

function createThumbnail(image){
    var container = $('<div>', {'class':'col-sm-2'})
    var thumb = $('<div>', {'class':'thumbnail'});
    var img = $('<img>');
    $(img).attr('src',image.url);
    $(thumb).append(img);
    $(container).append(thumb);
    return container;
}
function createThumbnailInPannel(image, pannel){
    var container = $('<div>', {'class':'col-sm-2'})
    var thumb = $('<div>', {'class':'thumbnail'});
    var img = $('<img>');
    $(img).attr('src',image.url);
    var caption = $('<div>', {'class':'caption'})
    var paragh = $('<p>');
    var deleteBtn = $('<a>', {'class': 'btn btn-danger delete','data-proj-id':image.project_id, 'data-pic-id': image.id,'text':'Delete', 'href':'#','role':'button'});
    $(paragh).append(deleteBtn);
    $(caption).append(paragh);
    $(thumb).append(img,caption);
    $(container).append(thumb);
    $(pannel).append(container);
}
var images;

$('#myModal').on('hidden.bs.modal', function () {
    if(images != undefined){
        var pannel = $('.row[data-container-pic='+images[0].project_id+']');
        $.each(images, function(i, image){
            createThumbnailInPannel(image, pannel);
        });
        $('#modal-panel').empty();
        $('.upload-span').empty();
    }
});

function cancelUpload(){
    if(images != undefined){
        var data = new FormData();
        $.each(images, function(i, image){
            data.append('pic-'+i, JSON.stringify(image));
        });
        $.ajax({
            url: "/cancel",
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false
        }).done(function(data){
            console.log(data);
            images = undefined;
            $('#modal-panel').empty();
            $('.upload-span').empty();
        });
    }
    else{
        $('#modal-panel').empty();
        $('.upload-span').empty();
    }
}

function createPannel(project){
    var pannel = "<div class='panel panel-default'> <div class='panel-heading'> <h3> <button class='btn btn-danger' id='delete' data-proj-id='' data-title=''>Delete</button> <span class='headline'> </span> <button class='btn btn-success add' data-proj-id='' data-title=''>Add</button> </h3> </div> <div class='panel-body'> <div class='row' data-container-pic=''> </div> </div> <div class='panel-footer'> <form action=''> <p id='about'></p> <textarea name='about' class='hidden' id='about-textarea'></textarea> <p class='edit'> <button class='btn btn-success' onclick='toEditMode(event)'> Edit</button> <button class='btn btn-success hidden' onclick='saveChanges(event)' data-id=''> Save</button> <button class='btn btn-primary hidden' onclick='cancelChanges(event)'> Cancel</button> </p> </form> </div> </div>";
    var html = $.parseHTML(pannel);
    var projId = $(html).find("[data-proj-id]");
    $(projId).attr('data-proj-id',project.id);
    var title = $(html).find("[data-title]");
    $(title).attr('data-title',project.title);
    var dataContainerPic = $(html).find("[data-container-pic]");
    $(dataContainerPic).attr('data-container-pic',project.id);
    var dataId = $(html).find("[data-id]");
    $(dataId).attr('data-id',project.id);
    var p = $(html).find("#about");
    $(p).text(project.about);
    var area = $(html).find("#about-textarea");
    $(area).val(project.about);
    var span = $(html).find(".headline");
    $(span).text(project.title);
    $("#proj-container").append(html);

}
$(document).on('click','#delete',function(e){
    e.stopPropagation();
    var button = e.target;
    var projId = $(button).attr('data-proj-id');
    $.post('/project/'+projId).done(function(){
        var pannel = $(button).parent().parent().parent();
        $(pannel).fadeOut();
    });
});
window.onmousewheel = document.onmousewheel = null
window.ontouchmove = null 
window.onwheel = null 