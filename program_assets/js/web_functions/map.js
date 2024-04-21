var marker;
var myCircle;
var arrEvents = [];
var eventMarkers = [];
var eventRadius = [];
var selectedEventID = 0;
var selectedLat = 0;
var selectedLng = 0;
var isAdd = 0;
var tblBarangay;
var selectNeedRadius = 0;
var selectNeedDuration = 0;
var brgyCommand = "";

mapboxgl.accessToken = 'pk.eyJ1IjoicGFnZW50ZSIsImEiOiJjbDc2eW52NTIwcDBlM3hrYWh0MWx2dnM2In0.k5b0sazc7NwabRj1SLiNAA';
const map = new mapboxgl.Map({
   container: 'map',
   style: 'mapbox://styles/mapbox/streets-v12',
   center: [120.8970, 14.4791],
   zoom: 15.8,
   projection: 'globe', // display the map as a 3D globe
   pitch: 50, // pitch in degrees
   bearing: -30, // bearing in degrees
   maxZoom: 20
});

map.addControl(
    new MapboxGeocoder({
      accessToken: mapboxgl.accessToken,
      mapboxgl: mapboxgl
    })
);

map.addControl(new mapboxgl.NavigationControl());

loadBarangayTable();
loadBrgyDD();

map.on('style.load', function() {
    
   map.on('click', function(e) {
      var coordinates = e.lngLat;
      latLong = coordinates.lat + "," + coordinates.lng;
      selectedLat = coordinates.lat;
      selectedLng = coordinates.lng;
      console.log(coordinates);
      
      if (isAdd == 1) {
         addMarker(coordinates.lat,coordinates.lng);
         ResetField();
         
         $(".checkEvent").prop("disabled", false);
         $("#txtRemarks").prop("disabled", false);
         $("#cmbBarangay").prop("disabled", false);
         $("#cmbAlertLevel").prop("disabled", false);
         $("#cmbPassableVehicle").prop("disabled", false);
         $("#cmbMonth").prop("disabled", false);
         $("#cmbDay").prop("disabled", false);
         $("#cmbHour").prop("disabled", false);
         $("#cmbMinute").prop("disabled", false);
         $("#btnAddEvent").prop("disabled", false);
      }
   });
   
   const layers = map.getStyle().layers;
   const labelLayerId = layers.find(
       (layer) => layer.type === 'symbol' && layer.layout['text-field']
   ).id;
   
   map.addLayer({
         'id': 'add-3d-buildings',
         'source': 'composite',
         'source-layer': 'building',
         'filter': ['==', 'extrude', 'true'],
         'type': 'fill-extrusion',
         'minzoom': 15,
         'paint': {
            'fill-extrusion-color': '#aaa',

            // Use an 'interpolate' expression to
            // add a smooth transition effect to
            // the buildings as the user zooms in.
            'fill-extrusion-height': [
               'interpolate',
               ['linear'],
               ['zoom'],
               15,
               0,
               15.05,
               ['get', 'height']
            ],
            'fill-extrusion-base': [
               'interpolate',
               ['linear'],
               ['zoom'],
               15,
               0,
               15.05,
               ['get', 'min_height']
            ],
            'fill-extrusion-opacity': 0.6
         }
      },
      labelLayerId
   );
   
   $.ajax({
      url: "../program_assets/php/web/map",
      data: {
         command : 'display_event',
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         for (var i = 0; i < data.length; i++) {
            arrEvents.push({
               'id'    : data[i].id,
               'event' : data[i].event,
               'needRadius' : data[i].needRadius
            });
         }
         
         console.log(arrEvents);
      }
   });
   
   loadDisasterMarker();
   loadLabels();
});

