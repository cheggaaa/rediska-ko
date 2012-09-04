<?php

/**
 * Insert a new value as the element before or after the reference value
 * 
 * @author Ivan Shumkov
 * @package Rediska
 * @subpackage Commands
 * @version 0.5.7
 * @link http://rediska.geometria-lab.net
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
class Rediska_Command_InsertToList extends Rediska_Command_InsertToListAbstract
{
    /**
     * Create command
     *
     * @param string  $key            Key name
     * @param string  $position       BEFORE or AFTER
     * @param mixed   $referenceValue Reference value
     * @param mixed   $value          Value
     * @return Rediska_Connection_Exec
     */
    public function create($key, $position, $referenceValue, $value)
    {
        return $this->_create($key, $position, $referenceValue, $value);
    }
}