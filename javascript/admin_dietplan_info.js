document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    let planId = urlParams.get('plan_id') || null;

    let currentDay = 1;
    let currentMealType = null;

    function fetchFoodData(callback) {
        console.log("Fetching food data...");
        fetch('../user/load_food.php')
            .then(response => response.json())
            .then(data => {
                console.log("Fetched food data:", data);
                foodData = data;
                if (callback) callback();
            })
            .catch(err => {
                console.error("Error fetching foods:", err);
            });
    }

    function openMenu(planId, menuType) {
        document.querySelector('.overlay').style.display = 'block';

        if (menuType === 'edit') {
            document.getElementById('editPlanMenu').style.display = 'block';
            document.getElementById('foodMenu').style.display = 'none';
        }
        else {
            document.getElementById('foodMenu').style.display = 'block';
            document.getElementById('editPlanMenu').style.display = 'none';
            fetchFoodData(() => displayFoodList(foodData, planId));
        }
    }

    function closeMenu() {
        document.querySelector('.overlay').style.display = 'none';
        document.getElementById('foodMenu').style.display = 'none';
        document.getElementById('editPlanMenu').style.display = 'none';
    }

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

    function displayFoodList(foods, planId) {
        console.log("Displaying food list:", foods);
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

                selectFoodItemAdmin(
                    food.name,
                    food.calories,
                    food.food_id,
                    portions,
                    portionLabel,
                    planId
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

    function selectFoodItemAdmin(foodName, caloriesPerPortion, foodId, portions, portionUnit, planId) {
        if (!currentMealType) {
            console.error('Meal type not set before adding food');
            return;
        }

        const totalCal = caloriesPerPortion * portions;

        const tableBody = document.querySelector(`.meal-table-${currentMealType} tbody`);
        if (tableBody) {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${foodName}</td>
            <td>${portions}</td>
            <td>${portionUnit}</td>
            <td>${totalCal}</td>
        `;
            tableBody.appendChild(row);
        }

        fetch('assign_meal_to_plan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                plan_id: planId,
                day_number: currentDay,
                meal_type: currentMealType,
                food_id: foodId,
                amount: portions
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log('Meal assigned successfully');
                    loadDietPlanMeals(planId, currentDay);
                } else {
                    console.error('Failed to assign meal:', data.message);
                }
            })
            .catch(err => {
                console.error('Error assigning meal:', err);
            });
    }

    function loadDietPlanMeals(planId) {
        console.log('Loading meals for plan:', planId, 'Day:', currentDay);
        fetch(`admin_load_dietplan_meal.php?plan_id=${planId}&day_number=${currentDay}`)
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

                // ⬇️ Also set values in the edit form popup
                const nameInput = document.querySelector('#editPlanMenu input[name="name"]');
                const descText = document.querySelector('#editPlanMenu textarea[name="description"]');
                const idInput = document.querySelector('#editPlanMenu input[name="plan_id"]'); // hidden input for ID

                if (nameInput) nameInput.value = data.name || '';
                if (descText) descText.value = data.description || '';
                if (idInput) idInput.value = planId;

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

    function deleteMeal(mealId, rowElement) {
        if (!confirm("Are you sure you want to delete this meal?")) return;

        fetch('admin_dietplan_deletemeal.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `meal_id=${mealId}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Remove row from DOM
                    rowElement.remove();
                } else {
                    alert("Failed to delete: " + (data.error || 'Unknown error'));
                }
            })
            .catch(err => console.error("Error:", err));
    }


    function initializeToggleButtons() {
        document.querySelectorAll('.toggle-button').forEach(button => {
            button.addEventListener('click', function () {
                const meal = this.getAttribute('data-meal');
                toggleTable(meal);
            });
        });
    }

    function initializeFoodMenuButtons() {
        document.querySelectorAll('.openFoodMenu').forEach(button => {
            button.addEventListener('click', function () {
                currentMealType = this.getAttribute('data-meal');
                openMenu(planId, 'food');
                console.log("Selected meal:", currentMealType);
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

    function populateEditForm(data) {
        const form = document.querySelector('.form-box form');
        form.plan_id.value = data.plan_id || data.id || '';
        form.name.value = data.name || '';
        form.description.value = data.description || '';

        const previewImg = document.getElementById('dietImagePreviewImg');
        if (data.image) {
            previewImg.src = data.image;
            previewImg.style.display = 'block';
        } else {
            previewImg.src = '';
            previewImg.style.display = 'none';
        }
    }

    // Upload image preview and button logic
    const fileInput = document.getElementById('dietImageInput');
    const uploadBtn = document.getElementById('uploadImageBtn');
    const previewImg = document.getElementById('dietImagePreviewImg');

    uploadBtn.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    });

    const overlay = document.querySelector('.overlay');
    if (overlay) overlay.addEventListener('click', closeMenu);

    const searchBtn = document.querySelector('.search-bar button');
    if (searchBtn) searchBtn.addEventListener('click', searchFood);

    editBtn.addEventListener('click', () => {
        fetch(`admin_load_dietplan_meal.php?plan_id=${planId}&day_number=1`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                openMenu(planId, 'edit');
                populateEditForm({
                plan_id: planId,
                name: data.name,
                description: data.description,
                image: data.image || '' // if you added image in PHP response
                });
            } else {
                alert('Failed to load plan data');
            }
        });
    });


    const deleteBtn = document.getElementById('deleteBtn');
    if (deleteBtn) deleteBtn.addEventListener('click', () => {
        const planId = deleteBtn.dataset.planId;
        if (!confirm('Delete this diet plan?')) return;
        window.location.href = `admin_delete_dietplan.php?plan_id=${planId}`;
    });

    initializeToggleButtons();
    initializeFoodMenuButtons()
    hideMealTables();
    viewDietInfo();
});