function loadDisasterMarker() {
   $.ajax({
      url: "../program_assets/php/web/map",
      data: {
         command : 'load_disaster',
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         try {
            if (eventMarkers!==null) {
               for (var i = eventMarkers.length - 1; i >= 0; i--) {
                   eventMarkers[i].remove();
               }
            }
         } catch(e) {
         }
         
         try {
            if (eventRadius!==null) {
               for (var i = eventRadius.length - 1; i >= 0; i--) {
                   eventRadius[i].remove();
               }
            }
         } catch(e) {
            
         }
         
         for (var i = 0; i < data.length; i++) {
            const el = document.createElement('div');
            el.className = 'marker';
            el.style.backgroundImage = "url(../dist/img/" + data[i].eventID + ".png)";
            el.style.width = `40px`;
            el.style.height = `40px`;
            el.style.backgroundSize = '100%';
            
            const sign_marker = document.createElement('div');
            sign_marker.id = 'marker';
            
            var hidden = data[i].radius == 0 ? 'hidden' : '';
            var hideDuration = data[i].needDuration == 0 ? 'hidden' : '';
            
            const popup = new mapboxgl.Popup({ offset: 25,closeButton: true}).setHTML(`
               <div class="row">
                  <div class="col-md-12">
                     <h4><b>` + data[i].event + `</b></h4>
                  </div>
               </div>
               <div class="row" `+ hidden +`>
                  <div class="col-md-12">
                     <label class="cust-label">Radius in Meters: </label>
                     <span class="cust-label">`+ data[i].radius +`</span>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <label class="cust-label">Remarks: </label>
                     <span class="cust-label">`+ data[i].remarks +`</span>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <label class="cust-label">Alert Level: </label>
                     <span class="cust-label">`+ data[i].alertLevel +`</span>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <label class="cust-label">Passable Vehicle: </label>
                     <span class="cust-label">`+ data[i].passableVehicle +`</span>
                  </div>
               </div>
               <div class="row" `+ hideDuration +`>
                  <div class="col-md-12">
                     <label class="cust-label">Display Duration: </label>
                     <span class="cust-label">`+ formatDuration(data[i].dateSeconds) +`</span>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <label class="cust-label">Barangay: </label>
                     <span class="cust-label">`+ data[i].barangayName +`</span>
                  </div>
               </div>
               <br>
               <div class="row">
                  <div class="col-md-12">
                      <button id="btnDelete" type="button" class="btn btn-block btn-danger btn-sm cust-textbox" onclick="deleteMarker(` + data[i].id + `)">
                          <i class="fa fa-trash"></i>
                          &nbsp;
                          Delete Event
                      </button>
                  </div>
              </div>
            `);
                
            // create DOM element for the marker
            const popup_destination = document.createElement('div');
            popup_destination.id = 'marker';
            
            if (isAdd) {
               disasterMarker = new mapboxgl.Marker(el)
               .setLngLat([data[i].lng,data[i].lat])
               .addTo(map);
            } else {
               disasterMarker = new mapboxgl.Marker(el)
               .setPopup(popup)
               .setLngLat([data[i].lng,data[i].lat])
               .addTo(map);
            }
            
            if (data[i].radius != 0) {
               console.log(data[i].radius);
               
               disasterRadius = new MapboxCircle({lat: +data[i].lat, lng: +data[i].lng}, data[i].radius, {
                  editable: false,
                  minRadius: 0,
                  fillColor: '#29AB87'
               }).addTo(map);
               
               eventRadius.push(disasterRadius);
            }
           
            eventMarkers.push(disasterMarker);
         }
      }
   });
}

function formatDuration(seconds) {
    // Average number of seconds in a month (30 days * 24 hours * 60 minutes * 60 seconds)
    const secondsInMonth = 30 * 24 * 60 * 60;

    // Calculate months, days, hours, and minutes
    const months = Math.floor(seconds / secondsInMonth);
    const remainingSeconds = seconds % secondsInMonth;
    const days = Math.floor(remainingSeconds / (24 * 60 * 60));
    const hours = Math.floor((remainingSeconds % (24 * 60 * 60)) / (60 * 60));
    const minutes = Math.floor((remainingSeconds % (60 * 60)) / 60);

    // Format the duration
    const formattedDuration = `${months} months, ${days} days, ${hours} hours, ${minutes} minutes`;

    return formattedDuration;
}


