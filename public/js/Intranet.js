document.querySelectorAll('.navbar li').forEach(item => {
    item.addEventListener('click', event => {
        const subMenu = item.querySelector('.sub-menu');
        const arrowIcon = item.querySelector('.arrow-icon');
        if (subMenu) {
            if (subMenu.style.display === 'block') {
                subMenu.style.display = 'none';
                arrowIcon.classList.remove('fa-arrow-up');
                arrowIcon.classList.add('fa-arrow-down');
            } else {
                subMenu.style.display = 'block';
                arrowIcon.classList.remove('fa-arrow-down');
                arrowIcon.classList.add('fa-arrow-up');
            }
        }
    });
});
