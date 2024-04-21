<?php
    if(!isset($_SESSION)) { session_start(); } 
    include 'appkey_generator.php';
    include dirname(__FILE__,2) . '/config.php';
    include $main_location . '/connection/conn.php';
    include '../builder/builder_select.php';
    include '../builder/builder_table.php';
    
    $command = $_POST["command"];
    $error   = false;
    $color   = "green";
    $message = "";
    $json    = array();
    
    switch ($command) {
        case "display_event" :
            
            $sql = "
                SELECT
                    a.event,
                    a.description,
                    IF(a.isActive = 1,'Active','Disabled') AS status,
                    DATE_FORMAT(a.dateCreated,'%M %d %Y %r') AS dateCreated,
                    IF(a.isActive = 1,true,false) AS isActive,
                    a.id,
                    a.hasImage,
                    a.origin,
                    a.needRadius,
                    a.needDuration
                FROM
                    yp_event a
                ORDER BY
                    a.dateCreated DESC;
            ";
            return builder($con,$sql);
            
        break;
    
        case "display_event_video" :
            
            $search = $_POST["search"];
            
            $sql    = "
                SELECT
                    a.id,
                    c.`event`,
                    a.fileName,
                    a.uploadedBy AS userID,
                    CONCAT(b.lastName,', ',b.firstName,' ',b.middleName) AS uploadedBy,
                    DATE_FORMAT(a.dateCreated,'%M %d %Y @ %r') AS dateCreated,
                    a.title,
                    a.hasThumbnail,
                    a.isActive,
                    a.isPrimary,
                    a.eventID
                FROM
                    yp_event_videos a 
                INNER JOIN
                    yp_user_registration b 
                ON
                    a.uploadedBy = b.id 
                INNER JOIN
                    yp_event c 
                ON 
                    a.eventID = c.id
                WHERE
                    c.`event` LIKE '%$search%'
                OR
                    a.`fileName` LIKE '%$search%'
                OR
                    CONCAT(b.lastName,', ',b.firstName,' ',b.middleName) LIKE '%$search%' 
                ORDER BY
                    a.dateCreated DESC;
            ";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_row($result)) {
                if ($row[8] == 1) {
                    $json[] = array(
                        'id'           => $row[0],
                        'event'        => $row[1],
                        'fileName'     => $row[2],
                        'userID'       => $row[3],
                        'uploadedBy'   => $row[4],
                        'dateCreated'  => $row[5],
                        'title'        => $row[6],
                        'hasThumbnail' => $row[7],
                        'isPrimary'    => $row[9],
                        'eventID'      => $row[10]
                    );
                }
            }
            echo json_encode($json);
                        
        break;
    
        case "display_select_event" :
            
            $sql = "
                SELECT
                    a.id,
                    a.event
                FROM
                    yp_event a
                WHERE
                    a.isActive = 1
                ORDER BY
                    a.event ASC;
            ";
            
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_row($result)) {
                $json[] = array(
                    'id'    => $row[0],
                    'event' => $row[1]
                );
            }
            echo json_encode($json);
            
        break;
    
        case "save_event" :
        
            $eventID         = $_POST["eventID"];
            $isNewEvent      = $_POST["isNewEvent"];
            $event           = $_POST["event"];
            $description     = $_POST["description"];
            $oldEventName    = $_POST["oldEventName"];
            $origin          = $_POST["origin"];
            $isActive        = $_POST["isActive"];
            $needRadius      = $_POST["needRadius"];
            $needDuration    = $_POST["needDuration"];
            $arr_exist       = array();
            
            if ($isNewEvent == 1) {
                $find_event = mysqli_query($con,"SELECT * FROM yp_event WHERE event = '$event'");
                if (mysqli_num_rows($find_event) != 0) {
                    mysqli_next_result($con);
                    array_push($arr_exist,"Event");
                }
                
                if (count($arr_exist) == 0) {
                    $query = "INSERT INTO yp_event (event,isActive,dateCreated,description,origin,needRadius,needDuration)
                    VALUES (?,?,?,?,?,?,?)";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"sssssss",$event,$isActive,$global_date,$description,$origin,$needRadius,$needDuration);
                        mysqli_stmt_execute($stmt);
                        
                        $error   = false;
                        $color   = "green";
                        $message = "Event has been save successfully"; 
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error saving event" . mysqli_error($con);
                    }
                } else {
                    $error   = true;
                    $color   = "orange";
                    $message = "Event already exist";
                }
            } else {
                if (strtolower($event) != strtolower($oldEventName)) {
                    $find_event = mysqli_query($con,"SELECT * FROM yp_event WHERE event = '$event'");
                    if (mysqli_num_rows($find_event) != 0) {
                        mysqli_next_result($con);
                        array_push($arr_exist,"Event");
                    }
                }
                
                if (count($arr_exist) == 0) {
                    $query = "UPDATE yp_event SET event=?,isActive=?,description=?,origin=?,needRadius=?,needDuration=? WHERE id=?";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"sssssss",$event,$isActive,$description,$origin,$needRadius,$needDuration,$eventID);
                        mysqli_stmt_execute($stmt);
                        
                        $error   = false;
                        $color   = "green";
                        $message = "Event has been save successfully"; 
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error saving Event" . mysqli_error($con);
                    }
                } else {
                    $error   = true;
                    $color   = "orange";
                    $message = "Event already exist";
                }
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "delete_video" :
            
            $id = $_POST["id"];
            
            $query = "UPDATE yp_event_videos SET isActive = 0 WHERE id=?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$id);
                mysqli_stmt_execute($stmt);
                
                $error   = false;
                $color   = "green";
                $message = "Video has been removed successfully"; 
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error removing video " . mysqli_error($con);
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "primary_video" :
            
            $id         = $_POST["id"];
            $categoryID = $_POST["categoryID"];
            
            $query = "UPDATE yp_event_videos SET isPrimary = 0 WHERE eventID=?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$categoryID);
                mysqli_stmt_execute($stmt);
                
                $query = "UPDATE yp_event_videos SET isPrimary = 1 WHERE id=?";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"s",$id);
                    mysqli_stmt_execute($stmt);
                    
                    $error   = false;
                    $color   = "green";
                    $message = "Video has been set to primary"; 
                } else {
                    $error   = true;
                    $color   = "red";
                    $message = "Error setting video as primary" . mysqli_error($con);
                }
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error setting video as primary" . mysqli_error($con);
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "save_dosdonts" :
            
            $eventID = $_POST["eventID"];
            $isDo    = $_POST["isDo"];
            $details = str_replace("'","",$_POST["details"]);
            $isNewDosDonts = $_POST["isNewDosDonts"];
            $doID = $_POST["doId"];
            $oldIsDo = $_POST["oldIsDo"];
            $oldDetails = $_POST["oldDetails"];
            $oldIsDoId = $_POST["oldIsDoId"];
            $category = $_POST["category"];
            
            if ($isNewDosDonts == 1) {
                $find_query = mysqli_query($con,"SELECT * FROM yp_dosdonts WHERE eventID = $eventID AND isDo = $isDo AND details = '$details' AND isDeleted = 0");
                if (mysqli_num_rows($find_query) == 0) {
                    mysqli_next_result($con);
                   
                    $query = "INSERT INTO yp_dosdonts (eventID,isDo,details,category) VALUES (?,?,?,?)";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"ssss",$eventID,$isDo,$details,$category);
                        mysqli_stmt_execute($stmt);
                       
                        $error   = false;
                        $color   = "green";
                        $message = "Dos and Donts has been saved successfully"; 
                       
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error saving Dos and Donts"; 
                    }
                   
                } else {
                    $error   = true;
                    $color   = "red";
                    $message = "Dos and Donts already exist"; 
                }
            } else {
                if ($oldIsDoId != $doID || $oldIsDo != $isDo || $details != $oldDetails) {
                    $find_query = mysqli_query($con,"SELECT * FROM yp_dosdonts WHERE eventID = $eventID AND isDo = $isDo AND details = '$details' AND isDeleted = 0");
                    if (mysqli_num_rows($find_query) == 0) {
                        mysqli_next_result($con);
                       
                        $query = "UPDATE yp_dosdonts SET isDo=?,details=?,category=? WHERE id = ?";
                        if ($stmt = mysqli_prepare($con, $query)) {
                            mysqli_stmt_bind_param($stmt,"ssss",$isDo,$details,$category,$doID);
                            mysqli_stmt_execute($stmt);
                           
                            $error   = false;
                            $color   = "green";
                            $message = "Dos and Donts has been updated successfully"; 
                           
                        } else {
                            $error   = true;
                            $color   = "red";
                            $message = "Error updating Dos and Donts"; 
                        }
                       
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Dos and Donts already exist"; 
                    }
                } else {
                    $query = "UPDATE yp_dosdonts SET isDo=?,details=?,category=? WHERE id = ?";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"ssss",$isDo,$details,$category,$doID);
                        mysqli_stmt_execute($stmt);
                       
                        $error   = false;
                        $color   = "green";
                        $message = "Dos and Donts has been updated successfully"; 
                       
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error updating Dos and Donts"; 
                    }
                }
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
        
        case "load_dosdonts" :
            
            $eventID = $_POST["eventID"];
            
            $sql = "
                SELECT * FROM yp_dosdonts WHERE eventID = $eventID AND isDeleted = 0 ORDER BY isDo DESC;
            ";
            return builder($con,$sql);
            
        break;
    
        case "remove_dosdonts" :
            
            $id = $_POST["id"];
            
            $query = "UPDATE yp_dosdonts SET isDeleted = 1 WHERE id = ?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$id);
                mysqli_stmt_execute($stmt);
               
                $error   = false;
                $color   = "green";
                $message = "Dos and Donts has been removed"; 
               
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error removing Dos and Donts"; 
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    }
    
    mysqli_close($con);
?>