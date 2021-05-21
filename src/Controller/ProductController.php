<?php

namespace App\Controller;

use App\Entity\Product;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProductController extends AbstractController
{
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    
    /**
     * @Rest\Get(
     *     path = "/products/{id}",
     *     name = "app_products_show",
     *     requirements = {"id"="\d+"}
     *     )
     */
    public function showAction(int $id): Response
    {
        /* @var $product Product */
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product == null) {
            $error = [
                'code' => 404,
                'message' => 'No product found with this id',
            ];
    
            return new Response($this->serializer->serialize($error, 'json'), 404);
        }
    
        return new Response($this->serializer->serialize($product, 'json'), 200);
    }
    
    
    /**
     * @Rest\Get("/products", name="app_product_list")
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of products per page."
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Page number."
     * )
     * @Rest\View()
     */
    public function listAction(
        ParamFetcherInterface $paramFetcher,
        PaginatorInterface $paginator
    ): Response {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $paginated = $paginator->paginate(
            $products,
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')
        );
        
        return new Response($this->serializer->serialize($paginated, 'json'), 200);
    }
}
