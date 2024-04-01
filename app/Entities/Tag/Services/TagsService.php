<?php

declare(strict_types=1);

namespace app\Entities\Tag\Services;

use app\Entities\Notes\Entities\Notes;
use app\Entities\Notes\Forms\NotesForm;
use app\Entities\Notes\Repositories\NotesRepository;
use app\Entities\Tag\Entities\Tags;
use app\Entities\Tag\Forms\TagsForm;
use app\Entities\Tag\Repositories\TagsRepository;

class TagsService
{
    public function __construct(readonly public TagsRepository $repository)
    {}

    public function create(TagsForm $form): Tags
    {
        $tags = Tags::create($form);
        return $this->repository->save($tags);
    }

    public function edit(Tags $tags, TagsForm $form): Tags
    {
        $tags->edit($form);
        return  $this->repository->save($tags);
    }
}