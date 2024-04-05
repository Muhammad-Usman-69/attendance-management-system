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

//including db
include ("partials/_dbconnect.php");

$id = $_SESSION["id"];

//taking account data from database
$sql = "SELECT * FROM `users` WHERE `id` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$name = $row["name"];
$profile_img = $row["img"];


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
  <!-- alert and error  -->
  <div class="alert transition-all duration-200">
    <?php
    if (isset($_GET["alert"])) {
      echo '<div class="bg-green-100 border border-green-400 hover:bg-green-50 text-green-700 px-4 py-3 rounded space-x-4 flex items-center justify-between fixed bottom-5 right-5 ml-5 transition-all duration-200 z-20"
        role="alert">
                <strong class="font-bold text-sm">' . $_GET["alert"] . '.</strong>
                <span onclick="hideAlert(this);">
                    <svg class="fill-current h-6 w-6 text-green-600 border-2 border-green-700 rounded-full" role="button"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>';
    } else if (isset($_GET["error"])) {
      echo '<div class="bg-red-100 border border-red-400 hover:bg-red-50 text-red-700 px-4 py-3 rounded space-x-4 flex items-center justify-between fixed bottom-5 right-5 ml-5 transition-all duration-200 z-20"
        role="alert">
                <strong class="font-bold text-sm">' . $_GET["error"] . '.</strong>
                <span onclick="hideAlert(this);">
                    <svg class="fill-current h-6 w-6 text-red-600 border-2 border-red-700 rounded-full" role="button"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>';
    }
    ?>
  </div>
  <!-- header -->
  <header class="m-4 p-4 flex justify-between bg-white rounded-md shadow-md relative">
    <div class="cursor-pointer">
      <button onclick="menuHandler()">
        <img src="images/menu.png" class="w-10 h-10" />
      </button>
      <div class="bg-white flex flex-col shadow-md rounded-md py-2 absolute z-10 left-0 top-[120%] border opacity-0"
        id="menu-container">
        <button type="button" onclick="window.location.assign('partials/_logout-handler.php')"
          class="hover:bg-[#f5f5f5] px-3 py-2 cursor-pointer hidden" id="logout-button">
          Log out
        </button>
      </div>
    </div>
    <div class="flex items-center space-x-4">
      <p class="font-bold">
        <?php echo $name; ?>
      </p>
      <button onclick="profileSetting()">
        <?php
        if ($profile_img == "none") {
          echo '<img src="images/profile/default.jpg" class="w-10 h-10 rounded-full" />';
        } else {
          echo '<img src="images/profile/' . $profile_img . '" class="w-10 h-10 rounded-full object-cover" />';
        }
        ?>
      </button>
      <div class="bg-white rounded-md shadow-md py-2 absolute right-0 top-[120%] border opacity-0" id="profile-setting">
        <form action="partials/_img-handler.php" enctype="multipart/form-data" method="post" id="profile-form">
          <button type="button" class="hover:bg-[#f5f5f5] px-3 py-2 cursor-default">
            Change Profile Picture
            <input type="file" accept="image/jpg, image/jpeg, image/png"
              class="absolute hidden opacity-0 w-full left-0 top-0 h-full" oninput="submitProfileImg()"
              name="profile-img" id="profile-img" />
          </button>
        </form>
      </div>
    </div>
  </header>

  <?php
  // checking for leave requests
  
  //getting names of column
  $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'attendance' AND table_schema = 'management'";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_execute($stmt);
  $resultNames = mysqli_stmt_get_result($stmt);
  $num = mysqli_num_rows($result);

  //initializing array
  $arr = array();

  while ($row = mysqli_fetch_assoc($resultNames)) {
    //pushing names to array
    array_push($arr, $row["COLUMN_NAME"]);

    //removing the first column 'date'
    unset($arr[0]);
  }

  //getting current date
  $date = date("Y-m-d");

  //request if there are leave requests
  $request = false;

  //initializing for people who want leave
  $requesters = array();

  //looping through account
  foreach ($arr as $n) {
    $sql = "SELECT `$n` FROM `attendance` WHERE `date` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row[$n] == 2) {
      $request = true;
      array_push($requesters, $n);
    }
  }

  ?>

  <?php
  if ($request) {
    //container start
    echo '<!-- leave requests -->
      <div class="m-4 p-4 bg-white rounded-md shadow-md">
      <div class="flex justify-between items-center">
        <p class="font-bold">Leave Requests:</p>
        <button class="py-2 px-4 rounded-md bg-blue-600 active:bg-blue-800 text-white shadow-md z-20" onclick="document.getElementById(`request-container`).classList.toggle(`hidden`)">View Requests</button>
        </div>
        <div class="space-y-4 hidden mt-4" id="request-container">';
    foreach ($requesters as $requester) {
      $sql = "SELECT `name` FROM `users` WHERE `id` = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "s", $requester);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      $name = $row["name"];
      echo '<div class="flex justify-between items-center p-2 border-2 bg-orange-100 border-orange-600 rounded-md shadow-md">
        <p class="font-semibold px-2 text-orange-700">' . $name . '</p>
        <div class="space-x-2 flex items-center">
          <button onclick="window.location.assign(`partials/_mark.php?id=' . $requester . '&mark=4`)"
            class="bg-green-100 border-green-600 border-2 rounded-md">
            <img src="images/tick.png" class="w-8 h-8 p-1" />
          </button>
          <button onclick="window.location.assign(`partials/_mark.php?id=' . $requester . '&mark=3`)"
            class="bg-red-100 border-red-700 border-2 rounded-md">
            <img src="images/close.png" class="w-8 h-8 p-1" />
          </button>
        </div>
      </div>';
    }
    //containter end
    echo '</div>
    </div>';

    //turning on req cont after redirection
    if (isset($_GET["req"]) && $_GET["req"] == "on") {
      echo '<script>
      document.getElementById("request-container").classList.toggle("hidden");
      </script>';
    }
  }
  ?>

  <!-- table container -->
  <div class="m-4 p-4 bg-white rounded-md shadow-md">
    <table class="w-full shadow-md">
      <thead>
        <tr class="border-b-gray-600 border-b bg-[#F3F2F7]">
          <th scope="col" class="p-4">Name</th>
          <th scope="col" class="p-4">Id</th>
          <th scope="col" class="p-4">Attendance</th>
          <th scope="col" class="p-4">Present</th>
          <th scope="col" class="p-4">Absent</th>
          <th scope="col" class="p-4">Leave</th>
          <th scope="col" class="p-4">Today's Status</th>
          <th scope="col" class="p-4">Grade</th>
          <th scope="col" class="p-4">Detail</th>
        </tr>
      </thead>
      <tbody>
        <?php
        //taking names of account
        $ids = $arr;
        foreach ($ids as $id) {
          $sql = "SELECT `$id` FROM `attendance` ORDER BY `date` ASC";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          //initializing var
          $attendance = 0;
          $present = 0;
          $leave = 0;
          $absent = 0;

          //increamenting data
          while ($row = mysqli_fetch_assoc($result)) {

            //arr for attendance
            $arrAttendance = [];
            array_push($arrAttendance, $row[$id]);

            if ($row[$id] != 0) {
              $attendance++;
            }
            if ($row[$id] == 1) {
              $present++;
            }
            if ($row[$id] == 3) {
              $absent++;
            }
            if ($row[$id] == 4) {
              $leave++;
            }
          }

          //implemeting current attendance
          //getting last row
          $end = end($arrAttendance);
          if ($end == 1) {
            $status = "Present";
          } else if ($end == 3) {
            $status = "Absent";
          } else if ($end == 4) {
            $status = "Leave";
          } else if ($end == 2) {
            $status = "Leave Request";
          } else {
            $status = "Unattended";
          }

          //implementing grading
          if ($present == ($attendance * 0)) {
            $grade = "E";
          } else if ($present <= $attendance * 30 / 100) {
            $grade = "D";
          } else if ($present <= $attendance * 50 / 100) {
            $grade = "C";
          } else if ($present <= $attendance * 70 / 100) {
            $grade = "B";
          } else if ($present <= $attendance * 90 / 100) {
            $grade = "A";
          } else {
            $grade = "A+";
          }

          //getting name
          $sql = "SELECT `name` FROM `users` WHERE `id` = ?";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_bind_param($stmt, "s", $id);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);

          //echoing data
          echo '<tr class="border-b-gray-500 border-b bg-[#F8F8F8] last:border-b-0">
          <td class="text-center py-3">' . $row["name"] . '</td>
          <td class="text-center py-3">' . $id . '</td>
          <td class="text-center py-3">' . $attendance . '</td>
          <td class="text-center py-3">' . $present . '</td>
          <td class="text-center py-3">' . $absent . '</td>
          <td class="text-center py-3">' . $leave . '</td>
          <td class="text-center py-3">' . $status . '</td>
          <td class="text-center py-3">' . $grade . '</td>
          <td class="flex items-center justify-center py-3">
            <button class="flex items-center p-2 text-white bg-cyan-500 shadow-md hover:bg-cyan-400 rounded-md"
              onclick="window.location.assign(`details?id=' . $id . '`)">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-eye">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </button>
          </td>
        </tr>';
        }
        ?>
      </tbody>
    </table>
  </div>


  <script src="side/script.js"></script>
</body>

</html>