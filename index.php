<?php
session_start();
include 'connection.php';
include 'password_compat.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Import JavaScript files -->
    <script src="js/tailwind3.4.1.js"></script>
    <script src="js/tailwind.config.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/predefined-script.js"></script>
    <link rel="icon" href="img/nobgcitsclogo.png">
    <link rel="stylesheet" href="inter-variable.css">
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
    # If session "cit-student-id" is found
    if (isset($_SESSION['cit-student-id'])) {
        $sql = "SELECT `type` FROM `accounts` WHERE `student_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['cit-student-id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $type = $row['type'];
            if ($type === 'admin') { # if account type is admin, redirect to admin
                header("location: admin/");
            } elseif ($type === 'user') { # if account type is user, redirect to user
                header("location: user/");
            }
            # Else, do nothing
        }
    }
    # If "login" is submitted
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
                if (password_verify($password, $row['password'])) { # If hashed password matches
                    $_SESSION['cit-student-id'] = $row['student_id'];
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
        } else { # If query did not return a result
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
