<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use App\Services\Notifications;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', name: 'post.')]
class PostController extends AbstractController
{
    #[Route('/ ', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        //dd($posts);

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }
    /*
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        // create a new Post with title
        $post = new Post();

        $post->setTitle('This is going to be a title');

        //entity manager
        $em = $this -> getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        // return a response
        return $this->redirect($this->generateUrl('post.index'));
    }*/
    /*
    
    public function show($id, PostRepository $postRepository ) : Response
    {

        $post = $postRepository->find($id);
        dd($post);

        return $this->render('post/show.html.twig',[
            'post' => $post
        ]);
    }*/


    #[Route('/show/{id}', name: 'show')]
    public function show(Post $post): Response
    {
        //dd($post);        
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function remove(Post $post): Response
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post was removed');

        return $this->redirect($this->generateUrl('post.index'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, FileUploader $fileUploader/*, Notifications $notification*/): Response
    {
        // create a new Post with title
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        //$form->getErrors();
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            //entity manager
            dump($request->files);
            $em = $this->getDoctrine()->getManager();
            $file = $request->files->get('post')['attachment'];

            if ($file) {

                $filename = $fileUploader->uploadFile($file);

                $post->setImage($filename);
            }
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('post.index'));
        }



        // return a response
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
