<?php

namespace app\controllers;

use app\Entities\Tag\Entities\Tags;
use app\Entities\Tag\Forms\TagsForm;
use app\Entities\Tag\Services\TagsService;
use app\SearchForms\TagsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TagsController implements the CRUD actions for Tags model.
 */
class TagsController extends Controller
{
    private TagsService $service;

    public function __construct($id, $module, TagsService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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
                            'actions' => ['index', 'create', 'update', 'view', 'delete'],
                            'allow'   => true,
                            'roles'   => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new TagsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id)
    {
        try{
            return $this->render('view', [
                'model' => $this->service->repository->get($id),
            ]);
        }catch (NotFoundHttpException $e) {
            \Yii::$app->session->setFlash('error',$e->getMessage());
            return $this->redirect('index');
        }
    }

    public function actionCreate()
    {
        $model = new TagsForm();
        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
                try{
                    $tags = $this->service->create($model);
                    return $this->redirect(['view', 'id' => $tags->id]);
                }catch (\RuntimeException $e) {
                    \Yii::$app->session->setFlash('error',$e->getMessage());
                }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id)
    {
        try{
            $tags = $this->service->repository->get($id);
            $model = new TagsForm($tags);
            if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
                $this->service->edit($tags,$model);
                return $this->redirect(['view', 'id' => $tags->id]);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }catch (NotFoundHttpException $e) {
            \Yii::$app->session->setFlash('error',$e->getMessage());
            return $this->redirect('index');
        }catch (\RuntimeException $e) {
            \Yii::$app->session->setFlash('error',$e->getMessage());
            return $this->redirect(['update',['id'=>$id]]);
        }
    }

    public function actionDelete(int $id)
    {
        try{
            $this->service->repository->delete($id);
        } catch (NotFoundHttpException $e) {
            \Yii::$app->session->setFlash('error',$e->getMessage());
        } finally {
            return $this->redirect(['index']);
        }
    }

}
