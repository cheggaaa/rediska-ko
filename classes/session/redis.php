<?php
class Session_Redis extends Session
{

        const COOKIE_SESS_NAME = 's';
        const DEFAULT_LIFETIME = 3600;

        protected $_id;

        protected $_keys = array();

        public function id()
	{
            if (!$this->_id) {
                $this->_id = Cookie::get(self::COOKIE_SESS_NAME);
                if (!$this->_id) {
                    $this->_regenerate();
                }
                Cookie::set(self::COOKIE_SESS_NAME, $this->_id, $this->_lifetime);
            }
            
            return $this->_id;
	}

        /**
         * @return Rediska_Key
         */
        protected function _getRedisKey($id = null)
        {
            if ($id === null) {
                $id = $this->id();
            }
            if (!isset($this->_keys[$id])) {
                $rediskaKeyName = 'session::' . md5($id);
                $this->_keys[$id] = new Rediska_Key($rediskaKeyName);
                $lifetime = $this->_lifetime;
                if(!$lifetime) {
                    $lifetime = self::DEFAULT_LIFETIME;
                }
                $this->_keys[$id]->expire($lifetime);
            }
            return $this->_keys[$id];
        }

	protected function _read($id = NULL)
	{
            return $this->_getRedisKey($id)->getValue();
	}

	protected function _regenerate()
	{
            $this->_id = uniqid();
            return $this->_id;
	}

	protected function _write()
	{
            return $this->_getRedisKey()->setValue($this->_data);
	}

	protected function _destroy()
	{
            $this->_getRedisKey()->delete();
            Cookie::delete(self::COOKIE_SESS_NAME);
	}
}
?>
