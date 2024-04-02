<?php

namespace app\controllers;

use app\Entities\Notes\Entities\Notes;
use app\Entities\Notes\Forms\NotesForm;
use app\Entities\Notes\Services\NotesService;
use app\SearchForms\NotesSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NotesController implements the CRUD actions for Notes model.
 */
class NotesController extends Controller
{

    private NotesService $notesService;

    public function __construct($id, $module, NotesService $notesService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->notesService = $notesService;
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'update', 'view', 'delete','tag'],
                            'allow'   => true,
                            'roles'   => ['@'],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Notes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new NotesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $tags = ArrayHelper::map($this->notesService->tagsRepository->getAllByQuery(),'id','name');
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => $tags
        ]);
    }

    public function actionView($id)
    {
        try {
            return $this->render('view', [
                'model' => $this
                    ->notesService
                    ->repository->findOneByQuery([
                        'id'      => $id,
                        'user_id' => \Yii::$app->user->identity->id,
                    ]),
            ]);
        } catch (NotFoundHttpException $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect('index');
        }
    }


    public function actionCreate()
    {
        $model = new NotesForm();
        $tags = ArrayHelper::map($this->notesService->tagsRepository->getAllByQuery(),'id','name');
        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $notes = $this->notesService->create($model);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $notes->id]);
            } catch (\RuntimeException $e) {
                \Yii::$app->session->setFlash('error', $e->getMessage());
                $transaction->rollBack();
            }
        }
        return $this->render('create', [
            'model' => $model,
            'tags' => $tags,
        ]);
    }

    public function actionUpdate($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $tags = ArrayHelper::map($this->notesService->tagsRepository->getAllByQuery(),'id','name');
        try {
            $notes = $this
                ->notesService
                ->repository->findOneByQuery([
                    'id'      => $id,
                    'user_id' => \Yii::$app->user->identity->id,
                ]);
            $model = new NotesForm($notes);
            if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
                $this->notesService->edit($notes, $model);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $transaction->rollBack();
            return $this->render('update', [
                'model' => $model,
                'tags' => $tags,
            ]);
        } catch (NotFoundHttpException $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
            $transaction->rollBack();
            return $this->redirect('index');
        } catch (\RuntimeException $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
            $transaction->rollBack();
            return $this->redirect(['url', 'id' => $id]);
        }
    }

    public function actionDelete($id)
    {
        try{
            $this->notesService->repository->delete($id);
        }catch (NotFoundHttpException $e) {
            \Yii::$app->session->setFlash('error',$e->getMessage());
        } finally {
            return $this->redirect(['index']);
        }
    }

    public function actionTag (string $tag)
    {
        $searchModel = new NotesSearch();
        $array['NotesSearch'] = ['tag'=>$tag];
        $dataProvider = $searchModel->search($array);
        $tags = ArrayHelper::map($this->notesService->tagsRepository->getAllByQuery(),'id','name');

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => $tags
        ]);
    }

}
