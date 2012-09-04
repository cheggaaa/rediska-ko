<?php

/**
 * Rediska serializer adapter interface
 * 
 * @author Ivan Shumkov
 * @package Rediska
 * @subpackage Serializer
 * @version 0.5.7
 * @link http://rediska.geometria-lab.net
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
interface Rediska_Serializer_Adapter_Interface
{
    /**
     * Serialize value
     *
     * @param mixed $value
     * @return string
     */
    public function serialize($value);
    
    /**
     * Unserialize value
     *
     * @param string $value
     * @return mixed
     */
    public function unserialize($value);
}