
        <!-- General JS Scripts -->
        <script src="<?= baseUrl() ?>assets/js/app.min.js"></script>
        <!-- JS Libraies -->
        <script src="<?= baseUrl() ?>assets/bundles/apexcharts/apexcharts.min.js"></script>

        <!-- Template JS File -->
        <script src="<?= baseUrl() ?>assets/js/scripts.js"></script>
        <!-- Custom JS File -->
        <script src="<?= baseUrl() ?>assets/js/custom.js"></script>

        <!-- Datatables -->
        <script src="<?= baseUrl() ?>assets/bundles/datatables/datatables.min.js"></script>
        <script src="<?= baseUrl() ?>assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?= baseUrl() ?>assets/bundles/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?= baseUrl() ?>assets/js/page/datatables.js"></script>

        <style>
            footer {
                background-color: rgb(240, 243, 243);
                padding: 3%;
                text-align: center;
            }

        </style>
        
        <footer class="mt-4">
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
            <div class="container mt-2">
                <?php if (Session::get('usuarioId') != false) : ?>
                    <a class="mt-2" href="<?= baseUrl() . $redirectUrl ?>">Home</a>
                <?php endif; ?>
            </div>
        </footer>
    </body>   
</html>