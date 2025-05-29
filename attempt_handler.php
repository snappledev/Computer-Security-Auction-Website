<?php
//Attempt handler class will handle failed login attempts
/*
include("database.php");


function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function is_account_locked($IP){
    include("database.php");
    updateAttemptsFromTimeout($IP);
    $timenow = date('Y-m-d H:i:s');

    $STMT = $connection->prepare('SELECT locked_until FROM attempts WHERE ip = ? ');
    if ($STMT){
        $STMT->bind_param('s', $IP);
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($locked_until);
            $STMT->fetch();
            return ($locked_until > $timenow);
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}
function handle_failed_login($IP){
    include("database.php");
    //Grab IP
    updateAttemptsFromTimeout($IP);
    $locked = is_account_locked($IP);
    if ($locked){
        return;
    }

    $timeoutlength = 20 * 60;
    $time = date('Y-m-d H:i:s');
    $new_time = date('Y-m-d H:i:s', strtotime("+$timeoutlength seconds"));
    $STMT = $connection->prepare('SELECT id, attempts, last_attempt, locked_until FROM attempts WHERE ip = ? ');
    if ($STMT){
        $STMT->bind_param('s', $IP);
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($id, $attempts, $last_attempt, $locked_until);
            $STMT->fetch();
            $attempts = $attempts + 1;
            if ($attempts < 5){
                //Update attempts in table
                //Reject user
                $STMT = $connection->prepare("UPDATE attempts SET last_attempt=?, attempts=? WHERE ip=?");
                $STMT->bind_param("sss", $time, $attempts, $IP);
                $STMT->execute();
            }
            else{
                $STMT = $connection->prepare("UPDATE attempts SET last_attempt=?, attempts=?, locked_until=? WHERE ip = ?");
                $STMT->bind_param("ssss", $time, $attempts, $new_time, $IP);
                $STMT->execute();
            }
        }
        else{
            $sql = $connection->prepare("INSERT INTO attempts (IP, attempts, last_attempt) VALUES (? ,? ,?)");
            $sql->execute(array($IP, 0, $time));
        }
    }
    $_SESSION["attempts"] = get_attempts_left($IP);
}
function updateAttemptsFromTimeout($IP){
    //If we arent in a timeout segment then reset attempts
    include("database.php");
    $timenow = date('Y-m-d H:i:s');
    $STMT = $connection->prepare('SELECT attempts, locked_until FROM attempts WHERE ip = ? ');
    if ($STMT){
        $STMT->bind_param('s', $IP);
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($attempts, $locked_until);
            $STMT->fetch();
            if ($locked_until < $timenow && $attempts >=5){
                $STMT = $connection->prepare("UPDATE attempts SET attempts=? WHERE ip = ?");
                $STMT->bind_param("ss",0,  $IP);
                $STMT->execute();
            }

        }
    }
    $_SESSION["attempts"] = get_attempts_left($IP);
}


function get_attempts_left($IP){
    include("database.php");
    $timenow = date('Y-m-d H:i:s');
    $STMT = $connection->prepare('SELECT attempts FROM attempts WHERE ip = ? ');
    if ($STMT){
        $STMT->bind_param('s', $IP);
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($returned_attempts);
            $STMT->fetch();
            $_SESSION["attempts"] = $returned_attempts;
            return (5 - $returned_attempts);
        }
        else{
            return 0;
        }
    }
    else {
        return 0;
    }
}

function get_time_left($IP){
    include("database.php");
    $timenow = date('Y-m-d H:i:s');
    $STMT = $connection->prepare('SELECT locked_until FROM attempts WHERE ip = ? ');
    if ($STMT){
        $STMT->bind_param('s', $IP);
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($locked_time);
            $STMT->fetch();
            return $locked_time;
        }
        else{
            return 0;
        }
    }
    else {
        return 0;
    }
}
function reset_attempts($IP){
    //Function to basically reset or clear a users attempt record if they complete a succesful signin
    include("database.php");
    $STMT = $connection->prepare('SELECT attempts, locked_until FROM attempts WHERE ip = ? ');
    if ($STMT){
        $STMT->bind_param('s', $IP);
        $STMT->execute();
        $STMT->store_result();
        if ($STMT->num_rows > 0){
            $STMT->bind_result($attempts, $locked_until);
            $STMT->fetch();
            $STMT = $connection->prepare("UPDATE attempts SET attempts=? WHERE ip = ?");
            $attemptzero = 0;
            $STMT->bind_param("ss",$attemptzero,  $IP);
            $_SESSION["attempts"] = 0;
            $STMT->execute();

        }
    }
    $_SESSION["attempts"] = get_attempts_left($IP);

}

 */
 
 include("database.php");
