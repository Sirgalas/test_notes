<?php

use app\Entities\Notes\Entities\Notes;
use app\Entities\Tag\Entities\Tags;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\Entities\Notes\Entities\Notes $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="notes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'comment:ntext',
            [
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
        ],
    ]) ?>

</div>
