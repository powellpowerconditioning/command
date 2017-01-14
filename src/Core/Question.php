<?php

namespace Certification\Core;

/**
 * Class Question
 */
class Question
{
    /**
     * @var string
     */
    protected $question;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var array
     */
    protected $answers;

    /**
     * @var bool
     */
    protected $multipleChoice;

    /**
     * @var string
     */
    protected $description;

    /**
     * Constructor
     *
     * @param string $question
     * @param string $category
     * @param array  $answers
     * @param string $description
     */
    public function __construct($question, $category, array $answers, $description = null)
    {
        $this->question       = $question;
        $this->category       = $category;
        $this->answers        = $answers;
        $this->multipleChoice = count($this->getCorrectAnswersValues()) > 1 ? true : false;
        $this->description = $description;
    }

    /**
     * Returns question label
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Returns question category name
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns question available answers
     *
     * @return array
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Returns question correct answers values
     *
     * @return array
     */
    public function getCorrectAnswersValues()
    {
        $answers = array();

        foreach ($this->getAnswers() as $answer) {
            if ($answer->isCorrect()) {
                $answers[] = $answer->getValue();
            }
        }

        return $answers;
    }

    /**
     * Returns if given answers are correct answers
     *
     * @param array $answers
     *
     * @return bool
     */
    public function areCorrectAnswers(array $answers)
    {
        if (!$answers) {
            return false;
        }

        $correctAnswers = $this->getCorrectAnswersValues();

        if (count($correctAnswers) != count($answers)) {
            return false;
        }

        foreach ($answers as $answer) {
            if (!in_array($answer, $correctAnswers)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns question available answers labels
     *
     * @return array
     */
    public function getAnswersLabels()
    {
        $answers = array();

        foreach ($this->getAnswers() as $answer) {
            $answers[] = $answer->getValue();
        }

        return $answers;
    }

    /**
     * Returns whether multiple answers are correct for this question
     *
     * @return bool
     */
    public function isMultipleChoice()
    {
        return $this->multipleChoice;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
