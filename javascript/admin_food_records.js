document.addEventListener('DOMContentLoaded', () => {
    const foodTableBody = document.querySelector('#foodTable tbody');

    function loadFoods() {
        fetch('admin_load_food.php')
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to load foods');
                    return;
                }

                // Clear table body
                foodTableBody.innerHTML = '';

                data.foods.forEach(food => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
          <td>${food.food_id}</td>
          <td>${food.name}</td>
          <td>${food.brand}</td>
          <td>${food.calories}</td>
          <td>${food.portion_unit}</td>
          <td>${food.carbs}</td>
          <td>${food.protein}</td>
          <td>${food.fats}</td>
          <td>
            <i class="fa-solid fa-pen-to-square edit-food-btn" style="cursor:pointer; margin-right:10px;" data-id="${food.food_id}"></i>
            <i class="fa-solid fa-trash delete-food-btn" style="cursor:pointer; color:red;" data-id="${food.food_id}"></i>
          </td>
        `;

                    foodTableBody.appendChild(tr);
                });

                attachFoodListeners();
            })
            .catch(err => {
                alert('Error loading foods');
                console.error(err);
            });
    }

    function attachFoodListeners() {
        document.querySelectorAll('.edit-food-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                location.href = `admin_food_record.php?food_id=${id}`;
            });
        });

        document.querySelectorAll('.delete-food-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this food item?')) {
                    fetch(`admin_delete_food.php?food_id=${id}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Food deleted');
                                loadFoods();
                            } else {
                                alert(data.message || 'Failed to delete food');
                            }
                        })
                        .catch(() => alert('Error deleting food'));
                }
            });
        });
    }

    loadFoods();
});