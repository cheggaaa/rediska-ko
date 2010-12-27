<?php

class Session_Redis extends Session
{
    /**
     * Cookie session key name
     */
    const COOKIE_SESS_NAME = 'SID';

    /**
     * Default session lifetime in seconds
     */
    const DEFAULT_LIFETIME = 3600;

    /**
     * Session id
     * @var string
     */
    protected $_id;
    
    /**
     * Rediska instance
     * @var Rediska
     */
    protected $_rediska;

    public function __construct()
    {
        parent::__construct();
        $this->_rediska = Rediska_Manager::get($this->_config['instance']);
    }

    /**
     * Return session id
     * @param string $id
     * @return string
     */
    public function id($id = null)
    {
        if ($id !== null) {
            return $id;
        }
        if (!$this->_id) {
            $this->_id = Cookie::get(self::COOKIE_SESS_NAME);
            if (!$this->_id) {
                $this->_regenerate();
            }
            Cookie::set(self::COOKIE_SESS_NAME, $this->_id, $this->_lifetime);
        }

        return $this->_id;
    }

    protected function _read($id = NULL)
    {
        return $this->_rediska->get($this->id($id));
    }

    protected function _regenerate()
    {
        $this->_id = uniqid();
        return $this->_id;
    }

    protected function _write()
    {
        return $this->_rediska->setAndExpire($this->id(), $this->_data, $this->_lifetime);
    }

    protected function _destroy()
    {
        Cookie::delete(self::COOKIE_SESS_NAME);
        return $this->_rediska->delete($this->id());
    }

}

?>
