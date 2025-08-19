document.addEventListener('DOMContentLoaded', () => {
    function loadExercises() {
        fetch('admin_load_exercises.php')
            .then(res => res.json())
            .then(json => {
                if (!json.success) {
                    alert(json.message || 'Failed to load exercises');
                    return;
                }

                const tbody = document.querySelector('#exerciseTable tbody');
                tbody.innerHTML = ''; // Clear existing rows

                json.data.forEach(exercise => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                <td>${exercise.exercise_id}</td>
                <td>${exercise.name}</td>
                <td>${exercise.category}</td>
                <td>${exercise.difficulty}</td>
                <td>
                    <a href="admin_exercise_record.php?exercise_id=${exercise.exercise_id}"><i class="fa-solid fa-pen-to-square edit-exercise" style="cursor:pointer; margin-right:10px;"></i></a>
                    
                    <i class="fa-solid fa-trash delete-exercise" style="cursor:pointer; color:red;" data-id="${exercise.exercise_id}"></i>
                </td>
                `;

                    tbody.appendChild(tr);
                });

                document.querySelectorAll('.delete-exercise').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const exerciseId = btn.getAttribute('data-id');
                        if (confirm('Are you sure you want to delete this exercise?')) {
                            fetch(`admin_delete_exercise.php?exercise_id=${exerciseId}`)
                            .then(res => res.json())
                            .then(json => {
                                if (json.success) {
                                    loadExercises(); // Reload exercises after deletion
                                } else {
                                    alert(json.message || 'Failed to delete exercise');
                                } 
                            })
                            .catch(() => alert('Error deleting exercise'));
                        }
                    });
                });
            })
            .catch(() => alert('Error loading exercises'));
    }

    loadExercises();
});