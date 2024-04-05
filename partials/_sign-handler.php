<?php
//check if req is fost
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("location: /sign?error=Access denied. Please try again later");
    exit();
}

//str for id
function random_str(
    $length,
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
) {
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

//taking diff values
$id = random_str(8);
$name = $_POST["name"];
$email = $_POST["email"];
$pass = $_POST["pass"];
$img = "none";

//check if any input is empty
if ($name == "" && $email == "" && $pass == "") {
    header("location: /sign?error=Invalid cresidentials.");
    exit();
}

//check if email already exists
include ("_dbconnect.php");
$sql = "SELECT * FROM `users` WHERE `email` = '$email'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if ($num != 0) {
    header("location: /sign?error=Email already in use");
    exit();
}

//check if name already exists
$sql = "SELECT * FROM `users` WHERE `name` = '$name'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if ($num != 0) {
    header("location: /sign?error=Name already in use");
    exit();
}

//inserting accont to db
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$pass = htmlspecialchars($pass, ENT_QUOTES, 'UTF-8');
$pass_hash = password_hash($pass, PASSWORD_DEFAULT);
$sql = "INSERT INTO `users` (`id`, `name`, `email`, `pass`, `img`) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssss", $id, $name, $email, $pass_hash, $img);
$result = mysqli_stmt_execute($stmt);

//checking if error occured
if (!$result) {
    header("location: /sign?error=Error occured. Please try again later");
    exit();
}

//inserting for attendance
$sql = "ALTER TABLE `attendance` ADD $id INT(1) NOT NULL DEFAULT '0' AFTER `date`";
$stmt = mysqli_prepare($conn, $sql);
$result = mysqli_stmt_execute($stmt);

//Get today's date
$date = date("Y-m-d");

//marking current as absent
$sql = "UPDATE `attendance` SET `$id` = '3' WHERE `date` = '$date'";
$stmt = mysqli_prepare($conn, $sql);
$result = mysqli_stmt_execute($stmt);

header("location: /sign?alert=You have been signed up");
exit();