<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include 'connection.php';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/tailwind3.4.1.js"></script>
    <script src="js/tailwind.config.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/predefined-script.js"></script>
    <link rel="icon" href="img/nobgcitsclogo.png">
</head>
<body class="bg-gradient-to-t from-purple-600 to-purple-200 h-screen flex items-center justify-center">
    <div class="w-112 bg-white shadow rounded-lg">
        <img src="img/headerlogo.png" class="w-full rounded-t-lg"><br>
        <img src="img/nobgcitsclogo.png" class="w-full p-5 opacity-25">
    </div>
    <div class="absolute">
        <h1 class="text-purple-900 text-4xl text-center font-bold mb-10 mt-20 opacity-90">SIGN IN</h1>
        <form method="POST">
            <label for="email" class="text-purple-900 text-md font-bold">Email:</label><br>
            <input type="email" name="email" class="w-72 px-2 py-1 border-2 border-purple-700 rounded-lg mb-4"><br>
            <label for="password" class="text-purple-900 text-md font-bold">Password:</label><br>
            <input type="password" name="password" class="w-72 px-2 py-1 border-2 border-purple-700 rounded-lg"><br>
            <div class="flex items-center justify-center mt-1">
                <button type="submit" class="mt-5 bg-purple-800 px-10 py-1 rounded text-white font-bold transition-all duration-300-ease-in-out hover:bg-purple-500" name="login">Sign In</button>
            </div>
        </form>
    </div>
    <?php
    if (isset($_COOKIE['cit-email']) && isset($_COOKIE['cit-password'])) {
        if ($_COOKIE['cit-type'] === 'admin') {
            header("location: admin/index.php");
        } else if ($_COOKIE['cit-type'] === 'user') {
            header("location: user/index.php");
        } else {
            header("location: index.php");
        }
    }
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM `accounts` WHERE `email` = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                if ($row['password'] === $password) {
                    $_SESSION['cit-email'] = $row['email'];
                    $_SESSION['cit-password'] = $row['password'];
                    $_SESSION['cit-type'] = $row['type'];

                    setcookie('cit-email', $_SESSION['cit-email'], time() + (86400 * 30), '/');
                    setcookie('cit-password', password_hash($_SESSION['cit-password'], PASSWORD_DEFAULT), time() + (86400 * 30), '/');
                    setcookie('cit-type', $_SESSION['cit-type'], time() + (86400 * 30), '/');
                    if ($row['type'] === 'admin') {
                        ?>
                        <script>
                            swal('Login Successful!', 'Welcome admin!', 'success')
                            .then((okay) => {
                                window.location.href = 'admin/index.php';
                            });
                        </script>
                        <?php
                    } else {
                        ?>
                        <script>
                            swal('Login Successful!', 'Welcome user!', 'success')
                            .then((okay) => {
                                window.location.href = 'user/index.php';
                            });
                        </script>
                        <?php
                    }
                    
                } else {
                    ?>
                    <script> swal('Incorrect password', '', 'error');</script>
                    <?php
                }
            }
        } else {
            ?>
            <script>swal('User not found', '', 'error');</script>
            <?php
        }
    }
    ?>
    <script type="text/javascript">
        sendNotif("Welcome to CITreasury!", "What's up?", "nobgcitsclogo.png", null);
    </script>
</body>
</html>
