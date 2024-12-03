<?php
session_start();
include '../connection.php';
include '../helperfunctions.php';
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
        $type = $row['type'];
        $lastname = $row['last_name'];
        $firstname = $row['first_name'];
        $mi = $row['middle_initial'];
        $yearsec = $row['year_and_section'];
        if ($type === 'user') { # If account type is user, redirect to user page
            header("location: ../user/");
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
            <div id="menu-items" class="hidden md:inline-block w-60 h-full">
                <?php menuContent(); ?>
            </div>
        </div>
        <div id="menu-items-mobile" class="fixed block md:hidden h-fit top-16 w-full p-4 bg-custom-purplo opacity-95">
            <?php menuContent(); ?>
        </div>
        <div class="w-full bg-red-50 px-6 min-h-screen">
            <div class="mt-24">
                <h1 class="text-3xl text-custom-purplo font-bold mb-5">Manage Your Account</h1>
            </div>
            <div class="mt-6 w-full flex justify-center">
                <div class="w-112 bg-white rounded-lg shadow">
                    <h3 class="text-2xl font-semibold px-6 py-4 text-custom-purple">Change Information:</h3>
                    <hr class="border border-lg border-custom-purple">
                    <form class="p-6" id="password-form" method="POST">
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
    </div>
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
    ?>
<?php
$html->endBody();
?>