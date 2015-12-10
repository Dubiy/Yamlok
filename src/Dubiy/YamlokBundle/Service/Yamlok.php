<?php

namespace Dubiy\YamlokBundle\Service;


class Yamlok
{
    private $path = '';
    private $data = [];
    private $yaml = [];
    private $indent = 0;

    /**
     * Yamlok constructor.
     */
    /**
     * @param string $path
     */
    public function __construct($path = '')
    {
        $this->path = $path;
    }

    public function fromFile($path = null)
    {
        if ($path !== null) {
            $this->path = $path;
        }
        $this->yaml = file_get_contents($this->path);
        return $this->fromString();
    }

    public function toFile($path = null)
    {
        if ($path !== null) {
            $this->path = $path;
        }
        $this->yaml = $this->toString();
        file_put_contents($this->path, $this->yaml);
    }

    public function toString($data = null)
    {
        if ($data !== null) {
            $this->data = $data;
        }
        return $this->yaml = $this->arrayToYaml($this->data);
    }

    public function fromString($yaml = null)
    {
        if ($yaml !== null) {
            $this->yaml = $yaml;
        }
        $this->data = [];
        $lines = explode("\n", $this->yaml);
        $level = 0;
        $path = [];
        foreach ($lines as $line) {
            //skip comments
            if (!strlen(trim($line)) || trim($line)[0] == '#') {
                continue;
            }
            preg_match('/^(?P<indent>\s+)(.*)$/m', $line, $matches);

            if (!$this->indent && isset($matches['indent']) && $matches['indent']) {
                $this->indent = strlen($matches['indent']);
            }

            if ($this->indent && isset($matches['indent']) && $matches['indent']) {
                $level = (int)(strlen($matches['indent']) / $this->indent);
            }

            $parts = explode(':', $line, 2);
            $key = trim($parts[0]);

            if (count($parts) == 1 && strlen($key) && $key[0] == '-') {
                $key = '';
                $value = trim($parts[0], "- \t\n\r\0");
            } else {
                $value = trim($parts[1]);
            }

            $path[$level] = $key;

            $this->data = $this->updArr($this->data, $path, $level, $value);
        }
        return $this->data;
    }

    private function arrayToYaml($array, $level = 0) {
        $string = '';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if ($key) {
                    $string .= str_repeat(' ', $this->indent * $level) . $key . ":\n" . $this->arrayToYaml($value, $level + 1);
                } else {
                    $string .= $this->arrayToYaml($value, $level);
                }
            } else {
                if ($key === (int)$key) {
                    $string .= str_repeat(' ', $this->indent * $level) . '- ' . $value . "\n";
                } else {
                    $string .= str_repeat(' ', $this->indent * $level) . $key . ': ' . $value . "\n";
                }
            }

        }
        return $string;
    }

    private function updArr($array, $path, $level, $value, $currentLevel = 0)
    {
        if ($currentLevel < $level) {
            if (! isset($array[$path[$currentLevel]])) {
                $array[$path[$currentLevel]] = [];
            }

            $array[$path[$currentLevel]] = $this->updArr($array[$path[$currentLevel]], $path, $level, $value, $currentLevel + 1);
        }
        if ($level == $currentLevel) {
            if ($path[$level]) {
                $array[$path[$level]] = $value;
            } else {
                $array[$path[$level]][] = $value;
            }
        }
        return $array;
    }
}