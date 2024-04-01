<?php

namespace app\Entities\Notes\Entities;

use app\Entities\Tag\Entities\Tags;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "notes_to_tags".
 *
 * @property int|null $notes_id
 * @property int|null $tag_id
 *
 * @property Notes $notes
 * @property Tags $tag
 */
class NotesToTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notes_to_tags';
    }


    public function getNotes(): ActiveQuery
    {
        return $this->hasOne(Notes::class, ['id' => 'notes_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(Tags::class, ['id' => 'tag_id']);
    }
}
