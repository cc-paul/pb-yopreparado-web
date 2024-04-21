$("#btnAddFile").click(function(){
	$("#txtFile").click();
});

$("#txtFile").change(function(){
    var file_data = $('#txtFile').prop('files')[0];
    var fileInput = document.getElementById('txtFile');   
    var filename = fileInput.files[0].name;
    var fileType = filename.split('.').pop().toLowerCase();
    var form_data = new FormData();
    
    const fileSizeInMB = file_data.size / 1024 / 1024; 

	if (fileSizeInMB > 20) {
		JAlert('File size exceeds 20MB. Please choose a smaller file.','orange');
		$("#txtFile").val(null);
		
		return;
	}
    
    form_data.append('file', file_data);    
    form_data.append('filename', filename);
    form_data.append('type', fileType);
    form_data.append('command', 'upload_file');
    $.ajax({
        url: "../program_assets/php/web/faq",
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data) {
            var data = jQuery.parseJSON(data);
            
            $("#txtFile").val(null);
            JAlert(data[0].message,data[0].color);
            
            if (!data[0].error) {
                RefreshFileList();
            }
        }
    });
});

$('#txtFileSearch').on('input',function(e){
	RefreshFileList()
});


RefreshFileList();

function RefreshFileList() {
    $("#dvRowFiles").html("");
    
    $.ajax({
        url: "../program_assets/php/web/faq",
        data: {
            command   : 'list_file',
            search    : $("#txtFileSearch").val().replace("'","")
        },
        type: 'post',
        success: function (data) {
            var data = jQuery.parseJSON(data);
            var strFileList = "";
            
            $("#spTotal").text(data.length);
            
            if (data.length != 0) {
                for (var i = 0; i < data.length; i++) {
                    strFileList += `
                        <div class="col-md-2 col-xs-12">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <label class="cust-label">` + Truncatefile(data[i].filename) + `</label>
                                    <button type="submit" class="btn btn-danger btn-xs pull-right" onclick="deleteFile('`+ data[i].id +`')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="box-body">
                                    <center>
                                        <img height="100" width="100" src="../files/folders.png"/>
                                    </center>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 cust-label">
                                            <b>Type :</b> <span class="cust-label">` + data[i].type + `</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 cust-label">
                                            <b>Uploaded By :</b> <span class="cust-label">` + data[i].fullName + `</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 cust-label">
                                            <b>Date Uploaded :</b> <span>` + data[i].dateCreated + `</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <button id="btnDownloadFile" type="button" class="btn btn-block btn-default btn-sm cust-textbox" onclick="downloadFile('`+ data[i].filename +`')">
                                                <i class="fa fa-download"></i>
                                                &nbsp;
                                                Download File
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            $("#dvRowFiles").html(strFileList);
        }
    });
}

function deleteFile(id) {
	JConfirm("Are you sure you want to delete this file-orange", () => {
		$.ajax({
			url: "../program_assets/php/web/faq",
			data: {
				command : "delete_file",
				id : id
			},
			type: 'post',
			success: function (data) {
				var data = jQuery.parseJSON(data);
				
				JAlert(data[0].message,data[0].color);
				
				if (!data[0].error) {
					RefreshFileList();
				}
			}
		});
	}, () => {
		JAlert("Process has been cancelled","blue");
	});

}

function downloadFile(filename) {
    window.open(`../files/${filename}`,'_blank');
}

function Truncatefile(fileName) {
    var fileLength = 28;
    
    if (fileName.length > fileLength) {
        return fileName.substring(0, fileLength) + "...";
    }
    
    return fileName;
}