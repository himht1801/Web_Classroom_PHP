<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Bài tập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<div class="title-first">
    <?php
        session_start();
        if (!isset($_SESSION['user']) || !isset($_SESSION['name'])) {
            header('Location: login.php');
            exit();
        }
        
        include 'db.php';
        $role = false;
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

        $root ="./baitap/$code_class";
        if (!file_exists($root)){
            mkdir($root);
        }
        $name_class_desc = get_name_class_by_code($code_class);
        if(isset($_POST['action'])){
            if($_POST['action']=="add-baitap"){
                if(!isset($_POST['title-bt']) || empty($_POST['title-bt'])){
                    echo "<script type='text/javascript'>alert('Vui lòng nhập tiêu đề bài tập');</script>";
                }
                else{
                    $file = '';
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
                    $tieudebt = "";
                    $ndbt = "";
                    $tgkt = "";
                    if(isset($_POST['noidung'])){
                        $ndbt = $_POST['noidung'];
                    }
                    if(isset($_POST['deadline'])){
                        $tgkt = $_POST['deadline'];
                    }
                    if(isset($_POST['title-bt'])){
                        $tieudebt = $_POST['title-bt'];
                    }
                    if(isset($_POST['file-to-upload'])){
                        $namefile_bt = $_POST['file-to-upload'];
                    }
                    print_r(array($username,$code_class,$tieudebt,$ndbt,$tgkt,$file));
                    if(insert_baitap($username,$code_class,$tieudebt,$ndbt,$tgkt,$file)){
                        echo "<script type='text/javascript'>alert('Tạo bài tập thành công');</script>";
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Tạo bài tập thất bại');</script>";
                    }
                }
            }
        }
    ?>
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
                            $conn = openMySQLConnection();
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

