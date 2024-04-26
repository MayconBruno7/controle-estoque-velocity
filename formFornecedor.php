<?php 

    $dados = [];

    /*
    *   Se for alteração, exclusão ou visualização busca a UF pelo ID que foi recebido via método GET
    */
    if ($_GET['acao'] != "insert") {

        try {
            require_once "library/Database.php";
            // Criando o objeto Db para classe de base de dados
            $db = new Database();
        
            // prepara comando SQL
            $dados = $db->dbSelect("SELECT * FROM fornecedor WHERE id_fornecedor = ?", 'first',[$_GET['id_fornecedor']]);
        
        // se houver erro na conexão com o banco de dados o catch retorna
        } catch (Exception $ex) {
            echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        }
    }

    // muda as ações para os nomes das página e muda o estado do item colocando 1 para novo e 2 para usado
    require_once "helpers/Formulario.php";
    require_once "library/Funcoes.php";

    // recupera o cabeçalho para a página
    require_once "comuns/cabecalho.php";
    require_once "library/protectUser.php";

    // Função para fazer uma requisição para a API do IBGE e obter os dados de um determinado campo
    function getIBGEData($field) {
        $url = "https://servicodados.ibge.gov.br/api/v1/localidades/" . $field;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    // Recupera todos os países
    $paises = getIBGEData("paises");

    // Recupera todos os estados do Brasil
    $estados = getIBGEData("estados");

    // Recupera todas as cidades de um determinado estado (nesse exemplo, São Paulo)
    $cidades = getIBGEData("estados/MG/municipios");


?>

    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <!-- muda o texto do form se e insert, delete, update a partir da função subTitulo -->
                <h2>Fornecedor<?= subTitulo($_GET['acao']) ?></h2>
            </div>
        </div>

        <!-- pega se é insert, delete ou update a partir do metodo get assim mandando para a página correspondente -->
        <form class="g-3" action="<?= $_GET['acao'] ?>Fornecedor.php" method="POST" id="form">

            <!--  verifica se o id está no banco de dados e retorna esse id -->
            <input type="hidden" name="id_fornecedor" id="id_fornecedor" value="<?= isset($dados->id_fornecedor) ? $dados->id_fornecedor : "" ?>">

            <div class="row">

                <div class="col-4">
                    <label for="nome_fornecedor" class="form-label mt-3">Nome</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="nome_fornecedor" id="nome_fornecedor" placeholder="Nome do fornecedor" required autofocus value="<?= isset($dados->nome_fornecedor) ? $dados->nome_fornecedor : "" ?>">
                </div>

                <div class="col-4">
                    <label for="cnpj_fornecedor" class="form-label mt-3">CNPJ</label>
                    <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                    <input type="text" class="form-control" name="cnpj_fornecedor" id="cnpj_fornecedor" placeholder="CNPJ do fornecedor" pattern="\d{14}" required value="<?= isset($dados->cnpj_fornecedor) ? Funcoes::formatarCNPJ($dados->cnpj_fornecedor) : "" ?>">
                </div>

                <div class="col-4">
                    <label for="status_fornecedor" class="form-label mt-3">Status</label>
                    <select name="status_fornecedor" id="status_fornecedor" class="form-control" required>
                        <!--  verifica se o status_fornecedor está no banco de dados e retorna esse status_fornecedor -->
                        <option value=""  <?= isset($dados->status_fornecedor) ? $dados->status_fornecedor == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->status_fornecedor) ? $dados->status_fornecedor == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->status_fornecedor) ? $dados->status_fornecedor == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-4">
                    <label for="pais_fornecedor" class="form-label mt-3">Pais</label>
                    <select name="pais_fornecedor" id="pais_fornecedor" class="form-control" required>
                        <?php foreach ($paises as $indice => $pais) : ?>
                            <option value="<?= $pais['id']['M49'] ?>" <?= isset($dados->pais_fornecedor) && $dados->pais_fornecedor == $pais['nome'] ? 'selected' : '' ?>>
                                <?= $pais['nome'] ?> - <?= $pais['id']['ISO-ALPHA-2'] ?> 
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-4">
                    <label for="estado_fornecedor" class="form-label mt-3">Estado</label>
                    <select name="estado_fornecedor" id="estado_fornecedor" class="form-control" required>
                        <?php foreach ($estados as $estado) : ?>
                            <option value="<?= $estado['id'] ?>"><?= $estado['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-4">
                    <label for="cidade_fornecedor" class="form-label mt-3">Cidade</label>
                    <select name="cidade_fornecedor" id="cidade_fornecedor" class="form-control" required>
                        <?php foreach ($cidades as $cidade) : ?>
                            <option value="<?= $cidade['id'] ?>"><?= $cidade['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                    <div class="col-4">
                        <label for="telefone_fornecedor" class="form-label mt-3">Telefone</label>
                        <!--  verifica se a nome está no banco de dados e retorna essa nome -->
                        <input type="text" class="form-control" name="telefone_fornecedor" id="telefone_fornecedor" placeholder="Telefone do fornecedor" pattern="\d{11}" required value="<?= isset($dados->telefone_fornecedor) ? Funcoes::formataTelefone($dados->telefone_fornecedor) : "" ?>">
                    </div>
                </div>
            </div>

            <div class="col-auto mt-4 mb-4">
                <a href="listafornecedor.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
                <!-- define o texto de cada botão de acordo com a sua ação -->
                <?php if ($_GET['acao'] == "delete") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Excluir</button>
                <?php endif; ?>

                <?php if ($_GET['acao'] == "update") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Alterar</button>
                <?php endif; ?>

                <?php if ($_GET['acao'] == "insert") : ?>
                    <button type="submit" class="btn btn-primary btn-sm">Inserir</button>
                <?php endif; ?>
            </div>
        </form>
    </main>


<?php

  require_once "comuns/rodape.php";

?>