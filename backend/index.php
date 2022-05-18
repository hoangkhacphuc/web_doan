<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    session_start();
    include ('./config.php');
    include ('./DB.php');

    if (isset($_GET['API']))
    {
        $func = $_GET['API'];
        if(!function_exists('API_' . $func)){
            echo "404";
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

        $result = db_select('account', "`user` = '$user'");
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

        $result = db_select('account', "`user` = '$user'");
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
        $result = db_select('posts', "`be_attended` = '0'");
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Chưa có bài viết nào',
                'data' => ''
            ));
            return;
        }
        $data = array();
        foreach ($result as $item) {
            $temp = array(
                'id' => $item['id'],
                'title' => $item['title'],
            );
            array_push($data, $temp);
        }

        echo json_encode(array(
            'status' => true,
            'message' => 'Đã tìm thấy thông tin bài viết',
            'data' => $data
        ));
    }

    function API_GetEvents()
    {
        $result = db_select('posts', "`be_attended` = '1'");
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Chưa có sự kiện nào',
                'data' => ''
            ));
            return;
        }
        $data = array();
        foreach ($result as $item) {
            $temp = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'deadline' => $item['deadline']
            );
            array_push($data, $temp);
        }

        echo json_encode(array(
            'status' => true,
            'message' => 'Đã tìm thấy thông tin sự kiện',
            'data' => $data
        ));
    }

    function API_GetPostsDetail()
    {
        if (!isset($_GET['id']))
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có ID bài viết',
                'data' => ''
            ));
            return;
        }
        $id = $_GET['id'];
        $post = db_select_query("SELECT p.id, p.title, p.content, p.be_attended, p.deadline, p.date_created, a.full_name
        FROM account as a, posts as p
        WHERE p.account_id = a.id AND p.id = '$id'");

        if (count($post) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy bài viết',
                'data' => ''
            ));
            return;
        }

        echo json_encode(array(
            'status' => true,
            'message' => 'Đã tìm thấy bài viết',
            'data' => $post[0]
        ));
    }

    function API_Subscribe()
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
        if (!isset($_GET['id']))
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có ID bài viết',
                'data' => ''
            ));
            return;
        }
        $id = $_GET['id'];
        $user = $_SESSION['user'];
        $date_current = date('Y-m-d');
        
        $post = db_select('posts', "`id` = '$id'");
        if (count($post) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy bài viết',
                'data' => ''
            ));
            return;
        }

        $post = db_select('posts', "`id` = '$id' AND `be_attended` = '1'");
        if (count($post) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không thể đăng ký vì bài viết này không phải sự kiện',
                'data' => ''
            ));
            return;
        }

        $post = db_select('posts', "`id` = '$id' AND `be_attended` = '1' AND `deadline` >= '$date_current'");
        if (count($post) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không thể đăng ký vì quá thời gian đăng ký',
                'data' => ''
            ));
            return;
        }


        $user_id = db_select('account', "`user` = $user")[0]['id'];

        $post = db_select('participate', "`account_id` = '$user_id' AND `post_id` = '$id'");
        if (count($post) > 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Đã đăng ký từ trước',
                'data' => ''
            ));
            return;
        }

        db_insert('participate', array(
            'account_id' => $user_id,
            'post_id' => $id
        ));

        echo json_encode(array(
            'status' => true,
            'message' => 'Đăng ký tham gia sự kiện thành công',
            'data' => ''
        ));
    }

    function API_Unsubscribe()
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
        if (!isset($_GET['id']))
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có ID bài viết',
                'data' => ''
            ));
            return;
        }
        $id = $_GET['id'];
        $user = $_SESSION['user'];
        
        $post = db_select('posts', "`id` = '$id'");
        if (count($post) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy bài viết',
                'data' => ''
            ));
            return;
        }

        $post = db_select('posts', "`id` = '$id' AND `be_attended` = '1'");
        if (count($post) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không thể hủy vì bài viết này không phải sự kiện',
                'data' => ''
            ));
            return;
        }

        $user_id = db_select('account', "`user` = $user")[0]['id'];

        $post = db_select('participate', "`account_id` = '$user_id' AND `post_id` = '$id'");
        if (count($post) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không thể hủy vì chưa đăng ký sự kiện này',
                'data' => ''
            ));
            return;
        }

        db_delete('participate', "`id` = $post[0]['id']");

        echo json_encode(array(
            'status' => true,
            'message' => 'Đăng ký tham gia sự kiện thành công',
            'data' => ''
        ));
    }

    function API_GetListAccount()
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
        if (!isAdmin())
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có quyền truy cập',
                'data' => ''
            ));
            return;
        }
        $user = $_SESSION['user'];
        $list = db_select('account', "`user` != '$user'");
        echo json_encode(array(
            'status' => true,
            'message' => 'Lấy danh sách đoàn viên thành công',
            'data' => $list
        ));
    }

    function API_UpdateInformation()
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
        if (!isAdmin())
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có quyền truy cập',
                'data' => ''
            ));
            return;
        }
        if (!isset($_POST['id']))
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có ID Đoàn viên',
                'data' => ''
            ));
            return;
        }
        if (!isset($_POST['full_name']) &&
            !isset($_POST['email']) &&
            !isset($_POST['birthday']) &&
            !isset($_POST['gender']) &&
            !isset($_POST['address']) &&
            !isset($_POST['user_type']) )
            {
                echo json_encode(array(
                    'status' => false,
                    'message' => 'Chưa có thông tin cập nhật',
                    'data' => ''
                ));
                return;
            }
            $data = array();
            if (isset($_POST['full_name']))
            {
                $data = array_merge($data, array(
                    'full_name' => $_POST['full_name']
                ));
            }
            if (isset($_POST['email']))
            {
                $data = array_merge($data, array(
                    'email' => $_POST['email']
                ));
            }
            if (isset($_POST['birthday']))
            {
                $data = array_merge($data, array(
                    'birthday' => $_POST['birthday']
                ));
            }
            if (isset($_POST['gender']))
            {
                $data = array_merge($data, array(
                    'gender' => $_POST['gender']
                ));
            }
            if (isset($_POST['address']))
            {
                $data = array_merge($data, array(
                    'address' => $_POST['address']
                ));
            }
            if (isset($_POST['user_type']))
            {
                $data = array_merge($data, array(
                    'user_type' => $_POST['user_type']
                ));
            }
            $id = $_POST['id'];
            $post = db_select('account', "`id` = '$id'");
            if (count($post) == 0)
            {
                echo json_encode(array(
                    'status' => false,
                    'message' => 'Không tìm thấy Đoàn viên',
                    'data' => ''
                ));
                return;
            }
            $result = db_update('account', $data, "`id` = '$id'");
            echo json_encode(array(
                'status' => true,
                'message' => 'Cập nhật thông tin thành công',
                'data' => ''
            ));
            return;
    }

    function API_DeleteAccount()
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
        if (!isAdmin())
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có quyền truy cập',
                'data' => ''
            ));
            return;
        }
        if (!isset($_POST['id']))
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không có ID Đoàn viên',
                'data' => ''
            ));
            return;
        }
        
        $id = $_POST['id'];

        $id = $_POST['id'];
        $result = db_select('account', "`id` = '$id'");
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy Đoàn viên',
                'data' => ''
            ));
            return;
        }

        $result = db_delete('account', "`id` = '$id'");
        echo json_encode(array(
            'status' => true,
            'message' => 'Xóa tài khoản Đoàn viên thành công',
            'data' => ''
        ));
        return;
    }

    function isAdmin() :bool
    {
        if (!isLogin())
        {
            return false;
        }
        $user = $_SESSION['user'];

        $user_type = db_select('account', "`user` = $user")[0]['user_type'];
        if ($user_type == 1)
            return true;
        return false;
    }

    function API_Logout()
    {
        session_unset();
        session_destroy();
        $response = array(
            'status' => true,
            'message' => 'Đã đăng xuất tài khoản',
            'data' => ''
        );
    }
?>