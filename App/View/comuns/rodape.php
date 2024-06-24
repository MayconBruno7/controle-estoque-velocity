
        <footer>
            <p>Departamento de Informática Rosário da Limeira - MG</p>
            <span>© 2024 Company, Inc</span>

            <?php 
                use App\Library\Session;

                $redirectUrl = '';

                if (Session::get('usuarioNivel') == 1) {
                    $redirectUrl = 'Home/homeAdmin';
                } elseif (Session::get('usuarioNivel') == 11) {
                    $redirectUrl = 'Home/home';
                } 

            ?>
            <div class="container">
                <?php if (Session::get('usuarioId') != false) : ?>
                    <a class="mt-2" href="<?= baseUrl() . $redirectUrl ?>">Home</a>
                <?php endif; ?>
            </div>
        </footer>
    </body>   
</html>