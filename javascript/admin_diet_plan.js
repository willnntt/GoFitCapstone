document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    let planId = urlParams.get('id') || null;

    function deleteDietPlan(planId) {
        if (!confirm('Delete this diet plan?')) return;

        window.location.href = `admin_delete_dietplan.php?plan_id=${planId}`;
    }

    function loadDietPlans() {
        const grid = document.querySelector('.photo-grid');
        if (!grid) return;

        fetch('../load_dietplan.php')
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
                        window.location.href = `dietinfo_database.php?plan_id=${plan.plan_id}`;
                    });

                    grid.appendChild(box);
                });
            })
            .catch(err => {
                console.error('Error fetching plans:', err);
            });
    }

    loadDietPlans();
})