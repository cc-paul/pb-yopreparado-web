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
			#map { height: 290px; width: 100%; }
			
			.image-custom {
				height: 122px;
				max-height: 122px;
				object-fit: cover !important;
				width: 100%; border:
				5px solid #555;
			}
			
			.image-no-thumb {
				width: 100%;
				height: 180px;
				object-fit: scale-down;
				background: #cbcbcb;
				border-radius: 8px;
			}
			
			.image-w-thumb {
				width: 100%;
				height: 180px;
				object-fit: cover;
				background: #cbcbcb;
				border-radius: 8px;
			}
			
			.panel {
				border-radius: 15px;
			}
			
			.videoHolder {
				height: 600px;
				overflow-x: auto;
			}
			
			.videoHolder::-webkit-scrollbar {
				display: none;
			}
			
			.tooltip {
				position: relative;
				display: inline-block;
				border-bottom: 1px dotted black;
			}
			  
			.tooltip .tooltiptext {
				visibility: hidden;
				width: 120px;
				background-color: black;
				color: #fff;
				text-align: center;
				border-radius: 6px;
				padding: 5px 0;
				
				/* Position the tooltip */
				position: absolute;
				z-index: 1;
				top: -5px;
				left: 105%;
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
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						@side_header
						<small>@side_desc</small>
					</h1>
					<ol class="breadcrumb page-order"></ol>
				</section>
				<!-- Main content -->
				<section class="content col-md-12 col-xs-12">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#admin" data-toggle="tab" aria-expanded="true" class="cust-label">Type of Events</a></li>
							<li class=""><a href="#videos" data-toggle="tab" aria-expanded="false" class="cust-label">Videos</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="admin">
								<div class="row">
									<div class="col-md-8 col-xs-12">
										<div class="form-group">
											<input id="txtSearchEvent" class="form-control input-sm cust-label" type="text" placeholder="Search events here...">
										</div>
									</div>
									<div class="col-md-2 col-xs-6">
										<div class="form-group">
											<button id="btnAddEvent" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
												<i class="fa fa-cloud"></i>
												&nbsp;
												New Event
											</button>
										</div>
									</div>
									<div class="col-md-2 col-xs-6">
										<div class="form-group">
											<button id="btnExportEvent" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
												<i class="fa fa-file-excel-o"></i>
												&nbsp;
												Export to Excel
											</button>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 col-sm-12">
										<div class="table-container">
											<table id="tblEvent" name="tblEvent" class="table table-bordered table-hover cust-label" style="width: 100% !important;">
												<thead>
													<tr>
														<th>Event</th>
														<th>Description</th>
														<th>Origin and History</th>
														<th>Status</th>
														<th>Date Registered</th>
														<th></th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="row" hidden>
									<div class="col-md-12 col-sm-12">
										<div class="table-container">
											<table id="tblEventHidden" name="tblEventHidden" class="table table-bordered table-hover cust-label" style="width: 100% !important;">
												<thead>
													<tr>
														<th>Event</th>
														<th>Description</th>
														<th>Origin and History</th>
														<th>Status</th>
														<th>Date Registered</th>
														<th></th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="videos">
								<div class="row">
									<div class="col-md-3 col-xs-12">
									</div>
									<div class="col-md-6 col-xs-12">
										<div class="row">
											<div class="col-md-9 col-xs-7">
												<div class="form-group">
													<input id="txtSearchVideo" class="form-control input-sm cust-label" type="text" placeholder="Search video or events here...">
												</div>
											</div>
											<div class="col-md-3 col-xs-5">
												<div class="form-group">
													<button id="btnUploadVideo" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
														<i class="fa fa-file-video-o"></i>
														&nbsp;
														Upload Video
													</button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3 col-xs-12">
									</div>
								</div>
								<div id="divVidHolder" class="row videoHolder">
									
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
			<div class="modal-dialog modal-md">
				<div class="box box-default">
					<div class="box-header with-border">
						<label class="cust-label">Event Form</label>
						<button type="submit" class="btn btn-default btn-xs pull-right" data-dismiss="modal"><i class="fa fa-close"></i>
						</button>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-6 col-sm-12">
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
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<label class="cust-label">Origin and History</label>
									<label class="cust-label text-danger">*</label>
									<textarea id="txtOrigin" name="txtOrigin" rows="4" cols="50" class="form-control cust-label cust-textbox" placeholder="Please provide origin and history for the Event"></textarea>
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
								<code class="cust-label"> - Once you select an image it will automatically updated and no need to save changes</code>
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
							<div class="col-md-3 col-sm-12">
								<label style="display: inline-block">
									<input id="chkActiveEvent" style="vertical-align: middle; margin-top: -4px;" type="checkbox"/>
									<label for="chkActiveEvent" style="vertical-align: middle" class="cust-label">&nbsp;&nbsp;Set as Active</label>
								</label>
							</div>
							<div class="col-md-3 col-sm-12">
								<label style="display: inline-block">
									<input id="chkAddRadius" style="vertical-align: middle; margin-top: -4px;" type="checkbox"/>
									<label for="chkAddRadius" style="vertical-align: middle" class="cust-label">&nbsp;&nbsp;Add a Radius</label>
								</label>
							</div>
							<div class="col-md-3 col-sm-12">
								<label style="display: inline-block">
									<input id="chkNeedDuration" style="vertical-align: middle; margin-top: -4px;" type="checkbox"/>
									<label for="chkNeedDuration" style="vertical-align: middle" class="cust-label">&nbsp;&nbsp;Needs Duration</label>
								</label>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="col-md-4 col-xs-12">
								<button id="btnDosDonts" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
									<i class="fa fa-hand-stop-o"></i>
									&nbsp;
									Dos and Donts
								</button>
							</div>
							<div class="col-md-4 col-xs-12">
								<!--<button id="btnVideo" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
									<i class="fa fa-file-video-o"></i>
									&nbsp;
									Videos
								</button>-->
								<button id="btnUpload" type="button" onclick="openImage_1()" class="btn btn-block btn-default btn-sm cust-textbox">
									<i class="fa fa-picture-o"></i>
									&nbsp;
									Upload Icon
								</button>
							</div>
							<div class="col-md-4 col-xs-12">
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
		<div class="modal fade" id="mdAddVideo" name="mdAddVideo" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-md">
				<div class="box box-default">
					<div class="box-header with-border">
						<label class="cust-label">Video Uploading Form</label>
						<button id="btnCloseVideoModal" type="submit" class="btn btn-default btn-xs pull-right" data-dismiss="modal"><i class="fa fa-close"></i>
						</button>
					</div>
					<form id="upload-form" class="upload-form" method="post">
						<div class="box-body">
							<div class="row">
								<div class="col-md-4 col-sm-12">
									<div class="form-group">
										<label class="cust-label">Event</label>
										<label class="cust-label text-danger">*</label>
										<select id="cmbEvent" name="cmbEvent" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
											
										</select>
									</div>
								</div>
								<div class="col-md-8 col-sm-12">
									<div class="form-group">
										<label class="cust-label">Video Name / Title</label>
										<label class="cust-label text-danger">*</label>
										<input type="text" class="form-control cust-label cust-textbox" id="txtVideoName" name="txtVideoName" placeholder="Enter Video Name...">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="cust-label">Select Video to Upload</label>
										<label class="cust-label text-danger">*</label>
										<input type="file" id="txtVideo" name="txtVideo" class="form-control cust-label cust-textbox" accept="video/mp4,video/x-m4v,video/*" onchange="uploadFile()">
									</div>
									<code class="cust-label">Please do not upload large size videos.</code>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<div class="progress progress-sm active">
										<div id="dvProgress" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="row">
								<div class="col-md-8 col-xs-12">
									
								</div>
								<div class="col-md-4 col-xs-12">
									<button id="btnSaveVideo" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
										<i class="fa fa-upload"></i>
										&nbsp;
										Start Uploading
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		
		<!-- Modal Name  -->
		<div class="modal fade" id="mdDoAndDont" name="mdDoAndDont">
			<div class="modal-dialog modal-md">
				<div class="box box-default">
					<div class="box-header with-border">
						<label class="cust-label">Dos and Donts Form</label>
						<button type="submit" class="btn btn-default btn-xs pull-right" data-dismiss="modal"><i class="fa fa-close"></i>
						</button>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-4 col-xs-12">
								<label class="cust-label">Event</label>
								<label class="cust-label text-danger">*</label>
								<select id="cmbEventDo" name="cmbEventDo" class="form-control select2 cust-label cust-textbox" style="width: 100%;" disabled>
									<?php
										include dirname(__FILE__,2) . '/program_assets/php/dropdown/events.php';
									?>
								</select>
							</div>
							<div class="col-md-4 col-xs-12">
								<label class="cust-label">Dos or Donts</label>
								<label class="cust-label text-danger">*</label>
								<select id="cmbDosDonts" name="cmbDosDonts" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
									<option value="" selected disabled>Please select Dos or Donts</option>
									<option value="1">Dos</option>
									<option value="0">Donts</option>
								</select>
							</div>
							<div class="col-md-4 col-xs-12">
								<label class="cust-label">Category</label>
								<label class="cust-label text-danger">*</label>
								<select id="cmbDosDontsCategory" name="cmbDosDontsCategory" class="form-control select2 cust-label cust-textbox" style="width: 100%;">
									<option value="" selected disabled>Please select category</option>
									<option value="General">General</option>
									<option value="Marginalized">Marginalized</option>
								</select>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="form-group">
									<label class="cust-label">Details</label>
									<label class="cust-label text-danger">*</label>
									<textarea id="txtDetails" name="txtDetails" rows="4" cols="50" class="form-control cust-label cust-textbox" placeholder="Please provide description for the Dos and Donts"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="col-md-8 col-xs-12"></div>
							<div class="col-md-4 col-xs-12">
								<button id="btnSaveDo" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
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
		<div class="modal fade" id="mdDosList" name="mdDosList">
			<div class="modal-dialog modal-md">
				<div class="box box-default">
					<div class="box-header with-border">
						<label class="cust-label">Dos and Donts</label>
						<button type="submit" class="btn btn-default btn-xs pull-right" data-dismiss="modal"><i class="fa fa-close"></i>
						</button>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-3 col-xs-6">
								<div class="form-group">
									<select id="cmbEventDoList" name="cmbEventDoList" class="form-control select2 cust-label cust-textbox" style="width: 100%;" disabled>
										<?php
											include dirname(__FILE__,2) . '/program_assets/php/dropdown/events.php';
										?>
									</select>
								</div>
							</div>
							<div class="col-md-9 col-xs-12">
								<!--<div class="form-group">
									<input  class="form-control input-sm cust-label" type="text" placeholder="Search Dos and Donts here..." autocomplete="off">
								</div>-->
								
								<div class="input-group">
									<input id="txtSearchDosAndDonts" placeholder="Search Dos and Donts here..." type="text" class="form-control cust-label" style="height: 30px !important;">
									<span id="btnNewDosDonts" style="cursor:pointer;" class="input-group-addon cust-label"><i class="fa fa-plus"></i></span>
								</div>
							</div>
							
							<!--<div class="col-md-3 col-xs-6">
								<div class="form-group">
									<button id="btnNewDosDonts" type="button" class="btn btn-block btn-default btn-sm cust-textbox">
										<i class="fa fa-plus"></i>
										&nbsp;
										New Dos and Donts
									</button>
								</div>
							</div>-->
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="table-container">
									<table id="tblDosDontsList" name="tblDosDontsList" class="table table-bordered table-hover cust-label" style="width: 100% !important;">
										<thead>
											<tr>
												<th>Details</th>
												<th>Category</th>
												<th></th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer"></div>
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
		
		<!-- StartUp Custom JS (do not remove)  -->
		<script src="../program_assets/js/site_essentials/sidebar.js?random=<?php echo uniqid(); ?>"></script>
		<script src="../program_assets/js/site_essentials/others.js?random=<?php echo uniqid(); ?>"></script>
		<script src="../program_assets/js/web_functions/events.js?random=<?php echo uniqid(); ?>"></script>
		
	</body>
</html>