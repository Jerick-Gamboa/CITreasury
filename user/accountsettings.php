<?php
session_start();
include '../connection.php';
include '../helperfunctions.php';
include '../password_compat.php';
include '../components/menu.php';
# Verify if login exists such that the session "cit-student-id" is found
if (isset($_SESSION['cit-student-id'])) {
    $sql = "SELECT `type`, `password` FROM `accounts` WHERE `student_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['cit-student-id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentpass = $row['password']; # Get account password
        $type = $row['type'];
        if ($type === 'admin') { # If account type is admin, redirect to admin page
            header("location: ../admin/");
        }
    } else { # If account is not found, return to login page
        header("location: ../");
    }
} else { # If session is not found, return to login page
    header("location: ../");
}

$html = new HTML("CITreasury - Account Settings");
$html->addLink('stylesheet', '../inter-variable.css');
$html->addLink('icon', '../img/nobgcitsclogo.png');
$html->addScript("../js/tailwind3.4.1.js");
$html->addScript("../js/tailwind.config.js");
$html->addScript("../js/sweetalert.min.js");
$html->addScript("../js/jquery-3.7.1.min.js");
$html->addScript("../js/predefined-script.js");
$html->addScript("../js/defer-script.js", true);
$html->startBody();
?>
    <nav class="fixed w-full bg-custom-purple flex flex-row shadow shadow-gray-800">
        <img src="../img/nobgcitsclogo.png" class="w-12 h-12 my-2 ml-6">
        <h1 class="text-3xl p-3 font-bold text-white">CITreasury</h1>
        <div class="w-full text-white">
            <svg id="mdi-menu" class="w-8 h-8 mr-3 my-4 p-1 float-right fill-current rounded transition-all duration-300-ease-in-out md:hidden hover:bg-white hover:text-custom-purple hover:cursor-pointer" viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>
        </div>
    </nav>
    <div class="flex flex-col md:flex-row bg-custom-purplo min-h-screen">
        <div class="mt-18 md:mt-20 mx-2">
            <div id="menu-user-items" class="hidden md:inline-block w-60 h-full">
                <?php menuUserContent(); ?>
            </div>
        </div>
        <div id="menu-user-items-mobile" class="fixed block md:hidden h-fit top-16 w-full p-4 bg-custom-purplo opacity-95">
            <?php menuUserContent(); ?>
        </div>
        <div class="w-full bg-red-50 px-6 min-h-screen">
            <div class="mt-24">
                <h1 class="text-3xl text-custom-purplo font-bold mb-5">Manage Your Account</h1>
            </div>
            <div class="mt-12 w-full flex justify-center">
                <div class="w-112 bg-white rounded-lg shadow">
                    <h3 class="text-2xl font-semibold px-6 py-4 text-custom-purple">Change Password:</h3>
                    <hr class="border border-lg border-custom-purple">
                    <form class="p-6" id="password-form" method="POST">
                        <label class="ml-1 text-sm">Old Password:</label>
                        <input type="password" id="old-password" name="old-password" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                        <label class="ml-1 text-sm">New Password:</label>
                        <input type="password" id="new-password" name="new-password" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                        <label class="ml-1 text-sm">Confirm New Password:</label>
                        <input type="password" id="confirm-password" name="confirm-password" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                        <div class="flex items-center justify-center m-4">
                            <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold disabled:bg-gray-400 hover:bg-custom-purplo" id="update-password" name="update-password" disabled>Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // While editing in new and confirm password
        $("#new-password, #confirm-password").on('input', () => {
            // If new password is not the same as confirm password, disable save changes button, otherwise enable it
            if ($("#new-password").val() !== $("#confirm-password").val()) {
                $("#update-password").prop('disabled', true);
            } else {
                $("#update-password").prop('disabled', false);
            }
        });
    </script>
    <?php
        if (isset($_POST['update-password'])) {
            if (!password_verify($_POST['old-password'], $currentpass)) { # If old password is not the same as current password
                ?>
                <script>
                    swal("Wrong old password!" ,'', 'error');
                </script>
                <?php
            } else { # Else update student password
                $sqlupdate_account = "UPDATE `accounts` SET `password`=? WHERE `student_id` = ?";
                $stmt_update_account = $conn->prepare($sqlupdate_account);
                $hash_password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
                $stmt_update_account->bind_param("ss", $hash_password, $_SESSION['cit-student-id']);
                if ($stmt_update_account->execute()) {
                    ?>
                    <script>
                        swal("Password changed successfully!" ,'', 'success').then(() => {
                            window.location.href = "index.php";
                        });
                    </script>
                    <?php
                }
            }
        }
    ?>
<?php
$html->endBody();
?>