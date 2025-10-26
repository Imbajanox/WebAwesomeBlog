// Theme Toggle Functionality
const themeToggle = document.getElementById('theme-toggle');
const body = document.body;

themeToggle.addEventListener('click', () => {
    body.classList.toggle('dark');
    const icon = themeToggle.querySelector('i');
    if (body.classList.contains('dark')) {
        icon.className = 'fas fa-sun';
    } else {
        icon.className = 'fas fa-moon';
    }
});

// Form Submission (Basic Example)
const form = document.querySelector('form');
form.addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Message sent! (This is a static demo)');
    form.reset();
});