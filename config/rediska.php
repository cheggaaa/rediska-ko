<?php
return array(
    'addtomanager' => true,
    'name'         => Rediska::DEFAULT_NAME,
    'namespace'    => '',
    'servers'      => array(
        array(
            'host'   => Rediska_Connection::DEFAULT_HOST,
            'port'   => Rediska_Connection::DEFAULT_PORT,
            'weight' => Rediska_Connection::DEFAULT_WEIGHT,
        )
    ),
    'serializeradapter' => 'phpSerialize',
    'keydistributor'    => 'consistentHashing',
    'redisversion'      => Rediska::STABLE_REDIS_VERSION,
);

?>
