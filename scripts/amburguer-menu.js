document.addEventListener('DOMContentLoaded', () => {
    const btnMobile = document.querySelector('.menu-toggle');
    const navList = document.querySelector('.nav-links');

    // Verificação de segurança: só executa se ambos os elementos existirem
    if (btnMobile && navList) {
        btnMobile.addEventListener('click', () => {
            navList.classList.toggle('active');
            console.log("Menu clicado!"); // Para testar no console
        });

        // Fecha o menu ao clicar num link (importante para navegação interna)
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navList.classList.remove('active');
            });
        });
    } else {
        console.error("Erro: .menu-toggle ou .nav-links não foram encontrados no HTML.");
    }
});