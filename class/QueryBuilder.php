<?php

class QueryBuilder
{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    function get_user(string $email)
    {
        $sql = "SELECT email FROM users WHERE email ='$email'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['email' => $email]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function user_register($email, $pass)
    {
        $pass = md5($pass);
        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$pass')";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $statement->fetchAll(PDO::FETCH_ASSOC);
        return $this->pdo->lastInsertId();
    }


    function user_authorization(string $email, string $pass)
    {
        $sql = "SELECT email, password FROM users WHERE email ='$email'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $item = $statement->fetchAll(PDO::FETCH_ASSOC);
        $pass = md5($pass);
        $login_data = [["email" => "$email", "password" => "$pass"]];
        if ($item == $login_data) {
            $_SESSION['auth'] = $email;
            return true;
        } else {
            return false;
        }
    }


    function check_for_admin()
    {
        $email = $_SESSION['auth'];
        $sql = "SELECT role FROM users WHERE email ='$email'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $item = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($item == [['role' => 'admin']]) {
            return true;
        } else {
            return false;
        }
    }

    function access_check()
    {
        $email = $_SESSION['auth'];
        $sql = "SELECT id FROM users WHERE email ='$email'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $item = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($item == [['id' => $_GET['id']]]) {
            return true;
        } else {
            return false;
        }
    }

    function all_users_withdrawal()
    {
        $sql = "SELECT * FROM users";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $i = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $i;
    }

    function edit_general_information($user_id, $first_name, $position, $phone, $address)
    {
        $sql = "UPDATE users SET first_name = '$first_name', position = '$position', phone = '$phone', address = '$address' WHERE id = $user_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    function edit_work_status($user_id, $work_status)
    {
        $status = [["status" => "online", "value" => "Онлайн"], ["status" => "away", "value" => "Отошел"], ["status" => "not disturb", "value" => "Не беспокоить"]];
        foreach ($status as $i) {
            if ($i['value'] == $work_status) {
                $j = $i['status'];
            }
        }
        $sql = "UPDATE users SET work_status = '$j' WHERE id = $user_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function edit_social($user_id, $vk, $telegram, $instagram)
    {
        $sql = "UPDATE users SET vk = '$vk', telegram = '$telegram', instagram = '$instagram' WHERE id = $user_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function edit_avatar($user_id)
    {
        $uploaddir = 'C:\\OpenServer\\domains\\php.lol\\traningProject\\img\\demo\\avatars\\';
        $uploadfile = $uploaddir . $user_id . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile);
        $link = 'img/demo/avatars/' . $user_id . $_FILES['avatar']['name'];

        $sql = "UPDATE users SET avatar = '$link' WHERE id = $user_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_user_id_by_email($email)
    {
        $sql = "SELECT id FROM users WHERE email ='$email'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_user_by_id($id)
    {
        $sql = "SELECT * FROM users WHERE id ='$id'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function edit_credential($user_id, $email, $password, $old_password)
    {
        if (isset($password)) {
            $pass = md5($password);
        } else {
            $pass = $old_password;
        }

        $sql = "UPDATE users SET email = '$email', password = '$pass' WHERE id = $user_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $statement->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['auth'] = $email;
    }

    function has_image($user_id)
    {
        $sql = "SELECT avatar FROM users WHERE id = $user_id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $item = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (empty($item)) {
            return 'img/demo/avatars/avatar-m.png';
        } else {
            return $item;
        }
    }

    function delete($user_id)
    {

        function user_delete($user_id)
        {
            $pdo = new PDO("mysql:host=127.0.0.1; dbname=phptraning;", "root", "root");
            $sql = "SELECT avatar FROM users WHERE id = $user_id";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $avatar = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (isset($avatar[0]['avatar'])) {
                unlink($avatar[0]['avatar']);
            }
            $sql = "DELETE FROM users WHERE id = $user_id";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        if (isset($_GET['delete'])) {
            if (access_check()) {
                user_delete($user_id);
                set_flash_message("success", "Ваш профиль удален");
                unset($_SESSION['auth']);
                redirect_to("page_login.php");
            } else {
                if (check_for_admin()) {
                    user_delete($user_id);
                    set_flash_message("success", "Профиль пользователя удален");
                } else {
                    set_flash_message("danger", "Можно удалить только свой профиль");
                }
                redirect_to("users.php");
            }
        }
    }
}

?>