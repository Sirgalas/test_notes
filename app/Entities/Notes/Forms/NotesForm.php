<?php

declare(strict_types=1);

namespace app\Entities\Notes\Forms;

use app\Entities\Notes\Entities\Notes;
use dektrium\user\models\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class NotesForm extends Model
{

    public ?int $id = null;
    public ?string $name = null;
    public ?int $user_id = null;
    public ?string $comment = null;
    public array $tags = [];

    public function __construct(Notes $notes = null, $config = [])
    {
        $this->user_id = \Yii::$app->user->identity->id;
        parent::__construct($config);
        if($notes) {
            $this->id = $notes->id;
            $this->name = $notes->name;
            $this->comment = $notes->comment;
            $this->tags = ArrayHelper::getColumn($notes->tags,'id');
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
                ['tags'],'each','rule' => ['integer']
            ],
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