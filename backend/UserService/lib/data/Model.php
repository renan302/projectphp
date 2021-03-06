<?php

abstract class Model
{

    private $properties = [];
    private $validates = [];
    private $values = [];
    protected $primarykey = "id";

    public function __construct()
    {
        foreach ($this->properties as $value) {
            $this->values[$value] = "";
        }
    }

    public function setValues(array $data)
    {
        foreach ($data as $key => $value) {
            $this->setValue($key, $value);
        }
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getProperties($includeprimarykey = true)
    {

        $properties = $this->properties;

        $data = [];

        foreach ($properties as $key => $value) {

            if ($key == $this->primarykey and !$includeprimarykey) {
                continue;
            }

            $data[$value] = $value;
        }

        return $data;
    }

    public function getParamsToQuery($includeprimarykey = true)
    {

        $properties = $this->properties;

        $data = [];

        foreach ($properties as $key => $value) {

            if ($key == $this->primarykey and !$includeprimarykey) {
                continue;
            }

            $data[$value] = ":" . $value;
        }

        return $data;
    }

    public function getValuesParamsToQuery($includeprimarykey = true)
    {

        $properties = $this->properties;

        $data = [];

        foreach ($properties as $key => $value) {

            if ($key == $this->primarykey and !$includeprimarykey) {
                continue;
            }

            $data[":" . $value] = $this->values[$value];
        }

        return $data;
    }

    public function getParamsToUpdateQuery($includeprimarykey = true)
    {

        $data = [];

        foreach ($this->properties as $key => $value) {

            if ($key == $this->primarykey and !$includeprimarykey) {
                continue;
            }

            $data[$value] =  $value ."=:" . $value;
        }

        return $data;
    }

    private function setValue($property, $value)
    {
        if (in_array($property, $this->properties)) {
            $this->validateValue($property, $value);
            $this->values[$property] = $value;
        }
    }

    public function __set($property, $value)
    {
        $this->setValue($property, $value);
    }

    public function __get($property)
    {
        return $this->values[$property];
    }

    public function validateValue($property, $value)
    {
        foreach ($this->validates[$property] as $validator) {
            $validator->validate($property, $value);
        }
    }

    public function validate()
    {
        foreach ($this->values as $key => $value) {
            $this->validateValue($key, $value);
        }
    }

    public function setProperty(string $property, array $validates = [])
    {
        if (in_array($property, $this->properties)) {
            throw new Exception("Property exists!");
        }

        foreach ($validates as $validator) {
            if (!($validator instanceof Validator)) {
                throw new Exception("Validates invalid!");
            }
        }

        $this->properties[] = $property;
        $this->validates[$property] = $validates;
    }
}
