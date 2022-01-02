<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đăng nhập</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <?php
    
    if (isset($_SESSION['user'])) {
        header("Location: index.php");
    }
    $error = '';

    $user = '';
    $pass = '';
    include 'db.php';
    $conn = openMySQLConnection();

    if (isset($_POST['user']) && isset($_POST['pass'])) {
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $data = check_login($user,$pass);
        if($data['code']==0){
            session_start();
            $_SESSION['user'] = $data['user'];
            $_SESSION['name'] = $data['name_user'];
            $_SESSION['email'] = $data['email'];
            header('Location: index.php');
            exit();
        }
        else{
            $error = $data['error'];
        }
    }
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h3 class="text-center text-secondary mt-5 mb-3">Đăng nhập</h3>
                <form method="post" action="" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                    <div class="form-group">
                        <label for="username">Tài khoản</label>
                        <input value="<?= $user ?>" name="user" id="user" type="text" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input name="pass" value="<?= $pass ?>" id="password" type="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group custom-control custom-checkbox">
                        <input <?= isset($_POST['remember']) ? 'checked' : '' ?> name="remember" type="checkbox" class="custom-control-input" id="remember">
                        <label class="custom-control-label" for="remember">Nhớ tài khoản</label>
                    </div>
                    <div class="form-group">
                        <?php
                        if (!empty($error)) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        ?>
                        <button class="btn btn-success px-5">Đăng nhập</button>
                    </div>
                    <div class="form-group">
                        <p>Bạn có tài khoản chưa? <a href="register.php">Đăng ký ngay</a>.</p>
                        <p>Bạn quên mật khẩu? <a href="forgot.php">Khôi phục mật khẩu</a>.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>