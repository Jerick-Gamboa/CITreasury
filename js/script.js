function sendNotif(notif_title, notif_body, notif_icon, notif_func) {
  new Notification(notif_title,
    {
      body: notif_body,
      icon: notif_icon
    }
  ).addEventListener('click', notif_func);
}

if ('Notification' in window) {
  Notification.requestPermission().then(function(result) {
    if (result === 'granted') {
      sendNotif("Welcome to CITreasury!", "What's up?", "COUNCIL-LOGO-removebg-preview.png", null);
    }
  });
}