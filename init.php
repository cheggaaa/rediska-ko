<?php
    require_once Kohana::find_file('vendor', 'rediska/Rediska.php');
    $config = Kohana::config('rediska');
    if (count($config)) {
        foreach ($config as $instanceName => $options) {
            Rediska_Manager::add(new Rediska($options));
        }
    }
?>
