<?php
    session_start();
    include ('./config.php');
    include ('./DB.php');

    if (isset($_GET['API']))
    {
        $func = $_GET['API'];
        if(!function_exists('API_' . $func)){
            require_once ('./404.php');
            return;
        }
        call_user_func('API_' . $func);
    }

    function isLogin()
    {
        if (isset($_SESSION['user']))
            return true;
        return false;
    }

    function API_Register()
    {
        if (isLogin())
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng đăng xuất và thực hiện lại',
                'data' => ''
            ));
            return;
        }

        if (!isset($_POST['user']) || 
            !isset($_POST['pass']) || 
            !isset($_POST['full_name']) )
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin',
                'data' => ''
            ));
            return;
        }

        if (strlen($_POST['user']) < 6 || strlen($_POST['user']) > 12)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Độ dài tên đăng nhập từ 6-12 ký tự',
                'data' => ''
            ));
            return;
        }

        if (strlen($_POST['pass']) < 8 || strlen($_POST['pass']) > 30)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Độ dài mật khẩu từ 8-30 ký tự',
                'data' => ''
            ));
            return;
        }

        if (strlen($_POST['full_name']) < 8 || strlen($_POST['full_name']) > 255)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Độ dài họ và tên từ 8-30 ký tự',
                'data' => ''
            ));
            return;
        }

        $user = $_POST['user'];
        $pass = md5($_POST['pass']);
        $full_name = $_POST['full_name'];

        $result = db_select('account', "`user` = $user");
        if (count($result )> 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Tài khoản đã tồn tại',
                'data' => ''
            ));
            return;
        }

        db_insert('account', array(
            'user' => $user,
            'pass' => $pass,
            'full_name' => $full_name
        ));

        echo json_encode(array(
            'status' => true,
            'message' => 'Đăng ký tài khoản thành công. Vui lòng kiểm tra Email để lấy thông tin tài khoản và mật khẩu',
            'data' => ''
        ));
        return;
    }

    function API_Login()
    {
        if (isLogin())
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng đăng xuất và thực hiện lại',
                'data' => ''
            ));
            return;
        }

        if (!isset($_POST['user']) || 
            !isset($_POST['pass']) )
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin',
                'data' => ''
            ));
            return;
        }
        $account_exist = db_select('account', "`user` = '". $_POST['user'] . "'");

        if (count($account_exist) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Tài khoản không tồn tại',
                'data' => ''
            ));
            return;
        }

        $result = db_select('account', "`user` = '". $_POST['user'] . "' AND `pass` = " . md5($_POST['pass']));

        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Mật khẩu không chính xác',
                'data' => ''
            ));
            return;
        }

        $_SESSION['user'] = $_POST['user'];

        
        echo json_encode(array(
            'status' => true,
            'message' => 'Đăng nhập tài khoản thành công',
            'data' => array(
                'user_type' => $result[0]['user_type']
            )
        ));
        return;
    }

    function API_GetInformation()
    {
        if (!isLogin())
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng đăng nhập và thực hiện lại',
                'data' => ''
            ));
            return;
        }
        $user = $_SESSION['user'];

        $result = db_select('account', "`user` = $user");
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy thông tin',
                'data' => ''
            ));
            return;
        }

        $info = array(
            'full_name' => $result[0]['full_name'],
            'email' => $result[0]['full_name'],
            'birthday' => $result[0]['full_name'],
            'gender' => $result[0]['full_name'],
            'address' => $result[0]['full_name'],
            'user_type' => $result[0]['full_name'],
        );

        echo json_encode(array(
            'status' => true,
            'message' => 'Lấy thông tin cá nhân thành công',
            'data' => $info
        ));
        return;
    }

    function API_GetPosts()
    {
        $result = db_select('posts', '1');
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Chưa có bài viết nào',
                'data' => ''
            ));
            return;
        }
        echo json_encode(array(
            'status' => false,
            'message' => 'Đã tìm thấy thông tin bài viết',
            'data' => $result
        ));
    }
?>