<?php

    require_once "helpers/Formulario.php";
    require_once "comuns/cabecalho.php";
    require_once "library/Database.php";
    require_once "library/Funcoes.php";
    require_once "library/protectNivel.php";

    if (!Funcoes::userLogado()) {
        return header("Location: viewLogin.php");
    }

    $db = new Database();
    
    $dados = [];

    if ($_GET['acao'] != "insert") {

        $dados = $db->dbSelect("SELECT * FROM usuario WHERE id = ?", 'first', [$_GET['id']]);

    };
?>

    <main class="container mt-5">

        <div class="row">
            <div class="col-10">
                <h2>Usuário<?= subTitulo($_GET['acao']) ?></h2>
            </div>
            <div class="col-2 text-end">
                <a href="listaUsuario.php" class="btn btn-outline-secondary btn-sm" title="Voltar">Voltar</a>
            </div>

        </div>

        <!--
            action => recebe a ação que foi adicionada no hyperlink da lista (insert, update, delete) mais Uf.php
        -->

        <form class="g-3" action="<?= $_GET['acao'] ?>Usuario.php" method="POST">

            <input type="hidden" name="id" id="id" value="<?= isset($dados->id) ? $dados->id : "" ?>">

            <div class="row">

                <div class="col-8">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" 
                        placeholder="Nome do usuário" required autofocus
                        value="<?= isset($dados->nome) ? $dados->nome : "" ?>">
                </div>

                <div class="col-4">
                    <label for="nivel" class="form-label">Nível</label>
                    <select name="nivel" id="nivel" class="form-control" required>
                        <option value=""  <?= isset($dados->nivel) ? $dados->nivel == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->nivel) ? $dados->nivel == 1  ? "selected" : "" : "" ?>>Administrador</option>
                        <option value="2" <?= isset($dados->nivel) ? $dados->nivel == 2  ? "selected" : "" : "" ?>>Usuário</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">

                <div class="col-8">
                    <label for="email" class="form-label">E-mail/Login</label>
                    <input type="email" class="form-control" name="email" 
                        id="email" placeholder="email" required
                        value="<?= isset($dados->email) ? $dados->email : "" ?>">
                </div>

                <div class="col-4">
                    <label for="statusRegistro" class="form-label">Status</label>
                    <select name="statusRegistro" id="statusRegistro" class="form-control" required>
                        <option value=""  <?= isset($dados->statusRegistro) ? $dados->statusRegistro == "" ? "selected" : "" : "" ?>>...</option>
                        <option value="1" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 1  ? "selected" : "" : "" ?>>Ativo</option>
                        <option value="2" <?= isset($dados->statusRegistro) ? $dados->statusRegistro == 2  ? "selected" : "" : "" ?>>Inativo</option>
                    </select>
                </div>

            </div>

            <div class="row mt-3">

                <div class="col-6">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="senha" id="senha"  maxlength="250"
                        placeholder="Senha">
                </div>

                <div class="col-6">
                    <label for="confSenha" class="form-label">Confere Nova Senha</label>
                    <input type="password" class="form-control" name="confSenha" id="confSenha"  maxlength="250"
                        placeholder="Confere senha">
                </div>

            </div>

            <div class="col-auto mt-5">
                <a href="listaUsuario.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                
                <?php if ($_GET['acao'] != "view"): /* botão gravar não é exibido na visualização dos dados */ ?>
                    <button type="submit" class="btn btn-primary btn-sm">Gravar</button>
                <?php endif; ?>
            </div>
        </form>

    </main>

    <?php

    require_once "comuns/rodape.php";