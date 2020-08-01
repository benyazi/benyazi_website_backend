<?php
namespace App\Controller;

use App\Entity\Post;
use App\Service\BlogService;
use App\Service\TelegramApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/getPosts", name="api_get_posts")
     */
    public function getPosts(Request $request, BlogService $bs)
    {
        $resp = [];
        $resp['list'] = $bs->getPostList();
        return new JsonResponse($resp);
    }
}