include ("encryption_manager.php");
 function get_client_ip() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     else if(getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
     else
         $ipaddress = 'UNKNOWN';
     return $ipaddress;
 }

 function is_account_locked($IP){
     include("database.php");
     updateAttemptsFromTimeout($IP);
     $timenow = date('Y-m-d H:i:s');
     $STMT = $connection->prepare('SELECT ip, locked_until FROM attempts');
     if ($STMT){
         $STMT->execute();
         $STMT->store_result();
         $STMT->bind_result($stored_encrypted_ip, $locked_until);
         while ($STMT->fetch()) {
             $decrypted_ip = decrypt_data($stored_encrypted_ip);
             if ($decrypted_ip === $IP) {
                 $STMT->close();
                 return ($locked_until > $timenow);
             }
         }
         $STMT->close();
     }
     return false;
 }

 function handle_failed_login($IP){
     include("database.php");
     updateAttemptsFromTimeout($IP);
     $locked = is_account_locked($IP);
     if ($locked){
         return;
     }

     $timeoutlength = 20 * 60;
     $time = date('Y-m-d H:i:s');
     $new_time = date('Y-m-d H:i:s', strtotime("+$timeoutlength seconds"));
     $STMT = $connection->prepare('SELECT id, attempts, last_attempt, locked_until, ip  FROM attempts');
     if ($STMT){
         $STMT->execute();
         $STMT->store_result();
         if ($STMT->num_rows > 0){
             $STMT->bind_result($id, $attempts, $last_attempt, $locked_until, $stored_encrypted_ip);
             $bFound = false;
             while ($STMT->fetch()) {

                 $decrypted_ip = decrypt_data($stored_encrypted_ip);
                 if ($decrypted_ip === $IP) {
                     $bFound = true;
                     $attempts = $attempts + 1;
                     $STMT->close();
                     if ($attempts < 5){
                         $STMT = $connection->prepare("UPDATE attempts SET last_attempt=?, attempts=? WHERE ip=?");
                         $STMT->bind_param("sss", $time, $attempts, $stored_encrypted_ip);
                         $STMT->execute();
                         $STMT->close();
                     }
                     else{
                         $STMT = $connection->prepare("UPDATE attempts SET last_attempt=?, attempts=?, locked_until=? WHERE ip=?");
                         $STMT->bind_param("ssss", $time, $attempts, $new_time, $stored_encrypted_ip);
                         $STMT->execute();
                         $STMT->close();
                     }
                     break;
                 }
             }
             if (!$bFound){
                $STMT->close();
             }
         }
         else{

             $encrypted_data = encrypt_data($IP);
             $STMT->close();
             $sql = $connection->prepare("INSERT INTO attempts (ip, attempts, last_attempt) VALUES (?, 0, ?)");
             $sql->bind_param("ss", $encrypted_data, $time);
             $sql->execute();
             $STMT->close();
         }
     }
     $_SESSION["attempts"] = get_attempts_left($IP);
 }

 function updateAttemptsFromTimeout($IP){
     include("database.php");
     $timenow = date('Y-m-d H:i:s');
     $STMT = $connection->prepare('SELECT ip, attempts, locked_until FROM attempts');
     if ($STMT){
         $STMT->execute();
         $STMT->store_result();
         $STMT->bind_result($stored_encrypted_ip, $attempts, $locked_until);
         $bFound = false;
         while ($STMT->fetch()) {
             $decrypted_ip = decrypt_data($stored_encrypted_ip);
             if ($decrypted_ip === $IP && $locked_until < $timenow && $attempts >= 5) {
                 $STMT->close();
                 $bFound = true;
                 $STMT = $connection->prepare("UPDATE attempts SET attempts=? WHERE ip=?");
                 $STMT->bind_param("ss", 0, $stored_encrypted_ip);
                 $STMT->execute();
                 break;
             }
         }
         if (!$bFound){
            $STMT->close();
         }
     }
     $_SESSION["attempts"] = get_attempts_left($IP);
 }

 function get_attempts_left($IP){
     include("database.php");
     $timenow = date('Y-m-d H:i:s');
     $STMT = $connection->prepare('SELECT ip, attempts FROM attempts');
     if ($STMT){
         $STMT->execute();
         $STMT->store_result();
         $STMT->bind_result($stored_encrypted_ip,  $returned_attempts);
         while ($STMT->fetch()) {
             $decrypted_ip = decrypt_data($stored_encrypted_ip);
             if ($decrypted_ip === $IP) {
                 $_SESSION["attempts"] = $returned_attempts;
                 $STMT->close();
                 return (5 - $returned_attempts);
             }
         }
         $STMT->close();
     }

     return 0;
 }

 function get_time_left($IP){
     include("database.php");
     $timenow = date('Y-m-d H:i:s');
     $STMT = $connection->prepare('SELECT ip,locked_until FROM attempts');
     if ($STMT){
         $STMT->execute();
         $STMT->store_result();
         $STMT->bind_result($stored_encrypted_ip, $locked_time);
         while ($STMT->fetch()) {
             $decrypted_ip = decrypt_data($stored_encrypted_ip);
             if ($decrypted_ip === $IP) {
                 $STMT->close();
                 return $locked_time;
             }
         }
         $STMT->close();
     }
     return 0;
 }

 function reset_attempts($IP){
     include("database.php");
     $STMT = $connection->prepare('SELECT ip, attempts FROM attempts');
     if ($STMT){
         $STMT->execute();
         $STMT->store_result();
         $STMT->bind_result($stored_encrypted_ip, $attempts);
         $bFound = false;
         while ($STMT->fetch()) {
             $decrypted_ip = decrypt_data($stored_encrypted_ip);
             if ($decrypted_ip === $IP) {
                 $STMT->close();
                 $bFound = true;
                 $STMT = $connection->prepare("UPDATE attempts SET attempts=? WHERE ip=?");
                 $attemptzero = 0;
                 $STMT->bind_param("ss", $attemptzero, $stored_encrypted_ip);
                 $_SESSION["attempts"] = 0;
                 $STMT->execute();
                 break;

             }
         }
         if (!$bFound){
            $STMT->close();
         }
     }
     $_SESSION["attempts"] = get_attempts_left($IP);
 }

?>