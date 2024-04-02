<?php

declare(strict_types=1);

namespace app\Entities\Notes\Repositories;

use app\Entities\Notes\Entities\Notes;
use app\Helpers\ErrorHelper;
use yii\web\NotFoundHttpException;

class NotesRepository
{
    public function get(int $id): Notes
    {
        if(!$notes = Notes::findOne($id)) {
            throw new NotFoundHttpException('Notes not found');
        }
        return $notes;
    }
    public function find(int $id): ?Notes
    {
        if(!$notes = Notes::findOne($id)) {
            return null;
        }
        return $notes;
    }

    public function save(Notes $notes): Notes
    {
        if(!$notes->save()) {
            throw new \RuntimeException(ErrorHelper::errorsToStr($notes->errors));
        }
        return $notes;
    }

    public function findOneByQuery(array $query): Notes
    {
        if(!$notes = Notes::findOne($query)) {
            throw new NotFoundHttpException('Notes not find');
        }
        return $notes;
    }

    public function delete(int $id)
    {
        $notes = $this->get($id);
        $notes->delete();
    }
}