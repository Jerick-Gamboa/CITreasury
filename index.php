<?php
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/tailwind3.4.1.js"></script>
    <script src="js/tailwind.config.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/predefined-script.js"></script>
    <link rel="icon" href="img/nobgcitsclogo.png">
</head>
<body class="bg-gradient-to-t from-custom-purple to-purple-200 h-screen flex items-center justify-center">
    <div class="w-112 bg-white shadow rounded-lg">
        <img src="img/headerlogo.png" class="w-full rounded-t-lg"><br>
        <img src="img/nobgcitsclogo.png" class="w-full p-5 opacity-25">
    </div>
    <div class="absolute">
        <h1 class="text-custom-purple text-4xl text-center font-bold mb-10 mt-20 opacity-90">SIGN IN</h1>
        <form method="POST">
            <label for="email" class="text-custom-purple text-md font-bold">Email:</label><br>
            <input type="email" name="email" id="email" class="w-72 px-2 py-1 border-2 border-custom-purple rounded-lg mb-4" required><br>
            <label for="password" class="text-custom-purple text-md font-bold">Password:</label><br>
            <input type="password" name="password" class="w-72 px-2 py-1 border-2 border-custom-purple rounded-lg" required><br>
            <div class="flex items-center justify-center mt-2">
                <button type="submit" class="mt-5 bg-custom-purple px-10 py-2 rounded-lg text-white font-bold transition-all duration-300-ease-in-out hover:bg-custom-purplo" name="login">Sign In</button>
            </div>
        </form>
    </div>
    <?php
    if (isset($_COOKIE['cit-student-id'])) {
        $sql_admin = "SELECT `student_id` FROM `accounts` WHERE `type` = 'admin' AND `student_id` = ?";
        $stmt_admin = $conn->prepare($sql_admin);
        $stmt_admin->bind_param("s", $_COOKIE['cit-student-id']);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        $sql_user = "SELECT `student_id` FROM `accounts` WHERE `type` = 'user' AND `student_id` = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $_COOKIE['cit-student-id']);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_admin->num_rows > 0) {
            header("location: admin/");
        } else if ($result_user->num_rows > 0) {
            header("location: user/");
        }
    }
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        ?>
        <script>
            $("#email").val("<?php echo $email ?>");
        </script>
        <?php
        $password = $_POST['password'];
        $sql = "SELECT * FROM `accounts` WHERE `email` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                if ($row['password'] === $password) {
                    $_SESSION['cit-student-id'] = $row['student_id'];
                    setcookie('cit-student-id', $_SESSION['cit-student-id'], time() + (86400 * 30), '/');
                    if ($row['type'] === 'admin') {
                        ?>
                        <script>
                            swal('Login Successful!', 'Welcome admin!', 'success')
                            .then(() => {
                                window.location.href = 'admin/index.php';
                            });
                        </script>
                        <?php
                    } elseif ($row['type'] === 'user') {
                        ?>
                        <script>
                            swal('Login Successful!', 'Welcome user!', 'success')
                            .then(() => {
                                window.location.href = 'user/index.php';
                            });
                        </script>
                        <?php
                    } else {
                        ?>
                        <script> swal('Invalid account', '', 'error');</script>
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
    <!-- script type="text/javascript">
        sendNotif("Welcome to CITreasury!", "What's up?", "nobgcitsclogo.png", null);
    </script -->
</body>
</html>
