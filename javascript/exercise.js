document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const timerDisplay = document.getElementById('timerDisplay');
    const timerElement = document.getElementById('timer');
    const startSessionBtn = document.getElementById('startSessionBtn');
    const addExerciseBtn = document.getElementById('addExerciseBtn');
    const exerciseList = document.getElementById('exerciseList');
    const exerciseLogHeader = document.getElementById('exerciseLogHeader');
    const currentExercises = document.getElementById('currentExercises');
    const exerciseDisplay = document.querySelector('.exercise-display');
    const dayHeader = document.querySelector('.day-header h2');
    const leftArrow = document.querySelector('.day-header .arrow-btn:first-child');
    const rightArrow = document.querySelector('.day-header .arrow-btn:last-child');

    // State Variables
    let timerInterval;
    let seconds = 0;
    let isSessionActive = false;
    let loggedExercises = [];
    let currentDate = new Date().toISOString().split('T')[0]; // YYYY-MM-DD
    let availableExercises = [];

    // Initialize
    fetchExercises();
    loadExercisesForDate();
    updateDateDisplay();
    setupDateNavigation();

    // Date Navigation Functions
    function setupDateNavigation() {
        leftArrow.addEventListener('click', function () {
            changeDate(-1);
        });

        rightArrow.addEventListener('click', function () {
            changeDate(1);
        });
    }

    function isAerobicExercise(exerciseId) {
        const exercise = availableExercises.find(e => e.exercise_id == exerciseId);
        return exercise && exercise.category.toLowerCase() === 'aerobic';
    }

    function changeDate(direction) {
        const date = new Date(currentDate);
        date.setDate(date.getDate() + direction);
        currentDate = date.toISOString().split('T')[0];
        updateDateDisplay();
        loadExercisesForDate();

        // Disable session controls for past/future dates
        const today = new Date().toISOString().split('T')[0];
        const isToday = currentDate === today;

        startSessionBtn.style.visibility = isToday ? 'visible' : 'hidden';
        if (!isToday && isSessionActive) {
            endSession();
        }
    }

    function updateDateDisplay() {
        const date = new Date(currentDate);
        const options = { month: 'long', day: 'numeric' };
        dayHeader.textContent = date.toLocaleDateString('en-US', options);
    }

    // Timer Functions
    function formatTime(totalSeconds) {
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const secs = totalSeconds % 60;

        return [
            hours.toString().padStart(2, '0'),
            minutes.toString().padStart(2, '0'),
            secs.toString().padStart(2, '0')
        ].join(':');
    }

    function startTimer() {
        timerInterval = setInterval(() => {
            seconds++;
            timerElement.textContent = formatTime(seconds);
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
        seconds = 0;
        timerElement.textContent = formatTime(seconds);
    }

    // Session Control
    function endSession() {
        isSessionActive = false;
        startSessionBtn.textContent = 'Start Session';
        timerDisplay.style.display = 'none';
        stopTimer();
        hideAddExercise();
    }

    startSessionBtn.addEventListener('click', function () {
        if (!isSessionActive) {
            // Start new session
            isSessionActive = true;
            this.textContent = 'End Session';
            timerDisplay.style.display = 'block';
            startTimer();
            showAddExercise();
        } else {
            endSession();
        }
    });

    // Popup Functions
    function showAddExercise() {
        addExerciseBtn.style.visibility = 'visible';
    }

    function hideAddExercise() {
        addExerciseBtn.style.visibility = 'hidden';
    }

    addExerciseBtn.addEventListener('click', function () {
        showExercisePopup();
    });

    function showExercisePopup() {
        document.getElementById('popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function closeExercisePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    document.getElementById('overlay').addEventListener('click', function () {
        closeExercisePopup();
    });

    // User Feedback Functions
    function showSuccess(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'feedback-message success';
        successDiv.textContent = message;
        successDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            font-weight: bold;
            animation: slideIn 0.3s ease-out;
        `;

        document.body.appendChild(successDiv);

        setTimeout(() => {
            successDiv.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                if (successDiv.parentNode) {
                    successDiv.parentNode.removeChild(successDiv);
                }
            }, 300);
        }, 3000);
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'feedback-message error';
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            font-weight: bold;
            animation: slideIn 0.3s ease-out;
        `;

        document.body.appendChild(errorDiv);

        setTimeout(() => {
            errorDiv.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.parentNode.removeChild(errorDiv);
                }
            }, 300);
        }, 4000);
    }

    // Fetch exercises from database
    function fetchExercises() {
        console.log('Fetching exercises...');

        fetch('get_exercises.php')
            .then(response => {
                console.log(`Response:`, response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Exercises received:', data);
                if (data.error) {
                    throw new Error(data.error);
                }
                availableExercises = data;
                populateExerciseList(data);
            })
            .catch(error => {
                console.log(`Path failed:`, error.message);
            });

    }

    function useFallbackExercises() {
        const fallbackExercises = [
            { exercise_id: 1, name: 'Push-ups', category: 'Strength', difficulty: 'Beginner' },
            { exercise_id: 2, name: 'Squats', category: 'Legs', difficulty: 'Beginner' },
            { exercise_id: 3, name: 'Running', category: 'Cardio', difficulty: 'Intermediate' },
            { exercise_id: 4, name: 'Pull-ups', category: 'Strength', difficulty: 'Advanced' }
        ];
        availableExercises = fallbackExercises;
        populateExerciseList(fallbackExercises);
    }

    // Exercise List
    function populateExerciseList(exercises) {
        const exerciseList = document.getElementById('exerciseList');
        exerciseList.innerHTML = '';

        if (!Array.isArray(exercises) || exercises.length === 0) {
            console.error('No exercises received or invalid format');
            useFallbackExercises();
            return;
        }

        exercises.forEach(exercise => {
            const exerciseOption = document.createElement('div');
            exerciseOption.className = 'exercise-row exercise-option';
            exerciseOption.innerHTML = `
                <div class="exercise-name">
                    <strong>${exercise.name}</strong>
                    <small style="display: block; color: #666;">${exercise.category || ''} - ${exercise.difficulty || ''}</small>
                </div>
                <div class="exercise-action">
                    <button class="add-exercise-btn" 
                            data-exercise-id="${exercise.exercise_id}" 
                            data-exercise-name="${exercise.name}">
                        +
                    </button>
                </div>
            `;
            exerciseList.appendChild(exerciseOption);
        });
    }

    // Load exercises for specific date
    function loadExercisesForDate() {
        console.log(`Loading exercises for date: ${currentDate}`);

        fetch(`get_exercises_log.php?date=${currentDate}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                loggedExercises = Array.isArray(data) ? data : [];
                updateExerciseLog();
                renderExerciseLogs();
            })
            .catch(error => {
                console.log(`Exercise log path failed:`, error.message);
            });
    }

    // Update Exercise Log Header
    function updateExerciseLog() {
        const uniqueExercises = {};

        // Group exercises by name and sum sets
        loggedExercises.forEach(exercise => {
            const name = exercise.exercise_name;
            if (uniqueExercises[name]) {
                uniqueExercises[name].totalSets += parseInt(exercise.sets || 0);
            } else {
                uniqueExercises[name] = {
                    name: name,
                    totalSets: parseInt(exercise.sets || 0)
                };
            }
        });

        if (Object.keys(uniqueExercises).length > 0) {
            if (exerciseLogHeader) {
                exerciseLogHeader.style.display = 'block';
                if (currentExercises) {
                    currentExercises.innerHTML = '';

                    Object.values(uniqueExercises).forEach(exercise => {
                        const exerciseEl = document.createElement('div');
                        exerciseEl.className = 'current-exercise';
                        exerciseEl.innerHTML = `
                            <span>${exercise.name}</span>
                            <small>${exercise.totalSets} sets</small>
                        `;
                        currentExercises.appendChild(exerciseEl);
                    });
                }
            }
        } else {
            if (exerciseLogHeader) {
                exerciseLogHeader.style.display = 'none';
            }
        }
    }

    // Render exercise logs
    function renderExerciseLogs() {
        exerciseDisplay.innerHTML = '';

        if (loggedExercises.length === 0) {
            const isToday = currentDate === new Date().toISOString().split('T')[0];
            const message = isToday ? 'No exercises added yet!' : 'No exercises logged for this date';

            exerciseDisplay.innerHTML = `
            <div class="empty-state">
                ${message}
                <div class="exercise-picture">
                    <i class="fas fa-dumbbell" style="font-size: 80px; color: #ddd; margin: 20px;"></i>
                </div>
            </div>
        `;
            return;
        }

        // Group exercises by exercise_id and exercise_name
        const groupedExercises = {};
        loggedExercises.forEach(exercise => {
            const key = `${exercise.exercise_id}_${exercise.exercise_name}`;
            if (!groupedExercises[key]) {
                // Check if this exercise is aerobic
                const isAerobic = isAerobicExercise(exercise.exercise_id);
                console.log(`Exercise ${exercise.exercise_name} (ID: ${exercise.exercise_id}) is aerobic: ${isAerobic}`);

                groupedExercises[key] = {
                    exercise_id: exercise.exercise_id,
                    exercise_name: exercise.exercise_name,
                    is_aerobic: isAerobic,
                    logs: []
                };
            }
            groupedExercises[key].logs.push(exercise);
        });

        Object.values(groupedExercises).forEach(exerciseGroup => {
            const wrapper = document.createElement('div');
            wrapper.className = 'exercise-log';

            exerciseGroup.logs.sort((a, b) => a.log_id - b.log_id);

            let setCounter = 1;
            let setsHtml = '';
            exerciseGroup.logs.forEach(log => {
                if (exerciseGroup.is_aerobic) {
                    // Display distance and duration for aerobic exercises
                    // Convert duration from HH:MM:SS to just MM:SS for the input
                    let durationValue = log.duration || '00:00:00';
                    if (durationValue.includes(':')) {
                        const parts = durationValue.split(':');
                        if (parts.length === 3) {
                            // Extract minutes and seconds, ignore hours
                            durationValue = `${parts[1]}:${parts[2]}`;
                        }
                    }

                    setsHtml += `
                    <div class="exercise-row aerobic-row" data-log-id="${log.log_id}" data-exercise-id="${exerciseGroup.exercise_id}">
                        <span class="set-label">Lap ${setCounter}:</span>
                        <div class="input-group">
                            <label>Distance (km)</label>
                            <input type="number" placeholder="0" step="0.1" class="exercise-distance" value="${log.distance || ''}" 
                                   onchange="updateExerciseLog(${log.log_id}, 'distance', this.value)" />
                        </div>
                        <div class="input-group">
                            <label>Duration</label>
                            <input type="text" class="exercise-duration" value="${durationValue}" 
                                   onchange="formatAndUpdateDuration(${log.log_id}, this.value)" 
                                   placeholder="MM:SS" />
                        </div>
                        <button class="remove-set-btn" data-log-id="${log.log_id}">×</button>
                    </div>
                `;
                setCounter++;
                } else {
                    // Display sets/reps/weight for strength exercises
                    setsHtml += `
                    <div class="exercise-row strength-row" data-log-id="${log.log_id}" data-exercise-id="${exerciseGroup.exercise_id}">
                        <span class="set-label">Set ${setCounter}:</span>
                        <div class="input-group">
                            <label>Weight (kg)</label>
                            <input type="number" placeholder="0" class="exercise-weight" value="${log.weight || ''}" 
                                   onchange="updateExerciseLog(${log.log_id}, 'weight', this.value)" />
                        </div>
                        <div class="input-group">
                            <label>Reps</label>
                            <input type="number" placeholder="0" class="exercise-reps" value="${log.reps || ''}" 
                                   onchange="updateExerciseLog(${log.log_id}, 'reps', this.value)" />
                        </div>
                        <button class="remove-set-btn" data-log-id="${log.log_id}">×</button>
                    </div>
                `;
                setCounter++;
                }
            });

            wrapper.innerHTML = `
            <h3>${exerciseGroup.exercise_name}</h3>
            ${setsHtml}
            <div class="exercise-controls">
                <button class="add-set-btn" data-exercise-id="${exerciseGroup.exercise_id}" data-is-aerobic="${exerciseGroup.is_aerobic}">+ Add ${exerciseGroup.is_aerobic ? 'Lap' : 'Set'}</button>
                <button class="remove-exercise-btn" data-exercise-id="${exerciseGroup.exercise_id}">Remove Exercise</button>
            </div>
        `;

            exerciseDisplay.appendChild(wrapper);
        });

        // Add event listeners for the new buttons
        addExerciseControlListeners();
    }


    function addExerciseControlListeners() {
        // Add set/session buttons
        document.querySelectorAll('.add-set-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const exerciseId = this.getAttribute('data-exercise-id');
                const isAerobic = this.getAttribute('data-is-aerobic') === 'true';
                addExerciseSet(exerciseId, isAerobic);
            });
        });

        // Remove set buttons
        document.querySelectorAll('.remove-set-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const logId = this.getAttribute('data-log-id');
                removeExerciseSet(logId);
            });
        });

        // Remove exercise buttons
        document.querySelectorAll('.remove-exercise-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const exerciseId = this.getAttribute('data-exercise-id');
                removeEntireExercise(exerciseId);
            });
        });
    }

    function formatAndUpdateDuration(logId, value) {
        // Convert MM:SS to HH:MM:SS format
        if (value.includes(':')) {
            const parts = value.split(':');
            if (parts.length === 2) {
                const minutes = parseInt(parts[0]) || 0;
                const seconds = parseInt(parts[1]) || 0;
                const hours = Math.floor(minutes / 60);
                const remainingMinutes = minutes % 60;

                const formattedDuration = `${hours.toString().padStart(2, '0')}:${remainingMinutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                updateExerciseLog(logId, 'duration', formattedDuration);
                return;
            }
        }

        // If format is invalid, just pass the value as is
        updateExerciseLog(logId, 'duration', value);
    }

    // Add exercise to log
    function addExercise(exerciseId, exerciseName) {
        console.log(`Adding exercise: ${exerciseName} (ID: ${exerciseId})`);

        const isAerobic = isAerobicExercise(exerciseId);

        // Show loading state
        const addBtn = document.querySelector(`[data-exercise-id="${exerciseId}"]`);
        if (addBtn) {
            addBtn.textContent = '...';
            addBtn.disabled = true;
        }

        const formData = new FormData();
        formData.append('exercise_id', exerciseId);
        formData.append('date', currentDate);

        if (isAerobic) {
            // For aerobic exercises, add distance and duration
            formData.append('distance', 0);
            formData.append('duration', '00:00:00');
        } else {
            // For strength exercises, add sets/reps/weight
            formData.append('sets', 1);
            formData.append('reps', 0);
            formData.append('weight', 0);
        }

        const path = 'log_exercise.php';
        fetch(path, {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccess(`${exerciseName} added successfully!`);
                    loadExercisesForDate();
                    closeExercisePopup();
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                console.log(`Log exercise path failed:`, error.message);
            })
            .finally(() => {
                if (addBtn) {
                    addBtn.textContent = '+';
                    addBtn.disabled = false;
                }
            });
    }

    // Add exercise set
    function addExerciseSet(exerciseId, isAerobic) {
        const formData = new FormData();
        formData.append('exercise_id', exerciseId);
        formData.append('date', currentDate);

        if (isAerobic) {
            formData.append('distance', 0);
            formData.append('duration', '00:00:00');
            formData.append('sets', nextSetNumber);
        } else {
            // Count existing sets from DOM
            const existingSets = document.querySelectorAll(
                `.exercise-row[data-exercise-id="${exerciseId}"]`
            ).length;

            const nextSetNumber = existingSets + 1;
            formData.append('sets', nextSetNumber);
            formData.append('reps', 0);
            formData.append('weight', 0);
        }

        fetch('log_exercise.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(isAerobic ? 'Session added!' : 'Set added!');
                    loadExercisesForDate();
                } else {
                    showError('Error adding ' + (isAerobic ? 'session' : 'set') + ': ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Error adding ' + (isAerobic ? 'session' : 'set') + '. Please try again.');
            });
    }

    // Remove single exercise set
    function removeExerciseSet(logId) {
        if (!confirm('Remove this set?')) return;

        fetch(`delete_exercise.php?log_id=${logId}`, {
            method: 'DELETE'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Set removed!');
                    loadExercisesForDate();
                } else {
                    showError('Error removing set: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Error removing set. Please try again.');
            });
    }

    // Remove entire exercise (all sets)
    function removeEntireExercise(exerciseId) {
        const exerciseName = loggedExercises.find(e => e.exercise_id == exerciseId)?.exercise_name || 'exercise';

        if (!confirm(`Remove all sets of ${exerciseName}?`)) return;

        // Find all log IDs for this exercise
        const logIds = loggedExercises
            .filter(e => e.exercise_id == exerciseId)
            .map(e => e.log_id);

        // Remove each set
        Promise.all(logIds.map(logId =>
            fetch(`delete_exercise.php?log_id=${logId}`, { method: 'DELETE' })
                .then(response => response.json())
        ))
            .then(results => {
                const allSuccessful = results.every(result => result.success);
                if (allSuccessful) {
                    showSuccess(`${exerciseName} removed completely!`);
                    loadExercisesForDate();
                } else {
                    showError('Some sets could not be removed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Error removing exercise. Please try again.');
            });
    }

    // Event delegation for exercise buttons
    exerciseList.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-exercise-btn')) {
            const exerciseId = e.target.getAttribute('data-exercise-id');
            const exerciseName = e.target.getAttribute('data-exercise-name');
            addExercise(exerciseId, exerciseName);
        }
    });

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .exercise-controls {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        
        .add-set-btn, .remove-exercise-btn, .remove-set-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
        }
        
        .add-set-btn {
            background-color: #26e1ee;
            color: white;
        }
        
        .add-set-btn:hover {
            background-color: #0126f7;
        }
        
        .remove-exercise-btn, .remove-set-btn {
            background-color: #ff6b6b;
            color: white;
        }
        
        .remove-set-btn:hover {
            background-color: #ff0000;
        }
        
        .remove-set-btn {
            padding: 4px 8px;
            font-size: 14px;
        }
    `;
    document.head.appendChild(style);
});

// Global function for updating exercise logs
function updateExerciseLog(logId, field, value) {
    const formData = new FormData();
    formData.append('log_id', logId);
    formData.append('field', field);
    formData.append('value', value);

    fetch('update_exercise.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error updating exercise:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}