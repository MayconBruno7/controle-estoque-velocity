<!DOCTYPE html>
<html>
<head>
    <title>Formulário de CNPJ</title>
</head>
<body>  
    <main>
        <form id="formCnpj">
            <div class="container">
                <label for="cnpj">CNPJ</label>
                <input type="text" id="cnpj" name="cnpj" required>
            </div>

            <div class="container">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome">
            </div>

            <div class="container">
                <label for="uf">UF</label>
                <input type="text" id="uf" name="uf">
            </div>

            <div class="container">
                <label for="municipio">Município</label>
                <input type="text" id="municipio" name="municipio">
            </div>

            <div class="container">
                <label for="bairro">Bairro</label>
                <input type="text" id="bairro" name="bairro">
            </div>

            <div class="container">
                <label for="logradouro">Logradouro</label>
                <input type="text" id="logradouro" name="logradouro">
            </div>

            <div class="container">
                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero">
            </div>

            <div class="container">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone">  
            </div>

            <button type="submit">Consultar</button>
        </form>
    </main>

    <script>
        const campoCNPJ = document.getElementById('cnpj');

        campoCNPJ.addEventListener('input', function() {
            if (campoCNPJ.value.length === 14) {
                // Quando o CNPJ tiver 14 dígitos, faça a solicitação para o servidor intermediário
                fetch('requireAPI.php?cnpj=' + campoCNPJ.value)
                    .then(response => response.json())
                    .then(data => {
                        // Atualize os campos com os dados da API
                        document.getElementById('nome').value = data['nome'];
                        document.getElementById('uf').value = data['uf'];
                        document.getElementById('municipio').value = data['municipio'];
                        document.getElementById('bairro').value = data['bairro'];
                        document.getElementById('logradouro').value = data['logradouro'];
                        document.getElementById('numero').value = data['numero'];
                        document.getElementById('telefone').value = data['telefone'];
                    })
                    .catch(error => {
                        console.error('Erro ao consultar API:', error);
                    });
            }
        });
    </script>
</body>
</html>
