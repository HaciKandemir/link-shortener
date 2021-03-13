<?php

namespace App\Entity;

use App\Repository\TermsOfUseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TermsOfUseRepository::class)
 */
class TermsOfUse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortener_form;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortenerForm(): ?string
    {
        return $this->shortener_form;
    }

    public function setShortenerForm(?string $shortener_form): self
    {
        $this->shortener_form = $shortener_form;

        return $this;
    }
}
