<?php

/**
 * Remove the specified member from the Set value at key
 * 
 * @author Ivan Shumkov
 * @package Rediska
 * @subpackage Commands
 * @version 0.5.7
 * @link http://rediska.geometria-lab.net
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
class Rediska_Command_DeleteFromSet extends Rediska_Command_Abstract
{
    /**
     * Create command
     *
     * @param string $key    Key name
     * @param mixed  $member Member
     * @return Rediska_Connection_Exec
     */
    public function create($key, $member)
    {
        $connection = $this->_rediska->getConnectionByKeyName($key);

        $member = $this->_rediska->getSerializer()->serialize($member);

        $command = array('SREM',
                         $this->_rediska->getOption('namespace') . $key,
                         $member);

        return new Rediska_Connection_Exec($connection, $command);
    }

    /**
     * Parse response
     * 
     * @param string $response
     * @return boolean
     */
    public function parseResponse($response)
    {
        return (boolean)$response;
    }
}