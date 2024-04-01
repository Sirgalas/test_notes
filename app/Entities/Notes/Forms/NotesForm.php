<?php

declare(strict_types=1);

namespace app\Entities\Notes\Forms;

use app\Entities\Notes\Entities\Notes;
use dektrium\user\models\User;
use yii\base\Model;

class NotesForm extends Model
{

    public int $id;
    public string $name;
    public int $user_id;
    public string $comment;

    public function __construct(Notes $notes = null, $config = [])
    {
        parent::__construct($config);
        if($notes) {
            $this->id = $notes->id;
            $this->name = $notes->name;
            $this->user_id = $notes->user_id;
            $this->comment = $notes->comment;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['comment'], 'string'],
            [['name'], 'string', 'max' => 255],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
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
            'user_id' => 'User ID',
            'comment' => 'Comment',
        ];
    }
}