<?php

namespace App\AppBundle\Normalizer;

use Exception;

interface NormalizerInterface
{
    public function normalize(Exception $exception);
    
    public function supports(Exception $exception);
}