var tblNotif;
var selectedEventID = 0;

$("#btnAddNotification").click(function(){
	showNotifModal();   
});

$("#btnSendNotification").click(function(){
    var title = $("#txtTitle").val();
    var description = $("#txtDescription").val();
    
    if (title == "" || description == "" || selectedEventID == 0) {
        JAlert("Please fill in all required fields","red");
    } else {
        var eventData = {
            "event_id" : selectedEventID,
            "title" : $("#txtTitle").val(),
            "body" : $("#txtDescription").val()
        };
        
        $.ajax({
            url: "../../../../yopreparado_api/v1/fcm",
            data: JSON.stringify(eventData),
            contentType: 'application/json',
            type: 'post',
            success: function (data) {
                var data = jQuery.parseJSON(data);
                
                saveNotification();
            }
        });
    }
});

function saveNotification() {
    $.ajax({
        url: "../program_assets/php/web/advisories",
        data: {
            command : 'save_notif',
            eventID : selectedEventID,
            title   : $("#txtTitle").val(),
            body    : $("#txtDescription").val()
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            
            if (!data[0].error) {
                $("#mdNotification").modal("hide");
                loadNotif();
            }
        }
    });
}

function showNotifModal() {
    $("#mdNotification").modal();
    selectedEventID = 0;
    
    $("#txtTitle").val(null);
	$("#txtDescription").val(null);
    
    $.ajax({
        url: "../program_assets/php/web/advisories",
        data: {
            command   : 'load_events',
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            var content = "";
            
            $("#dvEvents").html(content);
            
            for (var i = 0; i < data.length; i++) {
                var description = data[i].description.length > 100 ? data[i].description.substring(0, 95) + '...' : data[i].description + "<br><br>";
                var imageID = "no-picture-taking";
                
                if (data[i].hasImage != 0) {
                    imageID = data[i].id
                }
                
                content += `
                    <div class="row" onclick="checkThis(${data[i].id},'${data[i].event}')">
                        <div class="col-md-12" style="cursor: pointer;">
                            <div class="attachment-block clearfix" style="border-radius: 9px;">
                                <img style="height:67px;" class="attachment-img" src="../dist/img/${imageID}.png" alt="Attachment Image">
                                <div class="attachment-pushed" style="margin-left: 80px">
                                    <h4 class="attachment-heading cust-label"><a href="#">${data[i].event}</a></h4>
                                    <div class="attachment-text cust-label">
                                        ${description}
                                    </div>
                                    <input id="chkEvent${data[i].id}" type="checkbox" class="event-check pull-right" style="margin: 3px; pointer-events: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            $("#dvEvents").html(content);
        }
    });
}

loadNotif();

$('#txtSearchNotification').keyup(function(){
    tblNotif.search($(this).val()).draw();
});

$("#btnExportNotification").click(function(){
	$(".btn-export-notif").click();
});

function loadNotif() {
    tblNotif = 
    $('#tblNotif').DataTable({
        'destroy'       : true,
        'paging'        : true,
        'lengthChange'  : false,
        'pageLength'    : 15,
        "order"         : [],
        'info'          : true,
        'autoWidth'     : false,
        'select'        : true,
        'sDom'			: 'Btp<"clear">',
        //dom: 'Bfrtip',
        buttons: [{
            extend: "excel",
            className: "btn btn-default btn-sm hide btn-export-notif",
            titleAttr: 'Export in Excel',
            text: 'Export in Excel',
            init: function(api, node, config) {
               $(node).removeClass('dt-button buttons-excel buttons-html5')
            }
        }],
        'fnRowCallback' : function( nRow, aData, iDisplayIndex ) {
            $('td', nRow).attr('nowrap','nowrap');
            return nRow;
        },
        'ajax'          : {
        	'url'       : '../program_assets/php/web/advisories.php',
        	'type'      : 'POST',
        	'data'      : {
        		command : 'show_notif',
        	}    
        },
        'aoColumns' : [
        	{ mData: 'event'},
            { mData: 'title'},
			{ mData: 'body',
                render: function (data,type,row) {
                    return '<span style="white-space:normal">' + row.body + "</span>";
                }
            },
            { mData: 'dateCreated'},
            { mData: 'fullName'}
        ],
        //'aoColumnDefs': [
        //	{"className": "custom-center", "targets": [8]},
        //	{"className": "dt-center", "targets": [0,1,2,3,4,5,6,7]},
        //	{ "width": "1%", "targets": [8] },
        //	{"className" : "hide_column", "targets": [9]} 
        //],
        "drawCallback": function() {  
            row_count = this.fnSettings().fnRecordsTotal();
        },
        //"fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        //	console.log(aData["status"]);
        //	
        //	if (aData["status"] == "Pending") {
        //		count_pending++;
        //	} else if (aData["status"] == "Approved") {
        //		count_approved++;
        //	} else {
        //		count_rejected++;
        //	}
        //},
        "fnInitComplete": function (oSettings, json) {
            console.log('DataTables has finished its initialisation.');
        }
    }).on('user-select', function (e, dt, type, cell, originalEvent) {
        if ($(cell.node()).parent().hasClass('selected')) {
            e.preventDefault();
        }
    });
}

function checkThis(id,event) {
    selectedEventID = id;
    
    $("#txtTitle").val(`${event} : `);
    $("#txtDescription").val("");
    $('.event-check').attr('checked', false);
    $(`#chkEvent${id}`).attr('checked', true);
}