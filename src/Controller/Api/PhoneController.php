<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use App\Entity\Phone;
use App\Repository\PhoneRepository;
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
use Knp\Component\Pager\PaginatorInterface;

class PhoneController extends AbstractFOSRestController
{

    /**
     * Lists all phones.
     * @Rest\Get("/api/v1/phones", name="phones_list")
     *
     * @return Response
     */
    public function phonesList(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator)
    {
        $phones = $doctrine->getRepository(Phone::class)->findAll();

        $paginatedphones = $paginator->paginate(
            $phones, // Request containing the data to be paginated (here our articles)
            $request->query->getInt('page', 1), // Current page number, passed in the URL, 1 if no page
            10 // Number of results per page
        );

        return $this->handleView($this->view($paginatedphones));
    }

    /**
     * Lists phone details.
     * @Rest\Get("/api/v1/phone/{id}", name="phone_details")
     *
     * @return Response
     */
    public function phoneDetails($id, ManagerRegistry $doctrine)
    {
        $phone = $doctrine->getRepository(Phone::class)->find($id);

        return $this->handleView($this->view($phone));
    }

}