<?php
namespace Rindow\Module\Yaml;

class Module
{
    public function getConfig()
    {
        return array(
            'container' => array(
                'components' => array(
                    'Rindow\Module\Yaml\Yaml' => array(
                        //'factory' => 'Rindow\Module\Yaml\Yaml::factory',
                    ),
                ),
            ),
        );
    }
}
