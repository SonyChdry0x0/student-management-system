
document.getElementById("select-all").addEventListener("change", function () {
  const checkboxes = document.querySelectorAll("input[name='selected[]']");
  checkboxes.forEach(cb => cb.checked = this.checked);
});


document.querySelector('input[name="delete_attendance"]').addEventListener('click', function(event) {
  event.preventDefault();

  const selected = document.querySelectorAll("input[name='selected[]']:checked");
  if(selected.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'No records selected',
      text: 'Please select at least one attendance record to delete.',
    });
    return;
  }

  Swal.fire({
    title: 'Are you sure?',
    text: "This action will delete the selected attendance records!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete them!',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      // Submit the form that contains this button
      if (event.target.form) {
        event.target.form.submit();
      } else {
        console.error("Form not found for delete button");
      }
    }
  });
});




window.addEventListener('DOMContentLoaded', () => {
  const msgBox = document.getElementById('message-box');
  if (msgBox) {
    setTimeout(() => {
      msgBox.style.transition = "opacity 0.5s ease";
      msgBox.style.opacity = '0';
      setTimeout(() => { msgBox.style.display = 'none'; }, 500);
    }, 10000);
  }
});
