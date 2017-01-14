<?php

namespace Certification\Core;

/**
 * Class Answer
 */
class Answer
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $correct;

    /**
     * Constructor
     *
     * @param string $value
     * @param bool   $correct
     */
    public function __construct($value, $correct)
    {
        $this->value   = $value;
        $this->correct = $correct;
    }

    /**
     * Returns answer value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns if answer is correct
     *
     * @return bool
     */
    public function isCorrect()
    {
        return $this->correct;
    }
}
