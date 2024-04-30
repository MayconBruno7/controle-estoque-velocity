document.addEventListener("DOMContentLoaded", function() {
    function goBack() {
        window.history.back();
    }

    // Função para habilitar campos de entrada quando o botão de adicionar é clicado
    function enableInputs(idProduto) {
        document.getElementById('valor_' + idProduto).removeAttribute('disabled');
        document.getElementById('quantidade_' + idProduto).removeAttribute('disabled');
    }
});
