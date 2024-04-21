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
        
            $sql    = "SELECT id,event,needRadius,needDuration FROM yp_event WHERE isActive = 1 AND hasImage = 1";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_row($result)) {
                $json[] = array(
                    'id'         => $row[0],
                    'event'      => $row[1],
                    'needRadius' => $row[2],
                    'needDuration' => $row[3]
                );
            }
            echo json_encode($json);
            
        break;
    
        case "delete_event" :
            
            $id = $_POST["id"];
            
            $query = "UPDATE yp_disaster_mapping SET isActive = 0 WHERE id = ?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$id);
                mysqli_stmt_execute($stmt);
                
                $error   = false;
            } else {
                $error   = true;
            }

            $json[] = array(
                'error' => $error
            );
            echo json_encode($json);
            
        break;
    
        case "load_disaster" :
            
            $sql    = "
                SELECT
                    a.*,
                    b.`event`,
                    a.radius,
                    IF(TIMESTAMPDIFF(SECOND,'$global_date',a.dateDuration) < 0,0,TIMESTAMPDIFF(SECOND,'$global_date',a.dateDuration)) AS dateSeconds,
                    b.needDuration,
                    CONCAT('Barangay ',c.barangayName) as barangayName
                FROM
                    yp_disaster_mapping a 
                INNER JOIN
                    yp_event b 
                ON 
                    a.eventID = b.id
                INNER JOIN
                    yp_barangay c
                ON
                    a.brgyId = c.id
                WHERE
                    a.isActive = 1
            ";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_assoc($result)) {
                $addIt = false;
                
                if ($row["dateSeconds"] > 0) {
                    $addIt = true;
                } else {
                    if ($row["needDuration"] == 0) {
                       $addIt = true;
                    }
                }
                
                if ($addIt) {
                     $json[] = array(
                        'id'      => $row["id"],
                        'eventID' => $row["eventID"],
                        'radius'  => $row["radius"],
                        'remarks' => $row["remarks"],
                        'lat'     => $row["lat"],
                        'lng'     => $row["lng"],
                        'event'   => $row["event"],
                        'radius'  => $row["radius"],
                        'alertLevel'  => $row["alertLevel"],
                        'passableVehicle'  => $row["passableVehicle"],
                        'dateCreated'  => $row["dateCreated"],
                        'dateDuration'  => $row["dateDuration"],
                        'dateSeconds' => $row["dateSeconds"],
                        'needDuration' => $row["needDuration"],
                        'barangayName' => $row["barangayName"]
                    );
                }
            }
            echo json_encode($json);
            
        break;
    
        case "save_disaster" :
            
            $eventID   = $_POST["eventID"];
            $radius    = $_POST["radius"];
            $remarks   = $_POST["remarks"];
            $lat       = $_POST["lat"];
            $lng       = $_POST["lng"];
            $alertLevel = $_POST["alertLevel"];
            $passableVehicle = $_POST["passableVehicle"];
            $dateCreated     = $global_date;
            $month           = $_POST["month"];
            $day             = $_POST["day"];
            $hour            = $_POST["hour"];
            $minute          = $_POST["minute"];
            $brgyId       = $_POST["brgyId"];
            
            $date = new DateTime('now');
            $date->modify('+'.$month.' month');
            $date->modify('+'.$day.' days');
            $date->modify('+'.$hour.' hours');
            $date->modify('+'.$minute.' minutes'); 
            
            $futureDate = $date->format('Y-m-d H:i:s');
            
            $query = "INSERT INTO yp_disaster_mapping (eventID,radius,remarks,lat,lng,alertLevel,passableVehicle,dateCreated,dateDuration,brgyId) VALUES (?,?,?,?,?,?,?,?,?,?)";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"sssssssssi",$eventID,$radius,$remarks,$lat,$lng,$alertLevel,$passableVehicle,$dateCreated,$futureDate,$brgyId);
                mysqli_stmt_execute($stmt);
                
                $error   = false;
                $color   = "green";
                $message = "Disaster has been saved successfully";
                
            } else {
                
                $error   = true;
                $color   = "red";
                $message = "Error saving disaster";
                
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "save_barangay" :
            
            $barangayName    = $_POST["barangayName"];
            $totalPopulation = $_POST["totalPopulation"];
            $lat             = $_POST["lat"];
            $lng             = $_POST["lng"];
            
            $find_query = mysqli_query($con,"SELECT * FROM yp_barangay WHERE barangayName = '$barangayName' AND isActive = 1");
            if (mysqli_num_rows($find_query) == 0) {
                mysqli_next_result($con);
                
                $query = "INSERT INTO yp_barangay (barangayName,totalPopulation,lat,lng) VALUES (?,?,?,?)";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"ssss",$barangayName,$totalPopulation,$lat,$lng);
                    mysqli_stmt_execute($stmt);
                    
                    $error   = false;
                    $color   = "green";
                    $message = "Barangay has been saved successfully";
                    
                } else {
                    
                    $error   = true;
                    $color   = "red";
                    $message = "Error saving barangay";
                    
                }
                
            } else {
                
                $error   = true;
                $color   = "red";
                $message = "Barangay already exist";
                
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "load_barangay" :
            
            header('Content-Type: application/json');
            
            $places  = array();
            $features = array();
            
            $sql  = "
                SELECT
                    a.barangayName,
                    a.lat,
                    a.lng
                FROM
                    yp_barangay a
                WHERE
                    a.isActive = 1
            ";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_row($result)) {
                
                $features[] = array(
                    'type'=> 'Feature',
                    'properties'=> array(
                        'description'=> 'Barangay ' . $row[0],
                        'icon'=> 'theatre'
                    ),
                    'geometry'=> array(
                        'type'=> 'Point',
                        'coordinates'=> [$row[2],$row[1]]
                    )
                );
            }
            
            $places = array(
                'type' => 'FeatureCollection',
                'features' => $features
            );
            echo json_encode($places);
            
        break;
    
        case "load_barangay_table" :
            
            $sql = "
                SELECT
                    a.id,   
                    CONCAT('Barangay ',a.barangayName) as barangayName,
                    a.lat,
                    a.lng,
                    REPLACE(FORMAT(a.totalPopulation,2),'.00','') AS population
                FROM
                    yp_barangay a
                WHERE
                    a.isActive = 1
            ";
            return builder($con,$sql);
            
        break;
    
        case "delete_barangay" :
            
            $id = $_POST["id"];
            
            $query = "UPDATE yp_barangay SET isActive = 0 WHERE id = ?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$id);
                mysqli_stmt_execute($stmt);
                
                $error   = false;
            } else {
                $error   = true;
            }

            $json[] = array(
                'error' => $error
            );
            echo json_encode($json);
            
        break;
    
        case "load_brgy_dd" :
            
            $sql    = "
                SELECT
                    a.id,   
                    CONCAT('Barangay ',a.barangayName) as barangayName
                FROM
                    yp_barangay a
                WHERE
                    a.isActive = 1
            ";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_assoc($result)) {
                $json[] = array(
                    'id' => $row["id"],
                    'barangayName' => $row["barangayName"],
                );
            }
            echo json_encode($json);
            
        break;
    
        case "edit_brgy_details" :
            
            $id              = $_POST["id"];
            $oldBarangayName = $_POST["oldBarangayName"];
            $barangayName        = $_POST["barangayName"];
            $totalPopulation      = $_POST["totalPopulation"];
            $isExist         = 1;
            $query           = "";
            
            if (strtolower($oldBarangayName) != strtolower($barangayName) ) {
                $find_query = mysqli_query($con,"SELECT * FROM yp_barangay WHERE barangayName = '$barangayName' AND isActive = 1");
                if (mysqli_num_rows($find_query) == 0) {
                    mysqli_next_result($con);
                    $isExist = 0;
                } else {
                    $error   = true;
                    $color   = "red";
                    $message = "Barangay already exist";
                }
            }
            
            if ($isExist == 0) {
                $query = "UPDATE yp_barangay SET barangayName=?,totalPopulation=? WHERE id=?";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"sss",$barangayName,$population,$id);
                    mysqli_stmt_execute($stmt);
                    
                    $error   = false;
                    $color   = "green";
                    $message = "Barangay has been updated successfully";
                    
                } else {
                    
                    $error   = true;
                    $color   = "red";
                    $message = "Error updating barangay" . mysqli_error($con);;
                    
                }
            } else {
                $query = "UPDATE yp_barangay SET totalPopulation=? WHERE id=?";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"ss",$population,$id);
                    mysqli_stmt_execute($stmt);
                    
                    $error   = false;
                    $color   = "green";
                    $message = "Barangay has been updated successfully";
                    
                } else {
                    
                    $error   = true;
                    $color   = "red";
                    $message = "Error updating barangay" . mysqli_error($con);;
                    
                }
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "logs" :
            
            $sql = "
                SELECT 
                    b.`event`,
                    a.radius,
                    a.remarks,
                    a.alertLevel,
                    a.passableVehicle,
                    DATE_FORMAT(a.dateCreated,'%M %d %Y %r') AS dateCreated,
                    CONCAT('Barangay ',c.barangayName) as barangayName
                FROM
                    yp_disaster_mapping a 
                INNER JOIN
                    yp_event b 
                ON 
                    a.eventID = b.id
                LEFT JOIN
                    yp_barangay c
                ON
                    a.brgyId = c.id
                WHERE
                    a.isActive = 1 
                AND 
                    TIMESTAMPDIFF(SECOND,'$global_date',a.dateDuration) < 0
                GROUP BY
                    a.id 
                ORDER BY
                    a.dateCreated DESC;
            ";
            return builder($con,$sql);
            
        break;
    }
    
    mysqli_close($con);
?>