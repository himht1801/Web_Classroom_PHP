<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}
include 'db.php';
$role = false;
$conn = openMySQLConnection();
$username = $_SESSION['user'];
$fullname_user = $_SESSION['name'];
if(isset($_GET['code'])){
    $code_class = $_GET['code'];
    if(check_permission($code_class,$username)){
        $role = check_role($username,$code_class);
        
    }
    else{
        echo "<div class=\"alert alert-danger\" role=\"alert\">
        Không có quyền truy cập
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
$name_class_desc = get_name_class_by_code($code_class);
$name_room_class = get_room_by_code($code_class);
$name_teacher = get_name_register_class($code_class);
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Lớp học <?php echo $name_class_desc;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script type="text/javascript" src="main.js" ></script>
</head>
<body>
<?php
        

        $root ="./baidang/$code_class";
        if (!file_exists($root)){
            mkdir($root);
        }

        // else if (isset($_POST['download'])){
        //     header("Content-Length: " . filesize($name));
        //     header('Content-Type: application/octet-stream');
        //     header('Content-Disposition: attachment; filename='.$name);

        //     readfile($name);
        // }
        

        
        if(isset($_POST['action'])){
            if($_POST['action']=="post-baidang"){
                if(isset($_POST['content-baidang'])){
                    $noidung = $_POST['content-baidang'];
                    $file = '';
                    $name_file = "";
                    if (isset($_FILES['file-to-upload'])){
                        if ($_FILES['file-to-upload']['error'] === 0) {
                            $file = $_FILES['file-to-upload']['name'];
                            $fullPath = "$root/$file";
                            if (file_exists($fullPath)) {
                                unlink($fullPath);
                                move_uploaded_file($_FILES['file-to-upload']['tmp_name'], $fullPath);
                            } else {
                                move_uploaded_file($_FILES['file-to-upload']['tmp_name'], $fullPath);
                            }
                        }else{
                            $file = '';
                        }
                    }

                    if($noidung==""){
                        echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";

                    }
                    else{
                        
                        if(insert_baidang($noidung,$code_class,$username,$file)){
                            echo "<script type='text/javascript'>alert('Đăng bài thành công');</script>";
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Đăng bài thất bại');</script>";
    
                        }
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                }
            }
            if($_POST['action']=="delete-baidang"){
                if(isset($_POST['idbaidang'])){
                    $idbaidang = $_POST['idbaidang'];
                    if($role){
                        if(delete_baidang_by_id($idbaidang)){
                            echo "<script type='text/javascript'>alert('Xóa bài đăng thành công');</script>";
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Xóa bài đăng không thành công');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền xóa');</script>";
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                }
            }

            if($_POST['action']=="edit-baidang"){
                if(isset($_POST['id-edit'])){
                    $id_edit = $_POST['id-edit'];
                    if($role){
                        $file = '';
                        $name_file = "";
                        if (isset($_FILES['file-to-upload'])){
                            if ($_FILES['file-to-upload']['error'] === 0) {
                                $file = $_FILES['file-to-upload']['name'];
                                $fullPath = "$root/$file";
                                if (file_exists($fullPath)) {
                                    unlink($fullPath);
                                    move_uploaded_file($_FILES['file-to-upload']['tmp_name'], $fullPath);
                                } else {
                                    move_uploaded_file($_FILES['file-to-upload']['tmp_name'], $fullPath);
                                }
                            }else{
                                $file = '';
                            }
                        }
                        if(!empty($_POST['noidung'])){
                            $noidung = $_POST['noidung'];
                            
                            if(update_baidang_by_id($id_edit,$noidung,$file,$code_class,$username)){
                                echo "<script type='text/javascript'>alert('cập nhật thành công');</script>";
                            }
                            else{
                                echo "<script type='text/javascript'>alert('cập nhật thất bại');</script>";

                            }
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Vui lòng nhập nội dung');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền sửa');</script>";
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                }
            }

            if($_POST['action']=="binhluan"){
                if(isset($_POST['idbaidang'])){
                    $idbd_binhluan = $_POST['idbaidang'];
                    if(!isset($_POST['noidungbl'])|| empty($_POST['noidungbl'])){
                        echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                    }
                    else{
                        $noidung_bl = $_POST['noidungbl'];
                        if(insert_binhluan($idbd_binhluan,$noidung_bl,$username)){
                            echo "<script type='text/javascript'>alert('Đã bình luận');</script>";
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Bình luận thất bại');</script>";
                        }
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                }
            }

            if($_POST['action']=="delete-bl"){
                if(!isset($_POST['idbl']) || empty($_POST['idbl'])){
                    echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                }
                else{
                    $id_bl_xoa = $_POST['idbl'];
                    if(delete_binhluan($id_bl_xoa)){
                        echo "<script type='text/javascript'>alert('Đã xóa bình luận thành công');</script>";
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Xóa bình luận thất bại');</script>";
                    }
                }
            }
        
        }
    ?>
<!-- Thanh Trên cùng -->
<div class="title-first">
    <nav class="navbar navbar-expand-md bg-info navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/class.php?code=<?php echo $code_class;?>">
                        <div class="subject">
                            <b><?php echo $name_class_desc;?></b>
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"  href="/class-work.php?code=<?php echo $code_class;?>"><b>Bài tập</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/people.php?code=<?php echo $code_class;?>"><b>Thành viên</b></a>
                </li>

                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        <b>Lớp học</b>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/index.php">Trang chủ</a>
                        <?php
                            $sql = "SELECT lophoc.tenLop, lophoc.motaLop, lophoc.codelop, account_lophoc.role, lophoc.phanHoc, lophoc.phongHoc, lophoc.chude FROm account_lophoc INNER JOIN lophoc ON account_lophoc.idLop = lophoc.idLop INNER JOIN account on account.username = account_lophoc.username WHERE account.username ='$username'";
                            
                            $result = $conn->query($sql);
                            if(!$result){
                                trigger_error('Invalid query' . $conn->error);
                            
                            }
                            $counter = 0;
                            if($result->num_rows>0){
                                while($row = $result->fetch_assoc()){
                                    $tenlop = $row['tenLop'];
                                    $codelop = $row['codelop'];
                                    echo "<a class=\"dropdown-item\" href=\"class.php?code=$codelop\">$tenlop</a>";
                                }
                            }
                        ?>
                    </div>
                </li>

                <li class="nav-item">
                    <button class="btn-user" class="fa fa-trash action" data-toggle="modal" data-target="#manager-account"></button>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- Bìa môn học -->
<div class="container">
    <div class="row">
        <div class='col-sm-12 col-md-12 col-lg-12'>
            <div class="bg-title">
                <div class="sb-title">
                    <p id="sub-name"><?php echo $name_class_desc; ?></p>
                    <p id="sub-teacher">Tên giáo viên: <?php echo $name_teacher; ?></p>
                    <p><b>Mã lớp: <?php echo $code_class; ?></b></p>
                    <p><b>Phòng học: <?php echo $name_room_class; ?></b></p>
                </div>
            </div>
        </div>
    </div>
<!-- Thảo luận -->
    <div class="row">
        <div class='col-sm-12 col-md-12 col-lg-12'>
            <div class="border rounded mb-3 mt-5 p-3">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="row-discuss">
                            <img src="./images/person.png">
                            <input type="text" class="form-control" placeholder="Chia sẻ với lớp một điều gì đó" id="discuss" name="content-baidang">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="action" value="post-baidang">
                        <input type="file" id="myFile" name="file-to-upload">
                        
                    </div>
                    <button type="submit" class="btn btn-outline-info" id="submit">Đăng</button>
                </form>
            </div>
        </div>
    </div>
<!-- Thông báo -->

    <div class="row">
        
            <?php
                $sql = "SELECT * FROM baidang WHERE idlophoc = (SELECT idlop from lophoc WHERE codelop='$code_class') ORDER BY idbaidang DESC";
                $result = $conn->query($sql);
                if(!$result){
                    trigger_error('Invalid query' . $conn->error);
                
                }
                $counter = 0;
                if($result->num_rows>0){
                    while($row = $result->fetch_assoc()){
                        $idbaidang = $row['idbaidang'];
                        $idlophoc = $row['idlophoc'];
                        $name_post = get_name_by_username($row['username']);
                        $noidung = $row['noidung'];
                        $name_file_upload = $row['fileupload'];
                        $thoigian = $row['thoigiandang'];
                        
                        echo "
                        <div class='col-sm-12 col-md-12 col-lg-12'>
                        <div class=\"border rounded mb-3 mt-5 p-3\">
                        
                            <div class=\"row-notification\">
                                <div class=\"row-info\">
                                    <img src=\"./images/person.png\">
                                    <div class=\"name-teacher-status\">
                                        <p>$name_post <br> $thoigian</p>
                                    </div>
                                </div>
                                <p id=\"main-notification\">$noidung</p>
                                <a href=\"$name_file_upload\" download>File đính kèm</a>
                                <div>
                                ";
                                if($role){
                                    echo"
                                    <button class=\"btn btn-info\" id=\"btn-edit-notification\" onclick=\"showModalEditBaidang($idbaidang,'$noidung')\"  data-toggle=\"modal\" data-target='#suabaidang'>Sửa</button>
                                    <button class=\"btn btn-info\" id=\"btn-del\" onclick=\"showModalDelBaidang($idbaidang)\"  data-toggle=\"modal\" data-target=\"#confirm-delete-baidang\">Xóa</button>
                                    <h4>Bình Luận</h4>
                                    <br\>
                                    
                                    <hr >";
                                }
                                
                                $sql_load_bl = "SELECT * FROM `binhluan` WHERE `idbaidang`= $idbaidang";
                                $load_bl = $conn->query($sql_load_bl);
                                if($load_bl){
                                    if($load_bl->num_rows>0){
                                        while($row_bl = $load_bl->fetch_assoc()){
                                            $idbl = $row_bl['idbl'];
                                            $noidung_bluan = $row_bl['noidungbl'];
                                            $username_bl = get_name_by_username($row_bl['usernamebl']);
                                            $tg_bl = $row_bl['thoigianbl'];
                                            echo"<div class=\"row-info\">
                                                <img src=\"./images/person.png\">
                                                <div class=\"name-teacher-status\">
                                                    <p>$username_bl <br> $tg_bl</p>
                                                </div>
                                            </div>
                                            <p id=\"main-notification\">$noidung_bluan</p>";
                                            if($role){
                                                echo "<form method='POST' class='xoabl'>
                                                <input type=\"hidden\" name=\"action\" value=\"delete-bl\" >
                                                <input type=\"hidden\" name=\"idbl\" value=\"$idbl\" >
                                                <button type=\"submit\" >Xóa</button>
                                                </form>";
                                            }
                                            
                                            echo"<hr >";
                                        }
                                    }
                                }
                                echo " </div>
                            </div>
                            <form method='POST'>
                                <div class=\"form-group\">
                                    <div class=\"row-discuss\">
                                        <img src=\"./images/person.png\">
                                        <input type=\"text\" class=\"form-control comment\" placeholder=\"Chia sẻ với lớp một điều gì đó\" name='noidungbl'>
                                    </div>
                                </div>
                                <input type=\"hidden\" name=\"action\" value=\"binhluan\" >
                                <input type=\"hidden\" name=\"idbaidang\" value=\"$idbaidang\" id=\"id-binhluan\">
                                <button type=\"submit\" class=\"btn btn-outline-info\" id=\"send\">Bình luận</button>
                            </form>
                        </div>
                        </div>
                        ";
                    }
                }
            ?>

    </div>

    


    <!-- Quản lý tài khoản -->
    <div class="modal fade" id="manager-account">
        <div class="modal-dialog">
            <div class="modal-content" id="account">
                <div class="border rounded mb-3 mt-5 p-3">
                    <form method="post">
                        <div class="modal-header">
                            <h4 class="modal-title" id="header-account"><?php echo $username;?></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <img id="img-account" src="./images/person.png"/>
                            <br>
                            <br>
                            <p id="name-account"><?php echo $fullname_user;?></p>
                            <a class="btn btn-info" id="change" href="/changepassword.php">Đổi mật khẩu</a>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-info" href="/logout.php">Đăng xuất</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if($role){
        echo"
        <div id=\"confirm-delete-baidang\" class=\"modal fade\" role=\"dialog\">
        <div class=\"modal-dialog\">

            <!-- Modal content-->
            <form  method=\"POST\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <h4 class=\"modal-title\">Xóa bài đăng</h4>
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                    </div>
                    <div class=\"modal-body\">
                        Bạn có chắc muốn xóa bài đăng này không
                    </div>
                    <div class=\"modal-footer\" >
                        <input type=\"hidden\" name=\"action\" value=\"delete-baidang\" >
                        <input type=\"hidden\" name=\"idbaidang\" value=\"111111\" id=\"delete-baidang-value\">
                        <button type=\"submit\" class=\"btn btn-danger\">Xóa</button>
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                </div>
            </form>
        </div>
        </div>

        <div class=\"modal fade\" id=\"suabaidang\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
                        <form method=\"post\" enctype=\"multipart/form-data\">
                            <div class=\"modal-header\">
                                <h4 class=\"modal-title\">Sửa bài đăng</h4>
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                            </div>

                            <div class=\"modal-body\">
                                
                                <div class=\"form-group green-border-focus\">
                                <label for=\"nd-baidang\"><b>Nội dung bài đăng</b></label>
                                <textarea class=\"form-control\" id=\"nd-baidang\" rows=\"10\" name=\"noidung\"></textarea>
                                </div>
                                
                                <label><b>Tệp tin đính kèm</b></label>
                                <br/>
                                <div class=\"custom-file\">
                                    <input type=\"file\" class=\"custom-file-input\" name=\"file-to-upload\">
                                    <label class=\"custom-file-label\" for=\"fileupload\">Choose file</label>
                                </div>
                            </div>
                            <div class=\"modal-footer\">
                                
                                <input type=\"hidden\" name=\"action\" value=\"edit-baidang\" id=\"action-edit-baidang\">
                                <input type=\"hidden\" name=\"id-edit\" value=\"2222\" id=\"id-edit-baidang\">
                                <button type=\"submit\" class=\"btn btn-success\" >Sửa</button>
                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
        ";
    }
?>

</body>
<script>
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
</html>
