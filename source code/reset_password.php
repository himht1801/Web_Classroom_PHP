<DOCTYPE html>
    <html lang="en">

    <head>
        <title>Khôi phục tài khoản</title>
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
        session_start();

        if (isset($_SESSION['user'])) {
            header("Location: index.php");
        }
        $show_email = '';
        $error = '';
        $email = '';
        $pass = '';
        $pass_confirm = '';
        $post_error = '';
        $flag = '';
        $success_s = '';
        if(isset($_GET['email']) && isset($_GET['token'])){
            $email = $_GET['email'];
            $show_email = $_GET['email'];
            $token = $_GET['token'];
            $flag = '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error = 'Địa chỉ email không hợp lệ';
            }else if (strlen($token) != 32) {
                $error = 'Token không đúng định dạng';
            }else{
                if(!checkResetPassword($email,$token)){
                $error = 'Địa chỉ email không hợp lệ hoặc token không đã hết hạn';
                }
            }

        }
        else {
            $error = 'Đường dẫn không hợp lệ';
        }

        if (isset($_POST['email']) && isset($_POST['pass']) &&
            isset($_POST['pass-confirm'])) {
            $flag = 'valid';
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $pass_confirm = $_POST['pass-confirm'];
            $error = '';
            if (empty($email)) {
                $post_error = 'Vui lòng điền địa chỉ email';
            } else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
                $post_error = 'Địa chỉ email này không hợp lệ';
            } else if (empty($pass)) {
                $post_error = 'Vui lòng nhập mật khẩu';
            } else if (strlen($pass) < 6) {
                $post_error = 'Mật khẩu phải ít nhất 6 ký tự';
            } else if ($pass != $pass_confirm) {
                $post_error = 'Mật khẩu không khớp';
            } else {
                if(checkResetPassword($email,$token)){
                    $value = updatePassword($email,$pass);
                    if($value['code']==0){
                        $post_error = '';
                        $success_s = 'Mật khẩu của bạn đã được cập nhật';
                    }
                }
                else{
                    $post_error = 'Có vẻ như token đã hết hạn, vui lòng yêu cầu lại token';
                }
            }
        } else {
            
        }
        

        ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <?php
                        if(!empty($error)){
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        else {
                        ?>
                        <h3 class="text-center text-secondary mt-5 mb-3">Reset Password</h3>
                        <form novalidate method="post" action="" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input readonly value="<?php echo $show_email; ?>" name="email" id="email" type="text" class="form-control" placeholder="Email address">
                            </div>
                            <div class="form-group">
                                <label for="pass">Password</label>
                                <input value="<?= $pass ?>" name="pass" required class="form-control" type="password" placeholder="Password" id="pass">
                                <div class="invalid-feedback">Password is not valid.</div>
                            </div>
                            <div class="form-group">
                                <label for="pass2">Confirm Password</label>
                                <input value="<?= $pass_confirm ?>" name="pass-confirm" required class="form-control" type="password" placeholder="Confirm Password" id="pass2">
                                <div class="invalid-feedback">Password is not valid.</div>
                            </div>
                            <div class="form-group">
                                <?php
                                if (!empty($post_error)) {
                                    echo "<div class='alert alert-danger'>$post_error</div>";
                                }
                                if(!empty($success_s)) {
                                    echo "<div class='alert alert-success'>$success_s</div>";
                                }
                                ?>
                                <button class="btn btn-success px-5">Change password</button>
                            </div>
                        </form>
                        <?php
                    }?>
                </div>
            </div>
        </div>

    </body>

    </html>
