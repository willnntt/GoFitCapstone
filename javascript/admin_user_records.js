document.addEventListener("DOMContentLoaded", () => {
    const heightInput = document.getElementById("height");
    const weightInput = document.getElementById("weight");
    const goalWeightInput = document.getElementById("goal_weight");
    const bmiInput = document.getElementById("bmi");
    const calorieGoalInput = document.getElementById("calorie_goal");

    if (heightInput && weightInput && goalWeightInput && bmiInput && calorieGoalInput) {
        function recalcValues() {
            const height = parseFloat(heightInput.value);
            const weight = parseFloat(weightInput.value);
            const goalWeight = parseFloat(goalWeightInput.value);

            if (height > 0 && weight > 0) {
                const bmi = (weight / ((height / 100) ** 2)).toFixed(1);
                bmiInput.value = bmi;
            } else {
                bmiInput.value = "";
            }

            if (height > 0 && goalWeight > 0) {
                const bmr = 10 * goalWeight + 6.25 * height - 5 * 25; // fixed age placeholder
                const calorieGoal = Math.round(bmr * 1.2);
                calorieGoalInput.value = calorieGoal;
            } else {
                calorieGoalInput.value = "";
            }
        }

        [heightInput, weightInput, goalWeightInput].forEach(input => {
            input.addEventListener("input", recalcValues);
        });

        recalcValues();
    }

    const userTableBody = document.querySelector('.user-table tbody');

    if (userTableBody) {
        function loadUsers() {
            fetch('admin_load_users.php')
                .then(res => res.json())
                .then(json => {
                    if (!json.success) {
                        alert(json.message || 'Failed to load users');
                        return;
                    }

                    userTableBody.innerHTML = ''; // Clear table

                    json.data.forEach(user => {
                        const tr = document.createElement('tr');

                        tr.innerHTML = `
                            <td>${user.user_id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.gender}</td>
                            <td>${user.birthday}</td>
                            <td>${user.height}</td>
                            <td>${user.weight}</td>
                            <td>${user.bmi}</td>
                            <td>${user.goal_weight}</td>
                            <td>${user.calorie_goal}</td>
                            <td>${user.register_date}</td>
                            <td>
                                <i class="fa-solid fa-pen-to-square edit-user" style="cursor:pointer; margin-right:10px;" data-id="${user.user_id}"></i>
                                <i class="fa-solid fa-trash delete-user" style="cursor:pointer; color:red;" data-id="${user.user_id}"></i>
                            </td>
                        `;

                        userTableBody.appendChild(tr);
                    });

                    attachUserActions();
                })
                .catch(() => {
                    alert('Error fetching users');
                });
        }

        function attachUserActions() {
            document.querySelectorAll('.edit-user').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    window.location.href = `admin_user_record.php?user_id=${id}`;
                });
            });

            document.querySelectorAll('.delete-user').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this user?')) {
                        fetch(`admin_delete_user.php?user_id=${id}`)
                            .then(res => res.json())
                            .then(json => {
                                if (json.success) {
                                    alert('User deleted successfully');
                                    loadUsers();
                                } else {
                                    alert(json.message || 'Failed to delete user');
                                }
                            })
                            .catch(() => {
                                alert('Error deleting user');
                            });
                    }
                });
            });
        }

        loadUsers();
    }
});
