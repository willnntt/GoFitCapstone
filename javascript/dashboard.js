function loadCalorieChart(consumed, max, chartId, textId) {
    const remaining = max - consumed; // can be negative
    const textEl = document.getElementById(textId);
    const chartEl = document.getElementById(chartId);

    if (!chartEl || !textEl) {
        console.error(`Donut chart elements not found for IDs: ${chartId}, ${textId}`);
        return;
    }

    // Update text
    textEl.innerHTML = `Remaining<br>${remaining} kcal`;

    if (remaining >= 0) {
        const percent = remaining / max;
        const angle = percent * 360;
        chartEl.className = 'donut-chart donut-normal';
        chartEl.style.setProperty('--angle', `${angle}deg`);
    } else {
        chartEl.className = 'donut-chart donut-overeaten';
        chartEl.style.removeProperty('--angle');
    }
}