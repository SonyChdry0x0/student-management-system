function toggleCheckboxes() {
    let checkboxes = document.querySelectorAll('input[type="checkbox"]');
    let markAll = document.getElementById('markAll').checked;

    checkboxes.forEach(cb => {
        if (cb !== document.getElementById('markAll')) {
            cb.checked = markAll;
        }
    });
}
