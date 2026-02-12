document.addEventListener('DOMContentLoaded', function () {
    const burger = document.querySelector('.burger-icon');
    const nav = document.querySelector('.nav-menu');

    if (burger && nav) {
        burger.addEventListener('click', function () {
            nav.classList.toggle('active');
            burger.classList.toggle('active');
        });
    }
});
