<?php

//check if admin
session_start();
if ($_SESSION["status"] != "admin") {
    header("location: /?error=Access Denied");
}

include ("_dbconnect.php");

$id = $_GET["user"];

//initializing array for columns
$columns = [];

//getting names of columns if all or single
if ($id == "*") {
    $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'attendance' AND table_schema = 'management'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        //pushing names to array
        array_push($columns, $row["COLUMN_NAME"]);
    }
} else {
    array_push($columns, "date", $id);
}

//taking ids
array_shift($columns);
$ids = $columns;

//taking headers as names
$headers = 'Date';
foreach ($ids as $id) {
    $sql = "SELECT `name` FROM `users` WHERE `id` = '$id'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $name = $row["name"];
    $headers .= "\t" . ucwords($name);
}

//ending headers
$headers .= "\n";

//getting dates
$start = $_GET["start"];
$end = $_GET["end"];

//testing if start date is greator than end date
if ($start > $end) {
    header("location: /admin?error=Wrong Input Dates");
}

//intilalizing for multiple ids sql
$col = '';

//taking ids as a single id for fetching date and data
foreach ($ids as $id) {
    $col .= ",`" . $id . "`";
}


//getting dates data
$sql = "SELECT `date` $col FROM `attendance` WHERE `date` >= ? AND `date` <= ? ORDER BY `date` ASC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $start, $end);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);

$rows = '';

while ($row = mysqli_fetch_row($result)) {
    foreach ($row as $data) {
        //turning number to dates
        switch ($data) {
            case 0:
                $data = "Unattended";
                break;
            case 1:
                $data = "Present";
                break;
            case 2:
                $data = "Leave Request";
                break;
            case 3:
                $data = "Absent";
                break;
            case 4:
                $data = "Leave";
                break;
            default:
                $data;
                break;
        }
        //seperating columns
        $rows .= $data . "\t";
    }
    //seperating rows
    $rows .= "\n";
}

echo $headers . $rows;



header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Report.xls");