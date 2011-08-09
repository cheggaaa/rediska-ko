<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana Session driver
 */
class Session_Redis extends Session
{
    /**
     * Cookie session key name
     */
    protected $_cookie_name = 'SID';

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

    /**
     *
     * @param array $config
     * @param string $id
     */
    public function __construct(array $config = NULL, $id = NULL)
    {  
        // Check for the Rediska module
        if ( ! class_exists('Rediska')) 
        {
            throw new Kohana_Exception('Rediska module not loaded');
        }
        $instance = Arr::get($config, 'instance', Rediska::DEFAULT_NAME);
        $this->_cookie_name = Arr::get($config, 'cookie_name', $this->_cookie_name);
        $this->_rediska = Rediska_Manager::get($instance);
        parent::__construct($config, $id);
    }

    /**
     * Return session id
     * @param string $id
     * @return string
     */
    public function id($id = NULL)
    {
        if ($id !== NULL) 
        {
            return $id;
        }
        if ( ! $this->_id) 
        {
            $this->_id = Cookie::get($this->_cookie_name);
            if ( ! $this->_id) 
            {
                $this->_regenerate();
            }
            Cookie::set($this->_cookie_name, $this->_id, $this->_lifetime);
        }

        return $this->_id;
    }

    protected function _read($id = NULL)
    {
        $data = $this->_rediska->get($this->id($id));
        return (string)$data;
    }

    protected function _regenerate()
    {
        $this->_id = uniqid();
        return $this->_id;
    }

    protected function _write()
    {
        return $this->_rediska->setAndExpire($this->id(), (string)$this, $this->_lifetime);
    }
    
        
    protected function _restart()
    {
        return TRUE;
    }

    protected function _destroy()
    {
        Cookie::delete($this->_cookie_name);
        return $this->_rediska->delete($this->id());
    }

}

?>
