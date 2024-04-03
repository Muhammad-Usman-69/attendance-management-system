<?php
//check if server method is post
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("location:/log?error=Access denied");
    exit();
}

//taking email and pass
$email = $_POST["email"];
$pass = $_POST["pass"];

//checking if email and pass are empty
if ($email == "" && $pass == "") {
    header("location: /log?error=Invalid cresidentials");
    exit();
}

//verfiying email
include ("_dbconnect.php");
$sql = "SELECT * FROM `users` WHERE `email` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);

//check if email is inavlid
if ($num == 0) {
    header("location: /log?error=Invalid email");
    exit();

}

//fetching name and id
$row = mysqli_fetch_assoc($result);
$name = $row["name"];
$id = $row["id"];
$status = $row["status"];

//verifying pass
if (password_verify($pass, $row["pass"])) {
    session_start();
    $_SESSION["log"] = true;
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["id"] = $id;
    $_SESSION["status"] = $status;
    header("location: /?alert=You are logged in");
    exit();
} else {
    //wrong pass
    header("location: /log?error=Wrong password");
    exit();
}