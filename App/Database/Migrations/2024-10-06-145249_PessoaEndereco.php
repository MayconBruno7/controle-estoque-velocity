<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PessoaEndereco extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id"    => [
                'type'              => 'INT',
                'constraint'        => 11,
                'auto_increment'    => true,
            ],
            'pessoa_id' => [
                'type'              => 'INT',
                'constraint'        => 11,
            ],
            'tipoEndereco' => [
                'type'              => 'INT',
                'constraint'        => 11,
                'comment'           => '1=Cobrança;2=Endereço Entrega',
            ],
            'logradouro' => [
                'type'              => 'VARCHAR',
                'constraint'        => 60,
            ],
            'numero' => [
                'type'              => 'VARCHAR',
                'constraint'        => 10,
            ],
            'complemento' => [
                'type'              => 'VARCHAR',
                'constraint'        => 20,
            ],

            'bairro' => [
                'type'              => 'VARCHAR',
                'constraint'        => 40,
            ],
            'cep' => [
                'type'              => 'VARCHAR',
                'constraint'        => 8,
            ],
            'cidade_id' => [
                'type'              => 'INT',
                'constraint'        => 10,
            ],
            'created_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
            'updated_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
            'deleted_at' => [
                'type'              => 'DATETIME',
                'null'              => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pessoa_id', 'pessoa', 'id');
        $this->forge->addForeignKey('cidade_id', 'cidade', 'id');

        $this->forge->createTable('pessoaendereco', false, ['ENGINE' => 'InnoDB']);

    }

    public function down()
    {
        $this->forge->dropTable('pessoaendereco');
    }
}