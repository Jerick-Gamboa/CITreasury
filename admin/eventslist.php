<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/tailwind3.4.1.js"></script>
    <script src="../js/tailwind.config.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/apexcharts.js"></script>
    <script src="../js/predefined-script.js"></script>
    <script src="../js/defer-script.js" defer></script>
    <?php
    include '../connection.php';
    ?>
    <title>CITreasury - Events</title>
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
                <button id="add-event" title="Add New Event">
                    <svg id="mdi-plus-circle" class="w-16 h-16 fill-green-500 bg-white hover:fill-green-600 rounded-full shadow-md shadow-gray-500" viewBox="2 2 20 20"><path d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg>
                </button>
            </div>
            <div class="mt-24 flex flex-col lg:flex-row justify-between">
                <h1 class="text-3xl text-custom-purplo font-bold mb-3">Manage Events</h1>
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
                        if (isset($_GET['search'])) {
                            $search = $_GET['search'];
                            $sql .= " WHERE (`event_id` LIKE '%" . $search . "%' OR `event_name` LIKE '%" . $search . "%' OR `event_description` LIKE '%" . $search . "%' OR `event_date` LIKE '%" . $search . "%')";
                            ?>
                            <script>
                                $("#event-search").val("<?php echo $search; ?>");
                            </script>
                            <?php
                        }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            ?>
                            <thead class="text-white uppercase bg-custom-purplo ">
                                <tr>
                                    <th scope="col" class="py-2 border-r border-black">Event ID</th>
                                    <th scope="col" class="py-2 border-r border-black">Event Name</th>
                                    <th scope="col" class="py-2 border-r border-black">Event Description</th>
                                    <th scope="col" class="py-2 border-r border-black">Event Date</th>
                                    <th scope="col" class="py-2">Actions</th>
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
                                ?>
                                <tr class="border-t border-black">
                                    <td class="px-2 border-r border-black"><?php echo $eid; ?></td>
                                    <td class="px-2 border-r border-black"><?php echo $eventname; ?></td>
                                    <td class="px-2 border-r border-black"><?php echo $eventdesc; ?></td>
                                    <td class="px-2 border-r border-black"><?php echo $eventdate; ?></td>
                                    <td class="max-w-56">
                                        <button class="px-4 py-2 my-1 mx-1 bg-yellow-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-yellow-400" onclick="editRow(this)">Edit</button>
                                        <form method="POST" class="inline-block" id="delete-current-<?php echo str_replace(" ", "", $eid) ?>">
                                            <input type="hidden" name="eid-to-delete" value="<?php echo $eid; ?>">
                                            <button type="button" id="delete-events-<?php echo str_replace(" ", "", $eid) ?>" class="px-2 py-2 mb-1 mx-1 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-500">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <script>
                                    deleteIds.push("<?php echo str_replace(" ", "", $eid) ?>");
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
    <div id="popup-bg" class="fixed top-0 w-full min-h-screen bg-black opacity-50 hidden"></div>
    <div id="popup-item" class="fixed top-0 w-full min-h-screen hidden">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="m-5 w-full py-3 px-5 sm:w-1/2 lg:w-1/3 xl:1/4 rounded bg-white h-fit shadow-lg shadow-black">
                <div class="w-full flex justify-end">
                    <button id="close-popup">
                        <svg id="mdi-close-box-outline" class="mt-2 w-6 h-6 hover:fill-red-500" viewBox="0 0 24 24"><path d="M19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V5H19V19M17,8.4L13.4,12L17,15.6L15.6,17L12,13.4L8.4,17L7,15.6L10.6,12L7,8.4L8.4,7L12,10.6L15.6,7L17,8.4Z" /></svg>
                    </button>
                </div>
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Add Event</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Event ID:</label>
                    <input type="text" name="event-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" required>
                    <label class="ml-1 text-sm">Event Name:</label>
                    <input type="text" name="event-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" required>
                    <label class="ml-1 text-sm">Event Description:</label>
                    <input type="text" name="event-desc" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" required>
                    <label class="ml-1 text-sm">Event Date:</label>
                    <input type="date" name="event-date" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg text-base text-white font-bold hover:bg-custom-purplo" name="add-new-event">Add Event</button>
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
                <h3 class="text-2xl font-semibold text-custom-purple mb-3">Edit Event</h3>
                <form method="POST">
                    <label class="ml-1 text-sm">Event ID:</label>
                    <input type="text" id="edit-event-id" name="edit-event-id" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" readonly>
                    <label class="ml-1 text-sm">Event Name:</label>
                    <input type="text" id="edit-event-name" name="edit-event-name" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" required>
                    <label class="ml-1 text-sm">Event Description:</label>
                    <input type="text" id="edit-event-desc" name="edit-event-desc" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" required>
                    <label class="ml-1 text-sm">Event Date:</label>
                    <input type="date" id="edit-event-date" name="edit-event-date" class="w-full px-2 py-1 border-2 border-custom-purple rounded-lg mb-1 bg-purple-100" required>
                    <div class="flex items-center justify-center m-4">
                        <button type="submit" class="px-3 py-2 bg-custom-purple rounded-lg text-base text-white font-bold hover:bg-custom-purplo" name="update-this-event">Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#popup-bg").removeClass("hidden");
        $("#popup-item").removeClass("hidden");
        $("#popup-bg").hide();
        $("#popup-item").hide();
        $("#add-event").click((event) => {
            $("#popup-bg").fadeIn(150);
            $("#popup-item").delay(150).fadeIn(150);
            $("#close-popup").click((event) => {
                $("#popup-bg").fadeOut(150);
                $("#popup-item").fadeOut(150);
            });
        });
        $("#edit-popup-bg").removeClass("hidden");
        $("#edit-popup-item").removeClass("hidden");
        $("#edit-popup-bg").hide();
        $("#edit-popup-item").hide();

        function editRow(link) {
            $("#edit-popup-bg").fadeIn(150);
            $("#edit-popup-item").delay(150).fadeIn(150);
            $("#edit-close-popup").click((event) => {
                $("#edit-popup-bg").fadeOut(150);
                $("#edit-popup-item").fadeOut(150);
            });
            let row = link.parentNode.parentNode;
            $("#edit-event-id").val(row.cells[0].innerHTML);
            $("#edit-event-name").val(row.cells[1].innerHTML);
            $("#edit-event-desc").val(row.cells[2].innerHTML);
            $("#edit-event-date").val(row.cells[3].innerHTML);
        }

        function deleteEvents(button_id, form_id) {
            $(button_id).click((event) => {
                event.preventDefault();
                swal({
                    title: "Are you sure to delete this event?",
                    text: "This action can't be undone.",
                    icon: "info",
                    buttons: true,
                    buttons: {
                        cancel: 'Cancel',
                        confirm : {className:'bg-custom-purple'},
                    },
                    dangerMode: false,
                }).then((willDelete) => {
                    if (willDelete) {
                        $(form_id).submit();
                    }
                });
            });
        }

        for (let i=0; i<deleteIds.length; i++) {
            deleteEvents("#delete-events-" + deleteIds[i], "#delete-current-" + deleteIds[i]);
        }
    </script>
    <?php
    if (isset($_POST['add-new-event'])) {
        $eid = $_POST['event-id'];
        $eventname = ucwords($_POST['event-name']);
        $eventdesc = $_POST['event-desc'];
        $eventdate = $_POST['event-date'];
        $sql_event = "INSERT INTO `events`(`event_id`, `event_name`, `event_description`, `event_date`) VALUES ('$eid', '$eventname', '$eventdesc', '$eventdate')";
        if ($conn->query($sql_event)) {
            ?>
            <script>
                swal('Event added successfully!', '', 'success')
                .then((okay) => {
                    window.location.href = 'eventslist.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to add event!', '', 'error');</script>"<?php
        }
    }
    if (isset($_POST['update-this-event'])) {
        $eid = $_POST['edit-event-id'];
        $eventname = ucwords($_POST['edit-event-name']);
        $eventdesc = $_POST['edit-event-desc'];
        $eventdate = $_POST['edit-event-date'];
        $sqlupdate_event = "UPDATE `events` SET `event_name`='$eventname', `event_description`='$eventdesc', `event_date`='$eventdate' WHERE `event_id` = '$eid'";
        if ($conn->query($sqlupdate_event)) {
            ?>
            <script>
                swal('Event updated successfully!', '', 'success')
                .then((okay) => {
                    window.location.href = 'eventslist.php';
                });
            </script>
            <?php
        } else {
            ?><script>swal('Failed to update event!', '', 'error');</script>"<?php
        }
    }
    if (isset($_POST['eid-to-delete'])) {
        $sqldelete_event = "DELETE FROM `events` WHERE `event_id` = '". $_POST['eid-to-delete'] . "'";
        if ($conn->query($sqldelete_event)) {
            ?>
            <script>swal('Event successfully deleted', '', 'success').then(() => window.location.href = "eventslist.php")</script>
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
