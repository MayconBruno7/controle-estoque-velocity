<?php

    /**
     * subTitulo
     *
     * @param string $acao 
     * @return string
     */
    function subTitulo($acao)
    {
        if ($acao == "insert") {
            return " - Inclusão";
        } elseif ($acao == "update") {
            return " - Alteração";
        } elseif ($acao == "delete") {
            return " - Exclusão";
        } elseif ($acao == "view") {
            return " - Visualização";
        }
    }

    /**
     * getNivelDescricao
     *
     * @param int $nivel 
     * @return string
     */
    function getNivelDescricao($nivel)
    {
        if ($nivel == 1) {
            return "Administrador";
        } elseif ($nivel == 2) {
            return "Usuário";
        } else {
            return "...";
        }
    }

    /**
     * getStatusDescricao
     *
     * @param int $status 
     * @return string
     */
    function getStatusDescricao($status)
    {
        if ($status == 1) {
            return "Ativo";
        } elseif ($status == 2) {
            return "Inativo";
        } else {
            return "...";
        }
    }

    function getTipoMovimentacao($tipo)
    {
        if ($tipo == 1) {
            return "Entrada";
        } elseif ($tipo == 2) {
            return "Saída";
        } else {
            return "...";
        }
    }

    function getCondicao($status)
    {
        if ($status == 1) {
            return "Novo";
        } elseif ($status == 2) {
            return "Usado";
        } else {
            return "...";
        }
    }

    function getTipo($tipo)
    {
        if ($tipo == 1) {
            return "Entrada";
        } elseif ($tipo == 2) {
            return "Saida";
        } else {
            return "...";
        }
    }

    function formatarCNPJInput($cnpj) {
        // Remove caracteres especiais
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    
        // Adiciona pontos e traço
        $cnpjFormatado = substr($cnpj, 0, 2) . '.';
        $cnpjFormatado .= substr($cnpj, 2, 3) . '.';
        $cnpjFormatado .= substr($cnpj, 5, 3) . '/';
        $cnpjFormatado .= substr($cnpj, 8, 4) . '-';
        $cnpjFormatado .= substr($cnpj, 12, 2);
    
        return $cnpjFormatado;
    }    

    function formatarCPF($cpf) {
        // Remove caracteres indesejados do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
        // Adiciona pontos e traço ao CPF
        if(strlen($cpf) == 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
        }
    
        return $cpf; // Retorna o CPF não formatado se não tiver 11 dígitos
    }

    function formatarTelefone($telefone) {
        // Remove todos os caracteres não numéricos
        $telefone = preg_replace('/\D/', '', $telefone);
    
        // Verifica se o telefone possui 11 dígitos (incluindo o DDD) e formata de acordo
        if (strlen($telefone) == 11) {
            $telefoneFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } else {
            // Se não tiver 11 dígitos, trata como um telefone comum (DDD sem o 9)
            $telefoneFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }
    
        return $telefoneFormatado;
    }

    function formatarDataBrasileira($data) {
        // Converte a data para o formato timestamp
        $timestamp = strtotime($data);
        
        // Formata a data no padrão brasileiro
        $dataFormatada = date('d/m/Y', $timestamp);
        
        return $dataFormatada;
    }

    /**
     * getMensagem
     *
     * @return string
     */
    function getMensagem()
    {
        if (isset($_GET['msgSucesso'])) {
            return '<div class="row">
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>' . $_GET['msgSucesso'] . '</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>';
        }

        if (isset($_GET['msgError'])) {
            return '<div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>' . $_GET['msgError'] . '</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>';
        }
    }

    /**
     * datatables
     *
     * @param string $idTable 
     * @return string
     */
    function datatables($idTable)
    {
        return '

            <script src="assets/DataTables/datatables.min.js"></script>

            <style>
                .dataTables_filter {
                    margin-bottom: 10px;
                }
            </style>

            <script>
                $(document).ready(function() {
                    $("#' . $idTable . '").DataTable({
                        language:   {
                                        "sEmptyTable":      "Nenhum registro encontrado",
                                        "sInfo":            "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                                        "sInfoEmpty":       "Mostrando 0 até 0 de 0 registros",
                                        "sInfoFiltered":    "(Filtrados de _MAX_ registros)",
                                        "sInfoPostFix":     "",
                                        "sInfoThousands":   ".",
                                        "sLengthMenu":      "_MENU_ resultados por página",
                                        "sLoadingRecords":  "Carregando...",
                                        "sProcessing":      "Processando...",
                                        "sZeroRecords":     "Nenhum registro encontrado",
                                        "sSearch":          "Pesquisar",
                                        "oPaginate": {
                                            "sNext":        "Próximo",
                                            "sPrevious":    "Anterior",
                                            "sFirst":       "Primeiro",
                                            "sLast":        "Último"
                                        },
                                        "oAria": {
                                            "sSortAscending":   ": Ordenar colunas de forma ascendente",
                                            "sSortDescending":  ": Ordenar colunas de forma descendente"
                                        }
                                    }
                    });
                });
            </script>';
    }

   