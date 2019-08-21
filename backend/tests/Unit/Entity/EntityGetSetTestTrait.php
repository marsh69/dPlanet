<?php

namespace App\Tests\Unit\Entity;

trait EntityGetSetTestTrait
{
    /**
     * Get and set all the things!
     *
     * We basically loop through the getters and setters, set values and then get them to see
     * if it returns the same object
     *
     * @throws \ReflectionException
     */
    public function testGettersAndSetters(): void
    {
        $this->validateConfiguration();

        $reflectionInstanceOfClass = new \ReflectionClass($this->class);
        $this->validateTestedClass($reflectionInstanceOfClass);

        $concreteInstanceOfClass = new $this->class();

        $storedControlValues = [];

        /** @var \ReflectionMethod[]|array $entityMethods */
        $entityMethods = $this->sortArrayOfMethods($reflectionInstanceOfClass->getMethods());

        if (!isset($this->excludedGetters)) {
            $this->excludedGetters = [];
        }

        foreach ($entityMethods as $method) {
            $methodName = $method->getName();
            $methodVariable = lcfirst(substr($methodName, 3));

            if (
                !$methodName ||
                !$this->isMethodGetterOrSetter($methodName) ||
                !$reflectionInstanceOfClass->hasProperty($methodVariable) ||
                !$this->hasProperGetterAndSetter($methodVariable, $reflectionInstanceOfClass) ||
                in_array($methodName, $this->excludedGetters)
            ) {
                continue;
            }

            if ($this->isSetter($methodName)) {
                $appropriateValue = $this->getAppropriateValueForMethod($method);
                $concreteInstanceOfClass->$methodName($appropriateValue);
                $storedControlValues[$methodVariable] = $appropriateValue;
            } elseif ($this->isGetter($methodName)) {
                $returnValue = $concreteInstanceOfClass->$methodName();
                $this->assertSame($storedControlValues[$methodVariable], $returnValue);
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    protected function validateConfiguration(): void
    {
        if (!$this->class || empty($this->class)) {
            throw new \ReflectionException('You can only use this trait if you have configured a $class');
        }
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @throws \ReflectionException
     */
    protected function validateTestedClass(\ReflectionClass $reflectionClass)
    {
        if (
            $reflectionClass->getConstructor() &&
            $reflectionClass->getConstructor()->getNumberOfParameters() !== 0 &&
            isset($this->ignoreConstructor) &&
            !$this->ignoreConstructor
        ) {
            throw new \ReflectionException($this->class . ' has a special constructor and can not be used in this trait');
        }
    }

    /**
     * @param \ReflectionMethod[]|array $entityMethods
     * @return array
     */
    protected function sortArrayOfMethods(array $entityMethods): array
    {
        usort($entityMethods, function (\ReflectionMethod $method) {
            if ($this->isSetter($method->getName())) {
                return -1;
            } elseif ($this->isGetter($method->getName())) {
                return 1;
            }
            return 0;
        });

        return $entityMethods;
    }

    /**
     * Check if method has a 'set' or 'get' prefix and determine if it's a testable method
     *
     * @param $methodName
     * @return bool
     */
    protected function isMethodGetterOrSetter(string $methodName): bool
    {
        return $this->isSetter($methodName) || $this->isGetter($methodName);
    }

    /**
     * Check if the $methodName has 'set' as prefix
     *
     * @param string $methodName
     * @return bool
     */
    protected function isSetter(string $methodName): bool
    {
        return strpos($methodName, 'set') === 0;
    }

    /**
     * Check if the $methodName has 'get' as prefix
     *
     * @param string $methodName
     * @return bool
     */
    protected function isGetter(string $methodName): bool
    {
        return strpos($methodName, 'get') === 0;
    }

    /**
     * Make sure the entity actually has these methods by adding 'set' and 'get' to the variable name
     *
     * @param string $methodVariable
     * @param \ReflectionClass $entity
     * @return bool
     */
    protected function hasProperGetterAndSetter(string $methodVariable, \ReflectionClass $entity): bool
    {
        $methodName = ucfirst($methodVariable);
        return $entity->hasMethod("get$methodName") && $entity->hasMethod("set$methodName");
    }

    /**
     * Determine what value we should give the setter
     *
     * First we check if the first parameter actually has anything
     * If not we just return a string
     *
     * If yes then we return a value based on the name of the type
     * If the name is null then we just return a string again
     * if it is an ArrayCollection, return a new ArrayCollection
     *
     * If, however, the type is something special, we just mock the thing and send it on its way
     * Note that we also ignore missing methods, after all we're testing the get/setters not
     * anything else
     *
     * @param \ReflectionMethod $method
     * @return mixed
     */
    protected function getAppropriateValueForMethod(\ReflectionMethod $method)
    {
        $parameterType = $method->getParameters()[0]->getType();

        if (!$parameterType) {
            return "Random string";
        }

        switch ($parameterType->getName()) {
            case 'string':
                return "Another random string";
            case 'int':
                return 30;
            case 'bool':
                return false;
            case 'array':
                return ['ArrayOfThings'];
            case null:
                return "Does not matter!";
            default:
                return $this->createMock(($parameterType->getName()));
        }
    }
}
