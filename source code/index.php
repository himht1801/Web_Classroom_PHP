<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="main.js" ></script>
</head>
<body>
    <?php
        session_start();
        if (!isset($_SESSION['user']) || !isset($_SESSION['name'])) {
            header('Location: login.php');
            exit();
        }
        $username = $_SESSION['user'];
        $fullname_user = $_SESSION['name'];
        include 'db.php';
        $conn = openMySQLConnection();
        if(isset($_POST['action'])){
            if($_POST['action']=="join-class"){
                if(isset($_POST['key'])){
                    $code_join = $_POST['key'];
                    if(have_class_by_code($code_join)){
                        if(check_user_in_class_by_email(get_mail_by_username($username),$code_join)){
                            echo "<script type='text/javascript'>alert('Bạn đã ở trong nhóm này rồi');</script>";
                        }
                        else{
                            if(have_in_pending($username,$code_join)){
                                echo "<script type='text/javascript'>alert('Yêu cầu của bạn đã có, đang đợi xử lý');</script>";
                            }
                            else{
                                if(add_user_to_class_by_email($code_join,get_mail_by_username($username),2)){
                                    echo "<script type='text/javascript'>alert('Yêu cầu của bạn đang được xử lý');</script>";
                                }
                                else{
                                    echo "<script type='text/javascript'>alert('không thể tham gia');</script>";
                                }
                            }
                            
                        }
                        
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Mã này không tồn tại lớp học');</script>";
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Vui lòng nhập mã lớp học');</script>";
                }
            }
            if($_POST['action']=='delete-class'){
                if(isset($_POST['key'])){
                    $key_delete = $_POST['key'];
                    if(check_role($username,$key_delete)){
                        if(remove_class_by_key($key_delete)){
                            echo "<script type='text/javascript'>alert('Đã xóa lớp học');</script>";
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Xóa lớp học thất bại, vui lòng kiểm tra lại');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền xóa lớp học này, hoặc lớp học cần xóa không hợp lệ');</script>";
                    }
                    
                }
                else{
                    echo "<script type='text/javascript'>alert('Vui lòng chọn lớp học cần xóa');</script>";
                }
            }
            
            if($_POST['action']=='edit-class'){
                if(isset($_POST['key'])){
                    $key_update = $_POST['key'];
                    
                    if(check_role($username,$key_update)){
                        $ten = $_POST['name'];
                        $mt = $_POST['desc'];
                        $phan = $_POST['part'];
                        $phong = $_POST['room'];
                        $cd = $_POST['topic'];
                        if(strlen($ten)==0){
                            echo "<script type='text/javascript'>alert('Vui lòng nhập tên');</script>";
                        }
                        else{
                            if(update_class($key_update,$ten,$mt,$phan,$phong,$cd)){
                                echo "<script type='text/javascript'>alert('cập nhật lớp học $ten thành công');</script>";
                            }
                            else{
                                echo "<script type='text/javascript'>alert('cập nhật lớp học thất bại');</script>";
                            }
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Bạn không có quyền sửa lớp học này, hoặc lớp học cần xóa không hợp lệ');</script>";
                    }
                }
            }

            if($_POST['action']=='add-class'){
                if(isset($_POST['name'])){
                    $ten = $_POST['name'];
                    if(strlen($ten)>0){
                        $mt = $_POST['desc'];
                        $phan = $_POST['part'];
                        $phong = $_POST['room'];
                        $cd = $_POST['topic'];
                        
                        if(create_class($ten,$mt,$phan,$phong,$cd,$username)){
                            echo "<script type='text/javascript'>alert('Tạo lớp học thành công');</script>";
                        }
                        else{
                            echo "<script type='text/javascript'>alert('Tạo lớp học thất bại');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('Vui lòng nhập tên của lớp học');</script>";
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('Vui lòng nhập tên của lớp học');</script>";
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
                        <a class="nav-link" href="index.php">
                            <div class="name-title">
                                <b id="t">T</b>
                                <b id="d">D</b>
                                <b id="t">T</b>
                                <b>&nbsp;Classroom</b>
                            </div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#modalTaolophoc"><b>Tạo lớp học</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#join-class"><b>Tham gia lớp học</b></a>
                    </li>

                    <!-- Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            <b>Lớp học</b>
                        </a>
                        <div class="dropdown-menu">
                            <!-- hiện ra danh sách lớp học -->
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
                            <!-- <a class="dropdown-item" href="#">Lớp 1</a>
                            <a class="dropdown-item" href="#">Lớp 2</a>
                            <a class="dropdown-item" href="#">Lớp 3</a> -->
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
        <div class="row">
            <?php
                $sql = "SELECT lophoc.tenLop, lophoc.motaLop, lophoc.codelop, account_lophoc.role, lophoc.phanHoc, lophoc.phongHoc, lophoc.chude FROm account_lophoc INNER JOIN lophoc ON account_lophoc.idLop = lophoc.idLop INNER JOIN account on account.username = account_lophoc.username WHERE account.username ='$username'";
                $result = $conn->query($sql);
                if(!$result){
                    trigger_error('Invalid query' . $conn->error);
                
                }
                $counter = 0;
                if($result->num_rows>0){
                    while($row = $result->fetch_assoc()){
                        
                        $counter = $counter + 1;
                        $tenlop = $row['tenLop'];
                        $mota = $row['motaLop'];
                        $codelop = $row['codelop'];
                        $role = $row['role'];
                        $room = $row['phongHoc'];
                        $phanhoc = $row['phanHoc'];
                        $chude = $row['chude'];
                        // print_r($row);
                        if($role==1){
                            echo "<div class='col-sm-12 col-md-6 col-lg-4'>
                            <div class='card'>
                                <img class='card-img-top' src='./images/background-classroom.jpg' alt='Card image cap'>
                                <div class='card-body'>
                                    <b class='card-title' href='#'>$tenlop</b>
                                    <p class='name-teacher'>$mota</p>
                                </div>
                                <div class='card-footer' >
                                    <button  id='btn-view' value='$codelop' class='btn btn-success' onclick=\"window.location.href='/class.php?code=$codelop'\">Xem</button>
                                    <button  id='btn-edit' class='btn btn-warning' data-toggle='modal' data-target='#modalSualophoc' onclick=\"showEditClass('$tenlop','$mota','$phanhoc','$room','$chude','$codelop')\">Sửa</button>
                                    <button id='btn-delete' class = 'btn btn-danger' data-toggle='modal' data-target='#modalXoalophoc' onclick=\"showDeleteClass('$codelop','$tenlop')\"> Xóa</button>
                                </div>
                            </div>
                        </div>";
                        }
                        if($role == 2){
                            echo "<div class='col-sm-12 col-md-6 col-lg-4'>
                            <div class='card'>
                                <img class='card-img-top' src='./images/background-classroom.jpg' alt='Card image cap'>
                                <div class='card-body'>
                                    <a class='card-title' href='#'>$tenlop</a>
                                    <p class='name-teacher'>$mota</p>
                                </div>
                                <div class='card-footer' >
                                    <button  id='btn-edit' value='$codelop'  class='btn btn-success' onclick=\"window.location.href='/class.php?code=$codelop'\">Xem</button>
                                </div>
                            </div>
                        </div>";
                        }
                    }
                }
            ?>
        </div>
    </div>

    <!--Join class dialog-->
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
                        <input type="hidden" name="action" value="join-class" id="join-class">
                        <!-- <input type="hidden" name="key" value="12121" id="id-join"> -->
                        <button type="submit" class="btn btn-success" >Tham gia</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Account dialog-->
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
    <!-- modal xoa lop hoc -->
    <div id="modalXoalophoc" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form action="" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Xóa lớp học</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn xóa lớp học <strong id='nameClassDel'></strong> này không?</p>
                    </div>
                    <div class="modal-footer" >
                        <input type="hidden" name="action" value="delete-class" id="action-delete">
                        <input type="hidden" name="key" value="12121" id="key-delete">
                        <button type="submit" class="btn btn-danger">Xóa</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
        
    </div>
    <!-- model sửa lop hoc -->
    <div class="modal fade" id="modalSualophoc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Cập nhật thông tin</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <label for="name-class"><b>Tên lớp học</b></label>
                        <br>
                        <input type="text" id="name-class" placeholder="Tên lớp học(bắt buộc)" name="name" class="form-control">
                        <br>
                        <label for="desc-class"><b>Mô tả lớp học</b></label>
                        <br>
                        <input type="text" id="desc-class" placeholder="Mô tả lớp học(tùy chọn)" name="desc" class="form-control"> 
                        <br>
                        <label for="part-class"><b>Phần học</b></label>
                        <br>
                        <input type="text" id="part-class" placeholder="Phần học (tùy chọn)" name="part" class="form-control">
                        <br>
                        <label for="room-class"><b>Phòng học</b></label>
                        <br>
                        <input type="text" id="room-class" placeholder="Phòng học (tùy chọn)" name="room" class="form-control">
                        <br>
                        <label for="chude-class"><b>Chủ đề</b></label>
                        <br>
                        <input type="text" id="chude-class" placeholder="Chủ đề (tùy chọn)" name="topic" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="edit-class" id="action-edit">
                        <input type="hidden" name="key" value="121321323132" id="key-edit">
                        <button type="submit" class="btn btn-success" >Cập nhật</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal them lop hoc -->
    <div class="modal fade" id="modalTaolophoc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Tạo lớp học</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <label for="name-class"><b>Tên lớp học</b></label>
                        <br>
                        <input type="text" id="name-class" placeholder="Tên lớp học(bắt buộc)" name="name" class="form-control">
                        <br>
                        <label for="desc-class"><b>Mô tả lớp học</b></label>
                        <br>
                        <input type="text" id="desc-class" placeholder="Mô tả lớp học(tùy chọn)" name="desc" class="form-control">
                        <br>
                        <label for="part-class"><b>Phần học</b></label>
                        <br>
                        <input type="text" id="part-class" placeholder="Phần học (tùy chọn)" name="part" class="form-control">
                        <br>
                        <label for="room-class"><b>Phòng học</b></label>
                        <br>
                        <input type="text" id="room-class" placeholder="Phòng học (tùy chọn)" name="room" class="form-control">
                        <br>
                        <label for="chude-class"><b>Chủ đề</b></label>
                        <br>
                        <input type="text" id="chude-class" placeholder="Chủ đề (tùy chọn)" name="topic" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="add-class" id="action-add">
                        <button type="submit" class="btn btn-success" >Tạo</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        
    </script>
</body>
</html>
