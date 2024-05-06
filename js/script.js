function sendNotif(title, body, icon, func) {
  new Notification(title,
    {
      body: body,
      icon: icon
    }
  ).addEventListener('click', func);
}

if ('Notification' in window) {
  Notification.requestPermission().then(function(result) {
    if (result === 'granted') {
      sendNotif("Welcome to CITreasury!", "What's up?", "COUNCIL-LOGO-removebg-preview.png", null);
    }
  });
}