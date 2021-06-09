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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
            throw $this->createNotFoundException("No user found with this id");
            
        } elseif ($user->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        
        $context = SerializationContext::create()->setGroups(['details']);
        
        $response = new Response($this->serializer->serialize($user, 'json', $context), 200);
        $response->setMaxAge(3600);
        $response->setPublic();
        
        return $response;
    }
    
    
    /**
     * @Rest\Post(
     *     path = "/users",
     *     name = "app_user_create"
     * )
     * @Rest\View(StatusCode=201)
     * @ParamConverter("user", class="App\Entity\User", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"creation"}}})
     * @throws ResourceValidationException
     */
    public function createAction(
        User $user,
        UserPasswordEncoderInterface $encoder,
        ConstraintViolationList $violations
    ): Response {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }
        /* @var $currentClient Client */
        $currentClient = $this->getUser();
        $user->setClient($currentClient);
        $plainPassword = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user, $plainPassword));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        $context = SerializationContext::create()->setGroups(['details']);
        
        $response = new Response($this->serializer->serialize($user, 'json', $context), 201);
        $response->setMaxAge(3600);
        $response->setPublic();
        
        return $response;
    }
    
    /**
     * @Rest\Delete(
     *    path = "/users/{id}",
     *    name = "app_users_remove",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode=204)
     */
    public function removeAction(
        int $id
    ): Response {
        $user = $this->userRepository->find($id);
        if ($user == null) {
            throw $this->createNotFoundException("No user found with this id");
        } elseif ($user->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
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
        $client = $this->getUser()->getId();
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(["client" => "$client"]);
        $paginated = $paginator->paginate(
            $users,
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')
        );
        
        $context = SerializationContext::create()->setGroups(
            [
                'Default',
                'items' => [
                    'Default',
                    'details',
                    'client' => [
                        null,
                    ],
                ],
            ]
        );
        
        $response = new Response(
            $this->serializer->serialize(
                $paginated,
                'json',
                $context
            ),
            200
        );
        $response->setMaxAge(3600);
        $response->setPublic();
        
        return $response;
    }
}
