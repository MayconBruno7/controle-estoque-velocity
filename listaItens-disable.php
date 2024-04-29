    <?php

        $login = 1;
        
        // carrega o cabecalho
        require_once "comuns/cabecalho.php";
        require_once "library/protectUser.php";
        require_once "library/Database.php";
        require_once "library/Funcoes.php";
        require_once "helpers/Formulario.php";
        
        try {
            
            // Criando o objeto Db para classe de base de dados
            $db = new Database();

            // preparação da query que será executada no banco de dados
            $dados = $db->dbSelect("SELECT itens.*, setor.nome_setor AS nome_do_setor FROM itens JOIN setor ON itens.setor_item = setor.id_setor WHERE statusRegistro_itens = 1");

        // Se houver algum erro de conexão com o banco de dados será disparado pelo bloco catch
        } catch (Exception $ex) {
            echo json_encode(['status' => false, 'msgErro' => 'Erro interno ao processar a requisição']);
        }


    ?>

    <title>Estoque</title>

    <!-- Verifica e retorna mensagem de erro ou sucesso -->
    <main class="container mt-5">

        <div class="row">
            <div class="col-12 d-flex justify-content-start">
                <a href="formItens.php?acao=insert" class="btn btn-outline-primary btn-sm mt-3 mb-3 m-0 styleButton" title="Inserir">Adicionar item</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php if (isset($_GET['msgSucesso'])): ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgSucesso'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>

                <?php if (isset($_GET['msgError'])): ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?= $_GET['msgError'] ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php endif; ?>
            </div>
        </div>
        
        <!-- Parte de exibição da tabela -->
        <form action="updateQuantidade.php" method="POST">
            <table id="tbListaItens" class="table table-striped table-hover table-bordered table-responsive-sm display align-items-center" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nome item</th>
                        <th>Quantidade</th>
                        <th>Estado do Item</th>
                        <th>Setor</th>
                        <th>Opções</th>
                    </tr>
                <thead>   

                <tbody>
                    <?php
                        foreach ($dados as $row) {
                            ?>
                                <tr>
                                    <td> <?= $row['id'] ?> </td>
                                    <td> <?= $row['nomeItem'] ?> </td>
                                    <td> 
                                        <div class="relative text-gray-500">
                                            <input type="number" min="0" max="1000" class="text-center align-items-center" name="quantidade[<?= $row['id'] ?>]" value="<?= $row['quantidade'] ?>">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-secondary btn-sm " title="Alteração" value="<?= $row['id'] ?>"> Atualizar quantidade</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= getStatusItem($row['statusItem']) ?></td>
                                    <td> <?= $row['nome_do_setor'] ?> </td>
                                    <td>
                                        <a href="formItens.php?acao=update&id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm styleButton" title="Alteração">Alterar</a>&nbsp;
                                        <a href="formItens.php?acao=delete&id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm styleButton" title="Exclusão">Excluir</a>&nbsp;
                                        <a href="formItens.php?acao=view&id=<?= $row['id'] ?>" class="btn btn-outline-secondary btn-sm styleButton" title="Visualizar">Visualizar</a>&nbsp;
                                    </td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </form>
    </main>
    
    <?php

        echo datatables('tbListaItens');

        require_once "comuns/rodape.php";

    ?>
    
    