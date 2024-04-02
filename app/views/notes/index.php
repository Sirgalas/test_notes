<?php

use app\Entities\Notes\Entities\Notes;
use app\Entities\Tag\Entities\Tags;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\SearchForms\NotesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider
 * @var array $tags
 *
 */

$this->title = 'Notes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Notes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'comment:ntext',
            [
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'tag_id',
                    'data' => $tags,
                    'options' => ['placeholder' => 'Select a state ...']
                ]),
                'attribute' => 'tags',
                'format' => 'raw',
                'value' => function (Notes $notes) {
                    if(!empty($notes->tags)) {
                        $tagsArray = array_map(static function (Tags $tags) {
                            return sprintf(
                                '<p>%s</p>',
                                Html::a(
                                    $tags->name,
                                    Url::to(['/notes/tag','tag' => $tags->name]
                                    )
                                )
                            );
                        },
                            $notes->tags
                        );
                        return implode("\n", $tagsArray);
                    }
                    return '';
                },
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Notes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
            ],
        ],
    ]); ?>


</div>
