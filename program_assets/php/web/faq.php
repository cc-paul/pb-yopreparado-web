<?php
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\Exception;
    //
    require '../phpmailer/src/Exception.php';
    require '../phpmailer/src/PHPMailer.php';
    require '../phpmailer/src/SMTP.php';

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
        case "load_faq" :
            
            $sql = "
                SELECT 
                    a.id,
                    a.question,
                    a.answer,
                    DATE_FORMAT(a.dateCreated,'%M %d %Y %r') AS dateCreated,
                    CONCAT(IFNULL(b.lastName,''),', ',IFNULL(b.firstName,''),' ',IFNULL(b.middleName,'')) AS fullName
                FROM
                    yp_faq a 
                INNER JOIN
                    yp_user_registration b 
                ON 
                    a.createdBy = b.id 
                WHERE
                    a.isActive = 1
                ORDER BY
                    a.dateCreated DESC
            ";
            return builder($con,$sql);
            
        break;
    
        case "delete_faq":
            
            $faqID = $_POST["faqID"];
            
            $query = "UPDATE yp_faq SET isActive = 0 WHERE id = ?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$faqID);
                mysqli_stmt_execute($stmt);
               
                $error   = false;
                $color   = "green";
                $message = "FAQ has been deleted successfully"; 
               
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error deleting FAQ"; 
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "save_faq" :
            
            $faqID        = $_POST["faqID"];
            $oldQuestion  = $_POST["oldQuestion"];
            $question     = $_POST["question"];
            $answer       = $_POST["answer"];
            $id           = $_SESSION["id"];
            
            if ($faqID == 0) {

                $find_query = mysqli_query($con,"SELECT * FROM yp_faq WHERE question = '$question' AND isActive = 1");
                if (mysqli_num_rows($find_query) == 0) {
                    mysqli_next_result($con);
                   
                    $query = "INSERT INTO yp_faq (question,answer,dateCreated,createdBy) VALUES (?,?,?,?)";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"ssss",$question,$answer,$global_date,$id);
                        mysqli_stmt_execute($stmt);
                       
                        $error   = false;
                        $color   = "green";
                        $message = "FAQ has been saved successfully"; 
                       
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error saving FAQ"; 
                    }
                   
                } else {
                    $error   = false;
                    $color   = "green";
                    $message = "Question already exist"; 
                }

            } else {
                
                if ($oldQuestion != $question) {
                    $find_query = mysqli_query($con,"SELECT * FROM yp_faq WHERE question = '$question' AND isActive = 1");
                    if (mysqli_num_rows($find_query) == 0) {
                        mysqli_next_result($con);
                       
                        $query = "UPDATE yp_faq SET question = ?, answer = ? WHERE id = ?";
                        if ($stmt = mysqli_prepare($con, $query)) {
                            mysqli_stmt_bind_param($stmt,"sss",$question,$answer,$faqID);
                            mysqli_stmt_execute($stmt);
                           
                            $error   = false;
                            $color   = "green";
                            $message = "FAQ has been updated successfully"; 
                           
                        } else {
                            $error   = true;
                            $color   = "red";
                            $message = "Error updating FAQ"; 
                        }
                       
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Question already exist"; 
                    }
                } else {
                    $query = "UPDATE yp_faq SET question = ?, answer = ? WHERE id = ?";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"sss",$question,$answer,$faqID);
                        mysqli_stmt_execute($stmt);
                       
                        $error   = false;
                        $color   = "green";
                        $message = "FAQ has been updated successfully"; 
                       
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error updating FAQ"; 
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
    
        case "upload_file" :
            
            $filename    = $_POST["filename"];
            $type        = $_POST["type"];
            $createdBy   = $_SESSION["id"];
            $dateCreated = $global_date;
            $location   = '../../../files/' . $filename;
            
            
            $find_query = mysqli_query($con,"SELECT * FROM yp_files WHERE filename = '$filename' AND isActive = 1");
            if (mysqli_num_rows($find_query) == 0) {
                mysqli_next_result($con);
               
                $query = "INSERT INTO yp_files (filename,type,createdBy,dateCreated) VALUES (?,?,?,?)";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt,"ssss",$filename,$type,$createdBy,$dateCreated);
                    mysqli_stmt_execute($stmt);
                   
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                        $error   = false;
                        $color   = "green";
                        $message = "File has been uploaded successfully";
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "There is an error uploading your file. Please try again later";
                    }
                   
                } else {
                    $error   = true;
                    $color   = "red";
                    $message = "Error saving File"; 
                }
               
            } else {
                $error   = false;
                $color   = "orange";
                $message = "File already exist kindly delete before uploading a new one"; 
            }
            
            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
    
        case "list_file" :
            
            $filename = $_POST["search"];
            
            $sql = "
                SELECT 
                    a.id,
                    a.filename,
                    a.type,
                    DATE_FORMAT(a.dateCreated,'%b %d %Y %r') AS dateCreated,
                    CONCAT(IFNULL(b.lastName,''),', ',IFNULL(b.firstName,''),' ',IFNULL(b.middleName,'')) AS fullName
                FROM
                    yp_files a 
                INNER JOIN
                    yp_user_registration b 
                ON 
                    a.createdBy = b.id 
                WHERE
                    a.isActive = 1
                AND
                    a.filename LIKE '%$filename%'
                ORDER BY
                    a.dateCreated DESC;
            ";
            
            $result = mysqli_query($con,$sql);
            
            $json = array();
            while ($row  = mysqli_fetch_assoc($result)) {
                $json[] = array(
                    'id'  => $row["id"],
                    'filename'  => $row["filename"],
                    'type'  => $row["type"],
                    'dateCreated'  => $row["dateCreated"],
                    'fullName'  => $row["fullName"]
                );
            }
            echo json_encode($json);
            
        break;
    
        case "delete_file":
            
            $id = $_POST["id"];
            
            $query = "UPDATE yp_files SET isActive = 0 WHERE id = ?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"i",$id);
                mysqli_stmt_execute($stmt);
               
                $error   = false;
                $color   = "green";
                $message = "File has been deleted successfully"; 
               
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error deleting File"; 
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