<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use App\Entity\User;
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

class UserController extends AbstractFOSRestController
{

    /**
     * Lists all users for one client.
     * @Rest\Get("/api/v1/users/{client}", name="client_userslist")
     *
     * @return Response
     */
    public function usersForClient($client, ManagerRegistry $doctrine)
    {
        $users = $doctrine->getRepository(User::class)->findBy(['client' => $client]);

        return $this->handleView($this->view($users));
    }

    /**
     * Show details for one user.
     * @Rest\Get("/api/v1/user/{id}", name="user_details")
     *
     * @return Response
     */
    public function userDetails($id, ManagerRegistry $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->find($id);

        return $this->handleView($this->view($user));
    }

    /**
     * @Post(
     *     path = "/api/v1/new_user/{client}",
     *     name = "new_user"
     * )
     * @View
     */
    /**
     * Add a new user
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/api/v1/new_user/{client}", name="new_user")
     */
    public function postSong(User $user, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();

        $em->persist($user);
        $em->flush();

        return $user;
    }


    /**
     * @Delete(
     *     path = "/api/v1/user/{id}/delete",
     *     name = "delete_user"
     * )
     * @View
     */
    public function deleteAction(User $user, Request $request, ManagerRegistry $doctrine)
    {

        
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