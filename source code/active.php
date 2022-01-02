<DOCTYPE html>
<html lang="en">
    <head>
    <title>Kích hoạt tài khoản</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>

    <body>
    <?php
    include 'db.php';
    if (isset($_SESSION['user'])) {
        header("Location: index.php");
    }
    
    $message = '';
    $error = '';
    if (isset($_GET['email']) && isset($_GET['token'])) {
        $email = $_GET['email'];
        $token = $_GET['token'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
        } else if (strlen($token) != 32) {
            $error = 'Token không hợp lệ';
        } else {
            $result = activeAccount($email, $token);
            if ($result['code'] == 0) {
                $message = 'Tài khoản của bạn đã được kích hoạt, Đăng nhập ngay.';
            } else if ($result['code'] == 1) {
                $message = $result['error'];
            } else {
                $message = "Có lỗi gì đó ở đây, xin vui lòng thử lại.";
            }
        }
    } else {
        $error = 'Đường dẫn không hợp lệ';
    }
    ?>
    <div class="container">
    <?php
        if (!empty($error)) {
    ?>
        <div class="row">
        <div class="col-md-6 mt-5 mx-auto p-3 border rounded">
            <h4>Kích hoạt tìa khoản</h4>
            <p class="text-danger">
                <?php
                echo $error;
                ?>
            </p>
            <p>Click <a href="login.php">tại đây</a> để đăng nhập.</p>
            <a class="btn btn-success px-5" href="login.php">Đăng nhập</a>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="row">
            <div class="col-md-6 mt-5 mx-auto p-3 border rounded">
            <h4>Kích hoạt tài khoản</h4>
            <p class="text-success">Chúc mừng! Tài khoản của bạn đã được kích hoạt</p>
            <p>Click <a href="login.php">vào đây</a> để đăng nhập và quản lý thông tin tài khoản của bạn.</p>
            <a class="btn btn-success px-5" href="login.php">Đăng nhập</a>
            </div>
            <?php
        }
        ?>
        </div>
    </div>
    </body>

</html>
