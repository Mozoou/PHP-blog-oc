<?php

namespace App\Model;

use Core\Database\Db;

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

        foreach ($vars as $name => $value) {
            if ($value instanceof \DateTimeImmutable) {
                $vars[$name] = $value->format('Y-m-d');
            }
        }

        return $vars;
    }

    public function setDataFromArray(array $data): object
    {
        foreach ($data as $methodName => $value) {
            if ('password' === $methodName) {
                $method = 'set' . ucfirst($methodName);
                $value = password_hash((string) $value, null, []);
                $this->$method($value);
            } else {
                $method = 'set' . ucfirst($methodName);
                $this->$method($value);
            }
        }

        return $this;
    }

    protected function getAssociation(int $id, string $class): ?object
    {
        return Db::getInstance()->fetchOneById($class, $id);
    }
}
