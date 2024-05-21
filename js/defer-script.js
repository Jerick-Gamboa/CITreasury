menuContent = `
<ul>
    <li class="text-white font-bold">
        <a href="index.php" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-view-dashboard-outline" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M19,5V7H15V5H19M9,5V11H5V5H9M19,13V19H15V13H19M9,17V19H5V17H9M21,3H13V9H21V3M11,3H3V13H11V3M21,11H13V21H21V11M11,15H3V21H11V15Z" />
            </svg>
            Dashboard
        </a>
        <!--ul class="pl-6 py-2" id="dashboard-items">
            <li>
                <a href="#" id="modify-item" class="hover:text-purple-200 flex items-center align-middle">
                    <svg id="mdi-pencil" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                        <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                    </svg>
                    Modify Item
                </a>
            </li>
        </ul -->
    </li>
    <li class="text-white font-bold">
        <a href="students.php" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-account-outline" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M12,13C14.67,13 20,14.33 20,17V20H4V17C4,14.33 9.33,13 12,13M12,14.9C9.03,14.9 5.9,16.36 5.9,17V18.1H18.1V17C18.1,16.36 14.97,14.9 12,14.9Z" />
            </svg>
            Students
        </a>
    </li>
    <li class="text-white font-bold">
        <a href="eventslist.php" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-calendar" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                    <path d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z" />
            </svg>
            Events List
        </a>
    </li>
    <li class="text-white font-bold">
        <a href="eventsregistration.php" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-plus-box-outline" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M19,19V5H5V19H19M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5C3,3.89 3.9,3 5,3H19M11,7H13V11H17V13H13V17H11V13H7V11H11V7Z" />
            </svg>
            Events Registration
        </a>
    </li>
    <li class="text-white font-bold">
        <a href="sanctions.php" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-close-circle-outline" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2C6.47,2 2,6.47 2,12C2,17.53 6.47,22 12,22C17.53,22 22,17.53 22,12C22,6.47 17.53,2 12,2M14.59,8L12,10.59L9.41,8L8,9.41L10.59,12L8,14.59L9.41,16L12,13.41L14.59,16L16,14.59L13.41,12L16,9.41L14.59,8Z" />
            </svg>
            Sanctions
        </a>
    </li>
    <li class="text-white font-bold">
        <a href="accountsettings.php" id="account-set" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-cogs"class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M15.9,18.45C17.25,18.45 18.35,17.35 18.35,16C18.35,14.65 17.25,13.55 15.9,13.55C14.54,13.55 13.45,14.65 13.45,16C13.45,17.35 14.54,18.45 15.9,18.45M21.1,16.68L22.58,17.84C22.71,17.95 22.75,18.13 22.66,18.29L21.26,20.71C21.17,20.86 21,20.92 20.83,20.86L19.09,20.16C18.73,20.44 18.33,20.67 17.91,20.85L17.64,22.7C17.62,22.87 17.47,23 17.3,23H14.5C14.32,23 14.18,22.87 14.15,22.7L13.89,20.85C13.46,20.67 13.07,20.44 12.71,20.16L10.96,20.86C10.81,20.92 10.62,20.86 10.54,20.71L9.14,18.29C9.05,18.13 9.09,17.95 9.22,17.84L10.7,16.68L10.65,16L10.7,15.31L9.22,14.16C9.09,14.05 9.05,13.86 9.14,13.71L10.54,11.29C10.62,11.13 10.81,11.07 10.96,11.13L12.71,11.84C13.07,11.56 13.46,11.32 13.89,11.15L14.15,9.29C14.18,9.13 14.32,9 14.5,9H17.3C17.47,9 17.62,9.13 17.64,9.29L17.91,11.15C18.33,11.32 18.73,11.56 19.09,11.84L20.83,11.13C21,11.07 21.17,11.13 21.26,11.29L22.66,13.71C22.75,13.86 22.71,14.05 22.58,14.16L21.1,15.31L21.15,16L21.1,16.68M6.69,8.07C7.56,8.07 8.26,7.37 8.26,6.5C8.26,5.63 7.56,4.92 6.69,4.92A1.58,1.58 0 0,0 5.11,6.5C5.11,7.37 5.82,8.07 6.69,8.07M10.03,6.94L11,7.68C11.07,7.75 11.09,7.87 11.03,7.97L10.13,9.53C10.08,9.63 9.96,9.67 9.86,9.63L8.74,9.18L8,9.62L7.81,10.81C7.79,10.92 7.7,11 7.59,11H5.79C5.67,11 5.58,10.92 5.56,10.81L5.4,9.62L4.64,9.18L3.5,9.63C3.41,9.67 3.3,9.63 3.24,9.53L2.34,7.97C2.28,7.87 2.31,7.75 2.39,7.68L3.34,6.94L3.31,6.5L3.34,6.06L2.39,5.32C2.31,5.25 2.28,5.13 2.34,5.03L3.24,3.47C3.3,3.37 3.41,3.33 3.5,3.37L4.63,3.82L5.4,3.38L5.56,2.19C5.58,2.08 5.67,2 5.79,2H7.59C7.7,2 7.79,2.08 7.81,2.19L8,3.38L8.74,3.82L9.86,3.37C9.96,3.33 10.08,3.37 10.13,3.47L11.03,5.03C11.09,5.13 11.07,5.25 11,5.32L10.03,6.06L10.06,6.5L10.03,6.94Z" />
            </svg>
            Account Settings
        </a>
        <!-- ul class="pl-6 py-2" id="account-set-items">
            <li>
                <a href="#" class="hover:text-purple-200 flex items-center align-middle">
                    <svg id="mdi-pencil" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                        <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                    </svg>
                    Modify Item
                </a>
                <a href="#" id="modify-item" class="mt-2 hover:text-purple-200 flex items-center align-middle">
                    <svg id="mdi-pencil" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                        <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                    </svg>
                    Change Password
                </a>
            </li>
        </ul -->
    </li>
    <li class="text-white font-bold">
        <a href="#" id="logout" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-logout-variant" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M14.08,15.59L16.67,13H7V11H16.67L14.08,8.41L15.5,7L20.5,12L15.5,17L14.08,15.59M19,3A2,2 0 0,1 21,5V9.67L19,7.67V5H5V19H19V16.33L21,14.33V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19Z" />
            </svg>
            Logout
        </a>
    </li>
</ul>
`;

