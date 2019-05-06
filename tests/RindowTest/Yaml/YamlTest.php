<?php
namespace RindowTest\Yaml\YamlTest;

use PHPUnit\Framework\TestCase;
use Rindow\Stdlib\Entity\AbstractEntity;
use Rindow\Module\Yaml\Yaml;
use Rindow\Container\ModuleManager;

class Normal extends AbstractEntity
{
    protected $email;
}

class YamlError extends AbstractEntity
{
    protected $email;
}

class Test extends TestCase
{
    public static $skip = false;
    public static function setUpBeforeClass()
    {
        if (!Yaml::ready()) {
            self::$skip = true;
            return;
        }
    }

    public function setUp()
    {
        if(self::$skip) {
            $this->markTestSkipped();
            return;
        }
    }

    public function testNormal()
    {
        $config = array(
            'module_manager' => array(
                'modules' => array(
                    'Rindow\Module\Yaml\Module' => true,
                ),
                'enableCache'=>false,
            ),
        );
        $mm = new ModuleManager($config);

        $yaml = $mm->getServiceLocator()->get('Rindow\Module\Yaml\Yaml');
        $array = $yaml->toArray(file_get_contents(__DIR__.'/resources/Normal.form.yml'));
        $answer = array(
            'RindowTest\Web\Form\Builder\YamlBuilderTest\Normal' => array(
                'attributes' => array(
                    'action' => '/app/form',
                    'method' => 'post',
                ),
                'properties' => array(
                    'email' => array(
                        'type' => 'email',
                        'label' => 'Email',
                    ),
                ),
            ),
        );
        $this->assertEquals($answer,$array);
    }

    /**
     * @expectedException        Rindow\Module\Yaml\Exception\DomainException
     */
    public function testYamlError()
    {
        $config = array(
            'module_manager' => array(
                'modules' => array(
                    'Rindow\Module\Yaml\Module' => true,
                ),
            ),
        );
        $mm = new ModuleManager($config);

        $yaml = $mm->getServiceLocator()->get('Rindow\Module\Yaml\Yaml');
        $array = $yaml->toArray(file_get_contents(__DIR__.'/resources/YamlError.form.yml'));
    }
}