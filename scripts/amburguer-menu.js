document.addEventListener('DOMContentLoaded', () => {
    const btnMobile = document.querySelector('.menu-toggle');
    const navList = document.querySelector('.nav-links');

    if (btnMobile && navList) {
        btnMobile.addEventListener('click', () => {
            // Agora ambos alternam a classe active
            navList.classList.toggle('active');
            btnMobile.classList.toggle('active'); 
        });

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navList.classList.remove('active');
                btnMobile.classList.remove('active'); // Remove do botão também ao clicar no link
            });
        });
    }
});