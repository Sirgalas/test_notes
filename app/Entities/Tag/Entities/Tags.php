<?php

namespace app\Entities\Tag\Entities;

use app\Entities\Notes\Entities\NotesToTags;
use app\Entities\Tag\Forms\TagsForm;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property NotesToTags[] $notesToTags
 */
class Tags extends \yii\db\ActiveRecord
{
    public static function create(TagsForm $form)
    {
        $tags = new static();
        $tags->name = $form->name;
        return $tags;
    }

    public function edit(TagsForm $form): void
    {
        $this->name = $form->name;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }

    public function getNotesToTags(): ActiveQuery
    {
        return $this->hasMany(NotesToTags::class, ['tag_id' => 'id']);
    }
}
