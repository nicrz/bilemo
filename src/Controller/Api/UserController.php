<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use App\Entity\User;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Forms\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Exception\ResourceNoValidateException;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractFOSRestController
{

    /**
     * Lists all users for one client.
     * @Rest\Get("/api/v1/users/{client}", name="client_userslist")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function usersForClient($client, ManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if(!empty($user)){
        $userId = $user->getId();
        }

        if ($client != $userId){

        return new Response('Forbidden.', 403);

        }else{

        $users = $doctrine->getRepository(User::class)->findBy(['client' => $client]);

        $paginatedusers = $paginator->paginate(
            $users, // Request containing the data to be paginated (here our articles)
            $request->query->getInt('page', 1), // Current page number, passed in the URL, 1 if no page
            10 // Number of results per page
        );

        return $this->handleView($this->view($paginatedusers));

        }

    }

    /**
     * Show details for one user.
     * @Rest\Get("/api/v1/user/{id}", name="user_details")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function userDetails($id, ManagerRegistry $doctrine)
    {

        $clients = $doctrine->getRepository(User::class)->clientForUser($id);

        foreach ($clients as $client){
            $idclient = $client->getClient();
        }

        $usersession = $this->get('security.token_storage')->getToken()->getUser();
        if(!empty($usersession)){
        $userId = $usersession->getId();
        }

        if ($idclient != $userId){

            return new Response('Forbidden.', 403);
    
        }else{

            $user = $doctrine->getRepository(User::class)->find($id);
            return $this->handleView($this->view($user));

        }
    }

    /**
     * Add a new user
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/api/v1/add_user", name="new_user")
     * @IsGranted("ROLE_USER")
     */
    public function postUser(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $em)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if(!empty($user)){
        $userId = $user->getId();
        }

        $user = new User();
        $user->setFirstname($request->get('firstname'));
        $user->setLastname($request->get('lastname'));
        $user->setNickname($request->get('nickname'));
        $user->setEmail($request->get('email'));
        $user->setBirthday($request->get('birthday'));
        $user->setClient($request->get('client'));

        $idclient = $request->request->get('client');

        if ($idclient != $userId){

            return new Response('Forbidden.', 403);

        }else{

            $em->persist($user);
            $em->flush();
    
            return $this->handleView(
                $this->view(
                    [
                        'status' => 'ok',
                    ],
                    Response::HTTP_CREATED
                )
            );

        }

    }


    /**
     * @Delete(
     *     path = "/api/v1/user/{id}/delete",
     *     name = "delete_user"
     * )
     * @View
     */
    public function deleteAction($id, User $user, Request $request, ManagerRegistry $doctrine)
    {

        $clients = $doctrine->getRepository(User::class)->clientForUser($id);

        foreach ($clients as $client){
            $idclient = $client->getClient();
        }

        $usersession = $this->get('security.token_storage')->getToken()->getUser();
        if(!empty($usersession)){
        $userId = $usersession->getId();
        }

        if ($idclient != $userId){

            return new Response('Forbidden.', 403);

        }else{

            $manager = $doctrine->getManager();
            $manager->remove($user);
            $manager->flush();
    
            return $this->handleView(
                $this->view(
                    [
                        'status' => 'ok',
                    ],
                    Response::HTTP_CREATED
                )
            );

        }

    
    }



}