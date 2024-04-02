<?php

namespace app\SearchForms;

use app\Entities\Notes\Entities\NotesToTags;
use app\Entities\Tag\Entities\Tags;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\Entities\Notes\Entities\Notes;

/**
 * NotesSearch represents the model behind the search form of `app\Entities\Notes\Entities\Notes`.
 */
class NotesSearch extends Notes
{
    public ?string $tag = null;
    public ?int $tag_id = null;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tag_id'], 'integer'],
            [['name', 'comment'], 'safe'],
            ['tag', 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = Notes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => \Yii::$app->user->identity->id,
        ]);

        if($this->tag) {
            $tagId = Tags::find()->select('id')->where(['name' => $this->tag]);
            $notesId = NotesToTags::find()->select('notes_id')->where(['tag_id' => $tagId]);
            $query->andFilterWhere(['in','id', $notesId]);
        }

        if($this->tag_id) {
            $tagId = Tags::find()->select('id')->where(['id' => $this->tag_id]);
            $notesId = NotesToTags::find()->select('notes_id')->where(['tag_id' => $tagId]);
            $query->andFilterWhere(['in','id', $notesId]);
        }

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'comment', $this->comment]);

        return $dataProvider;
    }
}
