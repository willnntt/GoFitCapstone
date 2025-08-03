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
