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
            <div class="fixed bottom-10 right-6">
                <button id="add-event" class="focus:outline-none" title="Add New Event">
                    <svg id="mdi-plus-circle" class="w-16 h-16 fill-green-500 bg-white hover:fill-green-600 rounded-full shadow-md shadow-gray-500" viewBox="2 2 20 20"><path d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg>
                </button>
            </div>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 class="text-3xl text-custom-purplo font-bold mb-3">Manage Events</h1>
                <!-- Search Bar -->
                <div class="flex flex-row w-56 p-1 mb-3 border-2 border-custom-purple  focus:border-custom-purplo rounded-lg bg-white">
                    <svg id="mdi-calendar-search" class="h-6 w-6 mr-1 fill-custom-purple" viewBox="0 0 24 24"><path d="M15.5,12C18,12 20,14 20,16.5C20,17.38 19.75,18.21 19.31,18.9L22.39,22L21,23.39L17.88,20.32C17.19,20.75 16.37,21 15.5,21C13,21 11,19 11,16.5C11,14 13,12 15.5,12M15.5,14A2.5,2.5 0 0,0 13,16.5A2.5,2.5 0 0,0 15.5,19A2.5,2.5 0 0,0 18,16.5A2.5,2.5 0 0,0 15.5,14M19,8H5V19H9.5C9.81,19.75 10.26,20.42 10.81,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H6V1H8V3H16V1H18V3H19A2,2 0 0,1 21,5V13.03C20.5,12.22 19.8,11.54 19,11V8Z" /></svg>
                    <form method="GET">
                        <input type="text" id="event-search" name="search" placeholder="Search event..." class="w-full focus:outline-none">
                  </form>
                </div>
            </div>
            <div class="mt-1 mb-5 overflow-x-auto rounded-lg shadow-lg">
                <div class="overflow-x-auto rounded-lg border border-black">
                    <!-- Table of Events -->
                    <table class="w-full px-1 text-center">
                        <?php
                        $sql = "SELECT * FROM `events`"; # Query for showing all events
                        if (isset($_GET['search'])) {
                            $search = '%' . $_GET['search'] . '%';
                            $sql .= " WHERE (`event_id` LIKE ? OR `event_name` LIKE ? OR `event_description` LIKE ? OR `event_date` LIKE ?)"; # Append this if search in URL query is found
                            ?>
                            <script>
                                $("#event-search").val("<?php echo htmlspecialchars($_GET['search']); ?>");
                            </script>
                            <?php
                        }
                        $stmt = $conn->prepare($sql);
                        if (isset($search)) {
                            $stmt->bind_param("ssss", $search, $search, $search, $search);
                        }
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
                                    <th scope="col" class="p-2 border-r border-black">Sanction Fee (₱)</th>
                                    <th scope="col" class="p-2">Actions</th>
                                </tr>
                            </thead>
                            <script>
                                const deleteIds = []; // Declare array to store event-id
                            </script>
                            <?php
                            while($row = $result->fetch_assoc()) {
                                $eid = $row['event_id'];
                                $eventname = $row['event_name'];
                                $eventdesc = $row['event_description'];
                                $eventdate = $row['event_date'];
                                $feeperevent = $row['fee_per_event'];
                                $sanctionfee = $row['sanction_fee'];
                                ?>
                                <tr class="border-t border-black">
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eid; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventname; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventdesc; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $eventdate; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $feeperevent; ?></td>
                                    <td class="px-2 border-r border-black bg-purple-100"><?php echo $sanctionfee; ?></td>
                                    <td class="max-w-56 bg-purple-100">
                                        <a href="eventsregistration.php?event-id=<?php echo $eid ?>"><button class="px-3 py-2 my-1 mx-1 bg-blue-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-blue-400">View</button></a>
                                        <button class="px-4 py-2 my-1 mx-1 bg-yellow-500 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-yellow-400" onclick="editRow(this)">Edit</button>
                                        <form method="POST" class="inline-block mt-1" id="delete-current-<?php echo $eid; ?>"> <!-- Add unique form ID for each event-id  -->
                                            <input type="hidden" name="eid-to-delete" value="<?php echo $eid; ?>">
                                            <button type="button" id="delete-event-<?php echo $eid; ?>" class="px-2 py-2 mb-1 mx-1 bg-red-600 text-white text-sm font-semibold rounded-lg focus:outline-none shadow hover:bg-red-500">Delete</button> <!-- Add unique button ID for each event-id  -->
                                        </form>
                                    </td>
                                </tr>
                                <script>
                                    deleteIds.push("<?php echo $eid; ?>"); // Store event-ids in array for each query is executed
                                </script>
                                <?php
                            }
                        } else {
                            ?><h3 class="p-4">No events found.</h3><?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Darken Background for Modal, hidden by default -->
    <div id="popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <!-- Popup Modal for Adding Events, hidden by default -->
    <div id="popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Add Event</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Event Name:</label>
                    <input type="text" name="event-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <label class="ml-1 text-sm">Event Description:</label>
                    <textarea name="event-desc" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required></textarea>
                    <label class="ml-1 text-sm">Event Date:</label>
                    <input type="date" name="event-date" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <label class="ml-1 text-sm">Fee per event (₱):</label>
                    <input type="number" id="fee-per-event" name="fee-per-event" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <label class="ml-1 text-sm">Sanction Fee (₱):</label>
                    <input type="number" id="sanction-fee" name="sanction-fee" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold hover:bg-custom-purplo" name="add-new-event">Add Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Darken Background for Modal, hidden by default -->
    <div id="edit-popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <!-- Popup Modal for Editing Events, hidden by default -->
    <div id="edit-popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button class="focus:outline-none" id="edit-close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Edit Event</h3>
                <form method="POST">
                    <!--label class="ml-1 text-sm">Event ID:</label-->
                    <input type="hidden" id="edit-event-id" name="edit-event-id">
                    <label class="ml-1 text-sm">Event Name:</label>
                    <input type="text" id="edit-event-name" name="edit-event-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <label class="ml-1 text-sm">Event Description:</label>
                    <textarea id="edit-event-desc" name="edit-event-desc" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required></textarea> 
                    <label class="ml-1 text-sm">Event Date:</label>
                    <input type="date" id="edit-event-date" name="edit-event-date" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 hover:cursor-pointer bg-purple-100" required>
                    <div id="tooltip-content-date" class="absolute whitespace-normal break-words rounded-lg bg-red-500 py-1.5 px-3 font-sans text-xs font-normal text-white shadow shadow-black focus:outline-none">
                        Be careful when changing event dates, as this changes the <br>total fee to be paid with respect to the current date.
                    </div>
                    <label class="ml-1 text-sm">Fee per event (₱):</label>
                    <input type="number" id="edit-fee-per-event" name="edit-fee-per-event" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <label class="ml-1 text-sm">Sanction Fee (₱):</label>
                    <input type="number" id="edit-sanction-fee" name="edit-sanction-fee" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 focus:outline-none focus:border-purple-500 bg-purple-100" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg focus:outline-none focus:border-purple-500 text-base text-white font-bold hover:bg-custom-purplo" name="update-this-event">Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#popup-bg, #popup-item, #edit-popup-bg, #edit-popup-item").removeClass("hidden");
        $("#popup-bg, #popup-item, #edit-popup-bg, #edit-popup-item, #tooltip-content-date").hide();
        // If (+) button is pressed, fade in modals
        $("#add-event").click((event) => {
            $("#popup-bg").fadeIn(150);
            $("#popup-item").delay(150).fadeIn(150);
            $("#close-popup").click((event) => { // If closed, fade out modals
                $("#popup-bg, #popup-item").fadeOut(150);
            });
        });

        // If edit button is pressed, fade in modals
        function editRow(link) {
            $("#edit-popup-bg").fadeIn(150);
            $("#edit-popup-item").delay(150).fadeIn(150);
            $("#edit-close-popup").click((event) => { // If closed, fade out modals
                $("#edit-popup-bg").fadeOut(150);
                $("#edit-popup-item").fadeOut(150);
            });
            let row = link.parentNode.parentNode; // Get table data
            // Transfer table data to input fields
            $("#edit-event-id").val(row.cells[0].innerHTML);
            $("#edit-event-name").val(row.cells[1].innerHTML);
            $("#edit-event-desc").val(row.cells[2].innerHTML);
            $("#edit-event-date").val(row.cells[3].innerHTML);
            $("#edit-fee-per-event").val(row.cells[4].innerHTML);
            $("#edit-sanction-fee").val(row.cells[5].innerHTML);
        }

        $('#edit-event-date').hover(
            () => {
                $('#tooltip-content-date').fadeIn(150);
            }, 
            () => {
                $('#tooltip-content-date').fadeOut(150);
            }
        );

        // Apply deletion of data using event-id to each unique form id and button id
        for (let i=0; i<deleteIds.length; i++) {
            deleteData("#delete-event-" + deleteIds[i], "#delete-current-" + deleteIds[i], "Delete this event?", "This will also delete all registrations made.");
        }
    </script>
    <?php
    // If Add Event is submitted
    if (isset($_POST['add-new-event'])) {
        $eventname = ucwords($_POST['event-name']);
        $eventdesc = $_POST['event-desc'];
        $eventdate = $_POST['event-date'];
        $feeperevent = $_POST['fee-per-event'];
        $sanctionfee = $_POST['sanction-fee'];
        $sql_event = "INSERT INTO `events`(`event_name`, `event_description`, `event_date`, `fee_per_event`, `sanction_fee`) VALUES (?, ?, ?, ?, ?)";
        $stmt_event = $conn->prepare($sql_event);
        $stmt_event->bind_param("sssii", $eventname, $eventdesc, $eventdate, $feeperevent, $sanctionfee);
        if ($stmt_event->execute()) {
            ?>
            <script>
                swal('Event added successfully!', '', 'success')
                .then(() => {
                    window.location.href = 'eventslist.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to add event!', '', 'error');</script>"<?php
        }
    }
    // If Add Update Event is submitted
    if (isset($_POST['update-this-event'])) {
        $eid = str_replace(" ", "", $_POST['edit-event-id']);
        $eventname = ucwords($_POST['edit-event-name']);
        $eventdesc = $_POST['edit-event-desc'];
        $eventdate = $_POST['edit-event-date'];
        $feeperevent = $_POST['edit-fee-per-event'];
        $sanctionfee = $_POST['edit-sanction-fee'];
        $sqlupdate_event = "UPDATE `events` SET `event_name`=?, `event_description`=?, `event_date`=?, `fee_per_event`=?, `sanction_fee`=? WHERE `event_id` = ?";
        $stmt_update_event = $conn->prepare($sqlupdate_event);
        $stmt_update_event->bind_param("sssiii", $eventname, $eventdesc, $eventdate, $feeperevent, $sanctionfee, $eid);
        if ($stmt_update_event->execute()) {
            ?>
            <script>
                swal('Event updated successfully!', '', 'success')
                .then(() => {
                    window.location.href = 'eventslist.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to update event!', '', 'error');</script>"<?php
        }
    }
    // If Delete Event is submitted
    if (isset($_POST['eid-to-delete'])) {
        $sqldelete_reg = "DELETE FROM `registrations` WHERE `event_id` = ?";
        $stmt_delete_reg = $conn->prepare($sqldelete_reg);

        $sqldelete_event = "DELETE FROM `events` WHERE `event_id` = ?";
        $stmt_delete_event = $conn->prepare($sqldelete_event);

        $stmt_delete_reg->bind_param("i", $_POST['eid-to-delete']);
        $stmt_delete_event->bind_param("i", $_POST['eid-to-delete']);

        if ($stmt_delete_reg->execute() && $stmt_delete_event->execute()) {
            ?>
            <script>
                swal('Event successfully deleted', '', 'success')
                .then(() => {
                    window.location.href = "eventslist.php"
                });</script>
            <?php
        } else {
            ?>
            <script>swal('Event deletion failed', '', 'error');</script>
            <?php
        }
    }
    ?>
</body>
</html>