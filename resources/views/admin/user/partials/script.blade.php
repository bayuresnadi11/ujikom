<script>
function filterUsers(role) {
    const rows = document.querySelectorAll('.user-row');
    const buttons = document.querySelectorAll('.filter-btn');
    
    buttons.forEach(btn => {
        btn.classList.remove('active');
    });
    
    let activeButton = document.querySelector(`button[onclick="filterUsers('${role}')"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
    
    rows.forEach(row => {
        if (role === 'all') {
            row.style.display = '';
        } else if (row.dataset.role === role) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>