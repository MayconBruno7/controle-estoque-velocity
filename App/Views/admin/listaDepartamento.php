<?= $this->extend('layout/layout_default') ?>

<?= $this->section('conteudo') ?>

<div class="container">

    <?= exibeTitulo("Departamento") ?>

	<section class="login_box_area mb-5">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped table-sm">
                <thead>
                    <tr class="text-weight-bold">
                        <td>Id</td>
                        <td>Descrição</td>
                        <td>Status</td>
                        <td>Opções</td>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach($dados as $value): ?>
                        <tr>                    
                            <td><?= $value['id'] ?></td>
                            <td><?= $value['descricao'] ?></td>
                            <td><?= mostraStatus($value['statusRegistro']) ?></td>
                            <td>
                                <a href="/Departamento/form/view/<?= $value['id'] ?>" class="btn btn-secondary btn-sm btn-icons-crud" title="Visualizar"><i class="fa fa-eye" aria-hidden="true"></i></a>    
                                <a href="/Departamento/form/update/<?= $value['id'] ?>" class="btn btn-secondary btn-sm btn-icons-crud" title="Alterar"><i class="fa fa-file" aria-hidden="true"></i></a>    
                                <a href="/Departamento/form/delete/<?= $value['id'] ?>" class="btn btn-secondary btn-sm btn-icons-crud" title="Excluir"><i class="fa fa-trash" aria-hidden="true"></i></a>                               
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
	</section>
</div>

<?= $this->endSection() ?>