function loadBrgyDD() {
    $.ajax({
      url: "../program_assets/php/web/map",
      data: {
         command   : 'load_brgy_dd'
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         for (var i = 0; i < data.length; i++) {
            $("#cmbBarangay").append($('<option>', {
               value: data[i].id,
               text: data[i].barangayName
            }));
         }
      }
   });
}


function loadLabels() {
   
   $.ajax({
      url: "../program_assets/php/web/map",
      data: {
         command : 'load_barangay',
      },
      type: 'post',
      success: function (data) {
         //var data = jQuery.parseJSON(data); 
         
         map.addSource('places', {
            'type': 'geojson',
            'data': data
         });
             
         map.addLayer({
            'id': 'poi-labels',
            'type': 'symbol',
            'source': 'places',
            'layout': {
               'text-field': ['get', 'description'],
               'text-variable-anchor': ['top', 'bottom', 'left', 'right'],
               'text-radial-offset': 0.5,
               'text-justify': 'auto',
               'icon-image': ['concat', ['get', 'icon'], '-15'],
               'text-size': 12
            },
            //layout: {
            //   "icon-image": "../dist/img/12.png",
            //},
            'paint': {
               "text-color": "#900C3F"
            }
         });
      }
   });
}



function loadBarangayTable() {
   tblBarangay = 
   $('#tblBarangay').DataTable({
      'destroy'       : true,
      'paging'        : false,
      "order"         : [],
      'info'          : true,
      'autoWidth'     : true,
      'select'        : true,
      'sDom'			: 'tp<"clear">', 
      'fnRowCallback' : function( nRow, aData, iDisplayIndex ) {
         $('td', nRow).attr('nowrap','nowrap');
         return nRow;
      },
      'ajax'          : {
      	'url'       : '../program_assets/php/web/map.php',
      	'type'      : 'POST',
      	'data'      : {
      		command : 'load_barangay_table',
      	}    
      },
      'aoColumns' : [
      	{ mData: 'barangayName'},
         { mData: 'id',
            render: function (data,type,row) {
                return '<div class="input-group">' + 
                       '	<button id="list_' + row.id + '" name="list_' + row.id + '" type="submit" class="btn btn-default btn-xs dt-button list">' +
                       '		<i class="fa fa-trash"></i>' +
                       '	</button>' +
                       '</div>';
            }
         }
      ],
      'aoColumnDefs': [
      	{"className": "custom-center", "targets": [1]},
         {"className": "dt-center", "targets": [0]},
      	{ "width": "1%", "targets": [1] },
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

function loadBrgy() {
   tblBarangay.columns.adjust().draw();
}

$('#tblBarangay tbody').on('click', 'td', function (){
	var data = tblBarangay.row( $(this).parents('tr') ).data();
	
	map.flyTo({
      center: [data.lng, data.lat],
      essential: true 
   });
});

$('#txtSearchBarangay').keyup(function(){
    tblBarangay.search($(this).val()).draw() ;
});

$('#tblBarangay tbody').on('click', 'td button', function (){
	var data = tblBarangay.row( $(this).parents('tr') ).data();
   
   $.ajax({
      url: "../program_assets/php/web/map",
      data: {
         command : 'delete_barangay',
         id      : data.id
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         if (!data[0].error) {
            map.removeLayer("poi-labels");
            map.removeSource("places");
            loadLabels();
            loadBarangayTable();
         }
      }
   });
});

function deleteMarker(id) {
   $.ajax({
      url: "../program_assets/php/web/map",
      data: {
         command : 'delete_event',
         id      : id
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         
         if (!data[0].error) {
            loadDisasterMarker();
         }
      }
   });
}

function addMarker(lat,lng) {
    var selectionEvent = "";
    selectedEventID = 0;
    
    if (marker) {
      marker.remove();
    }
    
    try {
      if (myCircle) {
         myCircle.remove();
      }
    } catch(e) {
    }
    
    //for (var i = 0; i < arrEvents.length; i++) {
    //    selectionEvent += `
    //        <div class="col-md-6">
    //            <div class="checkbox">
    //                <label style="display: flex; margin-left: -19px;" onclick="choseEvent(` + arrEvents[i].id + `,` + arrEvents[i].needRadius + `)">
    //                    <input id="chkAdd` + arrEvents[i].id + `" type="radio" name="group1">
    //                    &nbsp;
    //                    &nbsp;
    //                    <img id="imgEvent" class="img-responsive" src="../dist/img/` + arrEvents[i].id + `.png" style="width:20px">
    //                    &nbsp;
    //                    &nbsp;
    //                    ` + arrEvents[i].event + `
    //                </label>
    //            </div>
    //        </div>
    //    `;
    //}
    
    //Removed from the popup below
    //<li class=""><a href="#barangay" data-toggle="tab" aria-expanded="false" class="cust-label">Barangay</a></li>
    
    //const popup = new mapboxgl.Popup({ offset: 25,closeButton: true}).setHTML(`
    //     <div class="nav-tabs-custom" style="box-shadow: 0 0px 0px rgb(0 0 0 / 10%);">
    //        <ul class="nav nav-tabs">
    //           <li class="active"><a href="#event" data-toggle="tab" aria-expanded="true" class="cust-label">Event</a></li>
    //           
    //        </ul>
    //        <div class="tab-content">
    //           <div class="tab-pane active" id="event">
    //              
    //              <div class="row">
    //                 ` + selectionEvent + `
    //              </div>
    //              <br>
    //                                              
    //              <div class="row">
    //                 <div class="col-md-7">
    //                 </div>
    //                 <div class="col-md-5">
    //                     <button id="btnAddEvent" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
    //                         <i class="fa fa-save"></i>
    //                         &nbsp;
    //                         Save Changes
    //                     </button>
    //                 </div>
    //              </div>
    //           </div>
    //           <div class="tab-pane" id="barangay">
    //              <div class="row">
    //                 <div class="col-md-12 col-sm-12">
    //                     <div class="form-group">
    //                         <label class="cust-label">Barangay Name</label>
    //                         <label class="cust-label text-danger">*</label>
    //                         <input type="text" class="form-control cust-label cust-textbox" id="txtBarangay" name="txtBarangay" placeholder="Enter Barangay Name">
    //                         <code>Please provide only the name do not include Brgy. or Barangay</code>
    //                     </div>
    //                 </div>
    //              </div>
    //              <div class="row">
    //                 <div class="col-md-12 col-sm-12">
    //                    <div class="form-group">
    //                       <label class="cust-label">Total Population</label>
    //                       <label class="cust-label text-danger">*</label>
    //                       <input type="text" class="form-control cust-label cust-textbox" id="txtPopulation" name="txtPopulation" placeholder="Enter Total Population" onkeyup="numOnly(this)">
    //                    </div>
    //                 </div>
    //              </div>
    //              <div class="row">
    //                 <div class="col-md-7">
    //                 </div>
    //                 <div class="col-md-5">
    //                     <button id="btnAddBarangay" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
    //                         <i class="fa fa-save"></i>
    //                         &nbsp;
    //                         Save Changes
    //                     </button>
    //                 </div>
    //              </div>
    //           </div>
    //        </div>
    //     </div>
    //`);
    //    
    // create DOM element for the marker
    //const popup_destination = document.createElement('div');
    //popup_destination.id = 'marker';
    
    marker = new mapboxgl.Marker()
   .setLngLat([lng,lat])
   //.setPopup(popup)
   .addTo(map)
   .togglePopup();
   
    $(".marker").click();
}

function updateRadius(radius) {
   try {
      if (myCircle) {
         myCircle.remove();
      }
   } catch(e) {
      
   }
   
   if (radius > 0) {
      myCircle = new MapboxCircle({lat: selectedLat, lng: selectedLng}, radius, {
         editable: false,
         minRadius: 0,
         fillColor: '#29AB87'
      }).addTo(map);
   }
}

$('body').on('click','#btnAddBarangay',function(){
   var barangayName    = $("#txtBarangay").val();
   var totalPopulation = $("#txtPopulation").val();
   
   if (barangayName == "" || totalPopulation == "") {
      JAlert("Please fill in required fields.","red");
   } else {
      $.ajax({
         url: "../program_assets/php/web/map.php",
         data: {
            command         : brgyCommand,
            barangayName    : barangayName,
            totalPopulation : totalPopulation,
            lat             : selectedLat,
            lng             : selectedLng
         },
         type: 'post',
         success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            
            if (!data[0].error) {
               if (marker) {
                  marker.remove();
               }
               
               if (myCircle) {
                  myCircle.remove();
               }
               
               map.removeLayer("poi-labels");
               map.removeSource("places");
               loadLabels();
               loadBarangayTable();
               loadDisasterMarker();
            }
         }
      });
   }
});


$('body').on('click','#btnAddEvent',function(){
   var radius  = $("#txtRadius").val();
   var remarks = $("#txtRemarks").val();
   var brgy = $("#cmbBarangay").val();
   var alertLevel = $("#cmbAlertLevel").val();
   var passableVehicle = $("#cmbPassableVehicle").val();
   var month = $("#cmbMonth").val();
   var day = $("#cmbDay").val();
   var hour = $("#cmbHour").val();
   var minute = $("#cmbMinute").val();
   var hasDuration = true;
   
   if (month == 0 && day == 0 && hour == 0 && minute == 0) {
      hasDuration = false;
   }

   if (selectNeedDuration == false) {
      hasDuration = true;
   }
   
   if (brgy == null) {
      brgy = 0;
   }
   
   if (selectedEventID == 0 || remarks == "") {
      JAlert("Please fill in required fields","red");  
   } else if ((selectNeedRadius == 1 && radius == null) || (selectNeedRadius == 1 && radius == 0)) {
      JAlert("Please fill in required fields","red");
   } else if (!hasDuration) {
      JAlert("Please provide duration to display","red");
   } else {
      $.ajax({
         url: "../program_assets/php/web/map.php",
         data: {
            command   : 'save_disaster',
            eventID   : selectedEventID,
            radius    : radius,
            remarks   : remarks,
            lat       : selectedLat,
            lng       : selectedLng,
            alertLevel : alertLevel,
            passableVehicle : passableVehicle,
            month : month,
            day : day,
            hour : hour,
            minute : minute,
            brgyId : brgy
         },
         type: 'post',
         success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            
            if (!data[0].error) {
               if (marker) {
                  marker.remove();
               }
               
               if (myCircle) {
                  myCircle.remove();
               }
               
               isAdd = 0;
               $('#chkSwitch').prop('checked', false);
               map.removeLayer("poi-labels");
               map.removeSource("places");
               loadLabels();
               loadBarangayTable();
               loadDisasterMarker();
               ResetField();
            }
         }
      });
   }
});


$('#chkSwitch').change(function() {   
   isAdd = this.checked ? 1 : 0;
   
   if (marker) {
      marker.remove();
   }
   
   ResetField();
   loadDisasterMarker();
});

/* Barangay Details */
var oldBarangayName;
var oldBarangayID;

$('#tblBarangay tbody').on('dblclick', 'td', function (){
	var data = tblBarangay.row( $(this).parents('tr') ).data();
   
   $("#txtBarangayEdit").val(data.barangayName.replace("Barangay ",""));
   //$("#txtPopulationEdit").val(data.population.replace(",",""));
   oldBarangayName = data.barangayName.replace("Barangay ","");
   oldBarangayID = data.id
   brgyCommand = "edit_brgy_details";
   
   $("#mdBarangayEdit").modal();
});

$("#btnAddBarangayEdit").click(function(){
   var brgyName    = $("#txtBarangayEdit").val();
   var population  = $("#txtPopulationEdit").val();
   
   if (brgyName == "") {
      JAlert("Please fill in required fields","red");
   } else {
      $.ajax({
         url: "../program_assets/php/web/map.php",
         data: {
            command : brgyCommand,
            id : oldBarangayID,
            oldBarangayName : oldBarangayName,
            barangayName : brgyName,
            totalPopulation : 0,
            lat : selectedLat,
            lng : selectedLng
         },
         type: 'post',
         success: function (data) {
            var data = jQuery.parseJSON(data);
            
            JAlert(data[0].message,data[0].color);
            
            if (!data[0].error) {
               if (marker) {
                  marker.remove();
               }
               
               if (myCircle) {
                  myCircle.remove();
               }
               
               $("#mdBarangayEdit").modal("hide");
               $("#txtSearchBarangay").val("");
               map.removeLayer("poi-labels");
               map.removeSource("places");
               loadLabels();
               loadBarangayTable();
            }
         }
      });
   }
});

function choseEvent(id,needRadius,needDuration) {
   if (isAdd == 1) {
      selectedEventID = id;
      selectNeedRadius = needRadius;
      selectNeedDuration = needDuration;
      
      $("txtRadius").val("");
      $("#txtRadius").prop("disabled", needRadius == 1 ? false : true);
      
      $("#cmbMonth").prop("disabled", needDuration == 1 ? false : true);
      $("#cmbDay").prop("disabled", needDuration == 1 ? false : true);
      $("#cmbHour").prop("disabled", needDuration == 1 ? false : true);
      $("#cmbMinute").prop("disabled", needDuration == 1 ? false : true);
   }
}

function numOnly(selector){
    selector.value = selector.value.replace(/[^0-9]/g,'');
}

DisplayEventMarker();

function DisplayEventMarker() {
   $.ajax({
      url: "../program_assets/php/web/map",
      data: {
         command : 'display_event',
      },
      type: 'post',
      success: function (data) {
         var data = jQuery.parseJSON(data);
         var eventHTML = "";
         var strEnable = isAdd == 0 ? "disabled" : "";
         
         $("#dvEventRow").html(eventHTML);
         
         
         for (var i = 0; i < data.length; i++) {
            eventHTML += `
               <div class="col-md-6">
                   <div class="checkbox">
                       <label style="display: flex; margin-left: -19px;" onclick="choseEvent(` + data[i].id + `,` + data[i].needRadius + `,` + data[i].needDuration + `)">
                           <input id="chkAdd` + data[i].id + `" type="radio" name="group1" class="checkEvent" ` + strEnable + `>
                           &nbsp;
                           &nbsp;
                           <img id="imgEvent" class="img-responsive" src="../dist/img/` + data[i].id + `.png" style="width:20px">
                           &nbsp;
                           &nbsp;
                           <span class="cust-label">` + data[i].event + `</span>
                       </label>
                   </div>
               </div>
            `;
         }
         
         $("#dvEventRow").html(eventHTML);
      }
   });
}

ResetField();

function ResetField() {
   selectedEventID = 0;
   
   $(".checkEvent").prop('checked',false);
   $("#txtRadius").val("");
   $("#txtRemarks").val("");
   $("#cmbBarangay").val(0).trigger('change.select2');
   $("#cmbAlertLevel").val("None").trigger('change.select2');
   $("#cmbPassableVehicle").val("All").trigger('change.select2');
   $("#cmbMonth").val(0).trigger('change.select2');
   $("#cmbDay").val(0).trigger('change.select2');
   $("#cmbHour").val(0).trigger('change.select2');
   $("#cmbMinute").val(5).trigger('change.select2');
   
   $(".checkEvent").prop("disabled", true);
   $("#txtRadius").prop("disabled", true);
   $("#txtRemarks").prop("disabled", true);
   $("#cmbBarangay").prop("disabled", true);
   $("#cmbAlertLevel").prop("disabled", true);
   $("#cmbPassableVehicle").prop("disabled", true);
   $("#cmbMonth").prop("disabled", true);
   $("#cmbDay").prop("disabled", true);
   $("#cmbHour").prop("disabled", true);
   $("#cmbMinute").prop("disabled", true);
   $("#btnAddEvent").prop("disabled", true);
}

$("#btnAddBrgy").click(function(){
	if (selectedLat == 0) {
      JAlert("Please select lat and long by clicking Switch to add Mode and click on map","red");
   } else {
      $("#txtBarangayEdit").val("");
      $("#txtPopulationEdit").val(0);
      oldBarangayName = "";
      oldBarangayID = 0;
      brgyCommand = "save_barangay";
      
      $("#mdBarangayEdit").modal();
   }
});
