<?php defined('SYSPATH') or die('No direct script access.');

class Cache_Redis extends Cache {

    /**
     *
     * @var Rediska
     */
    protected $_rediska;

    /**
     * Constructs the redis Kohana_Cache object
     *
     * @param   array     configuration
     * @throws  Kohana_Cache_Exception
     */
    public function __construct(array $config) {
        // Check for the Rediska module
        if (!class_exists('Rediska')) {
            throw new Kohana_Cache_Exception('Rediska module not loaded');
        }

        parent::__construct($config);

        $rediskaInstanceName = Arr::get($this->_config, 'instance', Rediska::DEFAULT_NAME);
        $this->_rediska = Rediska_Manager::get($rediskaInstanceName);
    }

    /**
     * Retrieve a cached value entry by id.
     * @param   string   id of cache to entry
     * @param   string   default value to return if cache miss
     * @return  mixed
     * @throws  Kohana_Cache_Exception
     */
    public function get($id, $default = NULL) {
        $key = $this->_getKey($id);
        
        if (!$this->_rediska->exists($key)) {
            $value = $default;
        } else {
            $value = $this->_rediska->get($key);
        }

        // Return the value
        return $value;
    }

    /**
     * Set a value to cache with id and lifetime
     * @param   string   id of cache entry
     * @param   mixed    data to set to cache
     * @param   integer  lifetime in seconds
     * @return  boolean
     */
    public function set($id, $data, $lifetime = 3600) {
        $key = $this->_getKey($id);
        $this->_rediska->setAndExpire($key, $data, $lifetime);
    }

    /**
     * Delete a cache entry based on id
     * @param   string   id of entry to delete
     * @param   integer  timeout of entry, if zero item is deleted immediately, otherwise the item will delete after the specified value in seconds
     * @return  boolean
     */
    public function delete($id, $timeout = 0) {
        $key = $this->_getKey($id);
        if ($timeout == 0) {
            $this->_rediska->delete($key);
        } else {
            $this->_rediska->setAndExpire($key, $this->get($id), $timeout);
        }
        return true;
    }

    /**
     * Delete all cache entries.
     * @return  boolean
     */
    public function delete_all() {
        return $this->_rediska->flushDb();
    }


    protected function _getKey($id)
    {
        return $this->_sanitize_id($id);
    }

}

?>