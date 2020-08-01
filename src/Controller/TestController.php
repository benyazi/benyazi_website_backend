<?php
namespace App\Controller;

use App\Entity\Post;
use App\Service\BlogService;
use App\Service\TelegramApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test_route")
     */
    public function testAction(Request $request, TelegramApi $api)
    {
        $updates = $api->setClientForBot($_ENV['TELEGRAM_BOT_TOKEN'])->getUpdates();
        return new Response('<pre>'.print_r($updates, true).'</pre>');
    }
    /**
     * @Route("/update", name="update_route")
     */
    public function updateAction(Request $request, BlogService $bs)
    {
        $bs->refreshBlogDataFromTelegram();
        return new Response('ok');
    }
}