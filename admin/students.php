<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/tailwind3.4.1.js"></script>
    <script src="../js/tailwind.config.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/predefined-script.js"></script>
    <script src="../js/defer-script.js" defer></script>
    <?php
    include '../connection.php';
    ?>
    <title>CITreasury - Students</title>
</head>
<body>
    <?php
    if (isset($_COOKIE['cit-email']) && isset($_COOKIE['cit-password'])) {
        if ($_COOKIE['cit-type'] === 'user') {
            header("location: ../user/index.php");
        }
    } else {
        header("location: ../index.php");
    }
    ?>
    <nav class="fixed w-full bg-custom-purple flex flex-row shadow shadow-gray-800">
        <img src="../img/nobgcitsclogo.png" class="w-12 h-12 my-2 ml-8">
        <h1 class="text-3xl p-3 font-bold text-white">CITreasury</h1>
        <div class="w-full text-white">
            <svg id="mdi-menu" class="w-8 h-8 mr-3 my-4 p-1 float-right fill-current rounded transition-all duration-300-ease-in-out md:hidden hover:bg-white hover:text-custom-purple hover:cursor-pointer" viewBox="0 0 24 24">
              <path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" />
            </svg>
        </div>
    </nav>
    <div class="flex flex-col md:flex-row bg-custom-purplo min-h-screen">
        <div class="mt-18 md:mt-20 mx-2">
            <div id="menu-items" class="hidden md:inline-block w-64 h-full">
            </div>
        </div>
        <div id="menu-items-mobile" class="fixed block md:hidden h-fit top-16 w-full p-4 bg-custom-purplo opacity-95">
        </div>
        <div class="w-full bg-red-50 px-6 min-h-screen">
            <div class="fixed bottom-10 right-6">
                <button id="add-student" title="Add New Student">
                    <svg id="mdi-plus-circle" class="w-16 h-16 fill-green-500 bg-white hover:fill-green-600 rounded-full shadow-md shadow-gray-500" viewBox="2 2 20 20"><path d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg>
                </button>
            </div>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 class="text-3xl text-custom-purplo font-bold mb-3">Manage Students</h1>
                <div class="flex flex-row w-56 p-1 mb-3 border-2 border-custom-purple  focus:border-custom-purplo rounded-lg bg-white">
                    <svg id="mdi-account-search" class="h-6 w-6 mr-1 fill-custom-purple" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M10,4A4,4 0 0,1 14,8C14,8.91 13.69,9.75 13.18,10.43C12.32,10.75 11.55,11.26 10.91,11.9L10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M2,20V18C2,15.88 5.31,14.14 9.5,14C9.18,14.78 9,15.62 9,16.5C9,17.79 9.38,19 10,20H2Z" /></svg>
                    <form method="GET">
                        <input type="text" id="student-search" name="search" placeholder="Search student..." class="w-full focus:outline-none">
                  </form>
                </div>
            </div>
            <div class="mt-1 mb-5 overflow-x-auto rounded-lg shadow-lg">
                <div class="overflow-x-auto rounded-lg border border-black">
                    <table class="w-full px-1 text-center">
                        <?php
                        $sql = "SELECT * FROM `students` JOIN `accounts` ON `students`.`student_id` = `accounts`.`student_id` WHERE `accounts`.`type` = 'user'";
                        if (isset($_GET['search'])) {
                            $search = $_GET['search'];
                            $sql .= " AND (`students`.`student_id` LIKE '%" . $search . "%' OR `students`.`last_name` LIKE '%" . $search . "%' OR `students`.`first_name` LIKE '%" . $search . "%' OR `students`.`year_and_section` LIKE '%" . $search . "%')";
                            ?>
                            <script>
                                $("#student-search").val("<?php echo $search; ?>");
                            </script>
                            <?php
                        }
                        $result = $conn->query($sql);
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
                            <script>
                                const namesArray = [];
                            </script>
                            <?php
                            while($row = $result->fetch_assoc()) {
                                $sid = $row['student_id'];
                                $lastname = $row['last_name'];
                                $firstname = $row['first_name'];
                                $mi = !empty($row['middle_initial']) ? $row['middle_initial'] . '.' : "";
                                $yearsec = $row['year_and_section'];
                                ?>
                                <tr class="border-t border-black">
                                    <td class="px-2 border-r border-black"><?php echo $sid; ?></td>
                                    <td class="px-2 border-r border-black"><?php echo $lastname . ', ' . $firstname . ' ' . $mi; ?></td>
                                    <td class="px-2 border-r border-black"><?php echo $yearsec; ?></td>
                                    <td class="max-w-56">
                                        <button class="px-4 py-2 my-1 mx-1 bg-yellow-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-yellow-400" onclick="editRow(this)">Edit</button>
                                        <form method="POST" class="inline-block" id="delete-current-<?php echo str_replace(" ", "", $sid) ?>">
                                            <input type="hidden" name="sid-to-delete" value="<?php echo $sid; ?>">
                                            <button id="delete-student-<?php echo str_replace(" ", "", $sid) ?>" class="px-2 py-2 mb-1 mx-1 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-500">Delete</button>
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
        </div>
    </div>
    <div id="popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <div id="popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button id="close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Add Student</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Student ID:</label>
                    <input type="text" name="student-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" maxlength="7" pattern="[0-9-]*" required>
                    <label class="ml-1 text-sm">Last Name:</label>
                    <input type="text" name="last-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">First Name:</label>
                    <input type="text" name="first-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">Middle Initial:</label>
                    <input type="text" name="middle-initial" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" maxlength="3" pattern="[a-zA-Z\s']+">
                    <label class="ml-1 text-sm">Year & Section:</label>
                    <input type="text" name="yearsec" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" maxlength="2" pattern="[A-Za-z0-9]+" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg text-base text-white font-bold hover:bg-custom-purplo" name="add-new-student">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="edit-popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <div id="edit-popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button id="edit-close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Edit Student</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Student ID:</label>
                    <input type="text" id="edit-student-id" name="edit-student-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" maxlength="7" readonly>
                    <label class="ml-1 text-sm">Last Name:</label>
                    <input type="text" id="edit-last-name" name="edit-last-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">First Name:</label>
                    <input type="text" id="edit-first-name" name="edit-first-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" pattern="[a-zA-Z\s']+" required>
                    <label class="ml-1 text-sm">Middle Initial:</label>
                    <input type="text" id="edit-middle-initial" name="edit-middle-initial" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" maxlength="3" pattern="[a-zA-Z\s']+">
                    <label class="ml-1 text-sm">Year & Section:</label>
                    <input type="text" id="edit-yearsec" name="edit-yearsec" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" maxlength="2" pattern="[A-Za-z0-9]+" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg text-base text-white font-bold hover:bg-custom-purplo" name="update-this-student">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#popup-bg, #popup-item, #edit-popup-bg, #edit-popup-item").removeClass("hidden");
        $("#popup-bg, #popup-item, #edit-popup-bg, #edit-popup-item").hide();
        $("#add-student").click((event) => {
            $("#popup-bg").fadeIn(150);
            $("#popup-item").delay(150).fadeIn(150);
            $("#close-popup").click((event) => {
                $("#popup-bg, #popup-item").fadeOut(150);
            });
        });

        function editRow(link) {
            $("#edit-popup-bg").fadeIn(150);
            $("#edit-popup-item").delay(150).fadeIn(150);
            $("#edit-close-popup").click(function() {
                $("#edit-popup-bg, #edit-popup-item").fadeOut(150);
            });
            
            var row = $(link).closest("tr");
            var studentId = row.find("td:eq(0)").text();
            var studentData = namesArray.find(function(student) {
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

        function deleteStudent(button_id, form_id) {
            $(button_id).click((event) => {
                event.preventDefault();
                swal({
                    title: "Delete this student?",
                    text: "This action can't be undone.",
                    icon: "info",
                    buttons: true,
                    buttons: {
                        cancel: 'No',
                        confirm : {text: "Yes", className:'bg-custom-purple'},
                    },
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $(form_id).submit();
                    }
                });
            });
        }

        for (let i=0; i<namesArray.length; i++) {
            deleteStudent("#delete-student-" + namesArray[i][0], "#delete-current-" + namesArray[i][0]);
        }
    </script>
    <?php
    if (isset($_POST['add-new-student'])) {
        $sid = $_POST['student-id'];
        $lastname = ucwords($_POST['last-name']);
        $firstname = ucwords($_POST['first-name']);
        $mi = ucwords($_POST['middle-initial']);
        $yearsec = strtoupper($_POST['yearsec']);
        $sql_student = "INSERT INTO `students`(`student_id`, `last_name`, `first_name`, `middle_initial`, `year_and_section`) VALUES ('$sid', '$lastname', '$firstname', '$mi', '$yearsec')";
        $email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";
        $password = "cit-" . $sid;
        $sql_account = "INSERT INTO `accounts`(`email`, `password`, `student_id`, `type`) VALUES ('$email', '$password', '$sid', 'user')";
        if ($conn->query($sql_student) && $conn->query($sql_account)) {
            ?>
            <script>
                swal('Student added successfully!', '', 'success')
                .then((okay) => {
                    window.location.href = 'students.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to add student!', '', 'error');</script>"<?php
        }
    }
    if (isset($_POST['update-this-student'])) {
        $sid = $_POST['edit-student-id'];
        $lastname = ucwords($_POST['edit-last-name']);
        $firstname = ucwords($_POST['edit-first-name']);
        $mi = ucwords($_POST['edit-middle-initial']);
        $yearsec = strtoupper($_POST['edit-yearsec']);
        $email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";
        $sqlupdate_account = "UPDATE `accounts` SET `email`='$email' WHERE `student_id` = '$sid' ";
        $sqlupdate_student = "UPDATE `students` SET `last_name`= '$lastname', `first_name`= '$firstname', `middle_initial`= '$mi', `year_and_section`= '$yearsec' WHERE `student_id` = '$sid'";
        if ($conn->query($sqlupdate_account) && $conn->query($sqlupdate_student)) {
            ?>
            <script>
                swal('Student updated successfully!', '', 'success')
                .then((okay) => {
                    window.location.href = 'students.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to add student!', '', 'error');</script>"<?php
        }
    }
    if (isset($_POST['sid-to-delete'])) {
        $sqldelete_account = "DELETE FROM `accounts` WHERE `student_id`= '". $_POST['sid-to-delete'] . "'";
        $sqldelete_student = "DELETE FROM `students` WHERE `student_id`= '". $_POST['sid-to-delete'] . "'";
        if ($conn->query($sqldelete_account) && $conn->query($sqldelete_student)) {
            ?>
            <script>swal('Successfully deleted', '', 'success').then(() => window.location.href = "students.php")</script>
            <?php
        } else {
            ?>
            <script>swal('Deletion failed', '', 'error');</script>
            <?php
        }
    }
    ?>
</body>
</html>
