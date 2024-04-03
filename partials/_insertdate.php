<?php
//Get today's date
$date = date("Y-m-d");

include ("_dbconnect.php");

//check if date is already inserted
$sql = "SELECT * FROM `attendance` WHERE `date` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);
if ($num != 0) {
    echo "already inserted";
    exit();
} 

//inserting date into column
$sql = "INSERT INTO `attendance` (`date`) VALUES (?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $date);
mysqli_stmt_execute($stmt);
echo "inserted successfully";

//marking absent of leave req
//getting previous date
$oldDate = date('Y-m-d',strtotime("-1 days"));

//getting names of column
$sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'attendance' AND table_schema = 'management'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);

//initializing array
$arr = array();

while ($row = mysqli_fetch_assoc($result)) {
    //pushing names to array
    array_push($arr, $row["COLUMN_NAME"]);

    //removing the first column 'date'
    unset($arr[0]);
}

//marking absent by looping though arr
foreach ($arr as $n) {
    $sql = "UPDATE `attendance` SET `$n` = 3 WHERE `date` = ? AND `$n` IN (0, 2) ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $oldDate);
    mysqli_stmt_execute($stmt);
}

// 0 = unattended
// 1 = present
// 2 = leave request
// 3 = absent
// 4 = leave