menuUserContent = `
<ul>
    <li class="text-white font-bold">
        <a href="index.php" id="dashboard" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-view-dashboard-outline" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M19,5V7H15V5H19M9,5V11H5V5H9M19,13V19H15V13H19M9,17V19H5V17H9M21,3H13V9H21V3M11,3H3V13H11V3M21,11H13V21H21V11M11,15H3V21H11V15Z" />
            </svg>
            Dashboard
        </a>
        <!--ul class="pl-6 py-2" id="dashboard-items">
            <li>
                <a href="#" id="modify-item" class="hover:text-purple-200 flex items-center align-middle">
                    <svg id="mdi-pencil" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                        <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                    </svg>
                    Modify Item
                </a>
            </li>
        </ul -->
    </li>
    <li class="text-white font-bold">
        <a href="sanctions.php" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-close-circle-outline" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2C6.47,2 2,6.47 2,12C2,17.53 6.47,22 12,22C17.53,22 22,17.53 22,12C22,6.47 17.53,2 12,2M14.59,8L12,10.59L9.41,8L8,9.41L10.59,12L8,14.59L9.41,16L12,13.41L14.59,16L16,14.59L13.41,12L16,9.41L14.59,8Z" />
            </svg>
            Sanctions
        </a>
    </li>
    <li class="text-white font-bold">
        <a href="accountsettings.php" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-cogs"class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M15.9,18.45C17.25,18.45 18.35,17.35 18.35,16C18.35,14.65 17.25,13.55 15.9,13.55C14.54,13.55 13.45,14.65 13.45,16C13.45,17.35 14.54,18.45 15.9,18.45M21.1,16.68L22.58,17.84C22.71,17.95 22.75,18.13 22.66,18.29L21.26,20.71C21.17,20.86 21,20.92 20.83,20.86L19.09,20.16C18.73,20.44 18.33,20.67 17.91,20.85L17.64,22.7C17.62,22.87 17.47,23 17.3,23H14.5C14.32,23 14.18,22.87 14.15,22.7L13.89,20.85C13.46,20.67 13.07,20.44 12.71,20.16L10.96,20.86C10.81,20.92 10.62,20.86 10.54,20.71L9.14,18.29C9.05,18.13 9.09,17.95 9.22,17.84L10.7,16.68L10.65,16L10.7,15.31L9.22,14.16C9.09,14.05 9.05,13.86 9.14,13.71L10.54,11.29C10.62,11.13 10.81,11.07 10.96,11.13L12.71,11.84C13.07,11.56 13.46,11.32 13.89,11.15L14.15,9.29C14.18,9.13 14.32,9 14.5,9H17.3C17.47,9 17.62,9.13 17.64,9.29L17.91,11.15C18.33,11.32 18.73,11.56 19.09,11.84L20.83,11.13C21,11.07 21.17,11.13 21.26,11.29L22.66,13.71C22.75,13.86 22.71,14.05 22.58,14.16L21.1,15.31L21.15,16L21.1,16.68M6.69,8.07C7.56,8.07 8.26,7.37 8.26,6.5C8.26,5.63 7.56,4.92 6.69,4.92A1.58,1.58 0 0,0 5.11,6.5C5.11,7.37 5.82,8.07 6.69,8.07M10.03,6.94L11,7.68C11.07,7.75 11.09,7.87 11.03,7.97L10.13,9.53C10.08,9.63 9.96,9.67 9.86,9.63L8.74,9.18L8,9.62L7.81,10.81C7.79,10.92 7.7,11 7.59,11H5.79C5.67,11 5.58,10.92 5.56,10.81L5.4,9.62L4.64,9.18L3.5,9.63C3.41,9.67 3.3,9.63 3.24,9.53L2.34,7.97C2.28,7.87 2.31,7.75 2.39,7.68L3.34,6.94L3.31,6.5L3.34,6.06L2.39,5.32C2.31,5.25 2.28,5.13 2.34,5.03L3.24,3.47C3.3,3.37 3.41,3.33 3.5,3.37L4.63,3.82L5.4,3.38L5.56,2.19C5.58,2.08 5.67,2 5.79,2H7.59C7.7,2 7.79,2.08 7.81,2.19L8,3.38L8.74,3.82L9.86,3.37C9.96,3.33 10.08,3.37 10.13,3.47L11.03,5.03C11.09,5.13 11.07,5.25 11,5.32L10.03,6.06L10.06,6.5L10.03,6.94Z" />
            </svg>
            Account Settings
        </a>
    </li>
    <li class="text-white font-bold">
        <a href="#" id="logout" class="py-2 px-2 rounded transition-all duration-300-ease-in-out hover:text-custom-purplo hover:bg-white flex items-center align-middle">
            <svg id="mdi-logout-variant" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 24 24">
                <path d="M14.08,15.59L16.67,13H7V11H16.67L14.08,8.41L15.5,7L20.5,12L15.5,17L14.08,15.59M19,3A2,2 0 0,1 21,5V9.67L19,7.67V5H5V19H19V16.33L21,14.33V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19Z" />
            </svg>
            Logout
        </a>
    </li>
</ul>
`;

