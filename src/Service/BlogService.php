<?php
namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\KernelInterface;

class BlogService
{
    /** @var EntityManager */
    private $em;
    /** @var TelegramApi  */
    private $api;
    /** @var KernelInterface  */
    private $appKernal;

    public function __construct(EntityManagerInterface $em, TelegramApi $api, KernelInterface $kernel)
    {
        $this->em = $em;
        $this->api = $api;
        $this->appKernal = $kernel;
    }

    public function findOneOrCreate($msgId, $chatId)
    {
        $post = $this->em->getRepository(Post::class)
            ->findOneBy([
                'chatId' => $chatId,
                'messageId' => $msgId
            ]);
        if(empty($post)) {
            $post = new Post();
            $post->setMessageId($msgId);
            $post->setChatId($chatId);
            $this->em->persist($post);
        }
        return $post;
    }

    public function updatePost($post, $data = [])
    {
        $this->em->flush($post);
    }

    /**
     * @param Post $post
     * @param array $attachments
     */
    public function updateAttachmentToPost($post, $attachments)
    {
        $ids = [];
        foreach ($attachments as $attachment) {
            $ids[] = $attachment->getId();
        }
        $post->setAttachments($ids);
    }

    /**
     * @param array $data
     * @return Attachment
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrUpdateAttachment($data)
    {
        $attachment = $this->em->getRepository(Attachment::class)
            ->findOneBy([
                'fileUniqId' => $data['file_unique_id']
            ]);
        if(empty($attachment)) {
            $attachment = new Attachment();
            $attachment->setFileUniqId($data['file_unique_id']);
            $this->em->persist($attachment);
        }
        $attachment->setDuration(@$data['duration']);
        $attachment->setMimeType(@$data['mime_type']);
        $attachment->setWidth($data['width']);
        $attachment->setHeight($data['height']);
        $attachment->setFileSize($data['file_size']);
        $attachment->setFileId($data['file_id']);
        $attachment->setFileUrl($data['fileUrl']);
        $this->em->flush($attachment);
        return $attachment;
    }

    /**
     * @param Post $post
     * @return array
     */
    public function getPostDataAsArray($post)
    {
        $attachments = [];
        foreach ($post->getAttachments() as $attachmentId)
        {
            $attachment = $this->em->getRepository(Attachment::class)
                ->find($attachmentId);
            if(empty($attachment)) {
                continue;
            }
            $attachments[] = [
                'url' => $attachment->getFileUrl()
            ];
        }
        return [
            'id' => $post->getId(),
            'text' => $post->getText(),
            'attachments' => $attachments
        ];
    }

    public function refreshBlogDataFromTelegram()
    {
        $api = $this->api;
        $kernel = $this->appKernal;
        $updates = $api->setClientForBot($_ENV['TELEGRAM_BOT_TOKEN'])->getUpdates();
        foreach ($updates as $update) {
            if(@$update['channel_post']) {
                $post = $update['channel_post'];
                $msg = '';
                if(isset($post['text'])) {
                    $msg = $post['text'];
                }
                if(isset($post['caption'])) {
                    $msg = $post['caption'];
                }
                $postItem = $this->findOneOrCreate($post['message_id'], $post['chat']['id']);
                $postItem->setText($msg);
                $postItem->setTimestamp($post['date']);
                $attachments = [];
                if(isset($post['animation'])) {
                    $fileData = $post['animation'];
                    $fileId = $post['animation']['file_id'];
                    $fileUrl = '/files/' . str_replace(' ','', $post['animation']['file_name']);
                    $fileName = $kernel->getProjectDir() . '/public' . $fileUrl;
                    if($api->downloadFile($fileId, $fileName)) {
                        $fileData['fileUrl'] = $fileUrl;
                        $attachments[] = $this->createOrUpdateAttachment($fileData);
                    }
                }
                if(isset($post['photo'])) {
                    $photoData = $post['photo'][(count($post['photo'])-1)];
                    $fileId = $photoData['file_id'];
                    $fileUrl = '/files/' . str_replace(' ','', $photoData['file_unique_id']) . '.jpg';
                    $fileName = $kernel->getProjectDir() . '/public' . $fileUrl;
                    $photoData['mime_type'] = 'image/jpg';
                    if($api->downloadFile($fileId, $fileName)) {
                        $photoData['fileUrl'] = $fileUrl;
                        $attachments[] = $this->createOrUpdateAttachment($photoData);
                    }
                }
                $this->updateAttachmentToPost($postItem, $attachments);
                $this->updatePost($postItem);
            }
        }
    }

    /**
     * @param int $page
     * @return array
     */
    public function getPostList($page = 1)
    {
        $postList = [];
        /** @var Post $post */
        foreach ($this->em->getRepository(Post::class)->findBy(['isVisible' => true]) as $post)
        {
            $postList[] = $this->getPostDataAsArray($post);
        }
        return $postList;
    }
}