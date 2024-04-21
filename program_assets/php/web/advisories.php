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
    
    switch($command) {
        case "load_events" :
            
            $sql    = "
                SELECT
                    a.id,
                    a.`event`,
                    a.description,
                    a.hasImage
                FROM
                    yp_event a 
                WHERE
                    a.isActive = 1
            ";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_assoc($result)) {
                $json[] = array(
                    'id'          => $row["id"],
                    'event'       => $row["event"],
                    'description' => $row["description"],
                    'hasImage'    => $row["hasImage"]
                );
            }
            echo json_encode($json);
            
        break;
        
        case "save_notif" :
            
            $createdBy = $_SESSION["id"];
            $eventID   = $_POST["eventID"];
            $title     = $_POST["title"];
            $body      = $_POST["body"];
            
            $query = "INSERT yp_notification (eventID,title,body,dateCreated,createdBy) VALUES (?,?,?,?,?)";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"sssss",$eventID,$title,$body,$global_date,$createdBy);
                mysqli_stmt_execute($stmt);
               
                $error   = false;
                $color   = "green";
                $message = "Notication has been sent to everyone"; 
               
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error sending notication. Please try again later"; 
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "show_notif" :
            
            $sql = "
                SELECT 
                    b.`event`,
                    a.title,
                    a.body,
                    DATE_FORMAT(a.dateCreated,'%M %d %Y %r') AS dateCreated,
                    CONCAT(IFNULL(c.lastName,''),', ',IFNULL(c.firstName,''),' ',IFNULL(c.middleName,'')) AS fullName
                FROM
                    yp_notification a 
                INNER JOIN
                    yp_event b 
                ON 
                    a.eventID = b.id 
                INNER JOIN
                    yp_user_registration c 
                ON 
                    a.createdBy = c.id
                ORDER BY
                    a.dateCreated DESC
            ";
            return builder($con,$sql);
            
        break;
    }
    
    mysqli_close($con);    
?>