$("#menu-items").html(menuContent);
$("#menu-items-mobile").html(menuContent);

$("#menu-user-items").html(menuUserContent);
$("#menu-user-items-mobile").html(menuUserContent);

function clickToShowHide(button_id, target_show_hide) {
    const target = $(target_show_hide);
    target.hide();
    let isOpened = false;
    $(button_id).click((e) => {
        isOpened = !isOpened;
        if (isOpened) {
            target.slideDown();
        } else {
            target.slideUp();
        }
    });
}

clickToShowHide("#mdi-menu", "#menu-items-mobile");
clickToShowHide("#mdi-menu", "#menu-user-items-mobile");
clickToShowHide("#menu-items #account-set", "#menu-items #account-set-items");
clickToShowHide("#menu-items-mobile #account-set", "#menu-items-mobile #account-set-items");

function logoutAction(button_id) {
    $(button_id).click((e) => {
        swal({
            title: "Are you sure to logout?",
            text: "You can log into your account at anytime.",
            icon: "info",
            buttons: true,
            buttons: {
                cancel: 'No',
                confirm : {text: "Yes", className:'bg-custom-purple'},
            },
            dangerMode: false,
        }).then((willLogout) => {
            if (willLogout) {
                window.location.href = "../logout.php";
            }
        });
    });
}

logoutAction("#menu-items-mobile #logout");
logoutAction("#menu-items #logout");
logoutAction("#menu-user-items-mobile #logout");
logoutAction("#menu-user-items #logout");