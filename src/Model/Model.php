<?php

namespace App\Model;

abstract class Model
{
    abstract public static function getTable(): string;

    /**
     * Transform Object to array
     * @return []
     */
    public function toArray(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function setDataFromArray(array $data): object
    {
        foreach ($data as $methodName => $value) {
            if ('password' === $methodName) {
                $method = 'set' . ucfirst($methodName);
                $value = password_hash($value, null, []);
                $this->$method($value);
            } else {
                $method = 'set' . ucfirst($methodName);
                $this->$method($value);
            }
        }

        return $this;
    }
}
