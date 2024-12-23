<?php
session_start();
include '../connection.php';
include '../helperfunctions.php';
include '../components/menu.php';
verifyAdminLoggedIn($conn);

$html = new HTML("CITreasury - Account Privileges");
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
    <!-- Top Navigation Bar -->
    <nav class="fixed w-full bg-custom-purple flex flex-row shadow shadow-gray-800">
        <img src="../img/nobgcitsclogo.png" class="w-12 h-12 my-2 ml-6">
        <h1 class="text-3xl p-3 font-bold text-white">CITreasury</h1>
        <div class="w-full text-white">
            <svg id="mdi-menu" class="w-8 h-8 mr-3 my-4 p-1 float-right fill-current rounded transition-all duration-300-ease-in-out md:hidden hover:bg-white hover:text-custom-purple hover:cursor-pointer" viewBox="0 0 24 24">
              <path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" />
            </svg>
        </div>
    </nav>
    <!-- Body -->
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
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 class="text-3xl text-custom-purplo font-bold mb-3">Manage Account Privileges</h1>
                <!-- Search Bar -->
                <div class="flex flex-row w-56 p-1 mb-3 border-2 border-custom-purple focus:border-custom-purplo rounded-lg bg-white">
                    <svg id="mdi-account-search" class="h-6 w-6 mr-1 fill-custom-purple" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M10,4A4,4 0 0,1 14,8C14,8.91 13.69,9.75 13.18,10.43C12.32,10.75 11.55,11.26 10.91,11.9L10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M2,20V18C2,15.88 5.31,14.14 9.5,14C9.18,14.78 9,15.62 9,16.5C9,17.79 9.38,19 10,20H2Z" /></svg>
                    <form method="GET">
                        <input type="text" id="account-search" name="search" placeholder="Search account..." class="w-full focus:outline-none">
                  </form>
                </div>
            </div>
            <div class="mt-1 mb-5 overflow-x-auto rounded-lg shadow-lg">
                <div class="overflow-x-auto rounded-lg border border-black">
                    <!-- Table of Accounts -->
                    <table class="w-full px-1 text-center">
                        <?php
                        // Pagination variables
                        $results_per_page = 10;
                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                        $offset = ($page - 1) * $results_per_page;
                        // SQL query for displaying all accounts
                        $sql = "SELECT * FROM `accounts`";
                        // Check if search is set
                        if (isset($_GET['search'])) {
                            $search = '%' . $_GET['search'] . '%';
                            $sql .= " WHERE (`student_id` LIKE ? OR `email` LIKE ? OR `type` LIKE ?)";
                            ?>
                            <script>
                                $("#account-search").val("<?php echo htmlspecialchars($_GET['search']); ?>");
                            </script>
                            <?php
                        }
                        // Add limit and offset for pagination
                        $sql .= " LIMIT ? OFFSET ?";
                        // Prepare the statement
                        $stmt = $conn->prepare($sql);
                        if (isset($search)) {
                            $stmt->bind_param("sssii", $search, $search, $search, $results_per_page, $offset);
                        } else {
                            $stmt->bind_param("ii", $results_per_page, $offset);
                        }
                        // Execute the statement
                        $stmt->execute();
                        $result = $stmt->get_result();
                        // Check if there are results
                        if ($result->num_rows > 0) {
                            ?>
                            <thead class="text-white uppercase bg-custom-purplo ">
                                <tr>
                                    <th scope="col" class="p-2 border-r border-black">Student ID</th>
                                    <th scope="col" class="p-2 border-r border-black">Email</th>
                                    <th scope="col" class="p-2 border-r border-black">Role</th>
                                    <th scope="col" class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <?php
                            // Loop through the results
                            while($row = $result->fetch_assoc()) {
                                $sid = $row['student_id'];
                                $email = $row['email'];
                                $type = $row['type'];
                                ?>
                                <tr class="border-t border-black">
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $sid; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $email; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $type; ?></td>
                                    <td class="px-1 bg-purple-100">
                                        <button class="px-3 py-2 my-1 mx-1 bg-orange-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-orange-400" onclick="editRow(this)">
                                            <svg id="mdi-pencil" class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?><h3 class="p-4">No accounts found.</h3><?php
                        }
                        ?>
                    </table>
                </div>

            </div>
            <!-- Pagination controls -->
            <div class="pagination my-2">
                <?php
                # Get the total number of rows for pagination
                $sql_total = "SELECT COUNT(*) FROM `accounts`";
                if (isset($search)) {
                    $sql_total .= " WHERE (`student_id` LIKE ? OR `email` LIKE ? OR `type` LIKE ?)";
                    $stmt_total = $conn->prepare($sql_total);
                    $stmt_total->bind_param("sss", $search, $search, $search);
                } else {
                    $stmt_total = $conn->prepare($sql_total);
                }
                $stmt_total->execute();
                $total_records = $stmt_total->get_result()->fetch_assoc()['COUNT(*)'];
                $stmt_total->close();
                if ($result->num_rows > 0) {
                    ?>
                     <div id="has-result" class="w-full mb-2">
                        <p>Showing <?php echo $results_per_page; ?> entries per page</p>
                        <p>Results: <?php echo $total_records; ?> row(s)</p>
                    </div>
                    <?php
                    # Calculate total pages
                    $total_pages = ceil($total_records / $results_per_page);
                    # Display pagination buttons
                    for ($i = 1; $i <= $total_pages; $i++) {
                        ?><a href='accountprivileges.php?page=<?php echo $i; ?>'><button class="px-3 py-2 my-1 mr-1 <?php echo $page == $i ? 'bg-purple-600' : 'bg-custom-purplo'; ?> text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-custom-purple"><?php echo $i; ?></button></a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div id="edit-popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <div id="edit-popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="edit-close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Edit Account Privilege</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Student ID:</label>
                    <input type="text" id="edit-student-id" name="edit-student-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="7" readonly>
                    <label class="ml-1 text-sm">Email:</label>
                    <input type="email" id="edit-email" name="edit-email" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" readonly>
                    <label class="ml-1 text-sm">Role:</label>
                    <select id="edit-account-type" name="edit-account-type" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 disabled:bg-gray-200 bg-purple-100" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold hover:bg-red-600" name="update-this-account">Update Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#edit-popup-bg, #edit-popup-item").removeClass("hidden");
        $("#edit-popup-bg, #edit-popup-item").hide();

        const editRow = (link) => {
            let row = link.parentNode.parentNode
            $("#edit-popup-bg").fadeIn(150);
            $("#edit-popup-item").delay(150).fadeIn(150);
            $("#edit-close-popup").click(function() {
                $("#edit-popup-bg, #edit-popup-item").fadeOut(150);
            });
            $("#edit-student-id").val(row.cells[0].innerHTML);
            $("#edit-email").val(row.cells[1].innerHTML);
            $("#edit-account-type").val(row.cells[2].innerHTML);
            if ($("#edit-student-id").val() === "<?php echo $_SESSION['cit-student-id']; ?>") {
                $("#edit-account-type").prop('disabled', true);
            } else {
                $("#edit-account-type").prop('disabled', false);
            }
        }
    </script>
    <?php
    if (isset($_POST['update-this-account'])) {
        $acctype = $_POST['edit-account-type'];
        $sid = $_POST['edit-student-id'];
        $sqlupdate_account = "UPDATE `accounts` SET `type`=? WHERE `student_id` = ?";
        $stmt_update_account = $conn->prepare($sqlupdate_account);
        $stmt_update_account->bind_param("ss", $acctype, $sid);
        if ($stmt_update_account->execute()) {
            ?>
            <script>
                swal('Account updated successfully!', '', 'success')
                .then(() => {
                    window.location.href = 'accountprivileges.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to update account!', '', 'error');</script>"<?php
        }
    }
    ?>
<?php
$html->endBody();
?>