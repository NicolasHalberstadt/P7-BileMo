<?php


namespace App\AppBundle;

use App\AppBundle\DependencyInjection\Compiler\ExceptionNormalizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AppBundle
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\AppBundle
 */
class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ExceptionNormalizerPass());
    }
}