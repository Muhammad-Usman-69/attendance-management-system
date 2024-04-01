<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="author" content="Muhammad Usman" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log in - Attendance Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="side/style.css" />
</head>

<body class="bg-[#F8F8F8]">
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
  <div class="min-h-screen flex items-center justify-center flex-col space-y-8">
    <h1 class="text-[28px] font-bold mx-4 text-center">
      Attendance Management System
    </h1>
    <form action="partials/_log-handler.php" method="post"
      class="flex flex-col p-6 border rounded-md space-y-3 bg-white text-[#3e363f]">
      <h1 class="font-bold text-xl text-center">Log In</h1>
      <hr />
      <div class="flex flex-col space-y-1">
        <label for="email" class="text-sm">Email:</label>
        <input class="text-sm p-1.5 px-3 outline-none border" type="text" name="email" id="email"
          placeholder="example@example.com" />
      </div>
      <div class="flex flex-col space-y-1">
        <label for="pass" class="text-sm">Password:</label>
        <input class="text-sm p-1.5 px-3 outline-none border" type="password" name="pass" id="pass"
          placeholder="••••••••" />
      </div>
      <input class="cursor-pointer bg-green-500 text-white py-2" type="submit" value="Submit" />
      <div>
        <p>
          Not a user? Please
          <a href="sign" class="underline hover:no-underline">sign up</a>
        </p>
      </div>
    </form>
  </div>
  <script src="side/script.js"></script>
</body>

</html>