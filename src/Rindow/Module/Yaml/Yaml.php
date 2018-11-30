<?php
namespace Rindow\Module\Yaml;

use Rindow\Stdlib\Cache\CacheHandlerTemplate;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Rindow\Module\Yaml\Exception;

class Yaml
{
    public function toArray($input)
    {
        return self::parse($input);
    }

    public function fileToArray($filename)
    {
        return self::parseFile($filename);
    }

    public function fromArray($array)
    {
        return self::dump($array);
    }

    public static function ready()
    {
        if(extension_loaded('yaml'))
            return true;
        if(class_exists('Symfony\Component\Yaml\Yaml'))
            return true;
        return false;
    }

    public static function parse($input)
    {
        if(extension_loaded('yaml')) {
            set_error_handler(function($errno, $errstr) {
                restore_error_handler();
                throw new Exception\DomainException($errstr,$errno);
            });
            $array = yaml_parse($input);
            restore_error_handler();
            return $array;
        } else if(class_exists('Symfony\Component\Yaml\Yaml')) {
            try {
                return SymfonyYaml::parse($input);
            } catch(ParseException $e) {
                throw new Exception\DomainException($e->getMessage(),0,$e);
            }
        } else {
            throw new Exception\DomainException('Yaml extension is not loaded.');
        }
    }

    public static function parseFile($filename)
    {
        if(extension_loaded('yaml')) {
            set_error_handler(function($errno, $errstr) {
                restore_error_handler();
                throw new Exception\DomainException($errstr,$errno);
            });
            $array = yaml_parse_file($filename);
            restore_error_handler();
            return $array;
        } else if(class_exists('Symfony\Component\Yaml\Yaml')) {
            $input = file_get_contents($filename);
            try {
                return SymfonyYaml::parse($input);
            } catch(ParseException $e) {
                throw new Exception\DomainException($e->getMessage().' in '.$filename,0,$e);
            }
        } else {
            throw new Exception\DomainException('Yaml extension is not loaded.');
        }
    }

    public static function dump($array,$inline = 4)
    {
        if(extension_loaded('yaml')) {
            set_error_handler(function($errno, $errstr) {
                restore_error_handler();
                throw new Exception\DomainException($errstr,$errno);
            });
            $array = yaml_emit($array,YAML_UTF8_ENCODING);
            restore_error_handler();
            return $array;
        } elseif (class_exists('Symfony\Component\Yaml\Yaml')) {
            try {
                return SymfonyYaml::dump($array,$inline);
            } catch(ParseException $e) {
                throw new Exception\DomainException($e->getMessage(),0,$e);
            }
        } else {
            throw new Exception\DomainException('Yaml extension is not loaded.');
        }
    }
}