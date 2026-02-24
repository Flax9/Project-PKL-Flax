// public/assets/js/theme.js

document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');
    const themeToggleText = document.getElementById('themeToggleText');
    const themeToggleCheckbox = document.getElementById('themeToggleCheckbox');

    // Menerapkan tema awal berdasarkan class 'dark' di <html>
    const isDarkMode = document.documentElement.classList.contains('dark');
    updateToggleUI(isDarkMode);
    if (themeToggleCheckbox) themeToggleCheckbox.checked = !isDarkMode; // Checked = light mode in our current UI logic, adjust as needed

    if (themeToggleCheckbox) {
        themeToggleCheckbox.addEventListener('change', () => {
            // Toggle class 'dark' di HTML tag
            document.documentElement.classList.toggle('dark');
            const newIsDarkMode = document.documentElement.classList.contains('dark');

            // Simpan preferensi di localStorage
            localStorage.setItem('theme', newIsDarkMode ? 'dark' : 'light');

            // Update UI tombol
            updateToggleUI(newIsDarkMode);
        });
    }

    function updateToggleUI(isDark) {
        if (!themeToggleText) return;

        if (isDark) {
            themeToggleText.textContent = 'Dark Mode';
        } else {
            themeToggleText.textContent = 'Light Mode';
        }
    }
});
