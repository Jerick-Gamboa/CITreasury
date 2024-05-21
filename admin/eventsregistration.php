<!DOCTYPE html>
<?php
include '../connection.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/nobgcitsclogo.png">
    <!-- Import JavaScript files -->
    <script src="../js/tailwind3.4.1.js"></script>
    <script src="../js/tailwind.config.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/predefined-script.js"></script>
    <script src="../js/defer-script.js" defer></script> <!-- Defer attribute means this javascript file will be executed once the HTML file is fully loaded -->
    <title>CITreasury - Events</title>
</head>
<body>
    <?php
    # Verify if login exists such that the cookie "cit-student-id" is found on browser
    if (isset($_COOKIE['cit-student-id'])) {
        $sql = "SELECT `type` FROM `accounts` WHERE `student_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_COOKIE['cit-student-id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $type = $row['type'];
            if ($type === 'user') { # If account type is user, redirect to user page
                header("location: ../user/");
            }
        } else { # If account is not found, return to login page
            header("location: ../");
        }
    } else { # If cookie is not found, return to login page
        header("location: ../");
    }
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
        <!-- Side Bar Menu Items -->
        <div class="mt-18 md:mt-20 mx-2">
            <div id="menu-items" class="hidden md:inline-block w-60 h-full">
            </div>
        </div>
        <!-- Harmonica Menu Items for mobile, hidden in medium to larger screens -->
        <div id="menu-items-mobile" class="fixed block md:hidden h-fit top-16 w-full p-4 bg-custom-purplo opacity-95">
        </div>
        <div class="w-full bg-red-50 px-6 min-h-screen">
            <?php
            # If event id is found in URL query
            if (isset($_GET['event-id'])) {
            ?>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <?php
                $sql_title = "SELECT `event_name`, `event_date` FROM `events` WHERE `event_id` = ?";
                $stmt_title = $conn->prepare($sql_title);
                $stmt_title->bind_param("s", $_GET['event-id']);
                $stmt_title->execute();
                $result_title = $stmt_title->get_result();
                if ($row_title = $result_title->fetch_assoc()) {
                    $dateofcurrenteventinget = $row_title['event_date'];
                    # Set event name in title using event-id in URL query
                    ?><h1 id="event-title" class="text-3xl text-custom-purplo font-bold mb-3"><?php echo $row_title['event_name']; ?></h1><?php
                }
                $sql_update_status = "UPDATE `registrations` 
                    JOIN `events` ON `registrations`.`event_id` = `events`.`event_id`
                    SET `registrations`.`status` = 'FULLY_PAID_BEFORE_EVENT'
                    WHERE `registrations`.`event_id` = ? 
                    AND `events`.`event_date` >= CURDATE() 
                    AND `events`.`event_fee` = `registrations`.`paid_fees`";
                $stmt_update_status = $conn->prepare($sql_update_status);
                $stmt_update_status->bind_param("i", $_GET['event-id']);
                $stmt_update_status->execute();
                ?>
                <!-- Search Bar -->
                <div class="flex flex-row w-56 p-1 mb-3 border-2 border-custom-purple  focus:border-custom-purplo rounded-lg bg-white">
                    <svg id="mdi-account-search" class="h-6 w-6 mr-1 fill-custom-purple" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M10,4A4,4 0 0,1 14,8C14,8.91 13.69,9.75 13.18,10.43C12.32,10.75 11.55,11.26 10.91,11.9L10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M2,20V18C2,15.88 5.31,14.14 9.5,14C9.18,14.78 9,15.62 9,16.5C9,17.79 9.38,19 10,20H2Z" /></svg>
                    <form method="GET">
                        <input type="hidden" name="event-id" value="<?php echo $_GET['event-id']; ?>">
                        <input type="text" id="registered-search" name="search" placeholder="Search registered..." class="w-full focus:outline-none">
                    </form>
                </div>
            </div>
            <div class="fixed bottom-10 right-6">
                <?php
                # If the event has passed, disable the registration button, otherwise enable it
                $isEventPast = strtotime($dateofcurrenteventinget) < strtotime(date("Y-m-d"));
                $buttonClasses = "focus:outline-none rounded-full shadow-md shadow-gray-500";
                $svgClasses = "w-16 h-16 bg-white rounded-full " . ($isEventPast ? "fill-gray-400" : "fill-green-500 hover:fill-green-600");
                ?>
                <button id="register-a-student" class="<?php echo $buttonClasses; ?>" <?php if (!$isEventPast) echo "title='Register a Student'"; ?> <?php if ($isEventPast) echo "disabled"; ?>>
                    <svg id="mdi-plus-circle" class="<?php echo $svgClasses; ?>" viewBox="2 2 20 20">
                        <path d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                    </svg>
                </button>
            </div>
            <div class="mt-1 mb-5 overflow-x-auto rounded-lg shadow-lg">
                <div class="overflow-x-auto rounded-lg border border-black">
                    <!-- Table of Registered Students -->
                    <table class="w-full px-1 text-center">
                        <?php
                        $eventId = $_GET['event-id'];
                        # Query for displaying registered students in a particular event
                        # If the event was happened and the student has still balance, the total fees to paid would be the event fee + sanction fee
                        # Else if the event wasnt happened yet or the event fee was fully paid after registration, the total fees would be the event fee alone
                        # Or else, sanction fee was also applied
                        # The balance would also be affected
                        $sql = "SELECT  `students`.*, `registrations`.`registration_date`, 
                                    CASE 
                                        WHEN `events`.`event_date` < CURDATE() AND `events`.`event_fee` > `registrations`.`paid_fees` 
                                        THEN `events`.`event_fee` + `events`.`sanction_fee` 
                                        WHEN `events`.`event_date` >= CURDATE() OR `registrations`.`status` = 'FULLY_PAID_BEFORE_EVENT' 
                                        THEN `events`.`event_fee` 
                                        ELSE `events`.`event_fee` + `events`.`sanction_fee` 
                                    END AS `total_fee`, 
                                    CASE 
                                        WHEN `events`.`event_date` < CURDATE() AND `events`.`event_fee` > `registrations`.`paid_fees` 
                                        THEN (`events`.`event_fee` + `events`.`sanction_fee`) - `registrations`.`paid_fees` 
                                        WHEN `events`.`event_date` >= CURDATE() OR `registrations`.`status` = 'FULLY_PAID_BEFORE_EVENT' 
                                        THEN `events`.`event_fee` - `registrations`.`paid_fees`
                                        ELSE (`events`.`event_fee` + `events`.`sanction_fee`) - `registrations`.`paid_fees` 
                                    END AS `balance`
                                FROM `students` 
                                JOIN `registrations` ON `students`.`student_id` = `registrations`.`student_id` 
                                JOIN `events` ON `events`.`event_id` = `registrations`.`event_id` 
                                WHERE `registrations`.`event_id` = ?";
                        if (isset($_GET['search'])) {
                            $search = '%' . $_GET['search'] . '%';
                            $sql .= " AND (`students`.`student_id` LIKE ? OR `students`.`last_name` LIKE ? OR `students`.`first_name` LIKE ? OR `students`.`year_and_section` LIKE ? OR `registrations`.`registration_date` LIKE ?)";
                            ?>
                            <script>
                                $("#registered-search").val("<?php echo htmlspecialchars($_GET['search']); ?>");
                            </script>
                            <?php
                        }
                        $sql .= " ORDER BY `balance` DESC";
                        $stmt = $conn->prepare($sql);
                        if (isset($search)) {
                            $stmt->bind_param("isssss", $eventId, $search, $search, $search, $search, $search);
                        } else {
                            $stmt->bind_param("i", $eventId);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            ?>
                            <thead class="text-white uppercase bg-custom-purplo ">
                                <tr>
                                    <th scope="col" class="p-2 border-r border-black">Student ID</th>
                                    <th scope="col" class="p-2 border-r border-black">Student Name</th>
                                    <th scope="col" class="p-2 border-r border-black">Year & Section</th>
                                    <th scope="col" class="p-2 border-r border-black">Registration Date</th>
                                    <th scope="col" class="p-2 border-r border-black">Total Fees (₱)</th>
                                    <th scope="col" class="p-2 border-r border-black">Balance (₱)</th>
                                    <th scope="col" class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <?php
                            while($row = $result->fetch_assoc()) {
                                $sid = $row['student_id'];
                                $lastname = $row['last_name'];
                                $firstname = $row['first_name'];
                                $mi = !empty($row['middle_initial']) ? $row['middle_initial'] . '.' : "";
                                $yearsec = $row['year_and_section'];
                                $regdate = $row['registration_date'];
                                $totalfee = $row['total_fee'];
                                $balance = $row['balance'];
                                ?>
                                <tr class="border-t border-black">
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $sid; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $lastname . ', ' . $firstname . ' ' . $mi; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $yearsec; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $regdate; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $totalfee; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $balance; ?></td>
                                    <td class="max-w-56 bg-purple-100">
                                        <button class="px-3 py-2 my-1 mx-1 bg-green-500 text-white text-sm font-semibold rounded-lg focus:outline-none disabled:bg-gray-400 shadow hover:bg-green-400" onclick='collect(this)' <?php if ($balance <= 0) echo 'disabled'; ?>>
                                            <svg id="mdi-wallet-plus" class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M3 0V3H0V5H3V8H5V5H8V3H5V0H3M9 3V6H6V9H3V19C3 20.1 3.89 21 5 21H19C20.11 21 21 20.11 21 19V18H12C10.9 18 10 17.11 10 16V8C10 6.9 10.89 6 12 6H21V5C21 3.9 20.11 3 19 3H9M12 8V16H22V8H12M16 10.5C16.83 10.5 17.5 11.17 17.5 12C17.5 12.83 16.83 13.5 16 13.5C15.17 13.5 14.5 12.83 14.5 12C14.5 11.17 15.17 10.5 16 10.5Z" /></svg>
                                        </button> <!-- Disable button if balance is zero -->
                                    </td>
                                </tr>
                                <?php
                            }
                        } else { // If query returned no results, display this
                            ?><h3 class="p-4">No registrations found.</h3><?php
                        }
                        ?>
                    </table>
                </div>
            </div>
            <?php
            } else {
            # If event id is not found in URL query, default landing page of eventregistrations.php
            ?>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 id="event-title" class="text-3xl text-custom-purplo font-bold mb-5">Manage Registrations</h1>
            </div>
            <div class="container mx-auto mb-6">
                <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <?php
                    $colors = ['red', 'green', 'blue', 'yellow', 'purple', 'pink'];
                    $sql_event = "SELECT `events`.*, COALESCE(`registration_counts`.`registration_count`, 0) AS `registration_count` FROM `events` LEFT JOIN (SELECT `event_id`, COUNT(*) AS `registration_count` FROM `registrations` GROUP BY `event_id`) `registration_counts` ON `events`.`event_id` = `registration_counts`.`event_id`";
                    $stmt_event = $conn->prepare($sql_event);
                    $stmt_event->execute();
                    $result_event = $stmt_event->get_result();
                    if ($result_event->num_rows > 0) {
                        $i = 0;
                        while ($row_event = $result_event->fetch_assoc()) {
                            $randomColor = $colors[$i];
                            ?>
                            <!-- Card of Events -->
                            <div class="w-full flex flex-col justify-between bg-<?php echo $randomColor; ?>-600 rounded shadow-lg">
                                <div class="w-full px-3 pt-3 flex flex-row justify-between items-center">
                                    <h3 class="text-2xl text-white font-semibold">
                                        <?php echo $row_event['event_name']; ?>
                                    </h3>
                                    <h2 class="text-4xl font-bold text-white">
                                        ₱ <?php echo $row_event['event_fee']; ?>
                                    </h2>
                                </div>
                                <div class="w-full px-3 py-2 text-white">
                                    <p class="my-1 text-sm font-bold">
                                        Total Registered: <?php echo $row_event['registration_count']; ?>
                                    </p>
                                    <p class="my-1 text-xs">
                                        Date: <?php echo $row_event['event_date']; ?>
                                    </p>
                                </div>
                                <div class="w-full px-3 py-2 bg-<?php echo $randomColor; ?>-700 rounded-b">
                                    <a href="eventsregistration.php?event-id=<?php echo $row_event['event_id']; ?>" class="text-xs font-bold text-white">View Registrations</a> <!-- Add URL query with event-id -->
                                </div>
                            </div>
                            <?php
                            $i++;
                            if ($i == sizeof($colors)) {
                                $i=0;
                            }
                        }
                    } else {
                        ?><p>No events found.</p><?php
                    }
                    ?>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <?php
    if (isset($_GET['event-id'])) {
    ?>
    <!-- Darken Background for Modal, hidden by default -->
    <div id="collect-popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <!-- Popup Modal For Collecting Fees, hidden by default -->
    <div id="collect-popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="collect-close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Collect Fee</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Student ID:</label>
                    <input type="text" id="collect-student-id" name="collect-student-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="7" readonly>
                    <label class="ml-1 text-sm">Total Fee (₱):</label>
                    <input type="number" id="collect-total-fee" name="collect-total-fee" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" readonly>
                    <label class="ml-1 text-sm">Balance (₱):</label>
                    <input type="number" id="collect-balance" name="collect-balance" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" readonly>
                    <label class="ml-1 text-sm">Collected Amount (₱):</label>
                    <input type="number" id="collect-amount" name="collect-amount" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold disabled:bg-gray-400 hover:bg-custom-purplo" id="collect-this-fee" name="collect-this-fee" disabled>Collect Fee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Darken Background for Modal, hidden by default -->
    <div id="register-popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <!-- Popup Modal For Registration, hidden by default  -->
    <div id="register-popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="register-close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Register a Student</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Student ID:</label>
                    <input type="text" id="register-student-id" name="register-student-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" maxlength="7" required>
                    <label class="ml-1 text-sm">Advance Fee (₱):</label>
                    <input type="number" id="register-advance-fee" name="register-advance-fee" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required value="0" min="0" max="<?php echo $totalfee; ?>">
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold hover:bg-custom-purplo" name="register-this-student">Register Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#collect-popup-bg, #collect-popup-item, #register-popup-bg, #register-popup-item").removeClass("hidden");
        $("#collect-popup-bg, #collect-popup-item, #register-popup-bg, #register-popup-item").hide();

        // If (+) button is pressed, fade in modals for register
        $("#register-a-student").click((event) => {
            $("#register-popup-bg").fadeIn(150);
            $("#register-popup-item").delay(150).fadeIn(150);
            $("#register-close-popup").click((event) => { // If [x] button is pressed, fade out modals
                $("#register-popup-bg, #register-popup-item").fadeOut(150);
            });
        });

        // If collect button is pressed, fade in modals for collection
        function collect(link) {
            $("#collect-popup-bg").fadeIn(150);
            $("#collect-popup-item").delay(150).fadeIn(150);
            $("#collect-close-popup").click((event) => {
                $("#collect-popup-bg, #collect-popup-item").fadeOut(150);
                $("#collect-amount").val(null);
            });

            let row = link.parentNode.parentNode; // Get table datas
            // Transfer table data to input fields
            $("#collect-student-id").val(row.cells[0].innerHTML);
            $("#collect-total-fee").val(row.cells[4].innerHTML); 
            $("#collect-balance").val(row.cells[5].innerHTML);

            $("#collect-amount").on('input', () => { // Input change in collected amount
                let collectAmount = parseFloat($("#collect-amount").val());
                let currentBalance = parseFloat(row.cells[5].innerHTML);

                if (isNaN(collectAmount) || collectAmount > currentBalance || collectAmount <= 0) {
                    // If collected amount is not valid, disable Collect Fee button and set balance input to default
                    $("#collect-this-fee").prop('disabled', true);
                    $("#collect-balance").val(currentBalance);
                } else {
                    // If valid, enable Collect Feebutton and set balance = (current balance - collected amount)
                    $("#collect-this-fee").prop('disabled', false);
                    $("#collect-balance").val(currentBalance - parseFloat($("#collect-amount").val()));
                }
            });
        }
    </script>
    <?php
    }
    ?>
    <?php
    # If collect fee is submitted
    if (isset($_POST['collect-this-fee'])) {
        $sid = $_POST['collect-student-id'];
        $collectamount = $_POST['collect-amount'];
        $sqlupdate_collect = "UPDATE `registrations` SET `paid_fees` = (`paid_fees` + ?) WHERE `event_id` = ? AND `student_id` = ? "; # Update fees of student
        $stmt_update_collect = $conn->prepare($sqlupdate_collect);
        $stmt_update_collect->bind_param("iis", $collectamount, $_GET['event-id'], $sid);
        if ($stmt_update_collect->execute()) {
            ?>
            <!-- SweetAlert popup -->
            <script>
                swal('Fees collected!', '', 'success')
                .then(() => {
                    window.location.href = 'eventsregistration.php?event-id=<?php echo htmlspecialchars($_GET['event-id']); ?>';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to collect fee!', '', 'error');</script><?php
        }
    }
    # If registration is submitted
    if (isset($_POST['register-this-student'])) {
        $sid = $_POST['register-student-id'];
        $advancefee = $_POST['register-advance-fee'];

        # Check first if student is already registered in current event
        $sql_verify_register = "SELECT * FROM `registrations` WHERE `event_id` = ? AND `student_id` = ?";
        $stmt_verify_register = $conn->prepare($sql_verify_register);
        $stmt_verify_register->bind_param("is", $_GET['event-id'], $sid);

        # Preparation of inserting data to database
        $sql_register = "INSERT INTO `registrations`(`event_id`, `student_id`, `registration_date`, `paid_fees`) VALUES (?, ?, NOW(), ?)";
        $stmt_register = $conn->prepare($sql_register);
        $stmt_register->bind_param("isi", $_GET['event-id'], $sid, $advancefee);

        # If query returned with another result for current student, then it is already registered
        if ($stmt_verify_register->execute() && $stmt_verify_register->get_result()->num_rows > 0) {
            ?><script>swal('You can\'t register a student twice in this event!', '', 'error');</script><?php
        } elseif ($stmt_register->execute()) { # Else if no result, insert data to database
            ?>
            <script>
                swal('Student registered successfully!', '', 'success')
                .then(() => {
                    window.location.href = 'eventsregistration.php?event-id=<?php echo htmlspecialchars($_GET['event-id']); ?>';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Registration failed!', '', 'error');</script><?php
        }
    }
    ?>
</body>
</html>