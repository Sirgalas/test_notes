<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notes}}`.
 */
class m240401_124116_create_notes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notes}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'user_id' => $this->integer(),
            'comment' => $this->text()
        ]);
        $this->createIndex('idx-notes-user_id','{{%notes}}','user_id');
        $this->addForeignKey(
            'fk-notes_to_user',
            '{{%notes}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('{{%tags}}',[
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

        $this->createTable('{{%notes_to_tags}}',[
            'notes_id' => $this->integer(),
            'tag_id' => $this->integer()
        ]);

        $this->createIndex('idx-notes_to_tags-notes_id','{{%notes_to_tags}}','notes_id');
        $this->addForeignKey(
            'fk-notes_to_tags-notes_id',
            '{{%notes_to_tags}}',
            'notes_id',
            '{{%notes}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createIndex('idx-notes_to_tags-tag_id','{{%notes_to_tags}}','tag_id');
        $this->addForeignKey(
            'fk-notes_to_tags-tag_id',
            '{{%notes_to_tags}}',
            'tag_id',
            '{{%tags}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{notes_to_tags}}');
        $this->dropTable('{{%notes}}');
        $this->dropTable('{{%tags}}');
    }
}