<div class="container">
    <!-- Danh sách giáo viên -->
    <div class="row align-items-center">
        <div>
            <h3>Bài tập</h3>
        </div>
    </div>
    <?php
        echo "<div class=\"btn-group my-3\">
                <button type=\"button\" class=\"btn btn-light border\" data-toggle=\"modal\" data-target=\"#addAssignment\">
                    Thêm bài tập
                </button>
            </div>";
    ?>
    

    <table class="table table-hover">
        <tbody>
        <tr>
            <td>
                <img src="./images/homework.png" class="img-people">
            </td>
            <td><b>Tên bài tập</b></td>
            <td><b>Thời gian<b></td>
            
        </tr>
        <?php
            $sql = "SELECT `idbaitap`, `tieude`, `thoigiandang`, `thoigianhethan` FROM `baitap` WHERE `idlophoc` = (SELECT idLop FROM lophoc WHERE lophoc.codelop='$code_class')";
            $result = $conn->query($sql);
            if(!$result){
                trigger_error('Invalid query' . $conn->error);
            
            }
            $counter = 0;
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()){
                    $idbt = $row['idbaitap'];
                    $tieude = $row['tieude'];
                    $tghh = $row['thoigianhethan'];
                    echo "<tr>
                    <td>
                        <img src=\"./images/homework.png\" class=\"img-people\">
                        </td>
                        <td><a href=\"/homework.php?code=$code_class&id=$idbt\">$tieude</a></td>
                        <td>$tghh</td>
                        
                    </tr>";
                }
            }
        ?>
        </tbody>
    </table>
    
    

    <!-- Quản lý tài khoản -->
    <div class="modal fade" id="manager-account">
        <div class="modal-dialog">
            <div class="modal-content" id="account">
                <form method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="header-account"><?php echo $username; ?></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <img id="img-account" src="./images/person.png"/>
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
    <?php
    if($role){
        echo "<div class=\"modal fade\" id=\"addAssignment\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
                        <form method=\"post\" enctype=\"multipart/form-data\">
                            <div class=\"modal-header\">
                                <h4 class=\"modal-title\">Tạo bài tập</h4>
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                            </div>

                            <div class=\"modal-body\">
                                <label for=\"title-bt\"><b>Tiêu đề bài tập</b></label>
                                <br/>
                                <input type=\"text\" id=\"title-bt\" name=\"title-bt\" class=\"form-control\" placeholder='Bắt buộc'>
                                <br/>

                                <div class=\"form-group green-border-focus\">
                                <label for=\"nd-baitap\"><b>Nội dung bài tập</b></label>
                                <textarea class=\"form-control\" id=\"nd-baitap\" rows=\"10\" name=\"noidung\" placeholder='Tùy chọn'></textarea>
                                </div>
                                <label for=\"datetimedeadline\"><b>Thời gian kết thúc</b></label>
                                <br/>
                                <input type=\"text\" id=\"datetimedeadline\" placeholder=\"Có dạng: dd/mm/yyyy hh:mm\" name=\"deadline\" class=\"form-control\">
                                <br/>
                                <label><b>Tệp tin đính kèm</b></label>
                                <br/>
                                <div class=\"custom-file\">
                                <input type=\"file\" class=\"custom-file-input\" name=\"file-to-upload\">
                                <label class=\"custom-file-label\" for=\"fileupload\">Choose file</label>
                                </div>
                            </div>
                            <div class=\"modal-footer\">
                                <input type=\"hidden\" name=\"action\" value=\"add-baitap\" id=\"action-add-baitap\">
                                <button type=\"submit\" class=\"btn btn-success\" >Thêm</button>
                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class=\"modal fade\" id=\"addNotification\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
                        <form method=\"post\">
                            <div class=\"modal-header\">
                                <h4 class=\"modal-title\">Tạo thông báo</h4>
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                            </div>

                            <div class=\"modal-body\">
                                <label for=\"title-bt\"><b>Tiêu đề thông báo</b></label>
                                <br/>
                                <input type=\"text\" id=\"title-bt\" name=\"title-bt\" class=\"form-control\">
                                <br/>

                                <div class=\"form-group green-border-focus\">
                                <label for=\"nd-baitap\"><b>Nội dung thông báo</b></label>
                                <textarea class=\"form-control\" id=\"nd-baitap\" rows=\"10\" name=\"noidung\"></textarea>
                                </div>
                                </br>
                                <label><b>Tệp tin đính kèm</b></label>
                                <br/>
                                <div class=\"custom-file\">
                                    <input type=\"file\" class=\"custom-file-input\" id=\"customFile\">
                                    <label class=\"custom-file-label\" for=\"customFile\">Choose file</label>
                                </div>
                            </div>
                            <div class=\"modal-footer\">
                                <input type=\"hidden\" name=\"action\" value=\"add-baitap\" id=\"action-add-thongbao\">
                                <button type=\"submit\" class=\"btn btn-success\" >Thêm</button>
                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class=\"modal fade\" id=\"addTopic\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
                        <form method=\"post\">
                            <div class=\"modal-header\">
                                <h4 class=\"modal-title\">Tạo thông báo</h4>
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                            </div>

                            <div class=\"modal-body\">
                                <label for=\"title-bt\"><b>Tiêu đề thông báo</b></label>
                                <br/>
                                <input type=\"text\" id=\"title-bt\" name=\"title-bt\" class=\"form-control\">
                                <br/>

                                <div class=\"form-group green-border-focus\">
                                <label for=\"nd-baitap\"><b>Nội dung thông báo</b></label>
                                <textarea class=\"form-control\" id=\"nd-baitap\" rows=\"10\" name=\"noidung\"></textarea>
                                </div>
                                </br>
                                <label><b>Tệp tin đính kèm</b></label>
                                <br/>
                                <div class=\"custom-file\">
                                    <input type=\"file\" class=\"custom-file-input\" id=\"customFile\">
                                    <label class=\"custom-file-label\" for=\"customFile\">Choose file</label>
                                </div>
                            </div>
                            <div class=\"modal-footer\">
                                <input type=\"hidden\" name=\"action\" value=\"add-thaoluan\" id=\"action-add-thongbao\">
                                <button type=\"submit\" class=\"btn btn-success\" >Thêm</button>
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

