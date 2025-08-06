document.addEventListener('DOMContentLoaded', function () {
    initializeToggleButtons();
    hideMealTables();
    loadDietPlansToGrid();
    viewDietInfo();
});


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


function viewDietInfo() {
    const urlParams = new URLSearchParams(window.location.search);
    const planId = urlParams.get('id');

    if (planId) {
        loadDayOneMeals(planId);
    }
}


function loadDayOneMeals(planId) {
    fetch(`admin_load_dietplan_meal.php?plan_id=${planId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to load meals:', data.message);
                return;
            }

            const meals = data.data;
            const mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];

            mealTypes.forEach(type => {
                const table = document.getElementById(`food-table-${type}`);
                if (!table) return;

                const tbody = table.querySelector('tbody');
                tbody.innerHTML = ''; // Clear previous rows

                (meals[type] || []).forEach(meal => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${meal.food}</td>
                        <td>${meal.portion}</td>
                        <td>${meal.serving}</td>
                        <td>${meal.calories}</td>
                        <td>${meal.carbs}</td>
                        <td>${meal.protein}</td>
                        <td>${meal.fats}</td>
                        <td class="actions">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <i class="fa-solid fa-trash"></i>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });
}


function loadDietPlansToGrid() {
    const grid = document.querySelector('.photo-grid');
    if (!grid) return;

    fetch('admin_load_dietplan.php')
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to load diet plans:', data.message);
                return;
            }

            grid.innerHTML = ''; // clear existing content

            data.data.forEach(plan => {
                const box = document.createElement('div');
                box.classList.add('photo-box');
                box.innerHTML = `
                    <img src="${plan.image}" alt="${plan.name}" class="photo-image">
                    <div class="photo-label">${plan.name}</div>
                `;

                box.addEventListener('click', () => {
                    window.location.href = `dietinfo_database.php?id=${plan.plan_id}`;
                });

                grid.appendChild(box);
            });
        })
        .catch(err => {
            console.error('Error fetching plans:', err);
        });
}
