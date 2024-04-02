<?php

namespace app\Entities\Notes\Entities;

use app\Entities\Notes\Forms\NotesForm;
use app\Entities\Tag\Entities\Tags;
use dektrium\user\models\User;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
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
 * @property Tags[] $tags
 * @property User $user
 */
class Notes extends \yii\db\ActiveRecord
{
    public static function create(NotesForm $form): self
    {
        $notes = new static();
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

    public function behaviors():array
    {
        $behaviorArray =[
            'saveRelations' =>[
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'tags'
                ],
            ],
        ];
        return array_merge(parent::behaviors(), $behaviorArray);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notes';
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tags::class,['id' => 'tag_id'])->via('notesToTags');
    }


    public function getNotesToTags(): ActiveQuery
    {
        return $this->hasMany(NotesToTags::class, ['notes_id' => 'id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function addTags(Tags $tags)
    {
        $oldTags = $this->tags;
        $oldTags[] = $tags;
        $this->updateTags($oldTags);
    }

    public function removeAllTags()
    {
        $this->updateTags([]);
    }

    public function updateTags(array $tags): void
    {
        $this->tags = $tags;
    }
}
