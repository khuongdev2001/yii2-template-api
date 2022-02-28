<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "student_subjects".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $score
 * @property string $created_at
 * @property string $updated_at
 * @property integer $subject_id
 *
 * @property \app\models\Student $student
 * @property \app\models\Subject $subject
 * @property string $aliasModel
 */
abstract class StudentSubject extends \yii\db\ActiveRecord
{
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at=time();
            return true;
        }
        $this->updated_at=time();
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_subjects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'score','subject_id'], 'required'],
            [['student_id', 'score', 'subject_id'],'number', 'max'=>10],
            [['created_at', 'updated_at'], 'string', 'max' => 200],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Student::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Subject::className(), 'targetAttribute' => ['subject_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'score' => 'Score',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'subject_id' => 'Subject ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\app\models\Student::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(\app\models\Subject::className(), ['id' => 'subject_id']);
    }


    
    /**
     * @inheritdoc
     * @return \app\models\StudentSubjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\StudentSubjectQuery(get_called_class());
    }


}
