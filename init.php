<?php
    require_once Kohana::find_file('vendor/rediska', 'Rediska');
    
    $config = Kohana::$config->load('rediska');   
    foreach ($config as $options) 
    {
        Rediska_Manager::add($options);
    }
?>