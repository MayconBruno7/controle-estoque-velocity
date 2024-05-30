<?php
    // App/View/produto.php
?>

<h2>Produtos</h2>

<div class="container">

    <form method="POST" action="<?= baseUrl() ?>Produto/<?= $this->getAcao() ?>">

        <div class="row">

            <div class="col-4 mb-3">
                <label for="categoria_id" class="form-label">Categoria</label>
                <select class="form-control" name="categoria_id" id="categoria_id"
                    required>
                    <option value="" selected disabled>...</option>
                    <?php foreach ($dados as $value): ?>
                        <option value="<?= $value['id'] ?>"><?= $value['descricao'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-4 mb-3">
                <label for="produto_id" class="form-label">Produto</label>
                <select class="form-control" name="produto_id" id="produto_id"
                    required>
                    <option value="" selected disabled>... Escolha uma Categoria ...</option>
                </select>
            </div>

        </div>

    </form>

</div>

<script type="text/javascript">

    $(function(){
        $('#categoria_id').change(function(){

            if ($(this).val()){
                $('#produto_id').hide();
                $('.carregando').show();
                
                var opt = '';

                $.getJSON('/Produto/getProdutoComboBox/lista/' + $(this).val(), 
                    {ajax: 'true'}, function(j) {
                        if (j.length > 0){
                            var opt = '<option value="" selected disabled>... Selecione uma Categoria ...</option>';
                            for (var i = 0; i < j.length; i++){
                                opt += '<option value="' + j[i].id + '">' + j[i].descricao + '</option>';
                            }
                        }
                        $('#produto_id').html(opt);
                        $('#produto_id').show()
                    });
            } else {
                //$('#produto_id').html(opt);
            }
        });
    });

</script>