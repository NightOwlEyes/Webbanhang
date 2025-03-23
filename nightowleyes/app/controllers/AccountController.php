<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/SessionHelper.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    function register() {
        include_once 'app/views/account/register.php';
    }

    public function login() {
        include_once 'app/views/account/login.php';
    }

    function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];

            if (empty($username)) {
                $errors['username'] = "Vui long nhap userName!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui long nhap fullName!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui long nhap password!";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mat khau va xac nhan chua dung";
            }

            // Kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tai khoan nay da co nguoi dang ky!";
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $password);
                if ($result) {
                    header('Location: /nightowleyes/account/login');
                }
            }
        }
    }

    function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if(isset($_SESSION['username'])) {
            error_log("Logging out user: " . $_SESSION['username']);
        }
        
        unset($_SESSION['username']);
        unset($_SESSION['user_role']);
        
        header('Location: /nightowleyes/product');
        exit;
    }

    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $account = $this->accountModel->getAccountByUsername($username);

            if ($account) {
                $pwd_hashed = $account->password;
                if (password_verify($password, $pwd_hashed)) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    $_SESSION['username'] = $account->username;
                    $_SESSION['user_role'] = $account->role;
                    
                    error_log("User logged in: " . $account->username);
                    
                    if ($account->role === 'admin') {
                        $_SESSION['login_message'] = "Bạn đang đăng nhập với tư cách quản trị viên";
                    }
                    
                    header('Location: /nightowleyes/product');
                    exit;
                } else {
                    $_SESSION['login_error'] = "Mật khẩu không chính xác";
                    header('Location: /nightowleyes/account/login');
                    exit;
                }
            } else {
                $_SESSION['login_error'] = "Tài khoản không tồn tại";
                header('Location: /nightowleyes/account/login'); 
                exit;
            }
        }
    }
}