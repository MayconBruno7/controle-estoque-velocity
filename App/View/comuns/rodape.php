        <style>
            body {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                margin: 0; 
            }

            main {
                flex: 1;
            }

            /* Página index */
            .about {
                flex: 1;
            }

            footer {
                background-color: rgb(240, 243, 243);
                padding: 3%;
                text-align: center;
            }
        </style>

        <footer>
            <p>Departamento de Informática Rosário da Limeira - MG</p>
            <span>© 2024 Company, Inc</span>

            <?php use App\Library\Session; ?>

            <div class="container">
                <a class="mt-2" href="<?= baseUrl() ?><?= Session::get('usuarioNivel') == 1 ? 'Home/homeAdmin' : (Session::get('usuarioNivel') == 2 ? 'Home/home' : "") ?>">Home</a>
            </div>
        </footer>
    </body>   
</html>