<?php
session_start();
require_once '../server/config.php'; // Sesuaikan path config.php dengan benar

$notification = ''; // Variabel untuk menampung pesan notifikasi

// Cek apakah form login disubmit
if (isset($_POST['login-btn'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Cek apakah username ada di database
    $sql = "SELECT * FROM pengguna WHERE Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['Password'])) {
            // Set session dan redirect berdasarkan role
            $_SESSION["logged_in"] = true;
            $_SESSION['user_id'] = $user['PenggunaID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['name'] = $user['NamaPengguna'];
            // $_SESSION['role'] = $user['Role'];

            header("Location: ../view/admin/index.php");

           
        } else {
            // Jika password salah
            $notification = '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                <strong>Error!</strong> Password salah!
                             </div>';
        }
    } else {
        // Jika username tidak ditemukan
        $notification = '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            <strong>Error!</strong> Username tidak ditemukan!
                         </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login || Web Kasir</title>
</head>
<body>
<section class="bg-gray-50 ">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 ">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-2xl font-bold leading-tight tracking-tight text-gray-900 ">
                  Sign in to your account
              </h1>

              <!-- Tempat untuk menampilkan notifikasi -->
              <?php echo $notification; ?>

              <form class="space-y-4 md:space-y-6" action="" method="post">
                  <div>
                      <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">Username </label>
                      <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="yanto123" required="">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                      <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                  </div>
                  <div class="flex items-center justify-between">
                      <div class="flex items-start">
                          <div class="flex items-center h-5">
                            <input id="showPassword" aria-describedby="remember" onclick="togglePassword()" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300">
                          </div>
                          <div class="ml-3 text-sm w-full">
                            <label for="showPassword" class="text-gray-500 hover:text-gray-900 cursor-pointer">Show Password</label>
                          </div>
                      </div>
                  </div>
                  <button type="submit" class="w-full text-white bg-sky-400 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" name="login-btn">Sign in</button>
                  <p class="text-sm font-light text-gray-500">
                      Don’t have an account yet? <a href="register.php" class="font-medium text-primary-600 hover:underline">Sign up</a>. <a href="loginpetugas/login2.php" class="hover:underline">Masuk Sebagai Petugas</a> 
                  </p>

              </form>
          </div>
      </div>
  </div>
</section>

<script>
    function togglePassword(){
        var showPassword = document.getElementById('showPassword');
        var passwordField = document.getElementById('password');

        if(showPassword.checked){
            passwordField.type = "text";
        }else{
            passwordField.type = "password";
        }
    }
</script>
</body>
</html>
