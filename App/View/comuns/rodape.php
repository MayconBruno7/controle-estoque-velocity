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
            
            <div class="container">
                <a class="mt-2" href="<?= baseUrl() ?>/details.php">Detalhes do projeto</a>
            </div>
        </footer>
        
        <!-- <script src="<?= baseUrl() ?>/assets/js/customEstoque.js"></script> -->
    </body>   
</html>