<?php

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symnedi\SymfonyBundlesExtension\Transformer;

use Nette\DI\ContainerBuilder as NetteContainerBuilder;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symnedi\SymfonyBundlesExtension\Utils\Naming;

final class ArgumentsTransformer
{
    /**
     * @var NetteContainerBuilder
     */
    private $netteContainerBuilder;

    /**
     * @var ServiceDefinitionTransformer
     */
    private $serviceDefinitionTransformer;

    public function setContainerBuilder(NetteContainerBuilder $netteContainerBuilder)
    {
        $this->netteContainerBuilder = $netteContainerBuilder;
    }

    public function transformFromSymfonyToNette(array $arguments) : array
    {
        foreach ($arguments as $key => $argument) {
            $arguments[$key] = $this->transformArgument($argument);
        }

        return $arguments;
    }

    /**
     * @param Reference|Definition|array $argument
     *
     * @return null|string
     */
    private function transformArgument($argument)
    {
        if ($argument instanceof Reference) {
            return $this->determineServiceName($argument);
        } elseif (is_array($argument)) {
            return $this->transformFromSymfonyToNette($argument);
        } elseif ($argument instanceof Definition) {
            $name = Naming::sanitazeClassName($argument->getClass());
            $netteServiceDefinition = $this->getServiceDefinitionTransformer()->transformFromSymfonyToNette(
                $argument
            );
            $this->netteContainerBuilder->addDefinition($name, $netteServiceDefinition);

            return '@'.$name;
        }

        return $argument;
    }

    private function determineServiceName(Reference $argument) : string
    {
        $name = (string) $argument;
        if ($name[0] === '@') {
            $className = (new ReflectionClass(substr($name, 1)))->getName();
            $this->netteContainerBuilder->prepareClassList();
            $name = $this->netteContainerBuilder->getByType($className);
        }

        return '@'.$name;
    }

    private function getServiceDefinitionTransformer() : ServiceDefinitionTransformer
    {
        if ($this->serviceDefinitionTransformer === null) {
            $this->serviceDefinitionTransformer = new ServiceDefinitionTransformer($this);
        }

        return $this->serviceDefinitionTransformer;
    }
}
