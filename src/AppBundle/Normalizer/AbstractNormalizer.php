<?php


namespace App\AppBundle\Normalizer;

use Exception;

/**
 * Class AbstractNormalizer
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\Normalizer
 */
abstract class AbstractNormalizer implements NormalizerInterface
{
    protected $exceptionTypes;
    
    public function __construct(array $exceptionTypes)
    {
        $this->exceptionTypes = $exceptionTypes;
    }
    
    public function supports(Exception $exception): bool
    {
        return in_array(get_class($exception), $this->exceptionTypes);
    }
}