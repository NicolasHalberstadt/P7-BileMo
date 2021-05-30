<?php

namespace App\Controller;

use App\AppBundle\Exception\ResourceValidationException;
use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;


class ProductController extends AbstractController
{
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    
    public function __construct(SerializerInterface $serializer, ProductRepository $productRepository)
    {
        $this->serializer = $serializer;
        $this->productRepository = $productRepository;
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
        $products = $this->productRepository->findAll();
        $paginated = $paginator->paginate(
            $products,
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')
        );
        
        return new Response($this->serializer->serialize($paginated, 'json'), 200);
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
        $product = $this->productRepository->find($id);
        
        if ($product == null) {
            throw $this->createNotFoundException("No product found with this id");
        }
        
        return new Response($this->serializer->serialize($product, 'json'), 200);
    }
    
    /**
     * @Rest\Post(
     *     path = "/products",
     *     name = "app_products_create"
     * )
     * @Rest\View(StatusCode=201)
     * @ParamConverter("product", class="App\Entity\Product", converter="fos_rest.request_body")
     * @throws ResourceValidationException
     */
    public function createAction(Product $product, ConstraintViolationList $violations): Response
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        
        return new Response($this->serializer->serialize($product, 'json'), 201);
    }
    
    /**
     * @Rest\Delete(
     *    path = "/products/{id}",
     *    name = "app_products_remove",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode=204)
     */
    public function removeAction(
        int $id
    ): Response {
        $product = $this->productRepository->find($id);
        if ($product == null) {
            throw $this->createNotFoundException("No product found with this id");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        
        return new Response(null, 204);
    }
}
