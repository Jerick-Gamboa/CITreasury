<?php
session_start();
include '../connection.php';
include '../helperfunctions.php';
include '../password_compat.php';
include '../components/menu.php';
verifyAdminLoggedIn($conn);

$html = new HTML("CITreasury - Students");
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
            <div class="fixed bottom-10 right-6">
                <button id="add-student" class="focus:outline-none" title="Add New Student">
                    <svg id="mdi-plus-circle" class="w-16 h-16 fill-green-500 bg-white hover:fill-green-600 rounded-full shadow-md shadow-gray-500" viewBox="2 2 20 20"><path d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg>
                </button>
            </div>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 class="text-3xl text-custom-purplo font-bold mb-3">Manage Students</h1>
                <!-- Search Bar -->
                <div class="flex flex-row w-56 p-1 mb-3 border-2 border-custom-purple focus:border-custom-purplo rounded-lg bg-white">
                    <svg id="mdi-account-search" class="h-6 w-6 mr-1 fill-custom-purple" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M10,4A4,4 0 0,1 14,8C14,8.91 13.69,9.75 13.18,10.43C12.32,10.75 11.55,11.26 10.91,11.9L10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M2,20V18C2,15.88 5.31,14.14 9.5,14C9.18,14.78 9,15.62 9,16.5C9,17.79 9.38,19 10,20H2Z" /></svg>
                    <form method="GET">
                        <input type="text" id="student-search" name="search" placeholder="Search student..." class="w-full focus:outline-none">
                  </form>
                </div>
            </div>
            <script>
                const namesArray = [];
            </script>
            <div class="mt-1 mb-5 overflow-x-auto rounded-lg shadow-lg">
                <div class="overflow-x-auto rounded-lg border border-black">
                    <table class="w-full px-1 text-center">
                        <?php
                        // Pagination variables
                        $results_per_page = 10;
                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                        $offset = ($page - 1) * $results_per_page;
                        // SQL query for displaying all students
                        $sql = "SELECT * FROM `students` JOIN `accounts` ON `students`.`student_id` = `accounts`.`student_id` WHERE `accounts`.`student_id` != ?";
                        // Check if search is set
                        if (isset($_GET['search'])) {
                            $search = '%' . $_GET['search'] . '%';
                            $sql .= " AND (`students`.`student_id` LIKE ? OR `students`.`last_name` LIKE ? OR `students`.`first_name` LIKE ? OR `students`.`year_and_section` LIKE ?)";
                            ?>
                            <script>
                                $("#student-search").val("<?php echo htmlspecialchars($_GET['search']); ?>");
                            </script>
                            <?php
                        }
                        // Add limit and offset for pagination
                        $sql .= " LIMIT ? OFFSET ?";
                        // Prepare the statement
                        $stmt = $conn->prepare($sql);
                        if (isset($search)) {
                            $stmt->bind_param("ssssssi", $_SESSION['cit-student-id'], $search, $search, $search, $search, $results_per_page, $offset);
                        } else {
                            $stmt->bind_param("sii", $_SESSION['cit-student-id'], $results_per_page, $offset);
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
                                    <th scope="col" class="p-2 border-r border-black">Student Name</th>
                                    <th scope="col" class="p-2 border-r border-black">Year & Section</th>
                                    <th scope="col" class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <?php
                            // Loop through the results
                            while($row = $result->fetch_assoc()) {
                                $sid = $row['student_id'];
                                $lastname = $row['last_name'];
                                $firstname = $row['first_name'];
                                $mi = !empty($row['middle_initial']) ? $row['middle_initial'] . '.' : "";
                                $yearsec = $row['year_and_section'];
                                ?>
                                <tr class="border-t border-black">
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $sid; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $lastname . ', ' . $firstname . ' ' . $mi; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $yearsec; ?></td>
                                    <td class="px-1 bg-purple-100">
                                        <button class="px-3 py-2 my-1 mx-1 bg-yellow-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-yellow-400" onclick="editRow(this)">
                                            <svg id="mdi-pencil" class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                                            </svg>
                                        </button>
                                        <form method="POST" class="inline-block" id="delete-current-<?php echo str_replace(' ', '', $sid); ?>">
                                            <input type="hidden" name="sid-to-delete" value="<?php echo $sid; ?>">
                                            <button id="delete-student-<?php echo str_replace(' ', '', $sid); ?>" class="px-3 py-2 mb-1 mx-1 bg-red-600 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-red-500">
                                                <svg id="mdi-delete" class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                    <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <script>
                                    namesArray.push(["<?php echo $sid; ?>", "<?php echo $lastname; ?>", "<?php echo $firstname; ?>", "<?php echo $mi; ?>", "<?php echo $yearsec; ?>"]);
                                </script>
                                <?php
                            }
                        } else {
                            ?><h3 class="p-4">No students found.</h3><?php
                        }
                        ?>
                    </table>
                </div>
            </div>
            <!-- Pagination controls -->
            <div class="pagination my-2">
                <?php
                # Get the total number of rows for pagination
                $sql_total = "SELECT COUNT(*) FROM `students` JOIN `accounts` ON `students`.`student_id` = `accounts`.`student_id` WHERE `accounts`.`student_id` != ?";
                if (isset($_GET['search'])) {
                    $sql_total .= " AND (`students`.`student_id` LIKE ? OR `students`.`last_name` LIKE ? OR `students`.`first_name` LIKE ? OR `students`.`year_and_section` LIKE ?)";
                    $stmt_total = $conn->prepare($sql_total);
                    $stmt_total->bind_param("sssss", $_SESSION['cit-student-id'], $search, $search, $search, $search);
                } else {
                    $stmt_total = $conn->prepare($sql_total);
                    $stmt_total->bind_param("s", $_SESSION['cit-student-id']);
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
                        ?><a href='students.php?<?php echo (isset($search)) ? "search=".htmlspecialchars($_GET['search'])."&" : ""; ?>page=<?php echo $i; ?>'><button class="px-3 py-2 my-1 mr-1 <?php echo $page == $i ? 'bg-purple-600' : 'bg-custom-purplo'; ?> text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-custom-purple"><?php echo $i; ?></button></a>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="mb-4">
                <form method="POST" enctype="multipart/form-data">
                    <fieldset class="p-3 w-fit border-2 rounded-lg border-custom-purple flex flex-row">
                        <legend class="font-semibold text-custom-purple">Import CSV File</legend>
                        <input type="file" name="students-csv-file" id="students-csv-file" class="w-full px-2 text-sm py-1 border-2 border-custom-purple rounded-lg focus:outline-none focus:border-purple-500 bg-purple-100" required accept=".csv">
                        <input type="submit" value="Import" class="ml-2 px-2 text-white font-semibold bg-custom-purplo rounded-lg hover:cursor-pointer hover:bg-purple-600" name="importcsv">
                    </fieldset>
                </form>
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
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Add Student</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Student ID:</label>
                    <input type="text" name="student-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="7" pattern="[0-9-]*" required>
                    <label class="ml-1 text-sm">Last Name:</label>
                    <input type="text" name="last-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">First Name:</label>
                    <input type="text" name="first-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">Middle Initial:</label>
                    <input type="text" name="middle-initial" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="3" pattern="[a-zA-Z\s']+">
                    <label class="ml-1 text-sm">Year & Section:</label>
                    <input type="text" name="yearsec" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="2" pattern="[A-Za-z0-9]+" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold hover:bg-custom-purplo" name="add-new-student">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="edit-popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="edit-close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Edit Student</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Student ID:</label>
                    <input type="text" id="edit-student-id" name="edit-student-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="7" readonly>
                    <label class="ml-1 text-sm">Last Name:</label>
                    <input type="text" id="edit-last-name" name="edit-last-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">First Name:</label>
                    <input type="text" id="edit-first-name" name="edit-first-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">Middle Initial:</label>
                    <input type="text" id="edit-middle-initial" name="edit-middle-initial" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="3" pattern="[a-zA-Z\s']+">
                    <label class="ml-1 text-sm">Year & Section:</label>
                    <input type="text" id="edit-yearsec" name="edit-yearsec" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="2" pattern="[A-Za-z0-9]+" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold hover:bg-custom-purplo" name="update-this-student">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#popup-bg, #popup-item, #edit-popup-item").removeClass("hidden");
        $("#popup-bg, #popup-item, #edit-popup-item").hide();
        $("#add-student").click((event) => {
            $("#popup-bg").fadeIn(150);
            $("#popup-item").delay(150).fadeIn(150);
            $("#close-popup").click((event) => {
                $("#popup-bg, #popup-item").fadeOut(150);
            });
        });

        const editRow = (link) => {
            $("#popup-bg").fadeIn(150);
            $("#edit-popup-item").delay(150).fadeIn(150);
            $("#edit-close-popup").click(function() {
                $("#popup-bg, #edit-popup-item").fadeOut(150);
            });
            
            const row = $(link).closest("tr");
            const studentId = row.find("td:eq(0)").text();
            const studentData = namesArray.find((student) => {
                return student[0] === studentId;
            });
            if (studentData) {
                $("#edit-student-id").val(studentData[0]);
                $("#edit-last-name").val(studentData[1]);
                $("#edit-first-name").val(studentData[2]);
                $("#edit-middle-initial").val(studentData[3].replace(".", ""));
                $("#edit-yearsec").val(studentData[4]);
            }
        }

        for (let i=0; i<namesArray.length; i++) {
            deleteData("#delete-student-" + namesArray[i][0], "#delete-current-" + namesArray[i][0], "Delete this student?", "This action can't be undone.");
        }
    </script>
    <?php
    if (isset($_POST['add-new-student'])) {
        $sid = str_replace(" ", "", $_POST['student-id']);
        $lastname = ucwords($_POST['last-name']);
        $firstname = ucwords($_POST['first-name']);
        $mi = ucwords($_POST['middle-initial']);
        $yearsec = strtoupper($_POST['yearsec']);
        $email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";
        $password = "cit-" . $sid;
        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $sql_student = "INSERT INTO `students`(`student_id`, `last_name`, `first_name`, `middle_initial`, `year_and_section`) VALUES (?, ?, ?, ?, ?)";
        $stmt_student = $conn->prepare($sql_student);

        $sql_account = "INSERT INTO `accounts`(`email`, `password`, `student_id`, `type`) VALUES (?, ?, ?, 'user')";
        $stmt_account = $conn->prepare($sql_account);

        $stmt_student->bind_param("sssss", $sid, $lastname, $firstname, $mi, $yearsec);
        $stmt_account->bind_param("sss", $email, $hash_password, $sid);

        if ($stmt_student->execute()) {
            ?>
            <script>
                swal('Student added successfully!', `<?php echo $stmt_account->execute() ? "Default account also created:\n\nEmail: ".$email."\nPassword: ".$password : "But default student account failed to create." ?>`, 'success')
                .then(() => {
                    window.location.href = 'students.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to add student!', '', 'error');</script>"<?php
        }
    }
    if (isset($_POST['update-this-student'])) {
        $sid = str_replace(" ", "", $_POST['edit-student-id']);
        $lastname = ucwords($_POST['edit-last-name']);
        $firstname = ucwords($_POST['edit-first-name']);
        $mi = ucwords($_POST['edit-middle-initial']);
        $yearsec = strtoupper($_POST['edit-yearsec']);
        $email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";

        $sqlupdate_account = "UPDATE `accounts` SET `email`=? WHERE `student_id` = ?";
        $stmt_update_account = $conn->prepare($sqlupdate_account);

        $sqlupdate_student = "UPDATE `students` SET `last_name`= ?, `first_name`= ?, `middle_initial`= ?, `year_and_section`= ? WHERE `student_id` = ?";
        $stmt_update_student = $conn->prepare($sqlupdate_student);

        $stmt_update_account->bind_param("ss", $email, $sid);
        $stmt_update_student->bind_param("sssss", $lastname, $firstname, $mi, $yearsec, $sid);
        if ($stmt_update_student->execute()) {
            ?>
            <script>
                swal('Student updated successfully!', '<?php echo $stmt_update_account->execute() ? "Modifying the name can also modify the student\'s email." : "But student email failed to update." ?>', 'success')
                .then(() => {
                    window.location.href = 'students.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to update student!', '', 'error');</script>"<?php
        }
    }
    if (isset($_POST['sid-to-delete'])) {
        $sqldelete_student = "DELETE FROM `students` WHERE `student_id`= ?";
        $stmt_delete_student = $conn->prepare($sqldelete_student);
        $stmt_delete_student->bind_param("s", $_POST['sid-to-delete']);

        if ($stmt_delete_student->execute()) {
            ?>
            <script>
                swal('Student successfully deleted', '', 'success')
                .then(() => {
                    window.location.href = "students.php"
                });</script>
            <?php
        } else {
            ?>
            <script>swal('Student deletion failed', '', 'error');</script>
            <?php
        }
    }
    if (isset($_POST['importcsv'])) {
        if (isset($_FILES['students-csv-file']) && $_FILES['students-csv-file']['error'] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['students-csv-file']['name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($file_ext !== 'csv') {
                ?>
                <script>
                    swal('Error: Please upload a CSV file.', '', 'error');
                </script>
                <?php
            } else {
                $file_tmp = $_FILES['students-csv-file']['tmp_name'];
                $file_handle = fopen($file_tmp, "r");
                if ($file_handle !== false) {
                    $expected_header = ['student_id', 'last_name', 'first_name', 'middle_initial', 'year_and_section'];
                    $csv_header = fgetcsv($file_handle);
                    $csv_header[0] = preg_replace('/\x{FEFF}/u', '', $csv_header[0]);
                    if ($csv_header !== $expected_header) {
                        ?>
                        <script>
                            swal('Error: Incorrect CSV file format!', 'Please make sure the CSV file follows the correct structure.', 'error');
                        </script>
                        <?php
                    } else {
                        rewind($file_handle);
                        fgetcsv($file_handle);
                        $errors = '';
                        $hasError = false;
                        while ($data = fgetcsv($file_handle)) {
                            $sid = $data[0];
                            $lastname = $data[1];
                            $firstname = $data[2];
                            $mi = $data[3];
                            $yearsec = $data[4];
                            $email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";
                            $password = "cit-" . $sid;
                            $hash_password = password_hash($password, PASSWORD_DEFAULT);
                            try {
                                $sql_student = "INSERT INTO `students`(`student_id`, `last_name`, `first_name`, `middle_initial`, `year_and_section`) VALUES (?, ?, ?, ?, ?)";
                                $stmt_student = $conn->prepare($sql_student);

                                $sql_account = "INSERT INTO `accounts`(`email`, `password`, `student_id`, `type`) VALUES (?, ?, ?, 'user')";
                                $stmt_account = $conn->prepare($sql_account);

                                $stmt_student->bind_param("sssss", $sid, $lastname, $firstname, $mi, $yearsec);
                                $stmt_account->bind_param("sss", $email, $hash_password, $sid);

                                if ($stmt_student->execute()) {
                                    $stmt_account->execute();
                                } else {
                                    $hasError = true;
                                    $errors .= "ID '".$sid."' failed to insert.\n";
                                }
                            } catch (mysqli_sql_exception $e) {
                                $hasError = true;
                                $errors .= $e->getMessage() . '\n';
                            }
                        }
                        fclose($file_handle);
                        $conn->close();
                        ?>
                        <script>
                            swal(<?php if ($hasError) {?>'CSV File imported with errors!', `<?php echo $errors; ?>`, 'warning' <?php } else { ?>'CSV File imported successfully!', '', 'success' <?php } ?> )
                            .then((okay) => {
                                window.location.href = 'students.php';
                            });
                        </script>
                        <?php
                    }
                } else {
                    ?>
                    <script>
                        swal('Failed to open CSV File!', '', 'error');
                    </script>
                    <?php
                }
            }
        } else {
            ?>
            <script>
                swal('Error uploading file!', '', 'error');
            </script>
            <?php
        }
    }
    ?>
<?php
$html->endBody();
?>