<?php

namespace App\Controller;

use App\AppBundle\Exception\ResourceValidationException;
use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Delete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\ConstraintViolationList;


class UserController extends AbstractFOSRestController
{
    
    /*
     * Note: no need for client controller => no creation.
     * Every user's related management is made here.
     * Need to get current client to manage users
     */
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    
    public function __construct(SerializerInterface $serializer, UserRepository $userRepository)
    {
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
        
    }
    
    /**
     * @Rest\Get(
     *     path = "/users/{id}",
     *     name = "app_users_show",
     *     requirements = {"id"="\d+"}
     *     )
     * @Rest\View(StatusCode= 200)
     */
    public function showAction(int $id): Response
    {
        /* @var $user User */
        $user = $this->userRepository->find($id);
        if ($user == null) {
            $error = [
                'code' => 404,
                'message' => 'No user found with this id',
            ];
            
            return new JsonResponse($error, 404);
        }
        
        $context = SerializationContext::create()->setGroups(['details']);
        
        return new Response($this->serializer->serialize($user, 'json', $context), 200);
    }
    
    
    /**
     * @Rest\Post(
     *     path = "/users",
     *     name = "app_user_create"
     * )
     * @Rest\View(StatusCode=201)
     * @ParamConverter("user", class="App\Entity\User", converter="fos_rest.request_body")
     * @throws ResourceValidationException
     */
    public function createAction(
        User $user,
        UserPasswordEncoderInterface $encoder,
        ConstraintViolationList $violations
    ): Response {
        /* todo: get current client here ! */
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }
        /* @var $client Client */
        $client = $this->getDoctrine()->getRepository(Client::class)->find(1);
        $user->setClient($client);
        $plainPassword = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user, $plainPassword));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        $context = SerializationContext::create()->setGroups(['details']);
        
        return new Response($this->serializer->serialize($user, 'json', $context), 201);
    }
    
    /**
     * @Rest\Delete(
     *    path = "/users/{id}",
     *    name = "app_user_remove",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode=204)
     */
    public function removeAction(
        int $id
    ): Response {
        $user = $this->userRepository->find($id);
        if ($user == null) {
            $error = [
                'code' => 404,
                'message' => 'No user found with this id',
            ];
            
            return new JsonResponse($error, 404);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        
        return new Response(null, 204);
    }
    
    /**
     * @Rest\Get("/users", name="app_user_list")
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
        /* todo: get current client here ! */
        $client = 1;
        
        $users = $this->getDoctrine()->getRepository(User::class)->findByClient($client);
        $paginated = $paginator->paginate(
            $users,
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')
        );
        
        $context = SerializationContext::create()->setGroups(
            [
                'Default',
                'items' => [
                    'list',
                ],
            ]
        );
        
        return new Response($this->serializer->serialize($paginated, 'json', $context), 200);
        
    }
}
