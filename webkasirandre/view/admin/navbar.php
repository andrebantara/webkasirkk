<nav class="bg-gray-600 shadow-md h-screen fixed top-0 left-0 w-64">
            <div class="py-6 px-4">
                <a href="#" class="text-2xl font-semibold text-gray-50">Cashier</a>
            </div>
            <div class="px-4 space-y-4">
                <a href="index.php" class="block text-gray-50 hover:text-blue-500 transition duration-200">Home</a>
                <a href="dataproduk.php" class="block text-gray-50 hover:text-blue-500 transition duration-200">Data Produk</a>
                <a href="datapetugas.php" class="block text-gray-50 hover:text-blue-500 transition duration-200">Data Petugas</a>
                <a href="#" class="block text-gray-50 hover:text-blue-500 transition duration-200">Tentang Kami</a>
            </div>
            <div class="absolute flex flex-col bottom-0 px-4 mb-4 space-y-2">
                <a href="" id="log-out" class="text-gray-50 hover:text-blue-500 transition duration-200">
                    <i class="fa-solid fa-user"></i> <?= $_SESSION['username'] ?>
                </a>
                <a href="../../auth/logout.php" id="log-out" class="text-gray-50 hover:text-blue-500 transition duration-200">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </nav>