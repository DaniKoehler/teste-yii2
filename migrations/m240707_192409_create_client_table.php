<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m240707_192409_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'cpf' => $this->string(14)->notNull()->unique(),
            'cep' => $this->string(9)->notNull(),
            'address' => $this->string(255)->notNull(),
            'number' => $this->string(10)->notNull(),
            'city' => $this->string(255)->notNull(),
            'state' => $this->string(2)->notNull(),
            'complement' => $this->string(255),
            'photo' => $this->string(255),
            'sex' => $this->string(1)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
