<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostTypeForm;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    function public(MicroPostRepository $posts, EntityManagerInterface $entityManager): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $microPost = new MicroPost();
        $form = $this->createForm(MicroPostTypeForm::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new \DateTime('now'));

            $entityManager->persist($post);
            $entityManager->flush();
            //add flash
            $this->addFlash('success', 'Post created!');
            return $this->redirectToRoute('app_micro_post');
            //redirect
        }
        return $this->render('micro_post/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MicroPostTypeForm::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new \DateTime('now'));
            $entityManager->flush();
            //add flash

            $this->addFlash('success', 'Post Updated!');
            return $this->redirectToRoute('app_micro_post');
            //redirect
        }
        // Create delete form for the same post
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_micro_post_delete', ['post' => $post->getId()]))
            ->setMethod('POST')
            ->add('delete', SubmitType::class, ['label' => 'Delete'])
            ->getForm();
        return $this->render('micro_post/edit.html.twig', [
            'form' => $form->createView() ,
            'deleteForm' => $deleteForm,
            'post' => $post,
        ]);
    }


    #[Route('/micro-post/{post}/delete', name: 'app_micro_post_delete', methods: ['POST'])]
    public function delete(Request $request, MicroPost $post, EntityManagerInterface $entityManager): Response
    {
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_micro_post_delete', ['post' => $post->getId()]))
            ->setMethod('POST')
            ->add('delete', SubmitType::class, ['label' => 'Delete'])
            ->getForm();
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $entityManager->remove($post);
            $entityManager->flush();
            //add flash
            $this->addFlash('success', 'Post Deleted!');
            return $this->redirectToRoute('app_micro_post');
            //redirect
        }

        return $this->render('micro_post/edit.html.twig', [
            'deleteForm' => $deleteForm,
            'post' => $post,
        ]);

    }
}