<?php

namespace phpDocumentor\Descriptor\Builder\Reflector\Tags;


use Mockery as m;
use phpDocumentor\Descriptor\Collection;
use phpDocumentor\Descriptor\ProjectDescriptorBuilder;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use phpDocumentor\Reflection\DocBlock\Type\Collection as TypeCollection;

class ReturnAssemblerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ReturnAssembler */
    private $fixture;

    /** @var m\MockInterface|ProjectDescriptorBuilder */
    private $builder;

    /**
     * Initializes the fixture for this test.
     */
    public function setUp()
    {
        $this->builder = m::mock('phpDocumentor\Descriptor\ProjectDescriptorBuilder');
        $this->fixture = new ReturnAssembler();
        $this->fixture->setBuilder($this->builder);
    }

    /**
     * @covers phpDocumentor\Descriptor\Builder\Reflector\Tags\ReturnAssembler::create
     */
    public function testCreatingReturnDescriptorFromReflector()
    {
        $types = new Collection();
        $this->builder->shouldReceive('buildDescriptor')
            ->with(
                m::on(
                    function ($value) {
                        return $value instanceof TypeCollection && $value[0] == 'string';
                    }
                )
            )
            ->andReturn($types);
        $reflector = new ReturnTag('return', 'string This is a description');

        $descriptor = $this->fixture->create($reflector);

        $this->assertSame('return', $descriptor->getName());
        $this->assertSame('This is a description', $descriptor->getDescription());
        $this->assertSame($types, $descriptor->getTypes());
    }
}
