<?php 
 include '../../server/config.php';
 $notification = "";

 if(isset($_POST["login-btn"])){
     $username = $_POST['username'];
     $password = $_POST['password'];

    //  cek apakah username ada di database
    $sql = "SELECT * FROM pengguna WHERE Username = '$username' ";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['Password'];

        // verifikasi password
        if(password_verify($password, $hashed_password)){
            // login berhasil
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION['namapengguna'] = $row["NamaPengguna"];
            $_SESSION["logged_in"] = true;
            header('location: ../../view/petugas/index.php');
        }else{
            // login gagal password salah
            $notification = '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            <strong>Error!</strong> Password salah!
                         </div>';
        }
    }  else{
        // login gagal username tidak ditemukan
        $notification = '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        <strong>Error!</strong> Petugas tidak ditemukan!
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
