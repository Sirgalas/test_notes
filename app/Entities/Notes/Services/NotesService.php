<?php

declare(strict_types=1);

namespace app\Entities\Notes\Services;

use app\Entities\Notes\Entities\Notes;
use app\Entities\Notes\Forms\NotesForm;
use app\Entities\Notes\Repositories\NotesRepository;
use app\Entities\Tag\Entities\Tags;
use app\Entities\Tag\Repositories\TagsRepository;

class NotesService
{
    public function __construct(
        readonly public NotesRepository $repository,
        readonly public TagsRepository $tagsRepository
    ) {
    }

    public function create(NotesForm $form): Notes
    {
        $notes = Notes::create($form);
        if(!empty($form->tags)) {
           $tags = $this->tagsRepository->getAllByQuery(['in','id',$form->tags]);
           if (!empty($tags)) {
               array_map(function (Tags $tags) use ($notes){
                   $notes->addTags($tags);
               },
               $tags);
           }
        }
        return $this->repository->save($notes);
    }

    public function edit(Notes $notes, NotesForm $form): Notes
    {
        $notes->edit($form);
        if(!empty($form->tags)) {
            $notes->removeAllTags();
            $tags = $this->tagsRepository->getAllByQuery(['in','id',$form->tags]);
            if (!empty($tags)) {
                array_map(function (Tags $tags) use ($notes){
                    $notes->addTags($tags);
                },
                $tags);
            }
        }
        return  $this->repository->save($notes);
    }

}