<?php

namespace app\models;

use Yii;
use \app\models\base\StudentSubject as BaseStudentSubject;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "student_subjects".
 */
class StudentSubject extends BaseStudentSubject
{
    public function fields()
    {
        return ArrayHelper::merge(
            parent::fields(),
            [
                "student"=>"student",
                "subject"=>"subject"
            ]
        );
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}
