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