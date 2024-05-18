function sendNotif(notif_title, notif_body, notif_icon, notif_clicked_function) {
  if ('Notification' in window) {
    Notification.requestPermission().then(function(result) {
      if (result === 'granted') {
        new Notification(notif_title,
          {
            body: notif_body,
            icon: notif_icon
          }
        ).addEventListener('click', notif_clicked_function);
      }
    });
  }
}

function deleteData(button_id, form_id, title_text, desc_text) {
  $(button_id).click((event) => {
      event.preventDefault(); // Prevent from submitting the form
      swal({
          title: title_text,
          text: desc_text,
          icon: "warning",
          buttons: true,
          buttons: {
              cancel: 'No',
              confirm : {text: "Yes", className:'bg-custom-purple'},
          },
          dangerMode: true,
      }).then((willDelete) => {
          if (willDelete) {
              $(form_id).submit(); // Submit form if pressed Yes
          }
      });
  });
}