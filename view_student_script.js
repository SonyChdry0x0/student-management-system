// Handle row selection and update hidden input
document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById('studentsTable');
    const deleteButton = document.getElementById('deleteButton');
    const selectedRollNoInput = document.getElementById('selectedRollNo');
    let selectedRow = null;

    if (table) {
        table.addEventListener('click', function (event) {
            const row = event.target.closest('tr');
            if (row && row.parentNode.tagName === "TBODY") {
                if (selectedRow) {
                    selectedRow.classList.remove('selected-row');
                }
                row.classList.add('selected-row');
                selectedRow = row;
                selectedRollNoInput.value = row.getAttribute('data-roll-no');
                deleteButton.disabled = false;
            }
        });
    }
});
