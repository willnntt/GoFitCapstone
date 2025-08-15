document.addEventListener('DOMContentLoaded', () => {
    let foodData = [];

    let totalCalories = 0;
    let maxCalories = window.calorie_goal;

    function fetchFoodData(callback) {
        fetch('../load_food.php')
            .then(response => response.json())
            .then(data => {
                foodData = data;
                if (callback) callback();
            })
            .catch(err => {
                console.error("Error fetching foods:", err);
            });
    }

    function openMenu(mealTime) {
        document.querySelector('.overlay').style.display = 'block';
        document.getElementById('foodMenu').style.display = 'block';
        fetchFoodData(() => displayFoodList(foodData, mealTime));
    }

    function closeMenu() {
        document.querySelector('.overlay').style.display = 'none';
        document.getElementById('foodMenu').style.display = 'none';
    }

    function displayFoodList(foods, mealTime) {
        const listContainer = document.getElementById('foodScrollList');
        listContainer.innerHTML = '';

        foods.forEach(food => {
            const foodDiv = document.createElement('div');
            foodDiv.className = 'food-item';

            const portionLabel = food.portion_unit && food.portion_unit.trim() !== ''
                ? food.portion_unit
                : '1 portion';

            foodDiv.innerHTML = `
            <div class="food-info">
                <strong>${food.name}</strong> - ${food.calories} kcal per ${portionLabel}, ${food.brand ?? 'No Brand'}
            </div>
            <div class="food-dropdown" style="display: none;">
                <p><strong>Nutrition per ${portionLabel}:</strong><br>
                Carbs: ${food.carbs}g, Protein: ${food.protein}g, Fats: ${food.fats}g</p>

                <label for="portion-${food.food_id}">Number of portions:</label>
                <input type="number" id="portion-${food.food_id}" placeholder="e.g. 1" min="1" />
                <button class="log-btn" disabled>Add</button>
            </div>
        `;

            const infoDiv = foodDiv.querySelector('.food-info');
            const dropdown = foodDiv.querySelector('.food-dropdown');
            const portionInput = foodDiv.querySelector('input');
            const addBtn = foodDiv.querySelector('button');

            // Toggle dropdown
            infoDiv.addEventListener('click', () => {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });

            // Enable Add button only with valid input
            portionInput.addEventListener('input', () => {
                addBtn.disabled = !(parseInt(portionInput.value) > 0);
            });

            addBtn.addEventListener('click', () => {
                const portions = parseInt(portionInput.value);
                if (isNaN(portions) || portions <= 0) return;

                selectFoodItem(
                    food.name,
                    food.calories,
                    food.food_id,
                    portions,
                    portionLabel,
                    mealTime
                );
            });

            listContainer.appendChild(foodDiv);
        });
    }

    function searchFood() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const filtered = foodData.filter(f =>
            f.name.toLowerCase().includes(query)
        );
        displayFoodList(filtered);
    }

    function selectFoodItem(foodName, caloriesPerPortion, foodId, portions, portionUnit, meal) {
        const ul = document.querySelector(`.meal-section[data-meal="${meal}"] .meal-food-list`);
        if (!ul) {
            console.error(`No meal section found for ${meal}`);
            return;
        }

        const totalCal = caloriesPerPortion * portions;
        const li = document.createElement('li');
        li.textContent = `${foodName} - ${portions} × ${portionUnit} = ${totalCal} kcal`;
        ul.appendChild(li);

        totalCalories += totalCal;
        updateChart();
        closeMenu();

        fetch('log_meal.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                food_id: foodId,
                meal_type: meal,
                amount: portions
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log('Meal logged successfully');
                    loadMealLogs();
                } else {
                    console.error('Failed to log meal:', data.message);
                }
            })
            .catch(err => {
                console.error('Error logging meal:', err);
            });
    }

    function loadMealLogs() {
        console.log('loadMealLogs() was called');
        fetch('get_meal_log.php')
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to load meals:', data.message);
                    return;
                }

                const mealTypes = ['breakfast', 'lunch', 'dinner', 'snack'];
                totalCalories = 0; // reset total calories

                mealTypes.forEach(meal => {
                    const mealSection = document.querySelector(`.meal-section[data-meal="${meal}"] .meal-food-list`);

                    // clear existing logs
                    mealSection.innerHTML = '';

                    (data.data[meal] || []).forEach(item => {
                        const portionCalories = item.calories * item.amount;
                        totalCalories += portionCalories;

                        const li = document.createElement('li');
                        li.classList.add('food-log-item');
                        li.innerHTML = `
                        ${item.name} ${item.brand ? `(${item.brand})` : ''}: 
                        ${item.amount} × ${item.unit} = ${portionCalories} kcal
                        <i class="fa-solid fa-trash delete-btn" style="cursor:pointer; color:red;" data-id="${item.id}"></i>
                    `;

                        // Delete button listener
                        li.querySelector('.delete-btn').addEventListener('click', function (event) {
                            event.preventDefault();

                            if (confirm('Delete this log?')) {
                                const logId = this.dataset.id;
                                const url = `delete_meal_log.php?log_id=${logId}`;

                                fetch(url, { method: 'GET' })
                                    .then(response => {
                                        if (response.ok) {
                                            loadMealLogs();
                                        } else {
                                            alert('Failed to delete log.');
                                        }
                                    })
                                    .catch(err => {
                                        console.error('Fetch error:', err);
                                        alert('Something went wrong.');
                                    });
                            }
                        });

                        mealSection.appendChild(li);
                    });
                });

                updateChart();
            })
            .catch(err => {
                console.error('Error fetching meal log:', err);
            });
    }

    function updateChart() {
        const consumed = totalCalories; // allow values above max
        const remaining = maxCalories - consumed; // can be negative

        document.getElementById('remaining').innerHTML = 
            `Remaining<br>${remaining} kcal`;

        const chart = document.getElementById('donut');

        if (remaining >= 0) {
            // Normal case
            const percent = remaining / maxCalories;
            const angle = percent * 360;
            chart.style.background = `conic-gradient(#c8aaf5 0deg ${angle}deg, #eee ${angle}deg 360deg)`;
        } else {
            // Overeaten case
            chart.style.background = `conic-gradient(#ff5e5e 0deg 360deg, #eee 0deg 0deg)`;
        }
    }

    function clearAllMeals() {
        ['breakfast', 'lunch', 'dinner'].forEach(meal => {
            const ul = document.querySelector(`#${meal}-list ul`);
            ul.innerHTML = '';
        });

        totalCalories = 0;
        updateChart();
    }

    // Toggle dropdown
    document.querySelectorAll(".meal-header").forEach(header => {
        header.addEventListener("click", (e) => {
            // Prevent triggering when clicking the + button
            if (e.target.classList.contains("openFoodMenu")) return;

            const section = header.parentElement;
            const foodList = section.querySelector(".meal-food-list");
            const arrow = section.querySelector(".fa-chevron-up, .fa-chevron-down");

            const isOpen = foodList.style.display === "block";

            // Toggle visibility
            foodList.style.display = isOpen ? "none" : "block";

            // Swap chevron classes
            if (isOpen) {
                arrow.classList.remove("fa-chevron-up");
                arrow.classList.add("fa-chevron-down");
            } else {
                arrow.classList.remove("fa-chevron-down");
                arrow.classList.add("fa-chevron-up");
            }
        });
    });

    document.querySelectorAll(".openFoodMenu").forEach(btn => {
        btn.addEventListener("click", () => {
            const meal = btn.dataset.meal;
            console.log(`Add food for ${meal}`);
            openMenu(meal);
        });
    });

    document.querySelector('.overlay')?.addEventListener('click', closeMenu);
    document.querySelector('.clear-btn')?.addEventListener('click', clearAllMeals);
    document.querySelector('.search-bar button')?.addEventListener('click', searchFood);

    updateChart();
    loadMealLogs();
});
