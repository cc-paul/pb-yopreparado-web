var tblEvent;
var tblEventHidden;
var isNewEvent;
var oldEventName = "";
var eventID = "";

loadEvents();
loadVideos("");

function loadEvents() {
    tblEvent = 
    $('#tblEvent').DataTable({
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
            className: "btn btn-default btn-sm hide btn-export-event",
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
        	'url'       : '../program_assets/php/web/event.php',
        	'type'      : 'POST',
        	'data'      : {
        		command : 'display_event',
        	}    
        },
        'aoColumns' : [
        	{ mData: 'event'},
            { mData: 'description',
                render: function (data,type,row) {
                    return truncString(row.description,110,'...');
                }
            },
            { mData: 'origin',
                render: function (data,type,row) {
                    return truncString(row.origin,110,'...');
                }
            },
            { mData: 'status'},
            { mData: 'dateCreated'},
            { mData: 'id',
                render: function (data,type,row) {
                    return '' + 
                           '<button type="submit" class="btn btn-default btn-xs dt-button list">' +
                           '	<i class="fa fa-edit"></i>' +
                           '</button>' +
                           '';
                }
            }
        ],
        'aoColumnDefs': [
        	{"className": "custom-center", "targets": [5]},
        	{"className": "dt-center", "targets": [0,1,2,3,4]},
        	{ "width": "1%", "targets": [0,1,3,4,5] }
        //	{"className" : "hide_column", "targets": [9]} 
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
    
    tblEventHidden = 
    $('#tblEventHidden').DataTable({
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
            className: "btn btn-default btn-sm hide btn-export-event-hidden",
            titleAttr: 'Export in Excel',
            text: 'Export in Excel',
            init: function(api, node, config) {
               $(node).removeClass('dt-button buttons-excel-hidden buttons-html5')
            }
        }],
        'fnRowCallback' : function( nRow, aData, iDisplayIndex ) {
            $('td', nRow).attr('nowrap','nowrap');
            return nRow;
        },
        'ajax'          : {
        	'url'       : '../program_assets/php/web/event.php',
        	'type'      : 'POST',
        	'data'      : {
        		command : 'display_event',
        	}    
        },
        'aoColumns' : [
        	{ mData: 'event'},
            { mData: 'description'},
            { mData: 'origin'},
            { mData: 'status'},
            { mData: 'dateCreated'},
            { mData: 'id',
                render: function (data,type,row) {
                    return '' + 
                           '<button type="submit" class="btn btn-default btn-xs dt-button list">' +
                           '	<i class="fa fa-edit"></i>' +
                           '</button>' +
                           '';
                }
            }
        ],
        'aoColumnDefs': [
        	{"className": "custom-center", "targets": [5]},
        	{"className": "dt-center", "targets": [0,1,2,3,4]},
        	{ "width": "1%", "targets": [0,1,3,4,5] }
        //	{"className" : "hide_column", "targets": [9]} 
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

function truncString(str, max, add){
   add = add || '...';
   return (typeof str === 'string' && str.length > max ? str.substring(0,max)+add : str);
};

$("#btnAddEvent").click(function(){
   isNewEvent = 1;
   eventID = 0;
   $("#dvImage").hide();
   $("#btnUpload").hide();
   $("#btnDosDonts").hide();
   $("#txtEvent").val("");
   $("#txtDescription").val("");
   $("#txtOrigin").val("");
   $("#chkActiveEvent").prop("checked", true);
   $("#chkAddRadius").prop("checked", false);
   $("#chkNeedDuration").prop("checked", false);
   $("#chkActiveEvent").prop("disabled", true);
   $("#mdAddEvent").modal();
});

$('#tblEvent tbody').on('click', 'td button', function (){
	var data = tblEvent.row( $(this).parents('tr') ).data();
    
    eventID = data.id;
    isNewEvent = 0;
    oldEventName = data.event;
    $("#txtEvent").val(data.event);
    $("#txtOrigin").val(data.origin);
    $("#dvImage").show();
    $("#btnUpload").show();
    $("#btnDosDonts").show();
    $("#aImage").attr("href", "https://www.flaticon.com/search?word=" + data.event.replace(" ", "%20"));
    
    if (data.hasImage != 0) {
        $('#imgEvent').attr("src", "../dist/img/" + eventID + ".png?random=" + Math.random());
    } else {
        $('#imgEvent').attr("src", "../dist/img/picture.png?random=" + Math.random());
    }
    
    $("#txtDescription").val(data.description);
    $("#chkActiveEvent").prop('checked',data.isActive == 1 ? true : false);
    $("#chkAddRadius").prop('checked',data.needRadius == 1 ? true : false);
    $("#chkNeedDuration").prop('checked',data.needDuration == 1 ? true : false); 
    $("#chkActiveEvent").prop("disabled",false);
    $("#mdAddEvent").modal();
});

//tippy('#btnUpload', {
//    content: 'Once you select an image it will automatically updated and no need to save changes',
//});

$("#btnSaveEvent").click(function(){
    var event = $("#txtEvent").val();
    var description = $("#txtDescription").val();
    var origin = $("#txtOrigin").val();
    var isActive;
    var needRadius = $("#chkAddRadius").prop('checked') == true ? 1:0;
    var needDuration = $("#chkNeedDuration").prop('checked') == true ? 1:0;
    
    if ($("#chkActiveEvent").prop('checked') == true) {
        isActive = 1;
    } else {
        isActive = 0;
    }
    
    if (event == "" || origin == "") {
        JAlert("Please fill in required fields","red");
    } else {
      $.ajax({
         url: "../program_assets/php/web/event.php",
         data: {
             command   : 'save_event',
             eventID : eventID,
             isNewEvent : isNewEvent,
             oldEventName : oldEventName,
             event  : event,
             isActive  : isActive,
             description : description,
             origin : origin,
             needRadius : needRadius,
             needDuration : needDuration
         },
         type: 'post',
         success: function (data) {
             var data = jQuery.parseJSON(data);
             
             JAlert(data[0].message,data[0].color);
                 
             if (!data[0].error) {
                 loadEvents();
                 $("#mdAddEvent").modal("hide");
             }
         }
      });
    }
});

$("#btnExportEvent").click(function(){
	$(".btn-export-event-hidden").click();
});

$('#txtSearchEvent').keyup(function(){
    tblEvent.search($(this).val()).draw();
    tblEventHidden.search($(this).val()).draw();
});

function openImage_1() {
    javascript:document.getElementById('image_uploader').click();
}

$('#image_uploader').change(function (e) {
	var file_data = $('#image_uploader').prop('files')[0];
	var form_data = new FormData();
	form_data.append('file', file_data);
   form_data.append('eventID', eventID);
	$.ajax({
	    url: '../program_assets/php/upload/event_icon.php',
	    dataType: 'text',
	    cache: false,
	    contentType: false,
	    processData: false,
	    data: form_data,
	    type: 'post',
	    success: function(data) {
            id = data.trim();
            $('#imgEvent').attr("src", "../dist/img/" + eventID + ".png?random=" + Math.random());
            loadEvents();
            
            //location.reload();
        }
	});
});

/* Videos */
var isThereAVideo;

$('#txtSearchVideo').on('input',function(e){
	loadVideos($('#txtSearchVideo').val());
});


function loadVideos(search) {
   $.ajax({
      url: "../program_assets/php/web/event.php",
      data: {
         command  : 'display_event_video',
         search   : search
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         var dataVideos = "";
         
         $("#divVidHolder").html("");
         
         for (var i = 0; i < data.length; i++) {
            var disable = data[i].isPrimary == 1 ? "disabled" : ""
            
            dataVideos += `
               <div id="dvVideo` + data[i].id + `" class="col-md-3 col-xs-12">
                  <div class="panel panel-default">
                     <div class="panel-body">
                        <div>
                           <a id="dv-n-thumb-`+ data[i].id +`" href="../videos/` + data[i].fileName + `.mp4" target="_blank">
                              <img id="img-n-thumb-`+ data[i].id +`" src="../thumbnails/empty.png" class="image-no-thumb">
                           </a>
                           <a id="dv-w-thumb-`+ data[i].id +`" href="../videos/` + data[i].fileName + `.mp4" target="_blank">
                              <img id="img-w-thumb-`+ data[i].id +`" src="../thumbnails/` + data[i].fileName + `.png?random=` + Math.random() + `" class="image-w-thumb">
                           </a>
                           <br>
                           <br>
                           <div class="row">
                              <div class="col-md-2 col-xs-3">
                                 <img src="./../profile/` + data[i].userID + `.png" class="img-circle" alt="User Image" onerror="this.onerror=null; this.src='./../profile/Default.png'" style="width: 55px; height: 53px; object-fit: cover;">
                              </div>
                              <div class="col-md-10 col-xs-9">
                                 <label class="cust-label">Title : </label>
                                 <span class="cust-label">`+ data[i].title +`</span>
                                 <br>
                                 <label class="cust-label">Uploaded By : </label>
                                 <span class="cust-label">`+ data[i].uploadedBy +`</span>
                                 <br>
                                 <label class="cust-label">Category : </label>
                                 <span class="cust-label">`+ data[i].event +`</span>
                                 <br>
                                 <label class="cust-label">Date Uploaded : </label>
                                 <span class="cust-label">`+ data[i].dateCreated +`</span>
                                 <br>
                              </div>
                              <input type="file" id="image_uploader_`+ data[i].id +`" name="image_uploader_`+ data[i].id +`" accept="image/png, image/jpeg" onchange="uploadThumb(`+ data[i].id +`,'`+ data[i].fileName +`')" style="display:none;">
                           </div>
                           <br>
                           <div class="row">
                              <div class="col-md-4 col-xs-12">
                                 <button id="btnThumbnail" type="button" class="btn btn-block btn-default btn-sm cust-textbox" onclick="openImage(`+ data[i].id +`)">
                                    <i class="fa fa-picture-o"></i>
                                    &nbsp;
                                    Thumbnail
                                 </button>
                              </div>
                              <div class="col-md-4 col-xs-12">
                                 <button id="btnPrimary${data[i].id}" type="button" class="btn btn-block btn-default btn-sm cust-textbox primary${data[i].eventID}" onclick="setPrimary(`+ data[i].id +`,`+ data[i].eventID +`)" ${disable}>
                                    <i class="fa fa-map-pin"></i>
                                    &nbsp;
                                    Pin
                                 </button>
                              </div>
                              <div class="col-md-4 col-xs-12">
                                 <button id="btnDelete" type="button" class="btn btn-block btn-default btn-sm cust-textbox" onclick="deleteVideo(`+ data[i].id +`)">
                                    <i class="fa fa-trash"></i>
                                    &nbsp;
                                    Remove
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            `;
         }
         
         $("#divVidHolder").html(dataVideos);
         
         for (var i = 0; i < data.length; i++) {
            if (data[i].hasThumbnail == 1) {
               $("#dv-w-thumb-" + data[i].id).show();
               $("#dv-n-thumb-" + data[i].id).hide();
            } else {
               $("#dv-w-thumb-" + data[i].id).hide();
               $("#dv-n-thumb-" + data[i].id).show();
            }
         }
      }
   });
}

$("#txtVideo").on("change", function () {
   const maxAllowedSize = 35 * 1024 * 1024;
   
   if(this.files[0].size > maxAllowedSize) {
      JAlert("Please upload file below 35mb","red");
      $(this).val('');
      isThereAVideo = 0;
   }
});

$("#btnUploadVideo").click(function(){
   $.ajax({
		url: "../program_assets/php/web/event.php",
		data: {
			command   : 'display_select_event'
		},
		type: 'post',
	}).done(function (data) {
		var data = jQuery.parseJSON(data);
		
		document.getElementById("cmbEvent").options.length = 0;
		
		$('#cmbEvent').append($('<option>', {
			value: null,
			text: "Please select an event",
			selected : true,
			disabled : true
		}));
		
		for (var i = 0; i < data.length; i++) {
			$('#cmbEvent').append($('<option>', {
				value: data[i].id,
				text: data[i].event
			}));
		}
      
      $("#dvProgress").css("width","0%");
      $('#txtVideoName').val("");
      $('#txtVideo').val("");
      isThereAVideo = 0;
      disableElement(false);
      
      $("#mdAddVideo").modal();
	});
});

function uploadFile() {
   isThereAVideo = 1;
}

function disableElement(isDisable) {
   $("#btnCloseVideoModal").prop("disabled", isDisable);
   $("#btnSaveVideo").prop("disabled", isDisable);
   $("#cmbEvent").prop("disabled", isDisable);
   $("#txtVideoName").prop("disabled", isDisable);
   $("#txtVideo").prop("disabled", isDisable);
}

function setPrimary(videoID,eventID) {
   $.ajax({
      url: "../program_assets/php/web/event.php",
      data: {
          command   : 'primary_video',
          id : videoID,
          categoryID : eventID
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         JAlert(data[0].message,data[0].color);
             
         if (!data[0].error) {
            $(".primary" + eventID).prop("disabled", false);
            $("#btnPrimary" + videoID).prop("disabled", true);
         }
      }
   });
}

function deleteVideo(id) {
   $.ajax({
      url: "../program_assets/php/web/event.php",
      data: {
          command   : 'delete_video',
          id : id
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         JAlert(data[0].message,data[0].color);
             
         if (!data[0].error) {
            $("#dvVideo" + id).remove();
         }
      }
   });
}

function openImage(id) {
    javascript:document.getElementById('image_uploader_' + id).click();
}

function uploadThumb(id,filename) {
   var file_data = $('#image_uploader_' + id).prop('files')[0];
	var form_data = new FormData();
	form_data.append('file', file_data);
   form_data.append('id', id);
   form_data.append('filename', filename);
	$.ajax({
      url: '../program_assets/php/upload/upload_thumb.php',
      dataType: 'text',
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      type: 'post',
      success: function(data) {
         var data = jQuery.parseJSON(data);
         
         JAlert(data[0].message,data[0].color);
         
         if (!data[0].error) {
            $("#image_uploader_" + id).val(null);
            $('#img-w-thumb-' + id).attr("src", "../thumbnails/" + filename + ".png?random=" + Math.random());
            $('#dv-w-thumb-' + id).show();
            $('#dv-n-thumb-' + id).hide();
         }
      }
	});
}

$("#btnSaveVideo").click(function(){
   var eventID   = $('#cmbEvent').val();
   var videoName = $('#txtVideoName').val();
   
   if (eventID == null || videoName == "" || isThereAVideo == 0) {
      JAlert("Please fill in required fields","red");
   } else {
      var file_data = $('#txtVideo').prop('files')[0];
      var form_data = new FormData();
      form_data.append('file', file_data);
      form_data.append('eventID', eventID);
      form_data.append('videoName', videoName);
      $.ajax({
         url: '../program_assets/php/upload/upload_video.php',
         dataType: 'text',
         cache: false,
         contentType: false,
         processData: false,
         data: form_data,
         beforeSend: function(){
            //$("#file-progress-bar").width('0%');
            console.log(0);
            $("#dvProgress").css("width","0%");
            disableElement(true);
         },
         xhr: function() {
            var xhr = new window.XMLHttpRequest();         
            xhr.upload.addEventListener("progress", function(element) {
               if (element.lengthComputable) {
                  var percentComplete = ((element.loaded / element.total) * 100);
                  //$("#file-progress-bar").width(percentComplete + '%');
                  //$("#file-progress-bar").html(percentComplete+'%');
                  
                  console.log(percentComplete);
                  $("#dvProgress").css("width",percentComplete + "%");
               }
            }, false);
            return xhr;
         },
         type: 'post',
         success: function(data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            
            if (data[0].error) {
               $("#dvProgress").css("width","0%");
               disableElement(false);
            } else {
               $("#mdAddVideo").modal("hide");
               
               if ($("#txtSearchVideo").val() == "") {
                  loadVideos("");
               }
            }
         },
         error: function(xhr, textStatus, error){
            $("#dvProgress").css("width","0%");
            disableElement(true);
            JAlert("Unable to upload. The file is too big","red");
         }
      });
   }
});

/* Dos and Donts */
var isNewDosDonts;
var doID;
var oldIsDoId;
var oldIsDo;
var oldDetails;

$('#txtSearchDosAndDonts').keyup(function(){
    tblDosDontsList.search($(this).val()).draw();
});

$("#btnDosDonts").click(function(){
   $("#mdDosList").modal();
   $("#mdAddEvent").modal("hide");
   $("#cmbEventDoList").val(eventID).trigger("change");
   $("#txtSearchDosAndDonts").val("");
   loadDosDonts();
});

$("#btnNewDosDonts").click(function(){
   $("#cmbDosDonts").val(null).trigger("change");
   $("#cmbDosDontsCategory").val(null).trigger("change");
   $("#cmbEventDo").val(eventID).trigger("change");
   $("#txtDetails").val("");
   isNewDosDonts = 1;
   doID = 0;
   
   oldIsDoId = 0;
   oldIsDo = 0;
   oldDetails = "";
   
   $("#mdDosList").modal("hide");
   $("#mdDoAndDont").modal();
});

$("#btnSaveDo").click(function(){
   var isDo     = $("#cmbDosDonts").val();
   var category = $("#cmbDosDontsCategory").val();
   var details  = $("#txtDetails").val();
   
   if (isDo == null || details == "" || category == null) {
      JAlert("Please fill in required fields","red");
   } else {
      $.ajax({
         url: "../program_assets/php/web/event.php",
         data: {
            command : 'save_dosdonts',
            eventID : eventID,
            isDo    : isDo,
            details : details,
            doId    : doID,
            isNewDosDonts : isNewDosDonts,
            oldIsDoId : oldIsDoId,
            oldIsDo : oldIsDo,
            oldDetails : oldDetails,
            category : category
         },
         type: 'post',
         success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            
            if (!data[0].error) {
               $("#mdDoAndDont").modal("hide");
               $("#mdDosList").modal();
               $("#txtSearchDosAndDonts").val("");
               loadDosDonts();
            }
         }
      });
   }
});

$('#tblDosDontsList tbody').on('click', '.delete', function (){
	var data = tblDosDontsList.row( $(this).parents('tr') ).data();
   
   $.ajax({
      url: "../program_assets/php/web/event.php",
      data: {
         command : 'remove_dosdonts',
         id : data.id
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         JAlert(data[0].message,data[0].color);
         
         if (!data[0].error) {
            loadDosDonts();
            tblDosDontsList.search($("#txtSearchDosAndDonts").val()).draw();
         }
      }
   });
});

$('#tblDosDontsList tbody').on('click', '.edit', function (){
	var data = tblDosDontsList.row( $(this).parents('tr') ).data();
   
   $("#cmbDosDonts").val(data.isDo).trigger("change");
   $("#cmbDosDontsCategory").val(data.category).trigger("change");
   $("#cmbEventDo").val(eventID).trigger("change");
   $("#txtDetails").val(data.details);
   isNewDosDonts = 0;
   doID = data.id;
   oldIsDoId = data.id;
   oldIsDo = data.isDo;
   oldDetails = data.details;
   
   $("#mdDosList").modal("hide");
   $("#mdDoAndDont").modal();
});

function loadDosDonts() {
   tblDosDontsList = 
   $('#tblDosDontsList').DataTable({
      'destroy'       : true,
      'paging'        : true,
      'lengthChange'  : false,
      'pageLength'    : 8,
      "order"         : [],
      'info'          : true,
      'autoWidth'     : false,
      'select'        : true,
      'sDom'			: 'Btp<"clear">',
      //dom: 'Bfrtip',
      buttons: [{
         extend: "excel",
         className: "btn btn-default btn-sm hide btn-export-admin",
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
      'ajax'         : {
      	'url'       : "../program_assets/php/web/event.php",
      	'type'      : 'POST',
      	'data'      : {
      		command : 'load_dosdonts',
            eventID : eventID
      	}    
      },
      'aoColumns' : [
      	{ mData: 'id',
            render: function (data,type,row) {
               var isDo = row.isDo == 1 ? `<i style="color:green;" class="fa fa-fw fa-check"></i>` : `<i style="color:red;" class="fa fa-fw fa-times"></i>`;
               
               return `
                  <span style="white-space:normal;">`+ isDo + "&nbsp;&nbsp;&nbsp;" + row.details +`</span>
               `;
            }
         },
         { mData: 'category'},
         { mData: 'id',
            //render: function (data,type,row) {
            //    return '<div class="input-group">' + 
            //           '	<button id="list_' + row.id + '" name="list_' + row.id + '" type="submit" class="btn btn-default btn-xs dt-button list">' +
            //           '		<i class="fa fa-trash"></i>' +
            //           '	</button>' +
            //           '</div>';
            //}
            
             render: function (data,type,row) {
                return `
                     <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm delete"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-default btn-sm edit"><i class="fa fa-edit"></i></button>
                     </div>
                `;
            }
         }
      ],
      'aoColumnDefs': [
      //	{"className": "custom-center", "targets": [8]},
      	{"className": "dt-center", "targets": [0,1,2]},
         { "width": "1%", "targets": [1] },
      	{ "width": "13%", "targets": [2] }
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
