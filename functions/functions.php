<?php

function clean($string) {
    return htmlentities($string);
}
function email_exsits($email) {
    //uklanjanje svih nedozvoljenih znakova iz e-poste
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = query($query);
    // Ukoliko postoji broj redova postoji i korisnik a ako ne onda je false
    if($result -> num_rows > 0) {
        return true;
    } else {
         return false;
    }
} 

function user_exsits($user) {
    //uklanjanje svih nedozvoljenih znakova iz e-poste
    $user = filter_var($user, FILTER_SANITIZE_EMAIL);
    $query = "SELECT id FROM users WHERE username = '$user'";
    $result = query($query);
    // Ukoliko postoji broj redova postoji i korisnik a ako ne onda je false
    if($result -> num_rows > 0) {
        return true;
    } else {
         return false;
    }
} 

function set_message($message) {
    if(!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        $message = "";
    }
}

function display_message() {
    if(isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location) {
    header("location: {$location}");
    exit();
}
function validate_user_registration() {
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $first_name = clean($_POST['first_name']);
        $last_name = clean($_POST['last_name']);
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);

        if(strlen($first_name) < 3) {
            $errors[] = "Your first name cannot be less than 3 characters";
        }
        if(strlen($last_name) < 3) {
            $errors[] = "Your last name cannot be less than 3 characters";
        }
        if(strlen($username) < 3) {
            $errors[] = "Your username cannot be less than 3 characters";
        }
        if(strlen($username) > 20) {
            $errors[] = "Your username cannot be longer than 3 characters";
        }

        // proveravamo da li email vec postoji u bazi
        if(email_exsits($email)) {
            $errors[] = "Sorry that Email is already taken";
        }
        // if(user_exists($username)) {
        //     $errors[] = "Sorry that Email is already taken";
        // }
        if(strlen($password) < 8) {
            $errors = "Your password cannot be less than 8 characters";
        }
        if($password != $confirm_password ) {
            $errors[] = "Your password wasn't confirmed correctly";
        }
        if(!empty($errors)) {
            foreach($errors as $error) {
                echo '<div class="alert">' . $error . '</div>';
            }
        } else  {
            $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
            $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($password, FILTER_SANITIZE_STRING);

            
            create_user($first_name, $last_name,$username,$email,$password);
        }
    }
}
function create_user($first_name, $last_name,$username,$email,$password) {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $first_name = escape($first_name);
        $last_name = escape($last_name);
        $username = escape($username);
        $email = escape($email);
        $password = escape($password);
        //da password ne bi mogao da se kriptuje u plain tekstu - cak i neko da nam provali u bazu nece moci da dodje do originalnog password-a
        $password = password_hash($password, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO users(first_name, last_name,username,profile_image,email,password)";
        $sql .= "VALUES('$first_name', '$last_name', '$username', 'uploads/default.jpg', '$email', '$password') ";
    
        confim(query($sql));
    }
    set_message("You have been succesfully registered! Please Log in!");
    redirect("login.php");
}
function validate_user_login() {
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);

        if(empty($email)) {
            $errors[] = "Email field cannot be empty";
        }
        if(empty($password)) {
            $errors[] = "Password field cannot be empty";
        }
        if(empty($errors)) {
            if(user_login($email, $password)) {
                redirect("index.php");
            }else {
                $errors[] = "Your email or password is incorrect, please try again.";
            }
        }
        if(!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert">' . $error . '</div>';

            }
        }
    }
}
function user_login($email, $password) {
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = query($query);

    if($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    if(password_verify($password, $data['password'] )) {
        $_SESSION['email'] = $email;
        return true;
        } else {
            return false;
        }
     } else {
        return false;
   }
}
function get_user($id = NULL)
{
    if ($id != NULL) {
        $query = "SELECT * FROM users WHERE id=" . $id;
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "User not found.";
        }
    } else {
        $query = "SELECT * FROM users WHERE email='" . $_SESSION['email'] . "'";
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "User not found.";
        }
    }
}
