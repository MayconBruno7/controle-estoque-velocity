
        <footer>
            <p>Departamento de Informática Rosário da Limeira - MG</p>
            <span>© 2024 Company, Inc</span>

            <?php 
                use App\Library\Session;
                use App\Library\Formulario;


            ?>
            <div class="container">
            <?php if (Session::get('usuarioId') != false): ?>
                <a class="mt-2" href="<?= baseUrl() . Formulario::retornaHomeAdminOuHome() ?>">Home</a>
            <?php endif; ?>
            </div>
        </footer>
    </body>   
</html>