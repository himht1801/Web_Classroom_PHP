<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Thành viên</title>
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
        $conn = openMySQLConnection();
        $name_class_desc = get_name_class_by_code($code_class);

        if(isset($_POST['action'])){
            if($_POST['action']=="delete-user"){
                if(isset($_POST['username'])){
                    $user_need_delete = $_POST['username'];
                    if(check_role($username,$code_class)){
                        if(reomve_person_by_username($user_need_delete,$code_class)){
                            echo "<script type='text/javascript'>alert('Xóa sinh viên thành công');</script>";
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Xóa sinh viên thất bại');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền xóa sinh viên này');</script>";
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                }
                
            }
            if($_POST['action']=="edit-role"){
                if(isset($_POST['username']) && isset($_POST['role'])){
                    $user_need_update_role = $_POST['username'];
                    $role_need_update = $_POST['role'];
                    if(check_role($username,$code_class)){
                        if(update_role_user_by_code($code_class,$user_need_update_role,$role_need_update)){
                            echo "<script type='text/javascript'>alert('Cập nhật quyền thành công');</script>";
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Cập nhật quyền thất bại');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền sửa thành viên này');</script>";
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Thiếu thông tin đầu vào');</script>";
                }
            }

            if($_POST['action']=="add-teach"){
                if(isset($_POST['username'])){
                    $teach_need_add = $_POST['username'];
                    if(check_role($username,$code_class)){
                        if(exist_email($teach_need_add)){
                            if(!check_user_in_class_by_email($teach_need_add,$code_class)){
                                if(add_user_to_class_by_email($code_class,$teach_need_add,1)){
                                    echo "<script type='text/javascript'>alert('Thêm giảng viên thành công');</script>";
                                }
                                else{
                                    echo "<script type='text/javascript'>alert('Thêm giảng viên thất bại');</script>";
                                }
                            }
                            else{
                                echo "<script type='text/javascript'>alert('Người này đã có trong lớp, bạn có thể chỉnh sửa quyền cho người này');</script>";
                            }
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Email này không có trong hệ thống, thêm giảng viên thất bại');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền thao tác chức năng này');</script>";
                    }
                    
                }
            }


            if($_POST['action']=="add-student"){
                if(isset($_POST['username'])){
                    $student_need_add = $_POST['username'];
                    if(check_role($username,$code_class)){
                        if(exist_email($student_need_add)){
                            if(!check_user_in_class_by_email($student_need_add,$code_class)){
                                if(add_user_to_class_by_email($code_class,$student_need_add,2)){
                                    echo "<script type='text/javascript'>alert('Thêm sinh viên thành công');</script>";
                                }
                                else{
                                    echo "<script type='text/javascript'>alert('Thêm sinh viên thất bại');</script>";
                                }
                            }
                            else{
                                echo "<script type='text/javascript'>alert('Người này đã có trong lớp, bạn có thể chỉnh sửa quyền cho người này');</script>";
                            }
                            
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Email này không có trong hệ thống, thêm sinh viên thất bại');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền thao tác chức năng này');</script>";
                    }
                    
                }
            }
        }
    ?>
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

<div class="container">
    <!-- Danh sách giáo viên -->
    <div class="row align-items-center">
        <div>
            <h3>Giáo viên</h3>
        </div>
    </div>
    <?php
        if($role){
            echo "<div class=\"btn-group my-3\">
                    <button type=\"button\" class=\"btn btn-light border\" data-toggle= \"modal\" data-target=\"#addTeachtoClass\">
                        Thêm giáo viên
                    </button>
                </div>";
        }
    ?>
    

    <table class="table table-hover">
        <tbody>
            <?php 
                $sql = "SELECT account.username,concat(`firstname`,' ',`lastname`) as 'fullname',`email` FROM `account` INNER JOIN account_lophoc ON account_lophoc.username = account.username INNER JOIN lophoc on lophoc.idLop = account_lophoc.idLop WHERE lophoc.codelop='$code_class' AND account_lophoc.role<=1";
                
                $result = $conn->query($sql);
                if(!$result){

                }
                else{
                    if($result->num_rows>0){
                        while($row = $result->fetch_assoc()){
                            $row_name = $row['fullname'];
                            $row_username = $row['username'];
                            $row_email = $row['email'];
                            if($role){
                                if($row_username==$username){
                                    echo "<tr>
                                    <td>
                                        <img src=\"images/person.png\" class=\"img-people\" style=\" width: 30px; height: 30px;\">
                                    </td>
                                    <td>$row_name</td>
                                    <td>$row_email</td>
                                    <td>
                                        
                                    </td>
                                    </tr>";
                                }
                                else{
                                    echo "<tr>
                                    <td>
                                        <img src=\"images/person.png\" class=\"img-people\" style=\" width: 30px; height: 30px;\">
                                    </td>
                                    <td>$row_name</td>
                                    <td>$row_email</td>
                                    <td>
                                        <button><i  onclick=\"showModalEditRole('$row_username','$row_name')\" class='fa fa-edit action' data-toggle= \"modal\" data-target=\"#modalEditRole\"></i></button>
                                        <button><i  onclick=\"showModalDeletePerson('$row_username','$row_name')\" class=\"fa fa-remove action\" data-toggle= \"modal\" data-target=\"#confirm-delete\"></i></button>
                                    </td>
                                    </tr>";
                                }
                            }
                            else{
                                echo "<tr>
                                <td>
                                    <img src=\"images/person.png\" class=\"img-people\" style=\" width: 30px; height: 30px;\">
                                </td>
                                <td>$row_name</td>
                                <td>$row_email</td>
                                <td>    
                                </td>
                                </tr>";
                            }
                        }
                    }
                }
            ?>

            
        </tbody>
    </table>

    <!-- Danh sách sinh viên -->
    <div class="row align-items-center">
        <div>
            <h3>Sinh viên</h3>
        </div>
    </div>
    <?php
        if($role){
            echo "<div class=\"btn-group my-3\">
                    <button type=\"button\" class=\"btn btn-light border\" data-toggle= \"modal\" data-target=\"#addStudenttoClass\">
                        Thêm sinh viên
                    </button>
                </div>";
        }
        
    ?>
    

    <table class="table table-hover">
        <tbody>
            <?php
                $sql = "SELECT account.username,concat(`firstname`,' ',`lastname`) as 'fullname',`email` FROM `account` INNER JOIN account_lophoc ON account_lophoc.username = account.username INNER JOIN lophoc on lophoc.idLop = account_lophoc.idLop WHERE lophoc.codelop='$code_class' AND account_lophoc.role>1";
                $conn = openMySQLConnection();
                $result = $conn->query($sql);
                if(!$result){

                }
                else{
                    if($result->num_rows>0){
                        while($row = $result->fetch_assoc()){
                            $row_name = $row['fullname'];
                            $row_username = $row['username'];
                            $row_email = $row['email'];
                            if($role){
                                echo "<tr>
                                <td>
                                    <img src=\"images/person.png\" class=\"img-people\" style=\" width: 30px; height: 30px;\">
                                </td>
                                <td>$row_name</td>
                                <td>$row_email</td>
                                <td>
                                    <button><i  onclick=\"showModalEditRole('$row_username','$row_name')\" class='fa fa-edit action' data-toggle= \"modal\" data-target=\"#modalEditRole\"></i></button>
                                    <button><i  onclick=\"showModalDeletePerson('$row_username','$row_name')\" class=\"fa fa-remove action\" data-toggle= \"modal\" data-target=\"#confirm-delete\"></i></button>
                                </td>
                                </tr>";
                            }
                            else{
                                echo "<tr>
                                <td>
                                    <img src=\"images/person.png\" class=\"img-people\" style=\" width: 30px; height: 30px;\">
                                </td>
                                <td>$row_name</td>
                                <td>$row_email</td>
                                <td>    
                                </td>
                                </tr>";
                            }
                        }
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
    
    <div class="modal fade" id="join-class">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Tham gia lớp học</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <label for="code"><b>Mã tham gia lớp học</b></label>
                        <br>
                        <input type="text" id="code" placeholder="Code" name="key" class="form-control">
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="action" value="join-class" id="action-delete">
                        <button type="submit" class="btn btn-success" >Tham gia</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    if($role){
        // xóa 1 dòng // sửa quyền
        echo "<div id=\"confirm-delete\" class=\"modal fade\" role=\"dialog\">
        <div class=\"modal-dialog\">

            <!-- Modal content-->
            <form  method=\"POST\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <h4 class=\"modal-title\">Xóa thành viên</h4>
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                    </div>
                    <div class=\"modal-body\">
                        Bạn có chắc rằng muốn xóa <strong id=\"nameDelete\">Tên người muốn xóa</strong> ra khỏi lớp
                    </div>
                    <div class=\"modal-footer\" >
                        <input type=\"hidden\" name=\"action\" value=\"delete-user\" id=\"action-delete-user\">
                        <input type=\"hidden\" name=\"username\" value=\"12121\" id=\"user-delete\">
                        <button type=\"submit\" class=\"btn btn-danger\">Xóa</button>
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                </div>
            </form>

        </div>
        
        </div>
        <div class=\"modal fade\" id=\"modalEditRole\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <form method=\"post\">
                        <div class=\"modal-header\">
                            <h4 class=\"modal-title\">Sửa quyền của thành viên</h4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                        </div>

                        <div class=\"modal-body\">
                            <label for=\"name-class\"><p>Thành viên <strong id='nameEditRole'></strong></p></label>
                            <br>
                            
                            <label for=\"desc-class\"><b>Quyền của người dùng</b></label>
                            <br>
                            <label class=\"checkbox-inline\"><input type=\"radio\" value=\"1\" name='role'>  Giảng viên</label><br/>
                            <label class=\"checkbox-inline\"><input type=\"radio\" value=\"2\" name='role' checked='checked'>  Sinh viên, học sinh</label>
                        </div>

                        <div class=\"modal-footer\">
                            <input type=\"hidden\" name=\"action\" value=\"edit-role\" id=\"action-edit\">
                            <input type=\"hidden\" name=\"username\" value=\"121321323132\" id=\"username-edit\">
                            <button type=\"submit\" class=\"btn btn-success\" >Cập nhật</button>
                            <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class=\"modal fade\" id=\"addStudenttoClass\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <form method=\"post\">
                        <div class=\"modal-header\">
                            <h4 class=\"modal-title\">Thêm sinh viên vào lớp học</h4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                        </div>

                        <div class=\"modal-body\">
                            <label for=\"username-add\"><b>Email sinh viên cần thêm</b></label>
                            <br>
                            <input type=\"text\" id=\"username-add\" placeholder=\"Ví dụ: sinhvien1@gmail.com,..\" name=\"username\" class=\"form-control\">
                        </div>

                        <div class=\"modal-footer\">
                            <input type=\"hidden\" name=\"action\" value=\"add-student\" id=\"action-add-student\">
                            <button type=\"submit\" class=\"btn btn-success\" >Thêm</button>
                            <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class=\"modal fade\" id=\"addTeachtoClass\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <form method=\"post\">
                        <div class=\"modal-header\">
                            <h4 class=\"modal-title\">Thêm giảng viên vào lớp học</h4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                        </div>

                        <div class=\"modal-body\">
                            <label for=\"username-add\"><b>Email giảng viên cần thêm</b></label>
                            <br>
                            <input type=\"text\" id=\"username-add\" placeholder=\"Ví dụ: giangvien1@gmail.com,..\" name=\"username\" class=\"form-control\">
                        </div>

                        <div class=\"modal-footer\">
                            <input type=\"hidden\" name=\"action\" value=\"add-teach\" id=\"action-add-teach\">
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
</html>
