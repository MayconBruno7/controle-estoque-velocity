<?php

namespace App\Models;

use CodeIgniter\Model;

class SetorModel extends Model
{
    protected $table = 'setor';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nome', 'statusRegistro', 'responsavel']; // Adicione os campos permitidos para inserção/atualização

    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[100]',
        'statusRegistro' => 'required|integer'
    ];

    /**
     * lista
     *
     * @param string $orderBy
     * @return array
     */
    public function lista($orderBy = 'id')
    {
        $builder = $this->db->table($this->table);
        $builder->select('s.*, f.nome as nomeResponsavel');
        $builder->join('funcionario as f', 's.responsavel = f.id', 'left');

        if (session()->get('usuarioNivel') != 1) {
            $builder->where('s.statusRegistro', 1);
        }

        $builder->orderBy($orderBy);
        return $builder->get()->getResultArray();
    }

    /**
     * getProdutoCombobox
     *
     * @param int $categoria_id
     * @return array
     */
    public function getProdutoCombobox($categoria_id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('p.id, p.descricao');
        $builder->join('categoria as c', 'c.id = p.categoria_id');
        $builder->where('c.id', $categoria_id);
        $builder->orderBy('p.descricao');

        return $builder->get()->getResultArray();
    }
}
