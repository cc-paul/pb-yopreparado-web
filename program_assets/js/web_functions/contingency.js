var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['blockquote', 'code-block'],
    
    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    [{ 'direction': 'rtl' }],                         // text direction
    
    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    
    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['link', 'image', 'video'],
    ['clean']                                         // remove formatting button
];

var options = {
    debug: 'info',
    modules: {
        imageResize: {
            displaySize: true
        },
        toolbar: toolbarOptions
    },
    placeholder: 'Create your contingency plan here...',
    readOnly: false,
    theme: 'snow'
};

var options_drrm = {
    debug: 'info',
    modules: {
        imageResize: {
            displaySize: true
        },
        toolbar: toolbarOptions
    },
    placeholder: 'Create your DRRM plan here...',
    readOnly: false,
    theme: 'snow'
};


var editor = new Quill('.editor', options);
var editor_drrm = new Quill('.editor_drrm', options_drrm);

$("#btnSaveChanges").click(function(){
    $.ajax({
        url: "../program_assets/php/web/hotline",
        data: {
            command  : 'contingency_plan',
            content  : editor.root.innerHTML
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
        }
    });
});

$("#btnSaveChanges_drrm").click(function(){
    $.ajax({
        url: "../program_assets/php/web/hotline",
        data: {
            command  : 'drrm',
            content  : editor_drrm.root.innerHTML
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
        }
    });
});

$(document).ready(function(){
    $.ajax({
        url: "../program_assets/php/web/hotline",
        data: {
            command  : 'display_contingency_plan'
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            

            if (data.length != 0) {
                editor.root.innerHTML = data[0].document;
            }
        }
    });
    
    $.ajax({
        url: "../program_assets/php/web/hotline",
        data: {
            command  : 'display_drrm'
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            

            if (data.length != 0) {
                editor_drrm.root.innerHTML = data[0].document;
            }
        }
    });
});