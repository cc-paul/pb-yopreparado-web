<?php
	if(!isset($_SESSION)) { session_start(); } 
	if (!isset($_SESSION['id'])) {
		header( "Location: login" );
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title></title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
		<!-- DataTables -->
  		<link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  		<link rel="stylesheet" href="../bower_components/datatables.select/select.dataTables.min.css">
		<!-- Select2 -->
  		<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!--link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"-->
		<link rel="stylesheet" href="../fonts/fonts.css">
		<!-- Custom Confirm -->
		<link rel="stylesheet" href="../bower_components/custom-confirm/jquery-confirm.min.css">
		<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
		
		<!-- StartUp Custom CSS (do not remove)  -->
		<link href="../plugins/bootoast/bootoast.css" rel="stylesheet" type="text/css">
		<link href="../program_assets/css/style.css" rel="stylesheet" type="text/css">
		<link
			rel="stylesheet"
			href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css"
			type="text/css"
		/>
		
		<style>
			#map { height: 700px; width: 100%; }
			
			.image-custom {
				height: 122px;
				max-height: 122px;
				object-fit: cover !important;
				width: 100%; border:
				5px solid #555;
			}
			
			.mapboxgl-popup {
				max-width: 300px !important;
				width: 300px !important;
			}
		</style>
	</head>
	
	<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
	<!-- the fixed layout is not compatible with sidebar-mini -->
	<body class="hold-transition skin-black-light fixed sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<header class="main-header">
				<!-- Logo -->
				<a href="#" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini"></span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"></span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">							
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img src="./../profile/<?php  echo $_SESSION['id'] ?>.png?random=<?php echo rand(10,100);?>" class="user-image" alt="User Image" onerror="this.onerror=null; this.src='./../profile/Default.png'">
									<span class="hidden-xs">
										<?php
											$to_display = "name";
											require dirname(__FILE__,2) . '/program_assets/php/naming/naming.php';
										?>
									</span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									<li class="user-header">
										<img src="./../profile/<?php  echo $_SESSION['id'] ?>.png?random=<?php echo rand(10,100);?>" class="img-circle" alt="User Image" onerror="this.onerror=null; this.src='./../profile/Default.png'">
										<p>
											<?php
												$to_display = "name";
												require dirname(__FILE__,2) . '/program_assets/php/naming/naming.php';
											?>
											&nbsp;-&nbsp;
											<?php
												$to_display = "branch";
												require dirname(__FILE__,2) . '/program_assets/php/naming/naming.php';
											?>

											<small>
												Member since : 
												<?php
													$to_display = "date_created";
													require dirname(__FILE__,2) . '/program_assets/php/naming/naming.php';
												?>
											</small>
										</p>
									</li>
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-left">
											<a href="profile" class="btn btn-default btn-flat">Profile</a>
										</div>
										<div class="pull-right">
											<a href="logout" class="btn btn-default btn-flat">Sign out</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			<!-- =============================================== -->
			<!-- Left side column. contains the sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- Sidebar user panel -->
					<div class="user-panel">
						<div class="pull-left image">
							<img src="./../profile/cs.png" class="img-circle" alt="User Image" onerror="this.onerror=null; this.src='./../profile/Default.png'" style="max-height: 50px;">
						</div>
						<div class="pull-left info">
							<p>
								<?php
									$to_display = "name";
									require dirname(__FILE__,2) . '/program_assets/php/naming/naming.php';
								?>
							</p>
							<a href="#"><i id="c_status" name="c_status" class="fa fa-circle text-success"></i> Online</a>
						</div>
					</div>
					<!-- search form -->
					<div class="sidebar-form">
						<div class="input-group">
							<input type="text" name="q" class="form-control" placeholder="Search...">
							<span class="input-group-btn">
								<button type="submit" name="search" id="search-btn" class="btn btn-flat">
									<i class="fa fa-search"></i>
								</button>
							</span>
						</div>
					</div>
					<!-- /.search form -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu" data-widget="tree">
						<li class="header">MAIN NAVIGATION</li>
						<?php
							include dirname(__FILE__,2) . '/program_assets/php/sidebar/sidebar.php';
						?>
					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>
			<!-- =============================================== -->
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Main content -->
				<section class="content col-md-12 col-xs-12">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#admin" data-toggle="tab" aria-expanded="true" class="cust-label">Event Mapping Control Panel</a></li>
							<!--<li class=""><a href="#freelancer" data-toggle="tab" aria-expanded="false" class="cust-label">Freelancer Approval</a></li>-->
						</ul>
						<div class="tab-content">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<label class="pull-right" style="display: inline-block">
										<input id="chkSwitch" style="vertical-align: middle; margin-top: -4px;" type="checkbox" autocomplete="off">
										<label for="chkSwitch" style="vertical-align: middle" class="cust-label">&nbsp;&nbsp;Switch to Add Mode</label>
									</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3 col-xs-12">
									<div class="nav-tabs-custom" style="height: 701px !important; overflow-y: auto; overflow-x: clip;">
										<ul class="nav nav-tabs">
											<li class="active"><a href="#event-mapping" data-toggle="tab" aria-expanded="true" class="cust-label">Event Mapping</a></li>
											<li class="" onclick="loadBrgy()"><a href="#brgy-mapping" data-toggle="tab" aria-expanded="false" class="cust-label" onclick="loadBrgy()">Barangay Mapping</a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="event-mapping" role="tabpanel">
												<div class="row">
													<div class="col-md-12">
														<div class="callout">
															<p class="cust-label">
																<i class="fa fa-lightbulb-o"></i>
																&nbsp;&nbsp;
																Please fill up the form below in order to create an Event. Fields will be required based on the event you will select.
															</p>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12 col-xs-12">
														<div class="row">
															<div class="col-md-12 col-sm-12">
																<div class="form-group">
																	<label class="cust-label">Select Event from List</label>
																	<br>
																	<code class="cust-label">Please add marker in the map by selecting checking the "Switch to Add Mode" and clicking on the map</code>
																</div>
															</div>
														</div>
														<div id="dvEventRow" class="row">
															
														</div>
														<br>
														<div class="row">
															<div class="col-md-4 col-sm-12">
																<div class="form-group">
																	<label class="cust-label">Radius in Meters</label>
																	<label class="cust-label text-danger">*</label>
																	<input type="number" class="form-control cust-label cust-textbox" id="txtRadius" name="txtRadius" placeholder="Enter Radius" onInput="updateRadius(this.value)">
																</div>
															</div>
															<div class="col-md-8 col-sm-12">
																<div class="form-group">
																	<label class="cust-label">Barangay</label>
																	<label class="cust-label text-danger">*</label>
																	<select id="cmbBarangay" name="cmbBarangay" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
																		<option value="0" selected disabled>Please select a barangay</option>
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12">
																<div class="form-group">
																	<label class="cust-label">Remarks</label>
																	<label class="cust-label text-danger">*</label>
																	<textarea id="txtRemarks" name="txtRemarks" rows="4" cols="50" class="form-control cust-label cust-textbox" placeholder="Please provide description,narrative or due to explanation"></textarea>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4 col-sm-12">
																<div class="form-group">
																	<label class="cust-label">Alert Level</label>
																	<!--<label class="cust-label text-danger">*</label>-->
																	<select id="cmbAlertLevel" name="cmbAlertLevel" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
																		<option value="None" selected>None</option>
																		<option value="Low">Low</option>
																		<option value="Medium">Medium</option>
																		<option value="High">High</option>
																		<option value="Very High">Very High</option>
																	</select>
																</div>
															</div>
															<div class="col-md-4 col-sm-12">
																<div class="form-group">
																	<label class="cust-label">Passable Vehicle</label>
																	<!--<label class="cust-label text-danger">*</label>-->
																	<select id="cmbPassableVehicle" name="cmbPassableVehicle" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
																		<option value="All" selected>All</option>
																		<option value="None">None</option>
																		<option value="Cars">Cars</option>
																		<option value="Trucks">Trucks</option>
																		<option value="Motorcycles">Motorcycles</option>
																		<option value="Bicycles">Bicycles</option>
																		<option value="Buses">Buses</option>
																		<option value="Vans">Vans</option>
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-sm-12">
																<div class="panel panel-default">
																	<div class="panel-heading">
																		<label class="cust-label">Display Duration</label>
																		<br>
																		<code class="cust-label">Please fill up at least one dropdown below</code>
																	</div>
																	<div class="panel-body">
																		<div class="row">
																			<div class="col-md-3 col-sm-12">
																				<!-- Month Dropdown -->
																				<div class="form-group">
																					<label class="cust-label">Month</label>
																					<label class="cust-label text-danger">*</label>
																					<select id="cmbMonth" name="cmbMonth" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
																						<!-- Start with 0 for months -->
																						<option value="0" selected>0</option>
																						<option value="1">1</option>
																						<option value="2">2</option>
																						<option value="3">3</option>
																						<option value="4">4</option>
																						<option value="5">5</option>
																						<option value="6">6</option>
																						<option value="7">7</option>
																						<option value="8">8</option>
																						<option value="9">9</option>
																						<option value="10">10</option>
																						<option value="11">11</option>
																						<option value="12">12</option>
																					</select>
																				</div>
																			</div>
																			<div class="col-md-3 col-sm-12">
																				<!-- Day Dropdown -->
																				<div class="form-group">
																					<label class="cust-label">Day</label>
																					<label class="cust-label text-danger">*</label>
																					<select id="cmbDay" name="cmbDay" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
																						<!-- Include all 31 days -->
																						<option value="0" selected>0</option>
																						<option value="1">1</option>
																						<option value="2">2</option>
																						<option value="3">3</option>
																						<option value="4">4</option>
																						<option value="5">5</option>
																						<option value="6">6</option>
																						<option value="7">7</option>
																						<option value="8">8</option>
																						<option value="9">9</option>
																						<option value="10">10</option>
																						<option value="11">11</option>
																						<option value="12">12</option>
																						<option value="13">13</option>
																						<option value="14">14</option>
																						<option value="15">15</option>
																						<option value="16">16</option>
																						<option value="17">17</option>
																						<option value="18">18</option>
																						<option value="19">19</option>
																						<option value="20">20</option>
																						<option value="21">21</option>
																						<option value="22">22</option>
																						<option value="23">23</option>
																						<option value="24">24</option>
																						<option value="25">25</option>
																						<option value="26">26</option>
																						<option value="27">27</option>
																						<option value="28">28</option>
																						<option value="29">29</option>
																						<option value="30">30</option>
																						<option value="31">31</option>
																					</select>
																				</div>
																			</div>
																			<div class="col-md-3 col-sm-12">
																				<!-- Hour Dropdown -->
																				<div class="form-group">
																					<label class="cust-label">Hour</label>
																					<label class="cust-label text-danger">*</label>
																					<select id="cmbHour" name="cmbHour" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
																						<!-- Include all 24 hours -->
																						<option value="0" selected>0</option>
																						<option value="1">1</option>
																						<option value="2">2</option>
																						<option value="3">3</option>
																						<option value="4">4</option>
																						<option value="5">5</option>
																						<option value="6">6</option>
																						<option value="7">7</option>
																						<option value="8">8</option>
																						<option value="9">9</option>
																						<option value="10">10</option>
																						<option value="11">11</option>
																						<option value="12">12</option>
																						<option value="13">13</option>
																						<option value="14">14</option>
																						<option value="15">15</option>
																						<option value="16">16</option>
																						<option value="17">17</option>
																						<option value="18">18</option>
																						<option value="19">19</option>
																						<option value="20">20</option>
																						<option value="21">21</option>
																						<option value="22">22</option>
																						<option value="23">23</option>
																					</select>
																				</div>
																			</div>
																			<div class="col-md-3 col-sm-12">
																				<!-- Minute Dropdown -->
																				<div class="form-group">
																					<label class="cust-label">Minute</label>
																					<label class="cust-label text-danger">*</label>
																					<select id="cmbMinute" name="cmbMinute" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
																						<!-- Include all 60 minutes -->
																						<!--<option value="0" selected>0</option>-->
																						<!--<option value="1">1</option>-->
																						<!--<option value="2">2</option>-->
																						<!--<option value="3">3</option>-->
																						<!--<option value="4">4</option>-->
																						<option value="5" selected>5</option>
																						<option value="6">6</option>
																						<option value="7">7</option>
																						<option value="8">8</option>
																						<option value="9">9</option>
																						<option value="10">10</option>
																						<option value="11">11</option>
																						<option value="12">12</option>
																						<option value="13">13</option>
																						<option value="14">14</option>
																						<option value="15">15</option>
																						<option value="16">16</option>
																						<option value="17">17</option>
																						<option value="18">18</option>
																						<option value="19">19</option>
																						<option value="20">20</option>
																						<option value="21">21</option>
																						<option value="22">22</option>
																						<option value="23">23</option>
																						<option value="24">24</option>
																						<option value="25">25</option>
																						<option value="26">26</option>
																						<option value="27">27</option>
																						<option value="28">28</option>
																						<option value="29">29</option>
																						<option value="30">30</option>
																						<option value="31">31</option>
																						<option value="32">32</option>
																						<option value="33">33</option>
																						<option value="34">34</option>
																						<option value="35">35</option>
																						<option value="36">36</option>
																						<option value="37">37</option>
																						<option value="38">38</option>
																						<option value="39">39</option>
																						<option value="40">40</option>
																						<option value="41">41</option>
																						<option value="42">42</option>
																						<option value="43">43</option>
																						<option value="44">44</option>
																						<option value="45">45</option>
																						<option value="46">46</option>
																						<option value="47">47</option>
																						<option value="48">48</option>
																						<option value="49">49</option>
																						<option value="50">50</option>
																						<option value="51">51</option>
																						<option value="52">52</option>
																						<option value="53">53</option>
																						<option value="54">54</option>
																						<option value="55">55</option>
																						<option value="56">56</option>
																						<option value="57">57</option>
																						<option value="58">58</option>
																						<option value="59">59</option>
																					</select>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-7">
															</div>
															<div class="col-md-5">
																<button id="btnAddEvent" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
																	<i class="fa fa-save"></i>
																	&nbsp;
																	Save Changes
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="brgy-mapping" role="tabpanel">
												<div class="row">
													<div class="col-md-9 col-xs-12">
														<div class="form-group">
															<input id="txtSearchBarangay" class="form-control input-sm cust-label" type="text" placeholder="Search barangay here..." autocomplete="off">
														</div>
													</div>
													<div class="col-md-3 col-xs-12">
														<button id="btnAddBrgy" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
															<i class="fa fa-plus"></i>
														</button>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12 col-xs-12">
														<code class="cust-label">Double click row to edit Barangay Details</code>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12 col-xs-12">
														<table id="tblBarangay" name="tblBarangay" class="table table-bordered table-hover cust-label" style="width: 100% !important;" >
															<thead>
																<th>Barangay</th>
																<th></th>
															</thead>
															<tbody></tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-9 col-xs-12">
									<div id="map"></div> 
								</div>
							</div>
						</div>
					</div>
				</div>
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<!-- Version or anything -->
				</div>
				<strong class="cust-label">Program created by: <a id="footer-cname" name="footer-cname" href="#">CompanyName</a> </strong> 
				<span class="cust-label">IT Department.</span>
			</footer>
			<!-- Add the sidebar's background. This div must be placed
			immediately after the control sidebar -->
			<div class="control-sidebar-bg"></div>
		</div>
		<!-- ./wrapper -->


		<!-- Modal Name  -->
		<div class="modal fade" id="mdAddEvent" name="mdAddService">
			<div class="modal-dialog modal-semismall">
				<div class="box box-default">
					<div class="box-header with-border">
						<label class="cust-label">Event Form</label>
						<button type="submit" class="btn btn-default btn-xs pull-right" data-dismiss="modal"><i class="fa fa-close"></i>
						</button>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<label class="cust-label">Name of Event</label>
									<label class="cust-label text-danger">*</label>
									<input type="text" class="form-control cust-label cust-textbox" id="txtEvent" name="txtEvent" placeholder="Enter Name of Event">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<label class="cust-label">Description</label>
									<label class="cust-label text-danger">*</label>
									<textarea id="txtDescription" name="txtDescription" rows="4" cols="50" class="form-control cust-label cust-textbox" placeholder="Please provide description for the Event"></textarea>
								</div>
							</div>
						</div>
						<div id="dvImage" class="row">
							<div class="col-md-4">
								<img id="imgEvent" class="img-responsive" src="../dist/img/picture.png" onerror="this.onerror=null; this.src='../dist/img/picture.png'" alt="Photo" >
								<input type="file" id="image_uploader" name="image_uploader" accept="image/png" style='display: none;'>
							</div>
							<div class="col-md-8">
								<span class="cust-label">
									You can get your icon at link below to have a more uniform set of event pictures.
								</span>
								<br>
								<br>
								<a id="aImage" href="" target="_blank">
									<label class="cust-label">
										Event Icons
									</label>
								</a>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<label style="display: inline-block">
									<input id="chkActiveEvent" style="vertical-align: middle; margin-top: -4px;" type="checkbox"/>
									<label for="chkActiveEvent" style="vertical-align: middle" class="cust-label">&nbsp;&nbsp;Set as Active</label>
								</label>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<button id="btnUpload" type="button" onclick="openImage()" class="btn btn-block btn-default btn-sm cust-textbox">
									<i class="fa fa-picture-o"></i>
									&nbsp;
									Upload Icon
								</button>
							</div>
							<div class="col-md-6 col-xs-12">
								<button id="btnSaveEvent" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
									<i class="fa fa-save"></i>
									&nbsp;
									Save Changes
								</button>
							</div>
						</div>
					</div>
				</div>
			<!-- /.modal-content -->
			</div>
		<!-- /.modal-dialog -->
		</div>
		
		
		<!-- Modal Name  -->
		<div class="modal fade" id="mdBarangayEdit" name="mdBarangayEdit">
			<div class="modal-dialog modal-sm">
				<div class="box box-default">
					<div class="box-header with-border">
						<label class="cust-label">Edit Barangay Details</label>
						<button type="submit" class="btn btn-default btn-xs pull-right" data-dismiss="modal"><i class="fa fa-close"></i>
						</button>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<label class="cust-label">Barangay Name</label>
									<label class="cust-label text-danger">*</label>
									<input type="text" class="form-control cust-label cust-textbox" id="txtBarangayEdit" name="txtBarangayEdit" placeholder="Enter Barangay Name">
									<code>Please provide only the name do not include Brgy. or Barangay</code>
								</div>
							</div>
						</div>
						<div class="row" hidden>
							<div class="col-md-12 col-sm-12">
							   <div class="form-group">
								  <label class="cust-label">Total Population</label>
								  <label class="cust-label text-danger">*</label>
								  <input type="text" class="form-control cust-label cust-textbox" id="txtPopulationEdit" name="txtPopulationEdit" placeholder="Enter Total Population" onkeyup="numOnly(this)">
							   </div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="col-md-5">
							</div>
							<div class="col-md-7">
								<button id="btnAddBarangayEdit" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
									<i class="fa fa-save"></i>
									&nbsp;
									Save Changes
								</button>
							</div>
						</div>
					</div>
				</div>
			<!-- /.modal-content -->
			</div>
		<!-- /.modal-dialog -->
		</div>

		<!-- NO INTERNET MODAL -->
		<div class="modal fade" id="modal-default" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="box box-danger">
					<div class="box-body">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<label>System Message</label>
								<br>
									<center>
										<img class="img-res" src="../dist/img/404-error.png" alt="No Internet Connection">
									</center>
								<br>
								<p class="cust-label">There is no Internet connection. Kindly use the local program to continue your transaction and sync later.</p>
							</div>
						</div>
					</div>
				</div>
			<!-- /.modal-content -->
			</div>
		<!-- /.modal-dialog -->
		</div>
		
		

		<!-- jQuery 3 -->
		<script src="../bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Select2 -->
		<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
		<!-- DataTables -->
		<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="../bower_components/datatables.select/dataTables.select.min.js"></script>
		<script src="../bower_components/datatables.button/dataTables.buttons.min.js"></script>
		<script src="../bower_components/datatables.button/jszip.min.js"></script>
		<script src="../bower_components/datatables.button/buttons.html5.min.js"></script>
		<!-- SlimScroll -->
		<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="../bower_components/fastclick/lib/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="../dist/js/adminlte.min.js"></script>
		<script src="../plugins/bootoast/bootoast.js"></script>
		<!-- Custom Confirm -->
		<script src="../bower_components/custom-confirm/jquery-confirm.min.js"></script>
		<script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
		<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>
		<script src="https://unpkg.com/@popperjs/core@2"></script>
		<script src="https://unpkg.com/tippy.js@6"></script>
		<script src='https://npmcdn.com/mapbox-gl-circle@1.6.7/dist/mapbox-gl-circle.min.js'></script>
		
		<!-- StartUp Custom JS (do not remove)  -->
		<script src="../program_assets/js/site_essentials/sidebar.js?random=<?php echo uniqid(); ?>"></script>
		<script src="../program_assets/js/site_essentials/others.js?random=<?php echo uniqid(); ?>"></script>
		<script src="../program_assets/js/web_functions/map.js?random=<?php echo uniqid(); ?>"></script>
		
	</body>
</html>