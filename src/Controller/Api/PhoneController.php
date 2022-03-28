<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class PhoneController extends AbstractFOSRestController
{

    /**
     * Lists all phones.
     * @Rest\Get("/api/v1/phones", name="phones_list")
     *
     * @return Response
     */
    public function phonesList(ManagerRegistry $doctrine)
    {
        $phones = $doctrine->getRepository(Phone::class)->findAll();

        return $this->handleView($this->view($phones));
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