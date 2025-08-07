document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const dayElement = document.getElementById('currentDay');
    const prevDayBtn = document.getElementById('prevDay');
    const nextDayBtn = document.getElementById('nextDay');
    const timerDisplay = document.getElementById('timerDisplay');
    const timerElement = document.getElementById('timer');
    const startSessionBtn = document.getElementById('startSessionBtn');
    const exerciseList = document.getElementById('exerciseList');
    const exerciseLogHeader = document.getElementById('exerciseLogHeader');
    const currentExercises = document.getElementById('currentExercises');
    const exerciseDisplay = document.querySelector('.exercise-display');

    // State Variables
    let currentDay = 1;
    let timerInterval;
    let seconds = 0;
    let isSessionActive = false;
    let loggedExercises = [];

    // Initialize
    updateDayDisplay();
    populateExerciseList();

    // Day Selection (1-31)
    function updateDayDisplay() {
        dayElement.textContent = `Day ${currentDay}`;
    }

    prevDayBtn.addEventListener('click', () => {
        currentDay = currentDay > 1 ? currentDay - 1 : 31;
        updateDayDisplay();
    });

    nextDayBtn.addEventListener('click', () => {
        currentDay = currentDay < 31 ? currentDay + 1 : 1;
        updateDayDisplay();
    });

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
    startSessionBtn.addEventListener('click', function() {
        if (!isSessionActive) {
            // Start new session
            isSessionActive = true;
            this.textContent = 'End Session';
            timerDisplay.style.display = 'block';
            startTimer();
            showPopup();
        } else {
            // End current session
            isSessionActive = false;
            this.textContent = 'Start Session';
            timerDisplay.style.display = 'none';
            stopTimer();
        }
    });

    // Popup Functions
    function showPopup() {
        document.getElementById('popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    // Exercise List
    function populateExerciseList() {
    const exercises = ['Running', 'Arnold Press', 'Push Up', 'Squats', 'Pull Ups'];
    const exerciseList = document.getElementById('exerciseList');
    
    exercises.forEach(exercise => {
        const exerciseOption = document.createElement('div');
        exerciseOption.className = 'exercise-row exercise-option';
        exerciseOption.innerHTML = `
            <div class="exercise-name">${exercise}</div>
            <div class="exercise-action">
                <button class="add-exercise-btn" data-exercise="${exercise}">+</button>
            </div>
        `;
        exerciseList.appendChild(exerciseOption);
    });
}

    // Update Exercise Log Header
    function updateExerciseLog() {
        if (loggedExercises.length > 0) {
            exerciseLogHeader.style.display = 'block';
            currentExercises.innerHTML = '';
            
            loggedExercises.forEach(exercise => {
                const exerciseEl = document.createElement('div');
                exerciseEl.className = 'current-exercise';
                exerciseEl.innerHTML = `
                    <span>${exercise.name}</span>
                    <small>${exercise.sets || 0} sets</small>
                `;
                currentExercises.appendChild(exerciseEl);
            });
        } else {
            exerciseLogHeader.style.display = 'none';
        }
    }

    // Exercise Functions
    function addExercise(name) {
        loggedExercises.push({
            name: name,
            sets: 1
        });
        
        updateExerciseLog();
        
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            exerciseDisplay.removeChild(emptyState);
        }
        
        const wrapper = document.createElement('div');
        wrapper.className = 'exercise-log';
        wrapper.innerHTML = `
            <h3>${name}</h3>
            <div class="exercise-row">
                <label>Set ${loggedExercises.find(e => e.name === name).sets}:</label>
                <label>Weight (lbs):</label><input type="number" placeholder="100" class="exercise-weight" />
                <label>Reps:</label><input type="number" placeholder="10" class="exercise-reps" />
                <button class="add-set-btn" data-exercise="${name}">+ Set</button>
                <input type="checkbox" class="exercise-checkbox" />
                <button class="remove-exercise-btn">×</button>
            </div>
        `;
        
        // Add event listeners
        wrapper.querySelector('.exercise-checkbox').addEventListener('change', function() {
            toggleDone(this);
        });
        
        wrapper.querySelector('.remove-exercise-btn').addEventListener('click', function() {
            removeExercise(this, name);
        });
        
        wrapper.querySelector('.add-set-btn').addEventListener('click', function() {
            addExerciseSet(name);
        });
        
        exerciseDisplay.appendChild(wrapper);
        closePopup();
    }

    function addExerciseSet(name) {
        const exercise = loggedExercises.find(e => e.name === name);
        if (exercise) {
            exercise.sets++;
            updateExerciseLog();
            
            // Add new set inputs
            const newSet = document.createElement('div');
            newSet.className = 'exercise-row';
            newSet.innerHTML = `
                <label>Set ${exercise.sets}:</label>
                <label>Weight (lbs):</label><input type="number" placeholder="100" class="exercise-weight" />
                <label>Reps:</label><input type="number" placeholder="10" class="exercise-reps" />
            `;
            
            const exerciseLog = document.querySelector(`.exercise-log h3:contains("${name}")`)?.parentNode;
            if (exerciseLog) {
                exerciseLog.appendChild(newSet);
            }
        }
    }

    function toggleDone(checkbox) {
        const exerciseLog = checkbox.closest('.exercise-log');
        if (checkbox.checked) {
            exerciseLog.classList.add('checked');
        } else {
            exerciseLog.classList.remove('checked');
        }
    }

    function removeExercise(button, name) {
        loggedExercises = loggedExercises.filter(e => e.name !== name);
        updateExerciseLog();
        
        const exerciseItem = button.closest('.exercise-log');
        exerciseItem.remove();
        
        if (exerciseDisplay.children.length === 0) {
            exerciseDisplay.innerHTML = `
                <div class="empty-state">
                    No exercises added yet
                    <div class="exercise-picture">
                        <img src="exercise-picture.jpg" alt="Exercise illustration">
                    </div>
                </div>
            `;
        }
    }

    // Event delegation for exercise buttons
    exerciseList.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-exercise-btn')) {
            const exerciseName = e.target.getAttribute('data-exercise');
            addExercise(exerciseName);
        }
    });
});