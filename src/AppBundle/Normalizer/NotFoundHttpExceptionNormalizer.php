<?php


namespace App\AppBundle\Normalizer;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NotFoundHttpExceptionNormalizer
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\Normalizer
 */
class NotFoundHttpExceptionNormalizer extends AbstractNormalizer
{
    
    public function normalize(Exception $exception): array
    {
        $result['code'] = Response::HTTP_NOT_FOUND;
        
        $result['body'] = [
            'code' => Response::HTTP_NOT_FOUND,
            'message' => $exception->getMessage(),
        ];
        
        return $result;
    }
}