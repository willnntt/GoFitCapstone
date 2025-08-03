document.addEventListener('DOMContentLoaded', () => {
    let foodData = [];

    let totalCalories = 0;
    const maxCalories = 2000;

    function fetchFoodData(callback) {
        fetch('user_load_food.php')
            .then(response => response.json())
            .then(data => {
                foodData = data;
                if (callback) callback();
            })
            .catch(err => {
                console.error("Error fetching foods:", err);
            });
    }

    function openMenu() {
        document.querySelector('.overlay').style.display = 'block';
        document.getElementById('foodMenu').style.display = 'block';
        fetchFoodData(() => displayFoodList(foodData));
    }

    function closeMenu() {
        document.querySelector('.overlay').style.display = 'none';
        document.getElementById('foodMenu').style.display = 'none';
    }

    function toggleList(meal) {
        const list = document.getElementById(`${meal}-list`);
        list.style.display = list.style.display === 'block' ? 'none' : 'block';
    }

    function displayFoodList(foods) {
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

            // Add food to log
            addBtn.addEventListener('click', () => {
                const portions = parseInt(portionInput.value);
                if (isNaN(portions) || portions <= 0) return;

                selectFoodItem(
                    food.name,
                    food.calories,
                    food.food_id,
                    portions,
                    portionLabel
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

    function selectFoodItem(foodName, caloriesPerPortion, foodId, portions, portionUnit) {
        const meal = document.getElementById('mealSelect').value;
        const ul = document.querySelector(`#${meal}-list ul`);
        const totalCal = caloriesPerPortion * portions;

        const li = document.createElement('li');
        li.textContent = `${foodName} - ${portions} × ${portionUnit} = ${totalCal} kcal`;
        ul.appendChild(li);

        totalCalories += totalCal;
        updateChart();
        closeMenu();

        fetch('log_meal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
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
                } else {
                    console.error('Failed to log meal:', data.message);
                }
            })
            .catch(err => {
                console.error('Error logging meal:', err);
            });
    }

    function loadMealLogs() {
        fetch('get_meal_log.php')
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to load meals:', data.message);
                    return;
                }

                const meals = data.data;

                Object.entries(meals).forEach(([mealType, entries]) => {
                    const listEl = document.querySelector(`#${mealType}-list ul`);
                    const summaryEl = document.querySelector(`#${mealType}-summary`);

                    listEl.innerHTML = '';
                    let totalCals = 0, totalCarbs = 0, totalProtein = 0, totalFats = 0;

                    entries.forEach(entry => {
                        const { name, amount, unit, calories, carbs, protein, fats } = entry;
                        const totalCal = calories * amount;
                        const li = document.createElement('li');
                        li.textContent = `${name} - ${amount} × ${unit} = ${totalCal} kcal`;
                        listEl.appendChild(li);

                        totalCals += calories * amount;
                        totalCarbs += carbs * amount;
                        totalProtein += protein * amount;
                        totalFats += fats * amount;
                    });

                    summaryEl.innerHTML = `
                        <strong>Total:</strong> ${totalCals.toFixed(0)} kcal,
                        <strong>Carbs:</strong> ${totalCarbs.toFixed(1)}g,
                        <strong>Protein:</strong> ${totalProtein.toFixed(1)}g,
                        <strong>Fats:</strong> ${totalFats.toFixed(1)}g
                    `;
                });
            })
            .catch(err => {
                console.error('Error fetching meal log:', err);
            });
    }

    function updateChart() {
        const consumed = Math.min(totalCalories, maxCalories);
        const remaining = Math.max(0, maxCalories - consumed);
        const percent = remaining / maxCalories;
        const angle = percent * 360;

        document.getElementById('remaining').innerHTML = `Remaining<br>${remaining} kcal`;

        const chart = document.getElementById('donut');
        chart.style.background = `conic-gradient(#c8aaf5 0deg ${angle}deg, #eee ${angle}deg 360deg)`;
    }

    function clearAllMeals() {
        ['breakfast', 'lunch', 'dinner'].forEach(meal => {
            const ul = document.querySelector(`#${meal}-list ul`);
            ul.innerHTML = '';
        });

        totalCalories = 0;
        updateChart();
    }

    document.querySelector('.add-btn')?.addEventListener('click', openMenu);
    document.querySelector('.overlay')?.addEventListener('click', closeMenu);
    document.querySelector('.clear-btn')?.addEventListener('click', clearAllMeals);
    document.querySelector('.search-bar button')?.addEventListener('click', searchFood);

    document.querySelector('.meal:nth-of-type(3)')?.addEventListener('click', () => toggleList('breakfast'));
    document.querySelector('.meal:nth-of-type(4)')?.addEventListener('click', () => toggleList('lunch'));
    document.querySelector('.meal:nth-of-type(5)')?.addEventListener('click', () => toggleList('dinner'));

    updateChart();
    loadMealLogs();
});
