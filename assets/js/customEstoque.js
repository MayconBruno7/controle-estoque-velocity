document.addEventListener("DOMContentLoaded", function() {
    async function viewItem(id) {
        console.log("Acessou: " + id);

        try {
            const dados = await fetch("viewItens.php?id=" + id);
            const resposta = await dados.json();

            if (resposta.status === true) {
                const visModal = new bootstrap.Modal(document.getElementById("viewItem"));
                visModal.show();

                document.getElementById("id").innerHTML = resposta['dados'].id;
                document.getElementById("nome").innerHTML = resposta['dados'].nomeItem;
                document.getElementById("descricao").innerHTML = resposta['dados'].descricao;
                document.getElementById("quantidade").innerHTML = resposta['dados'].quantidade;

                if (resposta['dados'].statusRegistro === 1) {
                    document.getElementById("statusRegistro").innerHTML = "Novo";
                } else {
                    document.getElementById("statusRegistro").innerHTML = "Usado";
                }

                document.getElementById("dataMod").innerHTML = resposta['dados'].dataMod;

            } else {
                document.getElementById("msgAlerta").innerHTML = resposta.msgErro;
            }
        } catch (error) {
            console.error("Erro ao processar requisição: ", error);
            document.getElementById("msgAlerta").innerHTML = "Erro interno ao processar a requisição";
        }
    }

    var search = document.getElementById('pesquisar');

    search.addEventListener("keydown", function(event){
        if(event.key === "Enter")
        {
            searchData();
        }
    });

    function searchData() 
    {
        window.location = 'listaItens.php?search=' + search.value;
    }

    function goBack() {
        window.history.back();
    }
});
