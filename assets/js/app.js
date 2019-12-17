const navSlide = () => {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('.nav-links');
    const navLinks = document.querySelectorAll('.nav-links li');


    burger.addEventListener('click', () => {
        // Toggle Nav
        nav.classList.toggle('nav-active');

        // Animate Links
        navLinks.forEach((link, index) => {
            var time = (index / 7 ) + 0.3;
            if(link.style.animation) {
                link.style.animation = '';
            } else {
                link.style.animation = `navLinkFade 0.5s ease forwards ${time}s`;
            }
        });

        // Burger animation
        burger.classList.toggle('toggle');
    });
}

navSlide();