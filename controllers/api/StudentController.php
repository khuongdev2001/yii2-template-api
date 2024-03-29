<?php

namespace app\controllers\api;

use Yii;
use app\models\Student;
use yii\rest\ActiveController;
use yii\data\Pagination;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

class StudentController extends ActiveController
{
    public $modelClass = "app\models\Student";

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (in_array(Yii::$app->controller->action->id, [
            "create", "update", "delete"
        ])) {
            $behaviors['authenticator'] = [
                'class' => CompositeAuth::class,
                'authMethods' => [
                    HttpBasicAuth::class,
                    HttpBearerAuth::class,
                    QueryParamAuth::class,
                ],
            ];
        }
        return $behaviors;
    }

    /**
     * Here is method get students without header bearear
     */
    public function actionIndex()
    {
        /**
         * [ 
         *  item: set page size
         *  page: set page current
         * ]
         */
        $request = Yii::$app->request;
        $params = $request->get();
        $query = Student::find();
        $countQuery = clone $query;
        $pages = new Pagination([
            "totalCount" => $countQuery->count(),
            "page" => $params["page"] ?? 0,
            "pageSize" => $params["item"] ?? 1
        ]);
        $models = $query
            ->select(["id", "student_name"])
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        $this->responseJson(true, $models, "");
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            return $this->responseJson(false, null, "Method not allow", 405);
        };
        $studentModel = new Student();
        $studentModel->load($request->post(), "");
        if ($studentModel->save()) {
            return $this->responseJson(true, [
                "student" => $studentModel
            ], "Thêm thành công student");
        }
        return $this->responseJson(false, $studentModel->getErrors());
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $studentModel = new Student(["scenario" => Student::SCENARIO_UPDATE]);
        /* add option update validate */
        $studentModel->load($request->getBodyParams(), "");
        $student = $studentModel->findOne(["id" => $request->get("id")]);
        if (!$studentModel->validate()) {
            return $this->responseJson(false, $studentModel->getErrors());
        }
        if ($student) {
            $student->load($request->getBodyParams(), "");
            $student->save();
            return $this->responseJson(true, [
                "student" => $student
            ], "Cập nhật thành công");
        }
        return $this->responseJson(false, null, "Get id in table students not found", 404);
    }

    public function actionDelete()
    {
        $request = Yii::$app->request;
        $studentModel = new Student();
        /* add option delete validate */
        $studentModel->scenario = Student::SCENARIO_DELETE;
        $studentModel->load($request->getBodyParams(), "");
        if ($studentModel->validate()) {
            $studentModel->deleteAll(["id" => $studentModel["id"]]);
            return $this->responseJson(true, null, "Xóa thành công học sinh");
        }
        return $this->responseJson(false, $studentModel->getErrors());
    }

    public function actionGet()
    {
    }

    /**
     * @overwrite
     */
    public function actions()
    {
    }
}
