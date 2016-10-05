<?php

namespace Symplify\NetteAdapaterForSymfonyBundles\Tests\Transformer;

use Nette\DI\ServiceDefinition;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symplify\NetteAdapaterForSymfonyBundles\Transformer\ArgumentsTransformer;
use Symplify\NetteAdapaterForSymfonyBundles\Transformer\ServiceDefinitionTransformer;

final class ServiceDefinitionTransformerTest extends TestCase
{
    /**
     * @var ServiceDefinitionTransformer
     */
    private $serviceDefinitionTransformer;

    protected function setUp()
    {
        $this->serviceDefinitionTransformer = new ServiceDefinitionTransformer(new ArgumentsTransformer());
    }

    public function testFactory()
    {
        $netteServiceDefinition = (new ServiceDefinition())->setFactory(stdClass::class, [1, 2, 3]);

        $symfonyServiceDefinition = $this->serviceDefinitionTransformer->transformFromNetteToSymfony(
            $netteServiceDefinition
        );

        $this->assertSame(stdClass::class, $symfonyServiceDefinition->getFactory());
        $this->assertSame([1, 2, 3], $symfonyServiceDefinition->getArguments());
    }
}
