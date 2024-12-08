<?php
session_start();
include '../connection.php';
include '../helperfunctions.php';
include '../password_compat.php';
include '../components/menu.php';
# Verify if login exists such that the session "cit-student-id" is found
if (isset($_SESSION['cit-student-id'])) {
    $sql = "SELECT `accounts`.`type`, `accounts`.`password`, `students`.*  FROM `accounts` JOIN `students` on `students`.`student_id` = `accounts`.`student_id` WHERE `accounts`.`student_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['cit-student-id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentpass = $row['password']; # Get account password
        $type = $row['type'];
        $lastname = $row['last_name'];
        $firstname = $row['first_name'];
        $mi = $row['middle_initial'];
        $fullname = $lastname.", ".$firstname." ".$mi.".";
        $yearsec = $row['year_and_section'];
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
            <div class="flex mt-2 ">
                <svg width="160px" height="160px" viewBox="0 0 24 24">
                    <path d="M22 12C22 6.49 17.51 2 12 2C6.49 2 2 6.49 2 12C2 14.9 3.25 17.51 5.23 19.34C5.23 19.35 5.23 19.35 5.22 19.36C5.32 19.46 5.44 19.54 5.54 19.63C5.6 19.68 5.65 19.73 5.71 19.77C5.89 19.92 6.09 20.06 6.28 20.2C6.35 20.25 6.41 20.29 6.48 20.34C6.67 20.47 6.87 20.59 7.08 20.7C7.15 20.74 7.23 20.79 7.3 20.83C7.5 20.94 7.71 21.04 7.93 21.13C8.01 21.17 8.09 21.21 8.17 21.24C8.39 21.33 8.61 21.41 8.83 21.48C8.91 21.51 8.99 21.54 9.07 21.56C9.31 21.63 9.55 21.69 9.79 21.75C9.86 21.77 9.93 21.79 10.01 21.8C10.29 21.86 10.57 21.9 10.86 21.93C10.9 21.93 10.94 21.94 10.98 21.95C11.32 21.98 11.66 22 12 22C12.34 22 12.68 21.98 13.01 21.95C13.05 21.95 13.09 21.94 13.13 21.93C13.42 21.9 13.7 21.86 13.98 21.8C14.05 21.79 14.12 21.76 14.2 21.75C14.44 21.69 14.69 21.64 14.92 21.56C15 21.53 15.08 21.5 15.16 21.48C15.38 21.4 15.61 21.33 15.82 21.24C15.9 21.21 15.98 21.17 16.06 21.13C16.27 21.04 16.48 20.94 16.69 20.83C16.77 20.79 16.84 20.74 16.91 20.7C17.11 20.58 17.31 20.47 17.51 20.34C17.58 20.3 17.64 20.25 17.71 20.2C17.91 20.06 18.1 19.92 18.28 19.77C18.34 19.72 18.39 19.67 18.45 19.63C18.56 19.54 18.67 19.45 18.77 19.36C18.77 19.35 18.77 19.35 18.76 19.34C20.75 17.51 22 14.9 22 12ZM16.94 16.97C14.23 15.15 9.79 15.15 7.06 16.97C6.62 17.26 6.26 17.6 5.96 17.97C4.44 16.43 3.5 14.32 3.5 12C3.5 7.31 7.31 3.5 12 3.5C16.69 3.5 20.5 7.31 20.5 12C20.5 14.32 19.56 16.43 18.04 17.97C17.75 17.6 17.38 17.26 16.94 16.97Z" fill="#621668"/>
                    <path d="M12 6.92969C9.93 6.92969 8.25 8.60969 8.25 10.6797C8.25 12.7097 9.84 14.3597 11.95 14.4197C11.98 14.4197 12.02 14.4197 12.04 14.4197C12.06 14.4197 12.09 14.4197 12.11 14.4197C12.12 14.4197 12.13 14.4197 12.13 14.4197C14.15 14.3497 15.74 12.7097 15.75 10.6797C15.75 8.60969 14.07 6.92969 12 6.92969Z" fill="#621668"/>
                </svg>
                <div class="ml-2 mt-3">
                    <h2 class="text-xl text-custom-purple font-bold mb-1"><?php echo $fullname; ?></h2>
                    <p class="mb-2 text-sm text-custom-purple font-bold"><?php echo ucfirst($type); ?></p>
                    <p><button class="text-sm text-custom-purple font-semibold hover:underline hover:text-custom-purplo" id="change-information-btn">Change information</button></p>
                    <p><button class="text-sm text-custom-purple font-semibold hover:underline hover:text-custom-purplo" id="change-password-btn">Change password</button></p>
                    <p><button class="text-sm text-red-800 font-semibold hover:underline" id="delete-account-btn">Delete account</button></p>
                </div>
            </div>
        </div>
    </div>
    <div id="popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <div id="popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Change Information</h3>
                <form id="password-form" method="POST">
                    <label class="ml-1 text-sm">Last Name:</label>
                    <input type="text" id="edit-last-name" name="edit-last-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" pattern="[a-zA-Z\s']+" value="<?php echo $lastname; ?>" required>
                    <label class="ml-1 text-sm">First Name:</label>
                    <input type="text" id="edit-first-name" name="edit-first-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" pattern="[a-zA-Z\s']+" value="<?php echo $firstname; ?>" required>
                    <label class="ml-1 text-sm">Middle Initial:</label>
                    <input type="text" id="edit-middle-initial" name="edit-middle-initial" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="3" pattern="[a-zA-Z\s']+" value="<?php echo $mi; ?>">
                    <label class="ml-1 text-sm">Year & Section:</label>
                    <input type="text" id="edit-yearsec" name="edit-yearsec" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="2" pattern="[A-Za-z0-9]+" value="<?php echo $yearsec; ?>" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold disabled:bg-gray-400 hover:bg-custom-purplo" name="update-information">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="chp-popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="chp-close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Change Password:</h3>
                <form id="password-form" method="POST">
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
    <script type="text/javascript">
        $("#popup-bg, #popup-item, #chp-popup-item").removeClass("hidden");
        $("#popup-bg, #popup-item, #chp-popup-item").hide();

        const closePopup = (button_id, bg, popup_item, close_popup) => {
            $(button_id).click((event) => {
                $(bg).fadeIn(150);
                $(popup_item).delay(150).fadeIn(150);
                $(close_popup).click((event) => {
                    $(bg + ", " + popup_item).fadeOut(150);
                });
            });
        };

        closePopup("#change-information-btn", "#popup-bg", "#popup-item", "#close-popup");
        closePopup("#change-password-btn", "#popup-bg", "#chp-popup-item", "#chp-close-popup");

        $("#delete-account-btn").click(() => {
            swal('', 'Only admins are allowed to delete your account!', 'error');
        });

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
    if (isset($_POST['update-information'])) {
        $sid = $_SESSION['cit-student-id'];
        $lastname = ucwords($_POST['edit-last-name']);
        $firstname = ucwords($_POST['edit-first-name']);
        $mi = ucwords($_POST['edit-middle-initial']);
        $email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";
        $yearsec = strtoupper($_POST['edit-yearsec']);
        $sqlupdate_student = "UPDATE `students` SET `last_name`= ?, `first_name`= ?, `middle_initial`= ?, `year_and_section`= ? WHERE `student_id` = ?";
        $stmt_update_student = $conn->prepare($sqlupdate_student);
        $stmt_update_student->bind_param("sssss", $lastname, $firstname, $mi, $yearsec, $sid);
        if ($stmt_update_student->execute()) {
            $sqlupdate_account = "UPDATE `accounts` SET `email`=? WHERE `student_id` = ?";
            $stmt_update_account = $conn->prepare($sqlupdate_account);
            $stmt_update_account->bind_param("ss", $email, $sid);
            $_SESSION['cit-student-id'] = $sid;
            ?>
            <script>
                swal('Changes saved!', '<?php echo $stmt_update_account->execute() ? "Modifying the name can also modify the email." : "But student email failed to update." ?>', 'success').then(() => {
                    window.location.href = 'accountsettings.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to update information!', '', 'error');</script>"<?php
        }
    }
    if (isset($_POST['update-password'])) {
        if (!password_verify($_POST['old-password'], $currentpass)) { # If old password is not the same as current password
            ?>
            <script>
                swal("Wrong old password!" ,'', 'error');
            </script>
            <?php
        } elseif ($_POST['new-password'] != $_POST['confirm-password']) {
            # If new password is not equal to confirm password
            ?>
            <script>
                swal("Password do not match" ,'', 'error');
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
                    swal("Password changed successfully!" ,'', 'success');
                </script>
                <?php
            }
        }
    }
    ?>
<?php
$html->endBody();
?>