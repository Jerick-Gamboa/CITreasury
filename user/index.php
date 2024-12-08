<?php
session_start();
include '../connection.php';
include '../helperfunctions.php';
include '../components/menu.php';
verifyUserLoggedIn($conn);

$html = new HTML("CITreasury - Dashboard");
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
                <?php
                $sql_title = "SELECT * FROM `students` WHERE `student_id` = ?";
                $stmt_title = $conn->prepare($sql_title);
                $stmt_title->bind_param("s", $_SESSION['cit-student-id']);
                $stmt_title->execute();
                $result_title = $stmt_title->get_result();
                if ($row_title = $result_title->fetch_assoc()) {
                    $lastname = $row_title['last_name'];
                    $firstname = $row_title['first_name'];
                    $mi = !empty($row_title['middle_initial']) ? $row_title['middle_initial'] . '.' : "";
                    # Set student name in title using student-id in cookie
                    ?><h1 class="text-3xl text-custom-purplo font-bold mb-5">Welcome, <?php echo $firstname . " ". $mi . " " . $lastname; ?>!</h1><?php
                }
                $sql_total = "SELECT SUM(total_paid) AS total_amount_paid FROM (SELECT SUM(`paid_fees`) AS total_paid FROM `registrations` WHERE `student_id` = ? UNION ALL SELECT SUM(`sanctions_paid`) AS total_paid FROM `sanctions` WHERE `student_id` = ?) AS combined_payments";
                $stmt_total = $conn->prepare($sql_total);
                $stmt_total->bind_param("ss", $_SESSION['cit-student-id'], $_SESSION['cit-student-id']);
                $stmt_total->execute();
                $result_total = $stmt_total->get_result();
                if ($row = $result_total->fetch_assoc()) {
                    $totalpaid = $row['total_amount_paid'];
                    if ($row['total_amount_paid'] === NULL) {
                        $totalpaid = "0";
                    }
                }
                ?>
                <div class="w-full p-4 bg-custom-purple rounded-lg shadow-lg mb-4">
                    <h2 class="text-2xl text-white font-semibold">Total Paid Fees: ₱ <?php echo $totalpaid; ?></h2>
                </div>
                <div class="flex lg:flex-row flex-col">
                    <div class="w-full p-4 bg-custom-purplo rounded-lg shadow-lg mr-5 mb-5">
                        <h3 class="text-white font-bold text-lg mb-4">Upcoming events</h3>
                        <?php
                        $sql_upcoming_events = "SELECT `events`.* FROM `students` INNER JOIN `events` ON FIND_IN_SET(SUBSTRING(`students`.`year_and_section`, 1, 1), `events`.`event_target`) > 0 WHERE `students`.`student_id` = ? AND `events`.`event_date` > CURDATE()";
                        $stmt_upcoming_events = $conn->prepare($sql_upcoming_events);
                        $stmt_upcoming_events->bind_param("s", $_SESSION['cit-student-id']);
                        $stmt_upcoming_events->execute();
                        $result_upcoming_events = $stmt_upcoming_events->get_result();
                        if ($result_upcoming_events->num_rows > 0) {
                            while ($row_event = $result_upcoming_events->fetch_assoc()) {
                                ?>
                                <div class="border-l-4 border-white m-2 p-3 bg-[#46064C] shadow-lg shadow-black mb-4 text-white">
                                    <h3 class="text-2xl font-bold mb-2"><?php echo $row_event['event_name']; ?></h3>
                                    <div class="text-sm">
                                        <p class="mb-2"><?php echo $row_event['event_description']; ?></p>
                                        <div class="font-semibold">
                                            <p>Date: <?php echo $row_event['event_date']; ?></p>
                                            <p>Event Fee: ₱ <?php echo $row_event['event_fee']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?><p class="text-sm">No upcoming events as of now.</p><?php
                        }
                        ?>
                    </div>
                    <div class="w-full p-4 bg-custom-purplo rounded-lg shadow-lg mb-4">
                        <h3 class="text-white font-bold text-lg mb-4">Registered events</h3>
                        <?php
                        $sql_registered_events = "SELECT `events`.`event_name`, `events`.`event_description`, `events`.`event_fee`, `registrations`.`registration_date`, `registrations`.`paid_fees` FROM `events` JOIN `registrations` ON `events`.`event_id` = `registrations`.`event_id` WHERE `registrations`.`student_id` = ?";
                        $stmt_registered_events = $conn->prepare($sql_registered_events);
                        $stmt_registered_events->bind_param("s", $_SESSION['cit-student-id']);
                        $stmt_registered_events->execute();
                        $result_registered_events = $stmt_registered_events->get_result();
                        if ($result_registered_events->num_rows > 0) {
                            while ($row_event = $result_registered_events->fetch_assoc()) {
                                ?>
                                <div class="border-l-4 border-white m-2 p-3 bg-[#46064C] shadow-lg shadow-black mb-4 text-white">
                                    <h3 class="text-2xl font-bold mb-2"><?php echo $row_event['event_name']; ?></h3>
                                    <div class="text-sm">
                                        <p class="mb-2"><?php echo $row_event['event_description']; ?></p>
                                        <div class="font-semibold">
                                            <p>Registered: <?php echo $row_event['registration_date']; ?></p>
                                            <p>Event Fee: ₱ <?php echo $row_event['event_fee']; ?></p>
                                            <p>Paid Fees: ₱ <?php echo $row_event['paid_fees']; ?></p>
                                        </div>
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
<?php
$html->endBody();
?>