<?php

declare(strict_types=1);

namespace app\Entities\Tag\Forms;

use app\Entities\Tag\Entities\Tags;
use yii\base\Model;

class TagsForm extends Model
{
    public string $name;

    public function __construct(Tags $tags = null, $config = [])
    {
        parent::__construct($config);
        if($tags) {
            $this->name = $tags->name;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}