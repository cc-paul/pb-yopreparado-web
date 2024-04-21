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
        case "display_hotline" :
            
            $sql = "
                SELECT
                    a.hotline,
                    a.mobileNumber,
                    a.telephoneNumber,
                    a.emailAddress,
                    IF(a.isActive = 1,'Active','Disabled') AS status,
                    DATE_FORMAT(a.dateCreated,'%M %d %Y %r') AS dateCreated,
                    IF(a.isActive = 1,true,false) AS isActive,
                    a.id
                FROM
                    yp_hotline a
                WHERE
                    a.isRemoved = 0
                ORDER BY
                    a.dateCreated DESC;
            ";
            return builder($con,$sql);
            
        break;
    
        case "save_hotline" :
        
            $hotline         = $_POST["hotline"];
            $oldHotline      = $_POST["oldHotline"];
            $mobileNumber    = $_POST["mobileNumber"];
            $telephoneNumber = $_POST["telephoneNumber"];
            $emailAddress    = $_POST["emailAddress"];
            $isActive        = $_POST["isActive"];
            $isNewHotline    = $_POST["isNewHotline"];
            $id              = $_POST["id"];
            $arr_exist       = array();
            
            if ($isNewHotline == 1) {
                $find_hotline = mysqli_query($con,"SELECT * FROM yp_hotline WHERE hotline = '$hotline'");
                if (mysqli_num_rows($find_hotline) != 0) {
                    mysqli_next_result($con);
                    array_push($arr_exist,"Hotline");
                }
                
                if (count($arr_exist) == 0) {
                    $query = "INSERT INTO yp_hotline (hotline,mobileNumber,telephoneNumber,emailAddress,isActive,dateCreated)
                    VALUES (?,?,?,?,?,?)";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"ssssss",$hotline,$mobileNumber,$telephoneNumber,$emailAddress,$isActive,$global_date);
                        mysqli_stmt_execute($stmt);
                        
                        $error   = false;
                        $color   = "green";
                        $message = "Hotline has been save successfully"; 
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error saving hotline" . mysqli_error($con);
                    }
                } else {
                    $error   = true;
                    $color   = "orange";
                    $message = "Hotline already exist";
                }
            } else {
                if (strtolower($oldHotline) != strtolower($hotline)) {
                    $find_hotline = mysqli_query($con,"SELECT * FROM yp_hotline WHERE hotline = '$hotline'");
                    if (mysqli_num_rows($find_hotline) != 0) {
                        mysqli_next_result($con);
                        array_push($arr_exist,"Hotline");
                    }
                }
                
                if (count($arr_exist) == 0) {
                    $query = "UPDATE yp_hotline SET hotline=?,mobileNumber=?,telephoneNumber=?,emailAddress=?,isActive=? WHERE id=?";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"ssssss",$hotline,$mobileNumber,$telephoneNumber,$emailAddress,$isActive,$id);
                        mysqli_stmt_execute($stmt);
                        
                        $error   = false;
                        $color   = "green";
                        $message = "Hotline has been save successfully"; 
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error saving hotline" . mysqli_error($con);
                    }
                } else {
                    $error   = true;
                    $color   = "orange";
                    $message = "Hotline already exist";
                }
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "delete_hotline" :

            $id = $_POST["id"];
        
            $query = "UPDATE yp_hotline SET isRemoved = 1 WHERE id = ?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$id);
                mysqli_stmt_execute($stmt);
                
                $error   = false;
                $color   = "green";
                $message = "Hotline has been removed successfully"; 
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error removing hotline" . mysqli_error($con);
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
        
        case "contingency_plan" :
            
            $content = $_POST["content"];
            
            $query = "DELETE FROM yp_contingency_plan";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_execute($stmt);
               
                $query = "REPLACE INTO yp_contingency_plan (document) VALUES (?)";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"s",$content);
                    mysqli_stmt_execute($stmt);
                   
                    $error   = false;
                    $color   = "green";
                    $message = "Content has been saved"; 
                   
                } else {
                    $error   = true;
                    $color   = "red";
                    $message = "Unable to save content"; 
                }
               
            } else {
                $error   = true;
                $color   = "red";
                $message = "Unable to delete content"; 
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
                        
        break;
    
        case "drrm" :
            
            $content = $_POST["content"];
            
            $query = "DELETE FROM yp_drrm";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_execute($stmt);
               
                $query = "REPLACE INTO yp_drrm (document) VALUES (?)";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"s",$content);
                    mysqli_stmt_execute($stmt);
                   
                    $error   = false;
                    $color   = "green";
                    $message = "Content has been saved"; 
                   
                } else {
                    $error   = true;
                    $color   = "red";
                    $message = "Unable to save content"; 
                }
               
            } else {
                $error   = true;
                $color   = "red";
                $message = "Unable to delete content"; 
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
                        
        break;
        
        case "display_contingency_plan" :
            
            $sql    = "SELECT * FROM yp_contingency_plan";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_assoc($result)) {
                $json[] = array(
                    'document' => $row["document"],
                );
            }
            echo json_encode($json);
            
        break;
    
        case "display_drrm" :
            
            $sql    = "SELECT * FROM yp_drrm";
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_assoc($result)) {
                $json[] = array(
                    'document' => $row["document"],
                );
            }
            echo json_encode($json);
            
        break;
    }
    
    mysqli_close($con);
?>