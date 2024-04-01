<?php

declare(strict_types=1);

namespace app\Entities\Notes\Services;

use app\Entities\Notes\Entities\Notes;
use app\Entities\Notes\Forms\NotesForm;
use app\Entities\Notes\Repositories\NotesRepository;

class NoteService
{
    public function __construct(readonly public NotesRepository $repository)
    {}

    public function create(NotesForm $form): Notes
    {
        $notes = Notes::create($form);
        return $this->repository->save($notes);
    }

    public function edit(Notes $notes, NotesForm $form): Notes
    {
        $notes->edit($form);
        return  $this->repository->save($notes);
    }

}