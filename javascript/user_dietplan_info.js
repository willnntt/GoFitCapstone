document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    let planId = urlParams.get('plan_id') || null;

    let currentDay = 1;

    function viewDietInfo() {
        const dayDisplay = document.querySelector('.day-selector span');
        const prevDayBtn = document.getElementById('prevDay');
        const nextDayBtn = document.getElementById('nextDay');

        if (planId) {
            console.log("Loading diet plan for ID:", planId);
            // Pagination buttons for next and previous days
            nextDayBtn.addEventListener('click', () => {
                if (currentDay < 7) currentDay++;
                updateDayDisplay(dayDisplay);
                loadDietPlanMeals(planId, currentDay);
            });

            prevDayBtn.addEventListener('click', () => {
                if (currentDay > 1) currentDay--;
                updateDayDisplay(dayDisplay);
                loadDietPlanMeals(planId, currentDay);
            });

            loadDietPlanMeals(planId);
        }
    }

    function loadDietPlanMeals(planId) {
        console.log('Loading meals for plan:', planId, 'Day:', currentDay);
        fetch(`../load_dietplan_meal.php?plan_id=${planId}&day_number=${currentDay}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to load meals:', data.message);
                    return;
                }

                // Update description and plan name on the page
                const descBox = document.querySelector('.description-text');
                const planNameText = document.querySelector('.plan-name-text');
                const imagePreview = document.getElementById('dietImagePreview');
                const pictureBox = document.querySelector('.picture-box');

                if (descBox) {
                    descBox.textContent = data.description || 'No description provided.';
                }

                if (planNameText) {
                    planNameText.textContent = data.name || 'Diet Plan Name';
                }

                if (data.image) {
                    imagePreview.src = data.image;
                    pictureBox.classList.add('has-image');
                } else {
                    imagePreview.src = "";
                    pictureBox.classList.remove('has-image');
                }
                console.log('Image URL set to:', data.image);


                // Load meals into the tables
                const meals = data.data;
                const mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];

                mealTypes.forEach(type => {
                    const table = document.getElementById(`food-table-${type}`);
                    if (!table) return;

                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = ''; // Clear previous

                    (meals[type] || []).forEach(meal => {
                        const row = document.createElement('tr');
                        row.setAttribute('data-meal-id', meal.id);

                        row.innerHTML = `
                        <td>${meal.food}</td>
                        <td>${meal.brand || 'No Brand'}</td>
                        <td>${meal.portion}</td>
                        <td>${meal.serving}</td>
                        <td>${meal.calories}</td>
                        <td>${meal.carbs}</td>
                        <td>${meal.protein}</td>
                        <td>${meal.fats}</td>
                        <td class="actions">
                            <i class="fa-solid fa-trash delete-meal"></i>
                        </td>
                    `;

                        row.querySelector('.delete-meal').addEventListener('click', () => {
                            deleteMeal(meal.id, row);
                        });

                        tbody.appendChild(row);
                    });
                });
            })
            .catch(err => {
                console.error('Fetch error:', err);
            });
    }


    function initializeToggleButtons() {
        document.querySelectorAll('.toggle-button').forEach(button => {
            button.addEventListener('click', function () {
                const meal = this.getAttribute('data-meal');
                toggleTable(meal);
            });
        });
    }

    function hideMealTables() {
        document.querySelectorAll('.food-table').forEach(table => {
            table.style.display = 'none';
        });
    }

    function toggleTable(meal) {
        const table = document.getElementById(`food-table-${meal}`);
        const icon = document.getElementById(`toggle-icon-${meal}`);

        if (table.style.display === "none") {
            table.style.display = "table";
            icon.classList.remove("fa-chevron-down");
            icon.classList.add("fa-chevron-up");
        } else {
            table.style.display = "none";
            icon.classList.remove("fa-chevron-up");
            icon.classList.add("fa-chevron-down");
        }
    }

    function updateDayDisplay(dayDisplay) {
        dayDisplay.textContent = `Day ${currentDay}`;
    }


    initializeToggleButtons();
    hideMealTables();
    viewDietInfo();
});

// Accept Plan
document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    let planId = urlParams.get('plan_id') || null;
    const acceptBtn = document.querySelector(".plan-btn");

    let planAccepted = false;

    acceptBtn.addEventListener("click", () => {
        const startDate = new Date().toISOString().split('T')[0];

        if (!planAccepted) {
            // Accept plan
            fetch("user_update_plan.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=accept&plan_id=${planId}&start_date=${startDate}`
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        planAccepted = true;
                        acceptBtn.classList.remove('accept-btn');
                        acceptBtn.classList.add('cancel-btn');
                        acceptBtn.textContent = 'Cancel Plan';
                    }
                });
        } else {
            // Cancel plan
            fetch("update_plan.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=cancel&plan_id=${planId}`
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        planAccepted = false;
                        acceptBtn.classList.remove('cancel-btn');
                        acceptBtn.classList.add('accept-btn');
                        acceptBtn.textContent = 'Accept Plan';
                    }
                });
        }
    });
});