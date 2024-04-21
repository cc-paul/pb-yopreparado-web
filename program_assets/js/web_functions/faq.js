var tblFAQ;
var oldQuestion;
var faqID;

loadFAQ();

$('#txtSearchFAQ').keyup(function(){
    tblFAQ.search($(this).val()).draw() ;
});

$("#btnAddFAQ").click(function(){
    $("#txtQuestion").val("");
    $("#txtAnswer").val("");
	$("#mdFAQ").modal();
    
    oldQuestion = "";
    faqID = 0;
});

$('#tblFAQ tbody').on('click', '.edit', function (){
	var data = tblFAQ.row( $(this).parents('tr') ).data();
    
    $("#txtQuestion").val(data.question);
    $("#txtAnswer").val(data.answer);
	$("#mdFAQ").modal();
    
    oldQuestion = data.question;
    faqID = data.id;
});

$('#tblFAQ tbody').on('click', '.delete', function (){
	var data = tblFAQ.row( $(this).parents('tr') ).data();
    
    $.ajax({
        url: "../program_assets/php/web/faq",
        data: {
            command      : 'delete_faq',
            faqID        : data.id
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            if (!data[0].error) {
                loadFAQ();
            }
        }
    });
});

$("#btnExportFAQ").click(function(){
	$(".btn-export-faq").click();
});

$("#btnSaveFAQ").click(function(){
    var question = $("#txtQuestion").val();
    var answer = $("#txtAnswer").val();
    
    if (question == "" || answer == "") {
        JAlert("Please fill in required fields","red");
    } else{
        $.ajax({
            url: "../program_assets/php/web/faq",
            data: {
                command      : 'save_faq',
                faqID        : faqID,
                oldQuestion  : oldQuestion.replaceAll("'",""),
                question     : question.replaceAll("'",""),
                answer       : answer.replaceAll("'","")
            },
            type: 'post',
            success: function (data) {
                var data = jQuery.parseJSON(data);
                
                JAlert(data[0].message,data[0].color);
                if (!data[0].error) {
                    loadFAQ();
                    $("#mdFAQ").modal("hide");
                }
            }
        });
    }
});

function loadFAQ() {
    tblFAQ = 
    $('#tblFAQ').DataTable({
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
            className: "btn btn-default btn-sm hide btn-export-faq",
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
        	'url'       : '../program_assets/php/web/faq.php',
        	'type'      : 'POST',
        	'data'      : {
        		command : 'load_faq'
        	}    
        },
        'aoColumns' : [
        	{ mData: 'question',
                render: function (data,type,row) {
                    return '<span style="white-space:normal">' + row.question + "</span>";
                }
            },
            { mData: 'answer',
                render: function (data,type,row) {
                    return '<span style="white-space:normal">' + row.answer + "</span>";
                }
            },
            { mData: 'dateCreated'},
            { mData: 'fullName'},
            { mData: 'id',
                render: function (data,type,row) {
                    return '<div class="input-group">' +
                           '	<button id="edit_' + row.id + '" name="list_' + row.id + '" type="submit" class="btn btn-default btn-xs dt-button edit">' +
                           '		<i class="fa fa-edit"></i>' +
                           '	</button>' +
                           '    &nbsp;&nbsp;' + 
                           '	<button id="list_' + row.id + '" name="list_' + row.id + '" type="submit" class="btn btn-default btn-xs dt-button delete">' +
                           '		<i class="fa fa-trash"></i>' +
                           '	</button>' +
                           '</div>';
                }
            }
        ],
        'aoColumnDefs': [
        	{"className": "custom-center", "targets": [4]},
        	//{"className": "dt-center", "targets": [0,1,2,3]},
        	{ "width": "1%", "targets": [2,3,4] },
        //	{"className" : "hide_column", "targets": [9]} 
        ],
        "drawCallback": function() {  
            row_count = this.fnSettings().fnRecordsTotal();
        },
        "fnInitComplete": function (oSettings, json) {
            console.log('DataTables has finished its initialisation.');
        }
    }).on('user-select', function (e, dt, type, cell, originalEvent) {
        if ($(cell.node()).parent().hasClass('selected')) {
            e.preventDefault();
        }
    });
}