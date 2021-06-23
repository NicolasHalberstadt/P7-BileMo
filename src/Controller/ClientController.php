<?php

namespace App\Controller;

use App\AppBundle\Exception\ResourceValidationException;
use App\Entity\Client;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ClientController
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\Controller
 */
class ClientController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    
    public function __construct(
        SerializerInterface $serializer,
        ClientRepository $clientRepository
    ) {
        $this->serializer = $serializer;
        $this->clientRepository = $clientRepository;
    }
    
    /**
     * @Rest\Get(
     *    path = "/clients",
     *     name = "app_clients_list"
     * )
     * @Rest\View(StatusCode=200)
     */
    public function listAction(): Response
    {
        $clients = $this->clientRepository->findAll();
        
        $response = new Response(
            $this->serializer->serialize(
                $clients,
                'json',
                SerializationContext::create()->setGroups(
                    ["Default", "list", "users" => [null]]
                )
            ), 200
        );
        $response->setMaxAge(3600);
        $response->setPublic();
        
        return $response;
    }
    
    /**
     * @Rest\Get(
     *     path = "/clients/{username}",
     *     name = "app_clients_show",
     *     requirements = {"username"="[a-zA-Z]+"}
     * )
     * @Rest\View(StatusCode=200)
     */
    public function detailsAction(string $username): Response
    {
        $client = $this->clientRepository->findOneBy(["username" => $username]);
        $isAdmin = in_array("ROLE_ADMIN", $this->getUser()->getRoles());
        if ($this->getUser() !== $client) {
            if (!$isAdmin) {
                throw $this->createAccessDeniedException();
            }
        }
        if ($client == null) {
            throw $this->createNotFoundException(
                "No client found with this id"
            );
        }
        $response = new Response(
            $this->serializer->serialize(
                $client,
                'json',
                SerializationContext::create()->setGroups(
                    ["Default", "details"]
                )
            ), 200
        );
        $response->setMaxAge(3600);
        $response->setPublic();
        
        return $response;
    }
    
    /**
     * @Rest\Post(
     *     path = "/clients",
     *     name = "app_client_create"
     * )
     * @Rest\View(StatusCode=201)
     * @ParamConverter("client", class="App\Entity\Client", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"creation"}}})
     * @throws ResourceValidationException
     */
    public function createAction(
        Client $client,
        ConstraintViolationList $violations,
        UserPasswordEncoderInterface $encoder
    ): Response {
        if (count($violations)) {
            $message =
                'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf(
                    "Field %s: %s ",
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                );
            }
            throw new ResourceValidationException($message);
        }
        $plainPassword = $client->getPassword();
        $client->setPassword($encoder->encodePassword($client, $plainPassword));
        $client->setRoles((array)"ROLE_CLIENT");
        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();
        $context = SerializationContext::create()->setGroups(["details"]);
        
        return new Response(
            $this->serializer->serialize($client, 'json', $context), 201
        );
    }
    
    /**
     * @Rest\Patch(
     *    path = "/clients",
     *    name = "app_client_update"
     * )
     * @Rest\View(StatusCode=200)
     * @throws ResourceValidationException
     */
    public function updateAction(
        UserPasswordEncoderInterface $encoder,
        Request $request
    ): Response {
        /* @var $client Client */
        $client = $this->getUser();
        $body = str_replace('{', '', $request->getContent());
        $body = str_replace('}', '', $body);
        $body = str_replace('"', '', $body);
        $body = trim(preg_replace('/\s+/', '', $body));
        $data = [];
        $errors = [];
        if (str_contains($body, ',')) {
            $arrays = list($key, $val) = explode(',', $body);
            foreach ($arrays as $array) {
                list($k, $v) = explode(':', $array);
                $data[$k] = $v;
            }
        }
        list($k, $v) = explode(':', $body);
        $data[$k] = $v;
        
        if (isset($data['password'])) {
            $client->setPassword(
                $encoder->encodePassword($client, $data['password'])
            );
            if (empty($data['password'])) {
                $errors[] = "Field Password: This value can't be empty. ";
            }
        }
        if (isset($data['username'])) {
            if ($this->clientRepository->findOneBy(
                ['username' => $data['username']]
            )) {
                $errors[] = "Field Username: This value is already used ";
            } elseif (empty($data['username'])) {
                $errors[] = "Field Username: This value can't be empty. ";
            } else {
                $client->setUsername($data['username']);
            }
        }
        if (isset($data['email'])) {
            if ($this->clientRepository->findOneBy(
                ['email' => $data['email']]
            )) {
                $errors[] = "Field Email: This value is already used ";
            } elseif (empty($data['email'])) {
                $errors[] = "Field Email: This value can't be empty. ";
            } else {
                $client->setEmail($data['email']);
            }
        }
        if (!empty($errors)) {
            $message =
                'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($errors as $error) {
                $message .= $error;
            }
            throw new ResourceValidationException($message);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $context = SerializationContext::create()->setGroups(["list"]);
        
        $response = new Response(
            $this->serializer->serialize($client, 'json', $context), 200
        );
        $response->setMaxAge(3600);
        $response->setPublic();
        
        return $response;
    }
    
    /**
     * @Rest\Delete(
     *    path = "/clients/{id}",
     *    name = "app_clients_remove",
     *    requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode=204)
     */
    public function removeAction(
        int $id
    ): Response {
        $client = $this->clientRepository->find($id);
        if ($client == null) {
            throw $this->createNotFoundException(
                "No client found with this id"
            );
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($client);
        $em->flush();
        
        return new Response(null, 204);
    }
    
}