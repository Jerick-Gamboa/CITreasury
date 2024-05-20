<!DOCTYPE html>
<?php
include '../connection.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/nobgcitsclogo.png">
    <script src="../js/tailwind3.4.1.js"></script>
    <script src="../js/tailwind.config.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/predefined-script.js"></script>
    <script src="../js/defer-script.js" defer></script>
    <title>CITreasury - Dashboard</title>
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
            if ($type === 'admin') { # If account type is admin, redirect to admin page
                header("location: ../admin/");
            }
        } else { # If account is not found, return to login page
            header("location: ../");
        }
    } else { # If cookie is not found, return to login page
        header("location: ../");
    }
    ?>
    <nav class="fixed w-full bg-custom-purple flex flex-row shadow shadow-gray-800">
        <img src="../img/nobgcitsclogo.png" class="w-12 h-12 my-2 ml-8">
        <h1 class="text-3xl p-3 font-bold text-white">CITreasury</h1>
        <div class="w-full text-white">
            <svg id="mdi-menu" class="w-8 h-8 mr-3 my-4 p-1 float-right fill-current rounded transition-all duration-300-ease-in-out md:hidden hover:bg-white hover:text-custom-purple hover:cursor-pointer" viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>
        </div>
    </nav>
    <div class="flex flex-col md:flex-row bg-custom-purplo min-h-screen">
        <div class="mt-18 md:mt-20 mx-2">
            <div id="menu-user-items" class="hidden md:inline-block w-64 h-full">
            </div>
        </div>
        <div id="menu-user-items-mobile" class="fixed block md:hidden h-fit top-16 w-full p-4 bg-custom-purplo opacity-95">
        </div>
        <div class="w-full bg-red-50 px-6 min-h-screen">
            <div class="mt-24">
                <?php
                $sql_title = "SELECT * FROM `students` WHERE `student_id` = ?";
                $stmt_title = $conn->prepare($sql_title);
                $stmt_title->bind_param("s", $_COOKIE['cit-student-id']);
                $stmt_title->execute();
                $result_title = $stmt_title->get_result();
                if ($row_title = $result_title->fetch_assoc()) {
                    $lastname = $row_title['last_name'];
                    $firstname = $row_title['first_name'];
                    $mi = !empty($row_title['middle_initial']) ? $row_title['middle_initial'] . '.' : "";
                    # Set student name in title using student-id in cookie
                    ?><h1 class="text-3xl text-custom-purplo font-bold mb-5">Welcome, <?php echo $firstname . " ". $mi . " " . $lastname; ?>!</h1><?php
                }
                $sql_total = "SELECT SUM(`paid_fees`) FROM `registrations` WHERE `student_id` = ?";
                $stmt_total = $conn->prepare($sql_total);
                $stmt_total->bind_param("s", $_COOKIE['cit-student-id']);
                $stmt_total->execute();
                $result_total = $stmt_total->get_result();
                if ($row = $result_total->fetch_assoc()) {
                    $totalpaid = $row['SUM(`paid_fees`)'];
                    if ($row['SUM(`paid_fees`)'] === NULL) {
                        $totalpaid = "0";
                    }
                }
                ?>
                <div class="w-full p-4 bg-custom-purplo rounded-lg shadow-lg mb-4">
                    <h2 class="text-2xl text-white font-semibold">Total Paid Fees: ₱ <?php echo $totalpaid; ?></h2>
                </div>
                <div class="flex lg:flex-row flex-col">
                    <div class="w-full p-4 bg-blue-300 rounded-lg shadow-lg mr-5 mb-5">
                        <h3 class="text-gray-800 font-bold text-lg mb-4">Upcoming events</h3>
                        <?php
                        $sql_upcoming_events = "SELECT * FROM `events` WHERE `event_date` > CURDATE()";
                        $stmt_upcoming_events = $conn->prepare($sql_upcoming_events);
                        $stmt_upcoming_events->execute();
                        $result_upcoming_events = $stmt_upcoming_events->get_result();
                        if ($result_upcoming_events->num_rows > 0) {
                            while ($row_event = $result_upcoming_events->fetch_assoc()) {
                                ?>
                                <div class="border-l-4 border-white m-2 p-3 bg-blue-600 shadow-lg text-white">
                                    <h3 class="text-2xl font-bold mb-2"><?php echo $row_event['event_name']; ?></h3>
                                    <div class="text-sm font-semibold">
                                        <p class="mb-2"><?php echo $row_event['event_description']; ?></p>
                                        <p>Date: <?php echo $row_event['event_date']; ?></p>
                                        <p>Event Fee: ₱ <?php echo $row_event['fee_per_event']; ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?><p class="text-sm">No upcoming events as of now.</p><?php
                        }
                        ?>
                    </div>
                    <div class="w-full p-4 bg-green-300 rounded-lg shadow-lg mb-4">
                        <h3 class="text-gray-800 font-bold text-lg mb-4">Registered events</h3>
                        <?php
                        $sql_registered_events = "SELECT `events`.`event_name`, `events`.`event_description`, `events`.`fee_per_event`, `registrations`.`registration_date`, `registrations`.`paid_fees` FROM `events` JOIN `registrations` ON `events`.`event_id` = `registrations`.`event_id` WHERE `registrations`.`student_id` = '22-1677';";
                        $stmt_registered_events = $conn->prepare($sql_registered_events);
                        $stmt_registered_events->execute();
                        $result_registered_events = $stmt_registered_events->get_result();
                        if ($result_registered_events->num_rows > 0) {
                            while ($row_event = $result_registered_events->fetch_assoc()) {
                                ?>
                                <div class="border-l-4 border-white m-2 p-3 bg-green-600 shadow-lg text-white">
                                    <h3 class="text-2xl font-bold mb-2"><?php echo $row_event['event_name']; ?></h3>
                                    <div class="text-sm font-semibold">
                                        <p class="mb-2"><?php echo $row_event['event_description']; ?></p>
                                        <p>Registered: <?php echo $row_event['registration_date']; ?></p>
                                        <p>Paid Fee: ₱ <?php echo $row_event['paid_fees']; ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?><p class="text-sm">You haven't registered any events.</p><?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
