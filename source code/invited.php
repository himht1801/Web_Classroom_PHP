<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận lời mời vào nhóm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="main.js" ></script>
</head>
<body>
    <div class="container">
    <?php
        include 'db.php';
        session_start();
        if (!isset($_SESSION['user']) || !isset($_SESSION['name'])) {
            header('Location: login.php');
            exit();
        }
        $username = $_SESSION['user'];
        $fullname_user = $_SESSION['name'];

        if(isset($_GET['code']) && isset($_GET['email'])){
            $code = $_GET['code'];
            $email = $_GET['email'];
            if($email==get_mail_by_username($username)){
                if(very_inpending_pending($username,$code)){
                    echo "<div class=\"alert alert-success\" role=\"alert\">
                    Bạn đã tham gia thành công, vui lòng quay lại trang chủ và chọn lớp học
                    </div>";
                    die();
                }
                else{
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                    Tham gia khoông thành công
                    </div>";
                    die();
                }
            }
            else{
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                Vui lòng chọn đường dẫn hợp lệ
                </div>";
                die();
            }
        }
        else{
            echo "<div class=\"alert alert-danger\" role=\"alert\">
            Đường dẫn không hợp lệ
            </div>";
            die();
        }
        sendMailInvited('cunkul35@gmail.com','dNbTP9VAFiGlKqW','Hải Đăng');
    ?>
    </div>
    
</body>
<footer>
</footer>