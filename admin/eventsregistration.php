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
            <div class="mt-24 mb-5">
                <div class="w-full">
                  <h1 class="text-3xl text-purple-800 font-bold ">Event Registration</h1>
                </div>
            </div>
            <div class="w-full bg-white p-8 h-fit rounded flex flex-col justify-center items-center">
                <form method="POST" class="w-full flex flex-col justify-center items-center">
                    <fieldset class="w-full border-2 border-black rounded p-3 mb-3">
                        <legend>Event</legend>
                        <div class="flex flex-row mb-3">
                            <label for="event-id" class="w-28 mr-3">Event ID:</label>
                            <select name="event-id" defaultValue="" class="w-full px-2 py-1 border border-black rounded">
                                <option value="" hidden>-</option>
                                <option>INTRAMS</option>
                            </select>
                        </div>
                        <div class="flex flex-row">
                            <label for="event-name" class="w-28 mr-3">Event Name:</label>
                            <input type="text" name="event-name" class="w-full px-2 py-1 border border-black rounded" readonly>
                        </div>
                    </fieldset>
                    <fieldset class="w-full border-2 border-black rounded p-3">
                        <legend>Student</legend>
                        <div class="flex flex-row mb-3">
                            <label for="student-id" class="w-28 mr-3">Student ID:</label>
                            <input type="text" name="student-id" class="w-full px-2 py-1 border border-black rounded">
                        </div>
                        <div class="flex flex-row">
                            <label for="student-name" class="w-28 mr-3">Student Name:</label>
                            <input type="text" name="student-name" class="w-full px-2 py-1 border border-black rounded" readonly>
                        </div>
                    </fieldset>
                    <fieldset class="w-full border-2 border-black rounded p-3">
                        <legend>Fees</legend>
                        <div class="flex flex-row mb-3">
                            <label for="student-id" class="w-28 mr-3">Total Fees:</label>
                            <input type="text" name="student-id" class="w-full px-2 py-1 border border-black rounded">
                        </div>
                        <div class="flex flex-row mb-3">
                            <label for="student-name" class="w-28 mr-3">Advance Fee:</label>
                            <input type="text" name="student-name" class="w-full px-2 py-1 border border-black rounded" readonly>
                        </div>
                        <div class="flex flex-row">
                            <label for="student-name" class="w-28 mr-3">Balance:</label>
                            <input type="text" name="student-name" class="w-full px-2 py-1 border border-black rounded" readonly>
                        </div>
                    </fieldset>
                    <div class="mt-3">
                        <button type="submit" class="px-5 py-2 my-2 mx-1 bg-green-600 text-white rounded hover:bg-green-500">Register to Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
