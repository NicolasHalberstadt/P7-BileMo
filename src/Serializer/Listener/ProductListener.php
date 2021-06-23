<?php


namespace App\Serializer\Listener;

use App\Entity\Product;
use DateTime;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;

/**
 * Class ProductListener
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\Serializer
 */
class ProductListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'format' => 'json',
                'class' => Product::class,
                'method' => 'onPostSerialize',
            ],
        ];
    }
    
    public static function onPostSerialize(ObjectEvent $event)
    {
        $date = new DateTime();
        $event->getVisitor()->visitProperty(
            new StaticPropertyMetadata('', 'delivered_at', null),
            $date->format('l jS \of F Y h:i:s A')
        );
    }
}