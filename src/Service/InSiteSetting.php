<?php

namespace App\Service;

use App\Entity\Banner;
use App\Entity\Customers;
use App\Entity\Features;
use App\Entity\TermsOfUse;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InSiteSetting extends AbstractController
{
    public function getBanner()
    {
        $data = $this->getDoctrine()->getRepository(Banner::class)->find(1);
        return is_null($data) ? null : $data;
    }

    public function getTermsOfUse()
    {
        $data = $this->getDoctrine()->getRepository(TermsOfUse::class)->find(1);
        return is_null($data) ? null : $data;
    }

    public function getFeatures()
    {
        $data = $this->getDoctrine()->getRepository(Features::class)->findAll();
        return is_null($data) ? null : $data;
    }

    public function getCustomers()
    {
        $data = $this->getDoctrine()->getRepository(Customers::class)->findAll();
        return is_null($data) ? null : $data;
    }

    public function getUserName(int $id){
        $data = $this->getDoctrine()->getRepository(User::class)->find($id);
        return is_null($data) ? null : $data->getUserName();
    }
}
