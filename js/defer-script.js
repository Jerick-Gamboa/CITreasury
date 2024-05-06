const dashboardItems = $("#dashboard-items");
dashboardItems.hide();
let isDashboardOpened = false;
$("#dashboard").click((e) => {
    isDashboardOpened = !isDashboardOpened;
    if (isDashboardOpened) {
        dashboardItems.slideDown();
    } else {
        dashboardItems.slideUp();
    }
});

$("#logout").click((e) => {
    swal({
        title: "Are you sure to logout?",
        text: "You can log into your account at anytime.",
        icon: "info",
        buttons: true,
        dangerMode: false,
    }).then((willLogout) => {
        if (willLogout) {
            window.location.href = "../index.html";
        }
    });
});