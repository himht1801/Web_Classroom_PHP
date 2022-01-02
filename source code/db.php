<?php
    include 'connection.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    function sendActivationEmail($email, $token)
    {
        require 'vendor/autoload.php';



        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->CharSet = 'UTF-8';
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'drom97977@gmail.com';                     // SMTP username
            $mail->Password   = 'zxhpggpufhxsiblc';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('drom97977@gmail.com', 'Admin TDTU Classroom');
            $mail->addAddress($email, 'Người nhận');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Xác minh tài khoản';
            $mail->Body    = "Vui lòng click  <a href='http://localhost/active.php?email=$email&token=$token'> vào đây </a> để xác minh tài khoản của mình";
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function sendResetPassword($email, $token)
    {
        require 'vendor/autoload.php';



        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->CharSet = 'UTF-8';
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'drom97977@gmail.com';                     // SMTP username
            $mail->Password   = 'zxhpggpufhxsiblc';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('drom97977@gmail.com', 'Admin TDTU Classroom');
            $mail->addAddress($email, 'Người nhận');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Khôi phục tài khoản';
            $mail->Body    = "Vui lòng click  <a href='http://localhost/reset_password.php?email=$email&token=$token'> vào đây </a> để khôi phục tài khoản của mình";
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function check_login($user,$pass){
        $error = '';
        $code = 1;
        $name_user = '';
        $sql = "SELECT * FROM account WHERE username='$user'";
        $conn = openMySQLConnection();
        $result = $conn->query($sql);
        if (!$result) {
            trigger_error('Invalid query: ' . $conn->error);
        }
        $rowcount = $result->num_rows;
        if (empty($user)) {
            $error = 'Please enter your username';
            return array('code'=>1,'error'=>$error);
        } else if (empty($pass)) {
            $error = 'Please enter your password';
            return array('code'=>1,'error'=>$error);
        } else if (strlen($pass) < 6) {
            $error = 'Password must have at least 6 characters';
            return array('code'=>1,'error'=>$error);
        } else if ($rowcount > 0) {
            $row = mysqli_fetch_row($result);
            $hash_pass = $row[4];
            if (password_verify($pass, $hash_pass) and $row[5] == 1) {
                $name_user =  $row[1] . ' ' . $row[2];
                $code = 0;
                return array('code'=>0,'user'=>$user,'name_user'=>$name_user);
            } else if (password_verify($pass, $hash_pass) and $row[5] == 0) {
                $error = 'Account not activated';
                return array('code'=>1,'error'=>$error);
            } else {
                $error = 'Invalid username or password';
                return array('code'=>1,'error'=>$error);
            }
        } else {
            $error = 'Invalid username or password';
            return array('code'=>1,'error'=>$error);
        }
    }

        // check exist email in database, if exist return true, else return false
    function exist_email($email)
    {
        $tmp = 'select * from account where email = ?';
        $conn = openMySQLConnection();
        $sql = $conn->prepare($tmp);
        $sql->bind_param('s', $email);

        if (!$sql->execute()) {
            die('Query error' . $sql->error);
        }
        $result = $sql->get_result();
        if ($result->num_rows > 0) {
            // email exists
            return true;
        } else {
            // email not exists
            return false;
        }
        $count = $result->fetch_assoc();
    }

    /// check exist username in database, if exist return true, else return false
    function exist_username($user)
    {
        $tmp = 'select * from account where username = ?';
        $conn = openMySQLConnection();
        $sql = $conn->prepare($tmp);
        $sql->bind_param('s', $user);

        if (!$sql->execute()) {
            die('Query error' . $sql->error);
        }
        $result = $sql->get_result();
        if ($result->num_rows > 0) {
            // username exists
            return true;
        } else {
            // username not exists
            return false;
        }
        $count = $result->fetch_assoc();
    }

        // save new account to database
    function register($user, $pass, $first_name, $last_name, $email)
    {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $rand = random_int(0, 1000);
        $token = md5($user . '+' . $rand);
        $tmp = 'INSERT INTO `account`(`username`, `firstname`, `lastname`, `email`, `password`, `activate_token`) VALUES (?,?,?,?,?,?) ';
        $conn = openMySQLConnection();
        $sql = $conn->prepare($tmp);
        $sql->bind_param('ssssss', $user, $first_name, $last_name, $email, $hash, $token);
        if (!$sql->execute()) {
            return array('code' => 2, 'error' => 'cannot execute command');
        } else {
            sendActivationEmail($email, $token);
            return array('code' => 0, 'error' => 'Create account successful');
        }
    }

    // active account when account not active
    function activeAccount($email, $token)
    {
        $row_counter = 0;
        $sql = "select username from account where email = ? and activate_token = ? and activated = 0";
        $conn = openMySQLConnection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss', $email, $token);
        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'cannot execute command');
        }
        $result = $stm->get_result();
        if ($result->num_rows == 0) {
            return array('code' => 1, 'error' => 'Email or token not found');
        }
        $sql = "update account set activated = 1, activate_token = '' where email = ? ";
        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $email);
        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'cannot execute command');
        }
        $result = $stm->get_result();
        $row_counter = $result->num_rows;
        if ( $row_counter== 0) {
            return array('code' => 1, 'error' => 'Email or token not found');
        }
    }

    // change password
    function change_password($user, $pass){
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $rand = random_int(0, 1000);
        $token = md5($user . '+' . $rand);
        $conn = openMySQLConnection();
        $sql = "UPDATE `account` SET `password`=? , `activate_token`=? WHERE `username`=?";
        $stmt = $conn->prepare(($sql));
        $stmt->bind_param('sss',$hash,$token,$user);
        if (!$stmt->execute()) {
            return array('code' => 2, 'error' => 'cannot execute command');
        } else {
            return array('code' => 0, 'error' => 'Create account successful');
        }
    }

    //check password có hợp lệ hay không ? nếu có return code = 0 else  !=0
    function authentic_password($user,$pass){
        $error = '';
        $code = 1;
        $name_user = '';
        $sql = "SELECT * FROM account WHERE username='$user'";
        $conn = openMySQLConnection();
        $result = $conn->query($sql);
        if (!$result) {
            trigger_error('Invalid query: ' . $conn->error);
        }
        $rowcount = $result->num_rows;
        
        if ($rowcount > 0) {
            $row = mysqli_fetch_row($result);
            $hash_pass = $row[4];
            if (password_verify($pass, $hash_pass) and $row[5] == 1) {
                $name_user =  $row[1] . ' ' . $row[2];
                $code = 0;
                return array('code'=>0,'user'=>$user,'name_user'=>$name_user);
            } else if (password_verify($pass, $hash_pass) and $row[5] == 0) {
                $error = 'Account not activated';
                return array('code'=>1,'error'=>$error);
            } else {
                $error = 'Invalid username or password';
                return array('code'=>1,'error'=>$error);
            }
        } else {
            $error = 'Invalid username or password';
            return array('code'=>1,'error'=>$error);
        }
    }

    /// change password
    function checkResetPassword($email,$token){
        $sql = 'select * from reset_token where token = ? and email = ?';
        $conn = openMySQLConnection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$token,$email);
        if (!$stm->execute()) {
            return false;

        }
        $result = $stm->get_result();
        $counter_rows = $result->num_rows;
        if ($counter_rows > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    function updatePassword($email,$pass){

        $rand = random_int(0, 1000);
        $token = md5($email . '+#(ssf@@$)' . $rand);
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $tmp = 'UPDATE `account` SET `password` = ? , `activate_token` = ? WHERE `email` = ? ';
        $conn = openMySQLConnection();

        $sql = $conn->prepare($tmp);
        $sql->bind_param('sss', $hash, $token, $email);
        $result = $sql->execute();
        if (!$result) {
            return array('code' => 2, 'error' => 'cannot execute command');
        } else {
            $key = resetTokenAfterReset($email);
            return array('code' => 0, 'error' => 'Create account successful');
        }

    }
    function resetTokenAfterReset($email){
        $conn = openMySQLConnection();
        $rand = random_int(0, 71215465244513456);
        $sql = 'update reset_token set token = ? where email = ?';
        $token = md5($email . '+da2610225032aaf#(ssf@@$)' .$rand);
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$token,$email);
        if (!$stm->execute()) {
            return array('code' => 2, 'error' => 'cannot execute command');
        } else {
            return array('code' => 0, 'error' => 'update token successful');
        }
    }

    // function join_class_by_code($username, $key){
        
    // }


    //remove row class in sql
    function remove_class_by_key($key_delete){
        $sql = "DELETE FROM `lophoc` WHERE `codelop`='$key_delete'";
        $conn = openMySQLConnection();
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    //check role admin hoặc gv, nếu hợp lê thì true, else thì false
    function check_role($username,$keylop){
        $codeRole = 99;
        $sql = "SELECT role FROM account_lophoc INNER JOIN lophoc on account_lophoc.idLop = lophoc.idLop WHERE account_lophoc.username ='$username' AND lophoc.codelop = '$keylop'";
        $conn = openMySQLConnection();
        $result = $conn->query($sql);
        if(!$result){
            trigger_error('Invalid query' . $conn->error);
        }
        
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            $codeRole = $row['role'];
        }
        if($codeRole<2){
            return true;
        }
        else{
            return false;
        }
    }

    function update_class($key,$ten,$mota, $phan, $phong, $chude ){
        $sql = "UPDATE `lophoc` SET `tenLop`='$ten',`motaLop`='$mota',`phanHoc`='$phan',`phongHoc`='$phong',`chude`='$chude' WHERE `codelop`='$key'";
        $conn = openMySQLConnection();
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    function create_class($ten,$mota, $phan, $phong, $chude,$username){
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789');
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, 15) as $k) $rand .= $seed[$k];
        //genaration code;
        $sql = "INSERT INTO `lophoc`(`idLop`, `tenLop`, `motaLop`, `phanHoc`, `phongHoc`, `chude`, `codelop`) VALUES (null, '$ten','$mota','$phan','$phong','$chude','$rand')";
        $conn = openMySQLConnection();
        if($conn->query($sql)){
            if($username!='admin'){
                $sql_new = "INSERT INTO `account_lophoc`(`idaccount_lophoc`, `idLop`, `username`, `role`) VALUES (null,(SELECT lophoc.idLop FROM lophoc WHERE lophoc.codelop='$rand'),'$username',1), (null,(SELECT lophoc.idLop FROM lophoc WHERE lophoc.codelop='$rand'),'admin',1) ";
            }
            else{
                $sql_new = "INSERT INTO `account_lophoc`(`idaccount_lophoc`, `idLop`, `username`, `role`) VALUES (null,(SELECT lophoc.idLop FROM lophoc WHERE lophoc.codelop='$rand'),'$username',1)";
            }
            if($conn->query($sql_new)){
                return true;
            }
            else{
                $sql_del = "DELETE FROM `lophoc` WHERE `codelop` = '$rand'";
                $conn->query($sql_del);
                return false;
            }
        }
        else{
            return false;
        }
    }
    

    function check_permission($code, $username){
        $conn = openMySQLConnection();
        $sql = "SELECT COUNT(*) FROM lophoc INNER JOIN account_lophoc On lophoc.idLop = account_lophoc.idLop WHERE account_lophoc.username ='$username' AND lophoc.codelop = '$code'";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_count = $row['0'];
            if($row_count==0){
                return false;
            }
            else{
                return true;
            }
        }
    }

    function reomve_person_by_username($username,$code){
        $conn = openMySQLConnection();
        $sql = "DELETE FROM `account_lophoc` WHERE username='$username' AND idLop=(SELECT idLop FROM lophoc WHERE lophoc.codelop='$code')";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
        
    }

    function get_name_class_by_code($code){
        $conn = openMySQLConnection();
        $sql = "SELECT concat(`tenLop`,' ')as'nameclass' FROM lophoc WHERE codelop='$code'";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $name_class = $row['0'];
            return $name_class;
        }
        else{
            return '';
        }
    }

    function update_role_user_by_code($code,$username,$role){
        $conn = openMySQLConnection();
        $sql = "UPDATE `account_lophoc` SET role=$role WHERE `username`='$username' AND `idLop`=(SELECT idLop FROM lophoc WHERE lophoc.codelop='$code')";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }
    
    function add_user_to_class_by_email($code,$email,$role){
        $conn = openMySQLConnection();
        $sql = "INSERT INTO `account_lophoc`(`idaccount_lophoc`, `idLop`, `username`, `role`) VALUES (null,(SELECT idLop FROM lophoc WHERE lophoc.codelop='$code'),(SELECT username FROM account WHERE account.email = '$email'),$role)";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }


    function check_user_in_class_by_email($email,$code){
        $conn = openMySQLConnection();
        $sql = "SELECT COUNT(*) FROM account_lophoc WHERE account_lophoc.idLop = (SELECT idLop FROM lophoc WHERE lophoc.codelop='$code') AND username = (SELECT username FROM account WHERE account.email='$email')";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_count = $row['0'];
            if($row_count==0){
                return false;
            }
            else{
                return true;
            }
        }
    }

    function have_class_by_code($code){
        $sql = "SELECT COUNT(*) FROM lophoc WHERE lophoc.codelop = '$code'";
        $conn = openMySQLConnection();
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_count = $row['0'];
            if($row_count==0){
                return false;
            }
            else{
                return true;
            }
        }
    }

    function sendMailInvited($email,$code,$nameGV){
        require 'vendor/autoload.php';



        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->CharSet = 'UTF-8';
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'drom97977@gmail.com';                     // SMTP username
            $mail->Password   = 'zxhpggpufhxsiblc';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('drom97977@gmail.com', 'Admin TDTU Classroom');
            $mail->addAddress($email, 'Người nhận');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $nameGV.' mời bạn làm thành viên của lớp '.get_name_class_by_code($code);
            $mail->Body    = "Vui lòng click  <a href='http://localhost/invited.php?email=$email&code=$code'> vào đây </a> để vào nhóm này";
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function get_mail_by_username($username){
        $conn = openMySQLConnection();
        $sql = "SELECT `email` FROM `account` WHERE username='$username'";
        $row_email = "";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_email = $row['0'];
        }
        else{
            $row_email = "";
        }
        return $row_email;
    }

    ///status = 0 là được mời, else là tự tham gia
    function insert_pendding_invite($username,$code,$role,$status){
        $conn = openMySQLConnection();
        $sql = "INSERT INTO `pending_invited`(`idinvite`, `username`, `codelophoc`, `role`, `status`) VALUES (null,(select username from account where email ='$username'),'$code','$role',$status)";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    function get_name_by_username($username){
        $sql = "SELECT concat( `firstname`,' ' ,`lastname`) FROM `account` WHERE username='$username'";
        $conn = openMySQLConnection();
        $row_name = "";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_name = $row['0'];
        }
        else{
            $row_name = "";
        }
        return $row_name;

    }
    function have_in_pending($username,$code){
        $conn = openMySQLConnection();
        $sql = "SELECT COUNT(*) FROM `pending_invited` WHERE username='$username' and codelophoc='$code'";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_count = $row['0'];
            if($row_count==0){
                closeMySQLConnection($conn);
                return false;
            }
            else{
                closeMySQLConnection($conn);
                return true;
            }
        }
    }

    function very_inpending_pending($username,$code){
        $conn = openMySQLConnection();
        $sql = "SELECT COUNT(*) FROM `pending_invited` WHERE username='$username' and codelophoc='$code' and status=0";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_count = $row['0'];
            if($row_count==0){
                return false;
            }
            else{
                $sql_new = "DELETE FROM `pending_invited` WHERE `username`='$username' and `codelophoc`='$code'";
                if($conn->query($sql_new)){
                    if(add_user_to_class_by_email($code,$username,get_role_pending($username,$code))){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
                else{
                    return false;
                }
                
            }
        }
    }

    function get_role_pending($username,$code){
        $conn = openMySQLConnection();
        $sql = "SELECT role FROM `pending_invited` WHERE username='$username' and codelophoc='$code'";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_role = $row['0'];
            if($row['0']!=null){
                return $row_role;
            }
            else{
                return 999;
            }
        }
        else{
            return 999;
        }
    }

    function get_room_by_code($code){
        $sql = "SELECT `phongHoc` FROM `lophoc` WHERE codelop = '$code'";
        $conn = openMySQLConnection();
        $row_class = "";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_class = $row['0'];
        }
        else{
            $row_class = "";
        }
        return $row_class;
    }

    function get_name_register_class($code){
        $conn = openMySQLConnection();
        $sql = "SELECT account_lophoc.idaccount_lophoc,concat(account.firstname,' ', account.lastname) FROM account INNER JOIN account_lophoc ON account_lophoc.username = account.username INNER JOIN lophoc ON lophoc.idLop = account_lophoc.idLop WHERE lophoc.codelop='$code' ORDER BY account_lophoc.idaccount_lophoc ASC";
        $row_name_teach = "";
        if($result = mysqli_query($conn,$sql)){
            $row = mysqli_fetch_row($result);
            $row_name_teach = $row['1'];
        }
        else{
            $row_name_teach = "";
        }
        return $row_name_teach;


    }

    function insert_baidang($noidung, $code,$username, $file){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_date_time = date("d/m/Y H:i");
        
        $conn = openMySQLConnection();
        if($file==""){
            $path_file="";
        }
        else{
            $path_file = "baidang/$code/$file";
        }
        
        $sql = "INSERT INTO `baidang`(`idbaidang`, `idlophoc`, `username`, `noidung`, `fileupload`, `thoigiandang`) VALUES (null,(SELECT idLop FROM lophoc WHERE lophoc.codelop='$code'),'$username','$noidung','$path_file','$current_date_time')";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    function delete_baidang_by_id($idbaidang){
        $sql = "DELETE FROM `baidang` WHERE `idbaidang`=$idbaidang";
        $conn = openMySQLConnection();
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    function update_baidang_by_id($idbaidang, $noidung,$file,$code,$username){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_date_time = date("d/m/Y H:i");
        
        $conn = openMySQLConnection();
        if($file==""){
            $path_file="";
            $sql = "UPDATE `baidang` SET `username`='$username',`noidung`='$noidung',`thoigiandang`='$current_date_time' WHERE idbaidang=$idbaidang";
            if($conn->query($sql)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            $path_file = "baidang/$code/$file";
            $sql = "UPDATE `baidang` SET `username`='$username',`noidung`='$noidung',`fileupload`='$path_file',`thoigiandang`='$current_date_time' WHERE idbaidang=$idbaidang";
            if($conn->query($sql)){
                return true;
            }
            else{
                return false;
            }
        }
        
        
    }

    function insert_binhluan($idbaidang,$noidung,$username){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_date_time = date("d/m/Y H:i");
        $conn = openMySQLConnection();
        $sql = "INSERT INTO `binhluan`(`idbl`, `idbaidang`, `noidungbl`, `usernamebl`, `thoigianbl`) VALUES (null,$idbaidang,'$noidung','$username','$current_date_time')";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }
    

    function delete_binhluan($idbinhluan){
        $sql = "DELETE FROM `binhluan` WHERE `idbl`='$idbinhluan'";
        $conn = openMySQLConnection();
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    function insert_baitap($username,$code,$tieude,$noidung,$tghan,$file){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_date_time = date("d/m/Y H:i");
        $path_file = "";
        if($file==""){
            $path_file = "";
        }
        else{
            $path_file = "baitap/$code/$file";
        }
        $sql = "INSERT INTO `baitap`(`idbaitap`, `usernamepost`, `idlophoc`, `tieude`, `noidung`, `fileupload`, `thoigiandang`, `thoigianhethan`) VALUES (null,'$username',(SELECT idLop FROM lophoc WHERE lophoc.codelop='$code'),'$tieude','$noidung','$path_file','$current_date_time','$tghan')";
        $conn = openMySQLConnection();
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }

    function have_baitap_by_code($code,$idbt){
        $conn = openMySQLConnection();
        $code_class = trim($code,' ');
        $idbaitap = trim($idbt,' ');
        $sql_new = "SELECT COUNT(*) FROM `baitap` WHERE idbaitap = '$idbaitap' AND idlophoc = (SELECT idLop FROM lophoc WHERE lophoc.codelop='$code_class')";
        if($result = mysqli_query($conn,$sql_new)){
            $row = mysqli_fetch_row($result);
            $row_count = $row['0'];
            if($row_count==0){
                return false;
            }
            else{
                return true;
            }
        }
    }

    function insert_cmt_baitap($username,$noidung,$idbaitap){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_date_time = date("d/m/Y H:i");
        $conn = openMySQLConnection();
        $sql = "INSERT INTO `binhluan_baitap`(`idbl_bt`, `id_baitap`, `username`, `noidungbl`, `thoigianbl`) VALUES (null,$idbaitap,'$username','$noidung','$current_date_time')";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }



?>
