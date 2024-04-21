var isNewHotline;
var tblHotline;
var oldHotline;
var id;

loadHotline();

function loadHotline() {
    tblHotline = 
    $('#tblHotline').DataTable({
        'destroy'       : true,
        'paging'        : true,
        'lengthChange'  : false,
        'pageLength'    : 15,
        "order"         : [],
        'info'          : true,
        'autoWidth'     : false,
        'select'        : true,
        'sDom'			: 'tp<"clear">', 
        'fnRowCallback' : function( nRow, aData, iDisplayIndex ) {
            $('td', nRow).attr('nowrap','nowrap');
            return nRow;
        },
        'ajax'          : {
        	'url'       : '../program_assets/php/web/hotline.php',
        	'type'      : 'POST',
        	'data'      : {
        		command : 'display_hotline',
        	}    
        },
        'aoColumns' : [
        	{ mData: 'hotline'},
            { mData: 'mobileNumber'},
            { mData: 'telephoneNumber'},
            { mData: 'emailAddress'},
            { mData: 'status'},
            { mData: 'dateCreated'},
            { mData: 'id',
                render: function (data,type,row) {
                    return '<div class="input-group">' + 
                           '	<button id="list_' + row.id + '" name="list_' + row.id + '" type="submit" class="btn btn-default btn-xs dt-button list">' +
                           '		<i class="fa fa-edit"></i>' +
                           '	</button>' +
                           '</div>';
                }
            }
        ],
        'aoColumnDefs': [
        	{"className": "custom-center", "targets": [6]},
        	{"className": "dt-center", "targets": [0,1,2,3,4,5,6]},
        	{ "width": "1%", "targets": [4,5,6] }
        ],
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


$("#btnAddContact").click(function(){
    $("#txtHotline").val("");
    $("#txtMobileNumber").val("");
    $("#txtTelephoneNumber").val("");
    $("#txtEmailAddress").val("");
    $("#chkActive").prop("checked", true);
    $("#chkActive").prop("disabled", true);
    isNewHotline = 1;
    oldHotline = "";
    id = 0;
    
    $("#btnRemoveHotline").hide();
	$("#mdAddHotline").modal();
});

$('#tblHotline tbody').on('click', 'td button', function (){
	var data = tblHotline.row( $(this).parents('tr') ).data();
    
    oldHotline = data.hotline;
    $("#txtHotline").val(data.hotline);
    $("#txtMobileNumber").val(data.mobileNumber);
    $("#txtTelephoneNumber").val(data.telephoneNumber);
    $("#txtEmailAddress").val(data.emailAddress);
    $("#chkActive").prop('checked',data.isActive == 1 ? true : false); 
    $("#chkActive").prop("disabled",false);
    isNewHotline = 0;
    id = data.id;
    
    $("#btnRemoveHotline").show();
	$("#mdAddHotline").modal();
});


$("#btnRemoveHotline").click(function(){
	$.ajax({
        url: "../program_assets/php/web/hotline.php",
        data: {
            command : "delete_hotline",
            id : id
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            
            if (!data[0].error) {
                $("#mdAddHotline").modal("hide");
                loadHotline();
            }
        }
    });
});

$("#btnSaveHotline").click(function(){
    var hotline         = $("#txtHotline").val();
    var mobileNumber    = $("#txtMobileNumber").val();
    var telephoneNumber = $("#txtTelephoneNumber").val();
    var emailAddress    = $("#txtEmailAddress").val();
    var isActive;
    
    if ($("#chkActive").prop('checked') == true) {
        isActive = 1;
    } else {
        isActive = 0;
    }
    
    if (hotline == "" || (mobileNumber == "" && telephoneNumber == "" && emailAddress == "")) {
        JAlert("Please provide hotline name and at least 1 contact number","red");
    } else if (emailAddress != "" && !validateEmail(emailAddress)) {
        JAlert("Please provide a proper email","red");
    } else if (mobileNumber != "" && mobileNumber.length < 11) {
        JAlert("Mobile number must be 11 digits","red");
    } else if (telephoneNumber != "" && telephoneNumber.length < 10) {
        JAlert("Telephone number must be 10 digits","red");
    } else {
        $.ajax({
            url: "../program_assets/php/web/hotline.php",
            data: {
                command : "save_hotline",
                hotline : hotline,
                oldHotline : oldHotline,
                mobileNumber : mobileNumber,
                telephoneNumber : telephoneNumber,
                emailAddress : emailAddress,
                isActive : isActive,
                isNewHotline : isNewHotline,
                id : id
            },
            type: 'post',
            success: function (data) {
                var data = jQuery.parseJSON(data);
                
                JAlert(data[0].message,data[0].color);
                
                if (!data[0].error) {
                    $("#mdAddHotline").modal("hide");
                    loadHotline();
                }
            }
        });
    }
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

function numOnly(selector){
    selector.value = selector.value.replace(/[^0-9]/g,'');
}
