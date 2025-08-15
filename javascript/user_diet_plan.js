document.addEventListener('DOMContentLoaded', () => {

    function loadDietPlans() {
        const container = document.querySelector('.scroll-container');
        if (!container) return;

        fetch('../load_dietplan.php')
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to load diet plans:', data.message);
                    return;
                }

                container.innerHTML = '';

                data.data.forEach((plan, index) => {
                    const card = document.createElement('div');
                    card.classList.add('plan-card');

                    card.innerHTML = `
                        <img src="${plan.image}" alt="${plan.name}">
                    `;

                    card.addEventListener('click', () => {
                        const focusedCard = document.querySelector('.focused-card');
                        if (!focusedCard) return;

                        document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('active'));
                        card.classList.add('active');

                        // Update name
                        focusedCard.querySelector('h3').textContent = plan.name;

                        // Update description
                        focusedCard.querySelector('p').textContent = plan.description;

                        // Update "View Plan" link
                        focusedCard.querySelector('.view-plan').setAttribute(
                            'href',
                            `diet_plan_info.php?plan_id=${plan.plan_id}`
                        );
                    });

                    container.appendChild(card);

                     // Auto-select the first plan
                    if (index === 0) {
                        setTimeout(() => card.click(), 0); // triggers the click event
                    }
                });

                // Dynamically toggle "few-items" class
                if (data.data.length > 0 && data.data.length < 4) {
                    container.classList.add('few-items');
                } else {
                    container.classList.remove('few-items');
                }
            })
            .catch(err => {
                console.error('Error fetching plans:', err);
            });
    }

    loadDietPlans();
});