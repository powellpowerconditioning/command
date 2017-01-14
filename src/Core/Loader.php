<?php

namespace Certification\Core;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Loader
 */
class Loader
{
    /**
     * @var integer
     */
    public static $count = null;

    /**
     * Returns a new set of randomized questions
     *
     * @param integer $number
     * @param array $categories
     *
     * @return Set
     */
    public static function init($number, array $categories, $path)
    {
        $data = self::prepareFromYaml($categories, $path);

        if (!$data) {
            return new Set(array());
        }

        if (null === $number) {
            $number = self::count($data);
        }

        $dataMax = count($data) - 1;

        $questions = array();

        for ($i = 0; $i < $number; $i++) {
            do {
                $random = rand(0, $dataMax);
            } while (isset($questions[$random]));

            $item = $data[$random];

            $answers = array();

            foreach ($item['answers'] as $dataAnswer) {
                $answers[] = new Answer($dataAnswer['value'], $dataAnswer['correct']);
            }

            if (!isset($item['shuffle']) || true === $item['shuffle']) {
                shuffle($answers);
            }

            $questions[$random] = new Question($item['question'], $item['category'], $answers, isset($item['description']) ? $item['description'] : null);
        }

        return new Set($questions);
    }

    /**
     * Counts total of available questions
     *
     * @return integer
     */
    public static function count()
    {
        if (is_null(self::$count)) {
            throw new \ErrorException('Questions were not loaded');
        }
        return self::$count;
    }

    /**
     * Get list of all categories
     *
     * @return array
     */
    public static function getCategories($path)
    {
        $categories = array();
        $files = self::prepareFromYaml(array(), $path);

        foreach ($files as $file) {
            $categories[] = $file['category'];
        }

        return array_unique($categories);
    }

    /**
     * Prepares data from Yaml files and returns an array of questions
     *
     * @param array $categories : List of categories which should be included, empty array = all
     *
     * @return array
     */
    protected static function prepareFromYaml(array $categories = array(), $path)
    {
        $data = array();
        self::$count = 0;
        $root = dirname($path) . '/../';
        $paths = Yaml::parse(file_get_contents($path))['paths'];

        foreach ($paths as $path) {
            $files = Finder::create()->files()->in($root . $path)->name('*.yml');

            foreach ($files as $file) {
                $fileData = Yaml::parse($file->getContents());

                $category = $fileData['category'];
                if (count($categories) == 0 || in_array($category, $categories)) {
                    array_walk($fileData['questions'], function (&$item, $key) use ($category) {
                        $item['category'] = $category;
                    });

                    $data = array_merge($data, $fileData['questions']);
                }
            }
        }
        self::$count = count($data);

        return $data;
    }
}
