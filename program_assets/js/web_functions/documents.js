$(document).ready(function(){
    var docu = getUrlParameter("docu");
    
    if (docu == "contingency") {
        $.ajax({
            url: "../program_assets/php/web/hotline",
            data: {
                command  : 'display_contingency_plan'
            },
            type: 'post',
            success: function (data) {
                var data = jQuery.parseJSON(data);
                
                $("#dvDocContainer").html(data[0].document);
            }
        });
    } else if (docu == "drrm") {
        $.ajax({
            url: "../program_assets/php/web/hotline",
            data: {
                command  : 'display_drrm'
            },
            type: 'post',
            success: function (data) {
                var data = jQuery.parseJSON(data);
                
    
                if (data.length != 0) {
                    $("#dvDocContainer").html(data[0].document);
                }
            }
        });
    }
});


var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};