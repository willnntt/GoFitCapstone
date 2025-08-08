const menuToggle = document.querySelector('.menu-toggle');
const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');
const arrowToggle = document.querySelector('.sidebar-toggle-arrow');

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    const isCollapsed = sidebar.classList.contains('collapsed');

    mainContent.style.marginLeft = isCollapsed ? '0' : '80px';
    arrowToggle.style.display = isCollapsed ? 'block' : 'none';
});

arrowToggle.addEventListener('click', () => {
    sidebar.classList.remove('collapsed');
    mainContent.style.marginLeft = '80px';
    arrowToggle.style.display = 'none';
});