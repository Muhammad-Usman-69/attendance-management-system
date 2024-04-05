<?php
session_start();

//checking if user is logged
if (!isset($_SESSION["log"])) {
    header("location:log");
    exit();
}

//check if student
if ($_SESSION["status"] == "student") {
    header("location:/");
    exit();
}

//getting id
$id = $_GET["id"];

//including db
include ("partials/_dbconnect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="author" content="Muhammad Usman" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Attendance Management System</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="side/style.css" />
</head>

<body class="bg-[#F8F8F8]">
    <button class="py-2 px-4 m-4 mb-0 rounded-md bg-blue-600 active:bg-blue-800 text-white shadow-md z-20"
        onclick="window.location.assign(`/admin`)">Home</button>
    <div class="m-4 p-4 space-y-4 bg-white rounded-md shadow-md">
        <?php
        //showing attendance
        //taking data of attendance
        $sql = "SELECT `date`, $id FROM `attendance`";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        //increamenting data
        while ($row = mysqli_fetch_assoc($result)) {

            $date = $row["date"];

            if ($row[$id] == 0) {
                echo '<div class="p-2 bg-[#F8F8F8] border-gray-700 border-2 flex items-center justify-between rounded-md shadow-md">
                <p class="text-lg font-semibold px-2 text-gray-700">' . $date . '</p>
                <!-- delete -->
                <button class="py-2 px-4 rounded-md bg-blue-600 active:bg-blue-800 text-white shadow-md z-20"
                    onclick="window.location.assign(`partials/_mark?mark=1&id=' . $id . '&date=' . $date . '`)">Add</button>
            </div>';
            }

            //displaying present
            if ($row[$id] == 1) {
                echo '<div class="p-2 bg-green-100 border-green-600 border-2 flex items-center justify-between rounded-md shadow-md">
                <p class="text-lg font-semibold px-2 text-green-700">' . $date . '</p>
                <div class="flex space-x-2">
                    <!-- present -->
                    <button class="flex items-center bg-green-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=1&id=' . $id . '&date=' . $date . '`)">
                        <img src="images/tick.png" class="w-10 h-10 p-1 border-2 border-green-700 rounded-full">
                    </button>
                    <!-- absent -->
                    <button class="flex items-center bg-red-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=3&id=' . $id . '&date=' . $date . '`)">
                        <img src="images/close.png" class="w-10 h-10 p-1 border-2 border-red-700 rounded-full">
                    </button>
                    <!-- leave -->
                    <button class="flex items-center bg-orange-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=4&id=' . $id . '&date=' . $date . '`)">
                        <img src="images/leave.png" class="w-10 h-10 p-1 border-2 border-orange-700 rounded-full">
                    </button>
                    <!-- delete -->
                    <button class="py-2 px-4 rounded-md bg-blue-600 active:bg-blue-800 text-white shadow-md z-20"
                    onclick="window.location.assign(`partials/_mark?mark=0&id=' . $id . '&date=' . $date . '`)">Delete</button>
                </div>
            </div>';
            }

            //displaying absent
            if ($row[$id] == 3) {
                echo '<div class="p-2 bg-red-100 border-red-700 border-2 flex items-center justify-between rounded-md shadow-md">
                <p class="text-lg font-semibold px-2 text-red-700">' . $row['date'] . '</p>
                <div class="flex space-x-2">
                    <!-- present -->
                    <button class="flex items-center bg-green-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=1&id=' . $id . '&date=' . $date . '`)">
                        <img src="images/tick.png" class="w-10 h-10 p-1 border-2 border-green-700 rounded-full">
                    </button>
                    <!-- absent -->
                    <button class="flex items-center bg-red-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=3&id=' . $id . '&date=' . $date . '`)">
                        <img src="images/close.png" class="w-10 h-10 p-1 border-2 border-red-700 rounded-full">
                    </button>
                    <!-- leave -->
                    <button class="flex items-center bg-orange-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=4&id=' . $id . '&date=' . $date . '`)">
                        <img src="images/leave.png" class="w-10 h-10 p-1 border-2 border-orange-700 rounded-full">
                    </button>
                    <!-- delete -->
                    <button class="py-2 px-4 rounded-md bg-blue-600 active:bg-blue-800 text-white shadow-md z-20"
                    onclick="window.location.assign(`partials/_mark?mark=0&id=' . $id . '&date=' . $date . '`)">Delete</button>
                </div>
                </div>';
            }

            //displaying leave
            if ($row[$id] == 4) {
                echo '<div class="p-2 bg-orange-100 border-orange-600 border-2 flex items-center justify-between rounded-md shadow-md">
                <p class="text-lg font-semibold px-2 text-orange-700">' . $row['date'] . '</p>
                <div class="flex space-x-2">
                        <!-- present -->
                        <button class="flex items-center bg-green-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=1&id=' . $id . '&date=' . $date . '`)">
                            <img src="images/tick.png" class="w-10 h-10 p-1 border-2 border-green-700 rounded-full">
                        </button>
                        <!-- absent -->
                        <button class="flex items-center bg-red-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=3&id=' . $id . '&date=' . $date . '`)">
                            <img src="images/close.png" class="w-10 h-10 p-1 border-2 border-red-700 rounded-full">
                        </button>
                        <!-- leave -->
                        <button class="flex items-center bg-orange-100 rounded-full" onclick="window.location.assign(`partials/_mark?mark=4&id=' . $id . '&date=' . $date . '`)">
                            <img src="images/leave.png" class="w-10 h-10 p-1 border-2 border-orange-700 rounded-full">
                        </button>
                        <!-- delete -->
                        <button class="py-2 px-4 rounded-md bg-blue-600 active:bg-blue-800 text-white shadow-md z-20"
                        onclick="window.location.assign(`partials/_mark?mark=0&id=' . $id . '&date=' . $date . '`)">Delete</button>
                    </div>
                </div>';
            }
        }
        ?>
    </div>
</body>

</html>