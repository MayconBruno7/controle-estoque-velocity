<?php

namespace App\Library;

use App\Library\Formulario as LibraryFormulario;

class Formulario
{
    static public function titulo($titulo, $btNew = true, $btVoltar = false) 
    {
        $service = new Service();
        $html = '';

        if ($service->getAcao() == 'insert') {
            $titulo .= " - Inclusão";
        } elseif ($service->getAcao() == 'update') {
            $titulo .= " - Alteração";
        } elseif ($service->getAcao() == 'delete') {
            $titulo .= " - Exclusão";
        } elseif ($service->getAcao() == 'view') {
            $titulo .= " - Visualização";
        }

        $html = '<div class="container-fluid">';
            $html .= '<div class="row">';

                $html .= '<div class="col-10"><h2>' . $titulo . '</h2></div>';

                $html .= '<div class="col-2 text-end">';

                    if ($btNew) {
                        $html .= Formulario::botao('insert');
                    }

                    if ($btVoltar) {
                        $html .= Formulario::botao('voltar');
                    }

                $html .= '</div>';

                $html .= Formulario::mensagem();

            $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * botao
     *
     * @param string $tipo 
     * @param mixed $id 
     * @return string
     */
    static public function botao($tipo, $id = null)
    {
        $service = new Service();

        $htmlBt = "";
        $url = baseUrl() . $service->getController();

        if ($tipo == 'insert') {
            $htmlBt = '<a href="' . $url . '/form/insert/0" title="Inclusão"><i class="fa fa-plus" area-hidden="true"></i></a>';
        } elseif ($tipo == 'update') {
            $htmlBt = '<a href="' . $url . '/form/update/' . $id . '" title="Alteração"><i class="fa fa-file" area-hidden="true"></i></a>&nbsp;&nbsp;';
        } elseif ($tipo == 'delete') {
            $htmlBt = '<a href="' . $url . '/form/delete/' . $id . '" title="Exclusão"><i class="fa fa-trash" area-hidden="true"></i></a>&nbsp;&nbsp;';
        } elseif ($tipo == 'view') {
            $htmlBt = '<a href="' . $url . '/form/view/' . $id . '" title="Visualização"><i class="fa fa-eye" area-hidden="true"></i></a>&nbsp;&nbsp;';
        } elseif ($tipo == 'voltar') {
            $htmlBt = '<a href="' . $url . '" class="btn btn-outline-secondary" title="Voltar">Voltar</a>';
        }

        return $htmlBt;
    }

    /**
     * exibeMsgSucesso
     *
     * @return string
     */
    static public function exibeMsgSucesso()
    {
        $html = "";

        if (Session::get("msgSuccess") != false) {
            $html .= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . Session::getDestroy("msgSuccess") . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }

        return $html;
    }

    /**
     * exibeMsgError
     *
     * @return string
     */
    static public function exibeMsgError()
    {
        $html = "";

        if (Session::get("msgError") != false) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . Session::getDestroy("msgError") . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }

        return $html;
    }

    /**
     * mensagem
     *
     * @return string
     */
    static public function mensagem()
    {
        $html = "";

        $html .= Formulario::exibeMsgSucesso();
        $html .= Formulario::exibeMsgError();

        if (Session::get("errors") != false) {
            
            $aErrors = Session::getDestroy('errors');
            $textoErros = "";

            foreach ($aErrors AS $value) {
                $textoErros .= ($textoErros != "" ? "<br />" : "") . $value;
            }

            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ' . $textoErros . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
        }

        return $html;
    }

    /**
     * getDataTables
     *
     * @param string $table_id 
     * @return string
     */
    static public function getDataTables($table_id)
    {
        return '
            <script type="text/javascript" src="' . baseUrl() . 'assets/datatables/datatables.min.js"></script>
            <script>
                $(document).ready( function() {
                    $("#' . $table_id . '").DataTable( {
                        "order": [],
                        "columnDefs": [{
                            "targets": "no-sort",
                            "orderable": false,
                        }],
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
                } );
            </script>
        ';
    }
}