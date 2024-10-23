<?php

require_once '../server/config.php';
$notification = ''; // Variable untuk menyimpan notifikasi

// Proses registrasi
if (isset($_POST['regis-btn'])) {
    // Ambil data dari form
    $NamaPengguna = $conn->real_escape_string($_POST['NamaPengguna']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    // $role = $conn->real_escape_string($_POST['role']); // Ambil role dari input selection
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Cek apakah username sudah ada
    $cek_user = "SELECT * FROM pengguna WHERE Username='$username'";
    $result = $conn->query($cek_user);

    if ($result->num_rows > 0) {
        $notification = '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            <strong>Error!</strong> Username sudah terdaftar!
                         </div>';
    } else {
        // Insert ke database
        $sql = "INSERT INTO pengguna (NamaPengguna, Username, Password, Role) 
                VALUES ('$NamaPengguna', '$username', '$hashed_password', 'administrator')";

        if ($conn->query($sql) === TRUE) {
            $notification = '<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                                <strong>Success!</strong> Registrasi berhasil!
                             </div>';
            header("Location: login.php");
            exit();
        } else {
            $notification = '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                <strong>Error!</strong> Error: ' . $conn->error . '
                             </div>';
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register || Web Kasir</title>
</head>
<body class="bg-gray-50">
<section class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
    <div class="w-full max-w-md bg-white rounded-lg shadow dark:border sm:max-w-md">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-2xl font-bold leading-tight tracking-tight text-gray-900 ">
                Sign Up Here
            </h1>

            <!-- Tempat untuk Notifikasi -->
            <?php echo $notification; ?>

            <form class="space-y-4 md:space-y-6" action="" method="post">
                <div>
                    <label for="NamaPengguna" class="block mb-2 text-sm font-medium text-gray-900 ">Your Name </label>
                    <input type="text" name="NamaPengguna" id="NamaPengguna" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5" placeholder="John Doe" required>
                </div>
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">Username </label>
                    <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5" placeholder="johndoe" required>
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-sky-400 focus:border-sky-400 block w-full p-2.5" required>
                </div>

              

                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="showPassword" aria-describedby="remember" onclick="togglePassword()" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-sky-300">
                        </div>
                        <div class="ml-3 text-sm w-full">
                            <label for="showPassword" class="text-gray-500 hover:text-gray-900 cursor-pointer">Show Password</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full text-white bg-sky-400 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" name="regis-btn">Sign up</button>
                <p class="text-sm font-light text-gray-500">
                    Already have an account? <a href="login.php" class="font-medium text-sky-600 hover:underline">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</section>

<script>
    function togglePassword() {
        var showPassword = document.getElementById('showPassword');
        var passwordField = document.getElementById('password');

        if (showPassword.checked) {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

</body>
</html>
