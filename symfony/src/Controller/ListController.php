<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route('/list', name: 'app_list')]
    public function index(TestRepository $testRepository, EntityManagerInterface $em): Response
    {
        $test = $testRepository->find(1);

        if (!$test) {
            $test = new Test();
        }

        $test->setName("name1");
        $test->setDescription("description");

        $em->persist($test);
        $em->flush();

        $companies
            = [
            'Apple'             => '$1.16 trillion USD',
            'Samsung'           => '$298.68 billion USD',
            'Microsoft'         => '$1.10 trillion USD',
            'Alphabet'          => '$878.48 billion USD',
            'Intel Corporation' => '$245.82 billion USD',
            'IBM'               => '$120.03 billion USD',
            'Facebook'          => '$552.39 billion USD',
            'Hon Hai Precision' => '$38.72 billion USD',
            'Tencent'           => '$3.02 trillion USD',
            'Oracle'            => '$180.54 billion USD',
        ];

        return $this->render('list/index.html.twig', [
            'companies' => $companies,
        ]);
    }
}
