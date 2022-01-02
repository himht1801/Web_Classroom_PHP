<DOCTYPE html>
<html lang="en">
<head>
    <title>Quên mật khẩu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php
    include 'db.php';
    $error = '';
    $email = '';
    $message_success = '';
    session_start();
    if (isset($_SESSION['user'])) {
        header("Location: index.php");
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        if (empty($email)) {
            $error = 'Vui lòng điền email';
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $error = 'Địa chỉ email này không hợp lệ';
        }
        else {
            // reset password
            $result = reset_password($email);
            if($result['code']!=0){
                $error = 'Địa chỉ email này không hợp lệ';
            }
            else{
                $error = '';
                $message_success = 'Vui lòng kiểm tra email để khôi phục tài khoản';
            }
        }
    }
    function reset_password($email){
        if(!exist_email($email)){
            return array('code' => 1, 'error'=>'Không tồn tại tài khoản này');

        }
        $token = md5(random_int(99999,100000000000).'+-$#!'.$email);
        $sql = 'update reset_token set token = ? where email = ? ';
        $conn = openMySQLConnection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$token,$email);
        if(!$stm->execute()){
            return array('code' => 2, 'error'=>'Cannot execute this command');
        }
        $effect_counter = $stm->affected_rows ;
        if($effect_counter == 0){
            $exp = time() + 3600;
            $sql = 'insert into reset_token values(?,?,?)';
            $stm = $conn->prepare($sql);
            $stm->bind_param('ssi',$email,$token,$exp);
            if(!$stm->execute()){
            return array('code' => 2, 'error'=>'Cannot execute this command');
            }
        }
        $result_success = sendResetPassword($email,$token);
        return array('code' => 0, 'error'=>'Khôi phục mật khẩu thành công');
    }
    

    /// check exist username in database, if exist return true, else return false
    
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <h3 class="text-center text-secondary mt-5 mb-3">Quên mật khẩu</h3>
            <form method="post" action="" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" id="email" type="text" class="form-control" placeholder="Email address">
                </div>
                <div class="form-group">
                    <p>Vui lòng nhập email tài khoản của bạn, chúng tôi sẽ gửi liên kết khôi phục mật khẩu cho bạn.</p>
                </div>
                <div class="form-group">
                    <?php
                        if (!empty($error)) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        if(!empty($message_success)) {
                            echo "<div class='alert alert-success'>$message_success</div>";
                        }
                    ?>
                    <button class="btn btn-success px-5">Khôi phục mật khẩu</button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>
