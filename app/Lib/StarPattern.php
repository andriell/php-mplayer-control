<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 02.05.2017
 * Time: 12:41
 */

namespace App\Lib;

class StarPattern {
    private $firstStar = false;
    private $lastStar = false;
    private $pattern = [];
    private $patternCount = 0;

    /**
     * StarPattern constructor.
     * @param $strPattern
     */
    public function __construct($strPattern)
    {
        $this->firstStar = mb_substr($strPattern, 0, 1) == '*';
        $this->lastStar = mb_substr($strPattern, -1, 1) == '*';
        $tmp = mb_split('\\*', mb_strtolower($strPattern));
        $this->patternCount = 0;
        foreach ($tmp as $p) {
            if (empty($p)) {
                continue;
            }
            $this->pattern[] = [
                'str' => $p,
                'len' => mb_strlen($p),
            ];
            $this->patternCount++;
        }
    }

    public function match($str) {
        if ($this->patternCount == 0) {
            return true;
        }
        $str = mb_strtolower($str);
        if (!$this->firstStar && mb_substr($str, 0, $this->pattern[0]['len']) != $this->pattern[0]['str']) {
            return false;
        }
        if (!$this->lastStar && mb_substr($str, -1 * $this->pattern[$this->patternCount - 1]['len']) != $this->pattern[$this->patternCount - 1]['str']) {
            return false;
        }
        $i = 0;
        foreach ($this->pattern as $p) {
            $i = mb_strrpos($str, $p['str'], $i);
            if ($i === false) {
                return false;
            }
            $i++;
        }
        return true;
    }
}