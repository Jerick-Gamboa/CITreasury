<?php
session_start();
include '../connection.php';
include '../helperfunctions.php';
verifyAdminLoggedIn($conn);

$html = new HTML("CITreasury - Sanctions");
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
            </div>
        </div>
        <div id="menu-user-items-mobile" class="fixed block md:hidden h-fit top-16 w-full p-4 bg-custom-purplo opacity-95">
        </div>
        <div class="w-full bg-red-50 px-6 min-h-screen">
            <div class="mt-24">
                <h1 class="text-3xl text-custom-purplo font-bold mb-5">Your Sanctions</h1>
            </div>
            <div class="w-full p-4 bg-custom-purplo rounded-lg shadow-lg mb-4">
                <h2 id="pending-fees" class="text-2xl text-white font-semibold">Pending Fees: ₱ --</h2>
            </div>
            <div class="flex lg:flex-row flex-col">
                <div class="w-full p-4 bg-red-300 rounded-lg shadow-lg mr-5 mb-5">
                    <h3 class="text-gray-800 font-bold text-lg mb-4">Unregistered Past Events</h3>
                    <?php
                    $pendingfees = 0;
                    $sql_unregistered_events = "
                        SELECT `events`.*, `events`.`event_fee` + `events`.`sanction_fee` AS `total_fee`,
                            (`events`.`event_fee` + `events`.`sanction_fee` - COALESCE(`sanctions`.`total_sanctions_paid`, 0)) AS `balance`
                        FROM `events`
                        LEFT JOIN `registrations` 
                        ON `events`.`event_id` = `registrations`.`event_id` 
                            AND `registrations`.`student_id` = ?
                        LEFT JOIN (
                                SELECT 
                                    `event_id`, 
                                    `student_id`, 
                                    SUM(`sanctions_paid`) AS `total_sanctions_paid`
                                FROM 
                                    `sanctions`
                                GROUP BY 
                                    `event_id`, `student_id`
                            ) AS `sanctions` 
                        ON `events`.`event_id` = `sanctions`.`event_id` AND `sanctions`.`student_id` = ?
                        WHERE `registrations`.`student_id` IS NULL 
                            AND `events`.`event_date` < CURDATE()
                            AND (`events`.`event_fee` + `events`.`sanction_fee`) > COALESCE(`sanctions`.`total_sanctions_paid`, 0)";
                    $stmt_unregistered_events = $conn->prepare($sql_unregistered_events);
                    $stmt_unregistered_events->bind_param("ss", $_SESSION['cit-student-id'], $_SESSION['cit-student-id']);
                    $stmt_unregistered_events->execute();
                    $result_unregistered_events = $stmt_unregistered_events->get_result();
                    if ($result_unregistered_events->num_rows > 0) {
                        while ($row_event = $result_unregistered_events->fetch_assoc()) {
                            ?>
                            <div class="border-l-4 border-white m-2 p-3 bg-red-600 shadow-lg text-white">
                                <h3 class="text-2xl font-bold mb-2"><?php echo $row_event['event_name']; ?></h3>
                                <div class="text-sm font-semibold">
                                    <p>Date: <?php echo $row_event['event_date']; ?></p>
                                    <p>To Pay: ₱ <?php echo $row_event['balance']; ?></p>
                                </div>
                            </div>
                            <?php
                            $pendingfees += $row_event['total_fee'];
                        }
                    } else {
                        ?><p class="text-sm">No events found.</p><?php
                    }
                    ?>
                </div>
                <div class="w-full p-4 bg-orange-300 rounded-lg shadow-lg mb-4">
                    <h3 class="text-gray-800 font-bold text-lg mb-4">Unsettled Registrations</h3>
                    <?php
                    $sql_unsettledbalance_events = "
                        SELECT `registrations`.`registration_date`, 
                            `registrations`.`paid_fees`, 
                            `events`.*, 
                            CASE 
                                WHEN `events`.`event_date` < CURDATE() AND `events`.`event_fee` > `registrations`.`paid_fees` THEN `events`.`event_fee` + `events`.`sanction_fee` 
                                WHEN `events`.`event_date` >= CURDATE() OR `registrations`.`status` = 'FULLY_PAID_BEFORE_EVENT' THEN `events`.`event_fee` 
                                ELSE `events`.`event_fee` + `events`.`sanction_fee` 
                            END AS `total_fee`, 
                            CASE 
                                WHEN `events`.`event_date` < CURDATE() AND `events`.`event_fee` > `registrations`.`paid_fees` THEN (`events`.`event_fee` + `events`.`sanction_fee`) - `registrations`.`paid_fees` 
                                WHEN `events`.`event_date` >= CURDATE() OR `registrations`.`status` = 'FULLY_PAID_BEFORE_EVENT' THEN `events`.`event_fee` - `registrations`.`paid_fees` 
                                ELSE (`events`.`event_fee` + `events`.`sanction_fee`) - `registrations`.`paid_fees` 
                            END AS `balance` 
                        FROM `students` 
                        JOIN `registrations` ON `students`.`student_id` = `registrations`.`student_id` 
                        JOIN `events` ON `events`.`event_id` = `registrations`.`event_id` 
                        WHERE `students`.`student_id` = ? 
                        HAVING `balance` <> 0";
                    $stmt_unsettledbalance_events = $conn->prepare($sql_unsettledbalance_events);
                    $stmt_unsettledbalance_events->bind_param("s", $_SESSION['cit-student-id']);
                    $stmt_unsettledbalance_events->execute();
                    $result_unsettledbalance_events = $stmt_unsettledbalance_events->get_result();
                    if ($result_unsettledbalance_events->num_rows > 0) {
                        while ($row_event = $result_unsettledbalance_events->fetch_assoc()) {
                            ?>
                            <div class="border-l-4 border-white m-2 p-3 bg-orange-600 shadow-lg text-white">
                                <h3 class="text-2xl font-bold mb-2"><?php echo $row_event['event_name']; ?></h3>
                                <div class="text-sm font-semibold">
                                    <p>Registered: <?php echo $row_event['registration_date']; ?></p>
                                    <p>Paid Fee: ₱ <?php echo $row_event['paid_fees']; ?></p>
                                    <p>To Pay: ₱ <?php echo $row_event['balance']; ?></p>
                                </div>
                            </div>
                            <?php
                            $pendingfees += $row_event['balance'];
                        }
                    } else {
                        ?><p class="text-sm">You have no unsettled balance during registration.</p><?php
                    }
                    ?>
                    <script>
                        $("#pending-fees").html("Pending Fees: ₱ <?php echo $pendingfees; ?>");
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
