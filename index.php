<?php
session_start();

//checking if user is logged
if (!isset($_SESSION["log"])) {
  header("location:log");
  exit();
}

//check if admin
if ($_SESSION["status"] == "admin") {
  header("location:admin");
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
  <title>Attendance Management System</title>
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
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
        <button type="button" class="hover:bg-[#f5f5f5] px-3 py-2 cursor-pointer hidden" id="leave-button"
          onclick="window.location.assign('partials/_mark?mark=2')">
          Leave Request
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
      <div class="bg-white rounded-md shadow-md py-2 absolute z-10 right-0 top-[120%] border opacity-0"
        id="profile-setting">
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
  //taking data of attendance
  $sql = "SELECT $id FROM `attendance`";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  //initializing var
  $attendance = 0;
  $present = 0;
  $leaves = 0;

  //increamenting data
  while ($row = mysqli_fetch_assoc($result)) {
    if ($row[$id] != 0) {
      $attendance++;
    }
    if ($row[$id] == 1) {
      $present++;
    }
    if ($row[$id] == 4) {
      $leaves++;
    }
  }
  ?>
  <!-- statistics container -->
  <div class="grid grid-cols-1 md:grid-cols-3">
    <!-- current attendance container -->
    <div class="m-4 p-4 flex justify-between bg-white rounded-md shadow-md">
      <p class="">Total Attendance:</p>
      <span>
        <?php echo $attendance; ?>
      </span>
    </div>
    <!-- current absent container -->
    <div class="m-4 p-4 flex justify-between bg-white rounded-md shadow-md">
      <p class="">Total Present:</p>
      <span>
        <?php echo $present; ?>
      </span>
    </div>
    <!-- current leave container -->
    <div class="m-4 p-4 flex justify-between bg-white rounded-md shadow-md">
      <p class="">Total Leaves:</p>
      <span>
        <?php echo $leaves; ?>
      </span>
    </div>
  </div>
  <!-- button container -->
  <div class="m-4 p-4 grid grid-cols-1 md:grid-cols-3 justify-between bg-white rounded-md shadow-md gap-4 md:gap-8">
    <button class="py-2 rounded-md bg-green-600 active:bg-green-800 text-white shadow-md"
      onclick="window.location.assign('partials/_mark?mark=1')">
      Mark Attendance
    </button>
    <button class="py-2 rounded-md bg-orange-600 active:bg-orange-700 text-white shadow-md"
      onclick="window.location.assign('partials/_mark?mark=2')">
      Mark Leave
    </button>
    <button class="py-2 rounded-md bg-blue-600 active:bg-blue-800 text-white shadow-md"
      onclick="document.getElementById('attendance-container').classList.toggle('hidden')">
      View Attendance
    </button>
  </div>
  <!-- attendance container -->
  <div class="mx-4 my-8 p-4 space-y-4 bg-white rounded-md shadow-md hidden" id="attendance-container">
    <?php
    //showing attendance
    //taking data of attendance
    $sql = "SELECT `date`, $id FROM `attendance`";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    //increamenting data
    while ($row = mysqli_fetch_assoc($result)) {

      //displaying present
      if ($row[$id] == 1) {
        echo '<div class="p-2 bg-green-100 border-green-600 border-2 flex items-center justify-between rounded-md shadow-md">
          <p class="text-lg font-semibold px-2 text-green-700">' . $row['date'] . '</p>
          <img src="images/tick.png" class="w-10 h-10 p-1 border-2 border-green-700 rounded-full">
        </div>';
      }

      //displaying leave req
      if ($row[$id] == 2) {
        echo '<div class="p-2 bg-orange-100 border-orange-600 border-2 flex items-center justify-between rounded-md shadow-md">
          <p class="text-lg font-semibold px-2 text-orange-700">' . $row['date'] . '</p>
          <img src="images/hourglass.png" class="w-10 h-10 p-1 border-2 border-orange-700 rounded-full">
        </div>';
      }

      //displaying absent
      if ($row[$id] == 3) {
        echo '<div class="p-2 bg-red-100 border-red-700 border-2 flex items-center justify-between rounded-md shadow-md">
          <p class="text-lg font-semibold px-2 text-red-700">' . $row['date'] . '</p>
          <img src="images/close.png" class="w-10 h-10 p-1 border-2 border-red-700 rounded-full">
        </div>';
      }

      //displaying leave
      if ($row[$id] == 4) {
        echo '<div class="p-2 bg-orange-100 border-orange-600 border-2 flex items-center justify-between rounded-md shadow-md">
          <p class="text-lg font-semibold px-2 text-orange-700">' . $row['date'] . '</p>
          <img src="images/leave.png" class="w-10 h-10 p-1 border-2 border-orange-700 rounded-full">
        </div>';
      }
    }
    ?>
  </div>
  
  <script src="side/script.js"></script>
</body>

</html>