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
        case "new_user" :
            
            $isNewUser          = $_POST["isNewUser"];
            $userID             = $_POST["userID"];
            $oldEmpID           = $_POST["oldEmpID"];
            $oldUsername        = $_POST["oldUsername"];
            $oldEmailAddress    = $_POST["oldEmailAddress"]; 
            $empID              = $_POST["empID"];
            $firstName          = $_POST["firstName"];
            $middleName         = $_POST["middleName"];
            $lastName           = $_POST["lastName"];
            $emailAddress       = $_POST["emailAddress"];
            $username           = $_POST["username"];
            $mobileNumber       = $_POST["mobileNumber"];
            $isActive           = $_POST["isActive"];
            
            $address             = $_POST["address"];
            $gender              = $_POST["gender"];
            $birthday            = $_POST["birthday"];
            $zipCode             = $_POST["zipCode"];
            $joinDate            = $_POST["joinDate"];
            $employeeType        = $_POST["employeeType"];
            $contactName         = $_POST["contactName"];
            $relationship        = $_POST["relationship"];
            $relationshipContact = $_POST["relationshipContact"];
            $relationshipAddress = $_POST["relationshipAddress"];
            
            
            $arr_exist       = array();
            
            if ($isNewUser == 1) {
                $find_email = mysqli_query($con,"SELECT * FROM yp_user_registration WHERE email = '$emailAddress'");
                if (mysqli_num_rows($find_email) != 0) {
                    mysqli_next_result($con);
                    array_push($arr_exist,"Email");
                }
                
                $find_user = mysqli_query($con,"SELECT * FROM yp_user_registration WHERE username = '$username'");
                if (mysqli_num_rows($find_user) != 0) {
                    mysqli_next_result($con);
                    array_push($arr_exist,"Username");
                }
                
                
                $find_empid = mysqli_query($con,"SELECT * FROM yp_user_registration WHERE employeeID = '$empID'");
                if (mysqli_num_rows($find_empid) != 0) {
                    mysqli_next_result($con);
                    array_push($arr_exist,"Employee ID");
                }
                
                if (count($arr_exist) != 0) {
                    $error   = true;
                    $color   = "orange";
                    $message = "";
                    
                    if (count($arr_exist) != 3) {
                        $message = implode(" and ",$arr_exist) . " already exist";
                    } else {
                        $message = $arr_exist[0] . "," . $arr_exist[1] . " and " . $arr_exist[2] . " " . " already exist";
                    }
                } else {
                    $query = "INSERT INTO yp_user_registration (firstName,middleName,lastName,employeeID,email,mobileNumber,username,password,dateCreated,
                    address,gender,birthday,zipCode,joinDate,employeeType,contactName,relationship,relationshipContact,relationshipAddress)
                    VALUES (?,?,?,?,?,?,?,MD5(?),?,?,?,?,?,?,?,?,?,?,?)";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"sssssssssssssssssss",$firstName,$middleName,$lastName,$empID,$emailAddress,$mobileNumber,$username,$username,$global_date,$address,$gender,$birthday,$zipCode,$joinDate,$employeeType,$contactName,$relationship,$relationshipContact,$relationshipAddress);
                        mysqli_stmt_execute($stmt);
                        
                        $error   = false;
                        $color   = "green";
                        $message = "Account has been save successfully"; 
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error saving account" . mysqli_error($con);
                    }
                }
            } else {
                if ($oldEmailAddress != $emailAddress) {
                    $find_email = mysqli_query($con,"SELECT * FROM yp_user_registration WHERE email = '$emailAddress'");
                    if (mysqli_num_rows($find_email) != 0) {
                        mysqli_next_result($con);
                        array_push($arr_exist,"Email");
                    }
                }
                
                if ($oldUsername != $username) {
                    $find_user = mysqli_query($con,"SELECT * FROM yp_user_registration WHERE username = '$username'");
                    if (mysqli_num_rows($find_user) != 0) {
                        mysqli_next_result($con);
                        array_push($arr_exist,"Username");
                    }
                }
                
                if ($oldEmpID != $empID) {
                    $find_empid = mysqli_query($con,"SELECT * FROM yp_user_registration WHERE employeeID = '$empID'");
                    if (mysqli_num_rows($find_empid) != 0) {
                        mysqli_next_result($con);
                        array_push($arr_exist,"Employee ID");
                    }
                }
                
                if (count($arr_exist) != 0) {
                    $error   = true;
                    $color   = "orange";
                    $message = "";
                    
                    if (count($arr_exist) != 3) {
                        $message = implode(" and ",$arr_exist) . " already exist";
                    } else {
                        $message = $arr_exist[0] . "," . $arr_exist[1] . " and " . $arr_exist[2] . " " . " already exist";
                    }
                } else {                    
                    $query = "UPDATE yp_user_registration SET firstName=?,middleName=?,lastName=?,employeeID=?,email=?,mobileNumber=?,username=?,isActive=?
                    ,address=?,gender=?,birthday=?,zipCode=?,joinDate=?,employeeType=?,contactName=?,relationship=?,relationshipContact=?,relationshipAddress=? WHERE id=?";
                    if ($stmt = mysqli_prepare($con, $query)) {
                        mysqli_stmt_bind_param($stmt,"sssssssssssssssssss",$firstName,$middleName,$lastName,$empID,$emailAddress,$mobileNumber,$username,$isActive,$address,$gender,$birthday,$zipCode,$joinDate,$employeeType,$contactName,$relationship,$relationshipContact,$relationshipAddress,$userID);
                        mysqli_stmt_execute($stmt);
                        
                        $error   = false;
                        $color   = "green";
                        $message = "Account has been updated successfully"; 
                    } else {
                        $error   = true;
                        $color   = "red";
                        $message = "Error updating account" . mysqli_error($con);
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
    
        case "reset_password" :
            
            $userID = $_POST["userID"];
            
            $query = "UPDATE yp_user_registration SET `password` = MD5(username),isPasswordChange = 0 WHERE id = ?";
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt,"s",$userID);
                mysqli_stmt_execute($stmt);
                
                $error   = false;
                $color   = "green";
                $message = "Password has been reset. Please inform the user that his current password is also same as his username"; 
            } else {
                $error   = true;
                $color   = "red";
                $message = "Error reseting password";
            }

            $json[] = array(
                'error' => $error,
                'color' => $color,
                'message' => $message
            );
            echo json_encode($json);
            
        break;
        
        case "display_user" :
            
            $sql = "
                SELECT
                    a.employeeID,
                    a.username,
                    a.firstName,
                    a.middleName,
                    a.lastName,
                    a.mobileNumber,
                    a.email,
                    IF(a.isActive = 1,'Active','Disabled') AS status,
                    DATE_FORMAT(a.dateCreated,'%M %d %Y %r') AS dateCreated,
                    IF(a.isActive = 1,true,false) AS isActive,
                    a.id,
                    address,
                    gender,
                    birthday,
                    zipCode,
                    joinDate,
                    employeeType,
                    contactName,
                    relationship,
                    relationshipContact,
                    relationshipAddress
                FROM
                    yp_user_registration a
                ORDER BY
                    a.employeeID
            ";
            return builder($con,$sql);
            
        break;

    }
    
    mysqli_close($con);
?>