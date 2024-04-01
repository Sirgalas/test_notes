<?php

namespace app\Entities\Notes\Entities;

use app\Entities\Notes\Forms\NotesForm;
use dektrium\user\models\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "notes".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $user_id
 * @property string|null $comment
 *
 * @property NotesToTags[] $notesToTags
 * @property Notes[] $notes
 * @property User $user
 */
class Notes extends \yii\db\ActiveRecord
{
    public static function create(NotesForm $form): self
    {
        $notes = new static();
        $notes->id = $form->id;
        $notes->name = $form->name;
        $notes->user_id = $form->user_id;
        $notes->comment = $form->comment;
        return $notes;
    }
    
    public function edit(NotesForm $form): void
    {
        $this->id = $form->id;
        $this->name = $form->name;
        $this->user_id = $form->user_id;
        $this->comment = $form->comment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notes';
    }

    public function getNotes(): ActiveQuery
    {
        return $this->hasMany(Notes::class,['id' => 'notes_id'])->via('notesToTags');
    }


    public function getNotesToTags(): ActiveQuery
    {
        return $this->hasMany(NotesToTags::class, ['notes_id' => 'id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
