<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Attachment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $duration = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $width = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $height = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileId = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileUniqId = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileSize = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileUrl = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filePath = null;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     * @return Attachment
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param null $duration
     * @return Attachment
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param null $width
     * @return Attachment
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param null $height
     * @return Attachment
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return null
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * @param null $fileId
     * @return Attachment
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
        return $this;
    }

    /**
     * @return null
     */
    public function getFileUniqId()
    {
        return $this->fileUniqId;
    }

    /**
     * @param null $fileUniqId
     * @return Attachment
     */
    public function setFileUniqId($fileUniqId)
    {
        $this->fileUniqId = $fileUniqId;
        return $this;
    }

    /**
     * @return null
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param null $fileSize
     * @return Attachment
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    /**
     * @return null
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * @param null $fileUrl
     * @return Attachment
     */
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;
        return $this;
    }

    /**
     * @return null
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param null $filePath
     * @return Attachment
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }
}