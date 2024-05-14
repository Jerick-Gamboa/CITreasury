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
    <script src="../js/apexcharts.js"></script>
    <script src="../js/predefined-script.js"></script>
    <script src="../js/defer-script.js" defer></script>
    <title>CITreasury - Events</title>
</head>
<body>
    <?php
    if (isset($_COOKIE['cit-student-id'])) {
        $sql = "SELECT `type` FROM `accounts` WHERE `student_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_COOKIE['cit-student-id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $type = $row['type'];
            if ($type === 'user') {
                header("location: ../user/");
            }
        } else {
            header("location: ../");
        }
    } else {
        header("location: ../");
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
            <?php
            # If event id is found in URL query
            if (isset($_GET['event-id'])) {
            ?>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 class="text-3xl text-custom-purplo font-bold mb-3"><?php echo $_GET['event-id']; ?></h1>
                <div class="flex flex-row w-56 p-1 mb-3 border-2 border-custom-purple  focus:border-custom-purplo rounded-lg bg-white">
                    <svg id="mdi-calendar-search" class="h-6 w-6 mr-1 fill-custom-purple" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M19,8H5V19H9.5C9.81,19.75 10.26,20.42 10.81,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H6V1H8V3H16V1H18V3H19A2,2 0 0,1 21,5V13.03C20.5,12.22 19.8,11.54 19,11V8Z" /></svg>
                    <form method="GET">
                        <input type="text" id="event-search" name="search" placeholder="Search event..." class="w-full focus:outline-none">
                  </form>
                </div>
            </div>
            <div class="mt-1 mb-5 overflow-x-auto rounded-lg shadow-lg">
                <div class="overflow-x-auto rounded-lg border border-black">
                    <table class="w-full px-1 text-center">
                        <?php
                        $sql = "SELECT *  FROM `students` JOIN `registrations` ON `students`.`student_id` = `registrations`.`student_id` AND `registrations`.`event_id` = ? ";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $_GET['event-id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            ?>
                            <thead class="text-white uppercase bg-custom-purplo ">
                                <tr>
                                    <th scope="col" class="p-2 border-r border-black">Student ID</th>
                                    <th scope="col" class="p-2 border-r border-black">Student Name</th>
                                    <th scope="col" class="p-2 border-r border-black">Student Description</th>
                                    <th scope="col" class="p-2 border-r border-black">Event Date</th>
                                    <th scope="col" class="p-2 border-r border-black">Event Fee (₱)</th>
                                    <th scope="col" class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <script>
                                const deleteIds = [];
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
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eid; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventname; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventdesc; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventdate; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $feeperevent; ?></td>
                                    <td class="max-w-56 bg-purple-100">
                                        <button class="px-4 py-2 my-1 mx-1 bg-yellow-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-yellow-400" onclick="editRow(this)">Edit</button>
                                        <form method="POST" class="inline-block" id="delete-current-<?php echo str_replace(" ", "", $eid) ?>">
                                            <input type="hidden" name="eid-to-delete" value="<?php echo $eid; ?>">
                                            <button type="button" id="delete-event-<?php echo str_replace(" ", "", $eid) ?>" class="px-2 py-2 mb-1 mx-1 bg-red-600 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-red-500">Delete</button>
                                        </form>
                                        <button class="px-3 py-2 my-1 mx-1 bg-blue-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-blue-400">View</button>
                                    </td>
                                </tr>
                                <script>
                                    deleteIds.push("<?php echo str_replace(" ", "", $eid) ?>");
                                </script>
                                <?php
                            }
                        } else {
                            ?><h3 class="p-4">No registrations found.</h3><?php
                        }
                        ?>
                    </table>
                </div>
            </div>
            <?php
            } else {
            # If event id is not found in URL query
            ?>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 class="text-3xl text-custom-purplo font-bold mb-3">Manage Registrations</h1>
                <div class="flex flex-row w-56 p-1 mb-3 border-2 border-custom-purple  focus:border-custom-purplo rounded-lg bg-white">
                    <svg id="mdi-calendar-search" class="h-6 w-6 mr-1 fill-custom-purple" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M19,8H5V19H9.5C9.81,19.75 10.26,20.42 10.81,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H6V1H8V3H16V1H18V3H19A2,2 0 0,1 21,5V13.03C20.5,12.22 19.8,11.54 19,11V8Z" /></svg>
                    <form method="GET">
                        <input type="text" id="event-search" name="search" placeholder="Search event..." class="w-full focus:outline-none">
                  </form>
                </div>
            </div>
            <div class="mt-1 mb-5 overflow-x-auto rounded-lg shadow-lg">
                <div class="overflow-x-auto rounded-lg border border-black">
                    <table class="w-full px-1 text-center">
                        <?php
                        $sql = "SELECT * FROM `events`";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            ?>
                            <thead class="text-white uppercase bg-custom-purplo ">
                                <tr>
                                    <th scope="col" class="p-2 border-r border-black">Event ID</th>
                                    <th scope="col" class="p-2 border-r border-black">Event Name</th>
                                    <th scope="col" class="p-2 border-r border-black">Event Description</th>
                                    <th scope="col" class="p-2 border-r border-black">Event Date</th>
                                    <th scope="col" class="p-2 border-r border-black">Event Fee (₱)</th>
                                    <th scope="col" class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <script>
                                const deleteIds = [];
                            </script>
                            <?php
                            while($row = $result->fetch_assoc()) {
                                $eid = $row['event_id'];
                                $eventname = $row['event_name'];
                                $eventdesc = $row['event_description'];
                                $eventdate = $row['event_date'];
                                $feeperevent = $row['fee_per_event'];
                                ?>
                                <tr class="border-t border-black">
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eid; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventname; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventdesc; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventdate; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $feeperevent; ?></td>
                                    <td class="max-w-56 bg-purple-100">
                                        <button class="px-4 py-2 my-1 mx-1 bg-yellow-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-yellow-400" onclick="editRow(this)">Edit</button>
                                        <form method="POST" class="inline-block" id="delete-current-<?php echo str_replace(" ", "", $eid) ?>">
                                            <input type="hidden" name="eid-to-delete" value="<?php echo $eid; ?>">
                                            <button type="button" id="delete-event-<?php echo str_replace(" ", "", $eid) ?>" class="px-2 py-2 mb-1 mx-1 bg-red-600 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-red-500">Delete</button>
                                        </form>
                                        <button class="px-3 py-2 my-1 mx-1 bg-blue-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-blue-400">View</button>
                                    </td>
                                </tr>
                                <script>
                                    deleteIds.push("<?php echo str_replace(" ", "", $eid) ?>");
                                </script>
                                <?php
                            }
                        } else {
                            ?><h3 class="p-4">No registrations found.</h3><?php
                        }
                        ?>
                    </table>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</body>
</html>
