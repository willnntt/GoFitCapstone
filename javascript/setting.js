document.addEventListener('DOMContentLoaded', function () {
    // Modal close button
    const closeBtn = document.getElementById('cancelBtn');
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

    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = document.getElementById(icon.getAttribute('data-target'));
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
});

let currentField = '';

function openModal(field, oldValue) {
    currentField = field;

    const activityLabels = {
        sedentary: 'Sedentary (little or no exercise)',
        light: 'Lightly active (1-3 days/week)',
        moderate: 'Moderately active (3-5 days/week)',
        active: 'Active (6-7 days/week)',
        very_active: 'Very active (twice per day)'
    };

    const weightChangeLabels = {
        '0.25': '0.25 kg/week',
        '0.5': '0.5 kg/week',
        '0.75': '0.75 kg/week',
        '1': '1 kg/week'
    };

    // Always stringify oldValue for comparison in selects
    let oldValueStr = oldValue !== null && oldValue !== undefined ? String(oldValue) : '';
    let displayValue = oldValueStr;

    if (field === 'activity_level' && activityLabels[oldValueStr]) {
        displayValue = activityLabels[oldValueStr];
    } else if (field === 'weight_change' && weightChangeLabels[oldValueStr]) {
        displayValue = weightChangeLabels[oldValueStr];
    }

    document.getElementById('oldInfo').innerText = "Old Info: " + (displayValue || '');

    const inputWrapper = document.getElementById('inputWrapper');

    if (field === 'dob') {
        inputWrapper.innerHTML = `
            <input type="date" id="newInfo" value="${oldValueStr}">
        `;
    } 
    else if (field === 'gender') {
        inputWrapper.innerHTML = `
            <select id="newInfo">
                <option value="Male" ${oldValueStr === 'Male' ? 'selected' : ''}>Male</option>
                <option value="Female" ${oldValueStr === 'Female' ? 'selected' : ''}>Female</option>
            </select>
        `;
    } 
    else if (field === 'email') {
        inputWrapper.innerHTML = `
            <input type="email" id="newInfo" value="${oldValueStr}" placeholder="email@domain.com">
        `;
    } 
    else if (field === 'activity_level') {
        inputWrapper.innerHTML = `
            <select id="newInfo">
                <option value="sedentary" ${oldValueStr === 'sedentary' ? 'selected' : ''}>${activityLabels.sedentary}</option>
                <option value="light" ${oldValueStr === 'light' ? 'selected' : ''}>${activityLabels.light}</option>
                <option value="moderate" ${oldValueStr === 'moderate' ? 'selected' : ''}>${activityLabels.moderate}</option>
                <option value="active" ${oldValueStr === 'active' ? 'selected' : ''}>${activityLabels.active}</option>
                <option value="very_active" ${oldValueStr === 'very_active' ? 'selected' : ''}>${activityLabels.very_active}</option>
            </select>
        `;
    } 
    else if (field === 'weight_change') {
        inputWrapper.innerHTML = `
            <select id="newInfo">
                <option value="0.25" ${oldValueStr == 0.25 ? 'selected' : ''}>0.25 kg/week</option>
                <option value="0.5" ${oldValueStr == 0.5 ? 'selected' : ''}>0.5 kg/week</option>
                <option value="0.75" ${oldValueStr == 0.75 ? 'selected' : ''}>0.75 kg/week</option>
                <option value="1" ${oldValueStr == 1 ? 'selected' : ''}>1 kg/week</option>
            </select>
        `;
    } 
    else if (field === 'password') {
        inputWrapper.innerHTML = `
            <input type="password" id="newInfo" placeholder="New Password">
        `;
    } 
    else {
        inputWrapper.innerHTML = `
            <input type="text" id="newInfo" value="${oldValueStr}" placeholder="Input New Info">
        `;
    }

    document.getElementById('changeModal').style.display = 'block';
}



function closeModal() {
    document.getElementById('changeModal').style.display = 'none';
}

function saveChange() {
    const newValue = document.getElementById('newInfo').value.trim();

    if (newValue === '') {
        alert("Please enter a value.");
        return;
    }

    // Email validation
    if (currentField === 'email') {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(newValue)) {
            alert("Please enter a valid email address.");
            return;
        }
    }

    // Date of birth validation
    if (currentField === 'dob') {
        const selectedDate = new Date(newValue);
        const today = new Date();

        if (isNaN(selectedDate.getTime())) {
            alert("Please select a valid date.");
            return;
        }

        if (selectedDate > today) {
            alert("Date of birth cannot be in the future.");
            return;
        }
    }

    // Send via AJAX
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
