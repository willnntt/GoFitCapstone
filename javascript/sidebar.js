document.querySelector('.menu-toggle').addEventListener('click', () => {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    sidebar.classList.toggle('collapsed');
    if (sidebar.classList.contains('collapsed')) {
        mainContent.style.marginLeft = '0';
    } else {
        mainContent.style.marginLeft = '80px';
    }
});