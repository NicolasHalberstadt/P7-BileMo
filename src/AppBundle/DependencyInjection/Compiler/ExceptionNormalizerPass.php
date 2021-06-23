<?php

namespace App\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ExceptionNormalizerPass
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\DependencyInjection\Compiler
 */
class ExceptionNormalizerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $exceptionListenerDef = $container
            ->findDefinition('app.exception_subscriber');
        $normalizers = $container->findTaggedServiceIds('app.normalizer');
        
        foreach ($normalizers as $id => $tags) {
            $exceptionListenerDef->addMethodCall(
                'addNormalizer',
                [new Reference($id)]
            );
        }
    }
}
