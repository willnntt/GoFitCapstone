document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle button
    const sidebarToggleBtn = document.querySelector('#sidebarToggle');
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', toggleSidebar);
    }

    // Modal close button
    const closeBtn = document.getElementById('closeModalBtn');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // Save button inside modal
    const saveBtn = document.getElementById('saveBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', saveChange);
    }

    // Add event listeners to all .edit-button elements (or whatever class you use)
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function () {
            const field = this.dataset.field;
            const oldValue = this.dataset.old || '';
            openModal(field, oldValue);
        });
    });
});

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
}

let currentField = '';

function openModal(field, oldValue) {
    currentField = field;
    document.getElementById('oldInfo').innerText = "Old Info: " + (oldValue || '');

    const inputWrapper = document.getElementById('inputWrapper');

    if (field === 'age') {
        inputWrapper.innerHTML = `
            <input type="date" id="newInfo" placeholder="Select Date">
        `;
    } else if (field === 'gender') {
        inputWrapper.innerHTML = `
            <select id="newInfo">
                <option value="Male" ${oldValue === 'Male' ? 'selected' : ''}>Male</option>
                <option value="Female" ${oldValue === 'Female' ? 'selected' : ''}>Female</option>
            </select>
        `;
    } else {
        inputWrapper.innerHTML = `
            <input type="text" id="newInfo" placeholder="Input New Info">
        `;
    }

    document.getElementById('changeModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('changeModal').style.display = 'none';
}

function saveChange() {
    const newValue = document.getElementById('newInfo').value;
    if (newValue.trim() === '') {
        alert("Please enter a value.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_setting.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            location.reload();
        } else {
            alert('Error updating data.');
        }
    };
    xhr.send(`field=${currentField}&value=${encodeURIComponent(newValue)}`);

    closeModal();
}