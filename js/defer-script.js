menuContent = `
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
// clickToShowHide("#menu-items #dashboard", "#menu-items #dashboard-items");
// clickToShowHide("#menu-items-mobile #dashboard", "#menu-items-mobile #dashboard-items");

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