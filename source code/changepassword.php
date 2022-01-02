<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php
        include 'db.php';
        $pass_current = '';
        $pass = '';
        $pass_confirm = '';
        $error = '';
        $message_success='';

        if(isset($_POST['pass-current']) && isset($_POST['pass']) && isset($_POST['pass-confirm'])){
            $user = $_SESSION['user'];
            $pass_current = $_POST['pass-current'];
            $pass = $_POST['pass'];
            $pass_confirm = $_POST['pass-confirm'];
            if(empty($pass_current)){
                $error = "Vui lòng nhập mật khẩu cũ";
            }
            else if(empty($pass)){
                $error = "Vui lòng nhập mật khẩu mới";
            }
            else if(empty($pass_confirm)){
                $error = "Vui lòng nhập lại mật khẩu mới";
            }
            else if(strlen($pass) < 6){
                $error = "Mật khẩu phải có nhiều hơn 6 ký tự";
            }
            else if($pass != $pass_confirm){
                $error = "Mật khẩu không khớp";
            }
            else{
                
                $check_auth = authentic_password($user, $pass_current);
                $code_auth = $check_auth['code'];
                if($code_auth===0){
                    
                    $result = change_password($user,$pass);
                    if($result['code']===0){
                        $message_success = "Mật khẩu của bạn đã được thay đổi.";
                    }
                    else{
                        $error = "Việc thay đổi mật khẩu thất bại";
                    }
                    
                }
                else{
                    $error = "Mật khẩu của bạn không đúng";
                }
            }
        }
        
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                    <h3 class="text-center text-secondary mt-5 mb-3">Đổi mật khẩu</h3>
                    <form novalidate method="post" action="" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                        <div class="form-group">
                            <label for="email">Mật khẩu hiện tại</label>
                            <input value="" name="pass-current" id="pass-current" type="password" class="form-control" placeholder="Nhập mật khẩu hiện tại">
                        </div>
                        <div class="form-group">
                            <label for="pass">Mật khẩu mới</label>
                            <input value="" name="pass" required class="form-control" type="password" placeholder="Nhập mật khẩu mới" id="pass">
                            <div class="invalid-feedback">Mật khẩu không hợp lệ.</div>
                        </div>
                        <div class="form-group">
                            <label for="pass2">Nhập lại mật khẩu mới</label>
                            <input value="" name="pass-confirm" required class="form-control" type="password" placeholder="Nhập lại mật khẩu mới" id="pass2">
                            <div class="invalid-feedback">Mật khẩu không hợp lệ.</div>
                        </div>
                        
                        <div class="form-group">
                        <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                            if(!empty($message_success)){
                                echo "<div class='alert alert-success'>$message_success</div>";
                            }
                            ?>
                            <button class="btn btn-success px-5">Đổi</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</body>
</html>