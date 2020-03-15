<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tag2article}}`.
 */
class m200313_173735_create_tag2article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=MyISAM';
        }

        $t = '{{%tag2article}}';
        $this->createTable($t, [
            'tag_id'     => $this->integer()->unsigned()->notNull(),
            'article_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('tag2article_pk', $t, ['tag_id', 'article_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tag2article}}');
    }
}
