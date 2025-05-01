<?php

namespace App\Controller;

use App\Repository\MicroPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_index')]
    public function lastBlogAdded(MicroPostRepository $posts): Response
    {
        $lastPosts = array_slice(array_reverse($posts->findAll()), 0, 3);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lastPosts' => $lastPosts,
        ]);
    }

}