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

                const mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
                totalCalories = 0; // reset before recalculating

                mealTypes.forEach(meal => {
                    const list = document.querySelector(`#${meal}-list ul`);
                    const summary = document.getElementById(`${meal}-summary`);

                    // clear existing list
                    list.innerHTML = '';
                    let mealCal = 0, carbs = 0, protein = 0, fats = 0;

                    (data.data[meal] || []).forEach(item => {
                        const portionCalories = item.calories * item.amount;
                        mealCal += portionCalories;
                        carbs += item.carbs * item.amount;
                        protein += item.protein * item.amount;
                        fats += item.fats * item.amount;

                        const li = document.createElement('li');
                        console.log('ITEM:', item);

                        li.innerHTML = `
                            ${item.name} (${item.brand}): ${item.amount} × ${item.unit} = ${portionCalories} kcal
                            <span class="material-symbols-outlined delete-btn" data-id="${item.id}">delete</span>
                        `;

                        li.classList.add('food-log-item');

                        // attach delete listener
                        li.querySelector('.delete-btn').addEventListener('click', function (event) {
                            event.preventDefault();

                            if (confirm('Delete this log?')) {
                                const logId = this.dataset.id;
                                const url = `delete_meal_log.php?log_id=${logId}`;
                                console.log('Deleting log with ID:', logId);
                                console.log('Fetch URL:', url);

                                fetch(url, { method: 'GET' })
                                    .then(response => {
                                        if (response.ok) {
                                            location.reload();
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

                        list.appendChild(li);
                    });

                    // update total calories
                    totalCalories += mealCal;

                    // update summary
                    summary.innerHTML = `
                    <small>
                        ${mealCal} kcal | 
                        C: ${carbs.toFixed(1)}g 
                        P: ${protein.toFixed(1)}g 
                        F: ${fats.toFixed(1)}g
                    </small>
                `;
                });

                updateChart();
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
