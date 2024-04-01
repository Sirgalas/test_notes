<?php

declare(strict_types=1);

namespace app\Entities\Tag\Repositories;

use app\Entities\Notes\Entities\Notes;
use app\Entities\Tag\Entities\Tags;
use app\Helpers\ErrorHelper;
use yii\web\NotFoundHttpException;

class TagsRepository
{
    public function get(int $id): Tags
    {
        if(!$tags = Tags::findOne($id)) {
            throw new NotFoundHttpException('Notes not found');
        }
        return $tags;
    }
    public function find(int $id): ?Tags
    {
        if(!$tags = Tags::findOne($id)) {
            return null;
        }
        return $tags;
    }

    public function save(Tags $tags): Tags
    {
        if(!$tags->save()) {
            throw new \RuntimeException(ErrorHelper::errorsToStr($tags->errors));
        }
        return $tags;
    }

    public function delete(int $id)
    {
        $tags = $this->get($id);
        $tags->delete();
    }
}