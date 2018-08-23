<?php
/** 
 * @package Kekere Framework (23/08/2018)
 * @version 0.0.1
 * @license https://opensource.org/licenses/MIT
 * @author Tochukwu Nwachukwu <truetochukz@gmail.com> 
 * @link http://kekere.tochukwu.xyz 
 */

namespace App\Models;

/**
 * The DB class can be used as a generic model.
 */
class DB extends Model
{
    /**
     * Creates an instances of a model with a given table t backup th model.
     *
     * @return \Models\Model
     */
    public static function table(string $tableName)
    {
        $model = new DB();
        $model->table = $tableName;
        return $model;
    }
        
}