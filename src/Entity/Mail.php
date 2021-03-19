<?php

namespace App\Entity;

use App\Repository\MailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 */
class Mail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $sender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $subject;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_readed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_answered;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getIsReaded(): ?bool
    {
        return $this->is_readed;
    }

    public function setIsReaded(bool $is_readed): self
    {
        $this->is_readed = $is_readed;

        return $this;
    }

    public function getIsAnswered(): ?bool
    {
        return $this->is_answered;
    }

    public function setIsAnswered(bool $is_answered): self
    {
        $this->is_answered = $is_answered;

        return $this;
    }
}
