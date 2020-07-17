<?php
require './config.php';
require './models/Auth.php';

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$birthdate = filter_input(INPUT_POST, 'birthdate');

if($name && $email && $password && $birthdate) {

    $auth = new Auth($pdo, $base);

    $birthdate = explode('/', $birthdate);
    if(count($birthdate) != 3) {
        $_SESSION['flash'] = '<span style="color: #f00">Data de nascimento inválida!<span>';
        header("Location: ".$base."/signup.php");
        exit;

    }

    $birthdate = $birthdate['2'].'-'.$birthdate['1'].'-'.$birthdate[0];
    if(strtotime($birthdate) === false) {
        $_SESSION['flash'] = '<span style="color: #f00">Data de nascimento inválida!<span>';
        header("Location: ".$base."/signup.php");
        exit;
    }

    if($auth->emailExists($email) === false) {

        $auth->registerUser($name, $email, $password, $birthdate);
        header("Location: ".$base);
        exit;

    } else {
        $_SESSION['flash'] = '<span style="color: #f00">E-mail já cadastrado!<span>';
        header("Location: ".$base."/signup.php");
        exit;
    }

}

$_SESSION['flash'] = '<span style="color: #f00">Campos não enviados!<span>';
header("Location: ".$base."/signup.php");
exit;
