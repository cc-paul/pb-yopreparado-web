<?php
    if(!isset($_SESSION)) { session_start(); }
    include dirname(__FILE__,2) . '/config.php';
    include $main_location . '/connection/conn.php';
    
    $eventID    = $_POST["eventID"];
    $videoName  = $_POST["videoName"];
    $uploadedBy = $_SESSION['id'];
    
    $filename = date('YmdHis', time());
    $error    = false;
    $color    = "green";
    $message  = "";
	
	if ( 0 < $_FILES['file']['error'] ) {
        
        $error   = true;
        $color   = "red";
        $message = "Error uploading files"; 
        
    } else {
        
        $find_video = mysqli_query($con,"SELECT * FROM yp_event_videos WHERE eventID = $eventID AND title = '$videoName' AND isActive = 1");
        if (mysqli_num_rows($find_video) != 0) {
            
            $error   = false;
            $color   = "green";
            $message = "Theres already a video that has a same title under the same category";
            
        } else {
            if (move_uploaded_file($_FILES['file']['tmp_name'], '../../../videos/' . $filename.".mp4")) {
                
                $query = "INSERT INTO yp_event_videos (eventID,fileName,uploadedBy,dateCreated,title) VALUES (?,?,?,?,?)";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"sssss",$eventID,$filename,$uploadedBy,$global_date,$videoName);
                    mysqli_stmt_execute($stmt);
                    
                    $error   = false;
                    $color   = "green";
                    $message = "Video has been added successfully";
                    
                } else {
                    
                    $error   = true;
                    $color   = "red";
                    $message = "There is an error uploading your video. Please try again later";
                    
                }

                
            } else {
                
                $error   = true;
                $color   = "red";
                $message = "There is an error uploading your video. Please try again later";
                
            }
        }
    }
    
    $json[] = array(
        'error' => $error,
        'color' => $color,
        'message' => $message
    );
    echo json_encode($json);
    
    mysqli_close($con);
?>