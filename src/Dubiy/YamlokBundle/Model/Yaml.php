<?php


namespace Dubiy\YamlokBundle\Model;


use Mcfedr\AwsPushBundle\Message\Message;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class Yaml
{
    private $data = [];

    /**
     * Yaml constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function __call($func, $params){
        if (strpos($func, 'get') === 0) {
            $key = strtolower(substr($func, 3));
            if (isset($this->data[$key])) {
                return $this->data[$key];
            } else {
                return null;
            }
        }

        if (strpos($func, 'set') === 0) {
            $key = strtolower(substr($func, 3));
            return $this->data[$key] = $params[0];
        }
    }
}
