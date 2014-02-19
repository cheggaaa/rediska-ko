<?php defined('SYSPATH') or die('No direct script access.');
class Kohana_Session_RedisHash extends Session {
    
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
     * @var boolean 
     */
    protected $_use_cookie = TRUE;

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
            if ($this->_id && $this->_id != $id)
            {
                $this->_rename($id);
            }
            $this->_id = $id;
            if ($this->_use_cookie)
            {
                Cookie::set($this->_cookie_name, $this->_id, $this->_lifetime());
            }
        }
        if ( ! $this->_id) 
        {
            if ($this->_use_cookie)
            {
                $this->_id = Cookie::get($this->_cookie_name);
            }
            if ( ! $this->_id) 
            {
                $this->_regenerate();
            }
            if ($this->_use_cookie)
            {
                Cookie::set($this->_cookie_name, $this->_id, $this->_lifetime());
            }
        }

        return $this->_id;
    }
    
    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Kohana_Session_RedisHash 
     */
    public function set($key, $value)
    {
        $this->_rediska->setToHash($this->id(), $key, $value);
        return $this;
    }
    
    /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed 
     */
    public function get($key, $default = NULL)
    {
        $value = $this->_rediska->getFromHash($this->id(), $key);
        return $value === NULL ? $default : $value;
    }
    
    /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed 
     */
    public function get_once($key, $default = NULL)
    {
        $value = $this->get($key, $default);
        $this->delete($key);
        return $value;
    }
    
    /**
     *
     * @param string $key
     * @return Session_RedisHash 
     */
    public function delete($key)
    {
        $this->_rediska->deleteFromHash($this->id(), $key);
        return $this;
    }
    
    /**
     *
     * @param type $id
     * @return Session_RedisHash 
     */
    protected function _rename($id)
    {
        if ($this->_rediska->exists($this->_id))
        {
            $this->_rediska->rename($this->_id, $id);
        }
        return $this;
    }
    
    /**
     *
     * @param boolean $is
     * @return Session_RedisHash 
     */
    public function use_cookie($is = FALSE)
    {
        $this->_use_cookie = (bool)$is;
        return $this;
    }

    protected function _read($id = NULL)
    {
        if ($id)
        {
            $this->id($id);
        }
        return NULL;
    }

    protected function _regenerate()
    {
        $this->_id = uniqid() . text::random(NULL, 4);;
        return $this->_id;
    }
    

    protected function _write()
    {
        return $this->_rediska->expire($this->id(), $this->_lifetime());
    }
    
    protected function _lifetime()
    {
        return $this->get('_lifetime', $this->_lifetime);
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