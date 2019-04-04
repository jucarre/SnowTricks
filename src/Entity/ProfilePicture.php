<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfilePictureRepository")
 */
class ProfilePicture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Image(
     *     mimeTypes= {"image/jpeg", "image/jpg", "image/png"},
     *     mimeTypesMessage = "Veuillez selectionner une image en .jpg, .jpeg ou .png",
     *     maxHeightMessage ="La hauteur de cette image est trop grande",
     *     minRatio = 1,
     *     minRatioMessage = "L'image doit être carré, un ratio de 1:1",
     *     maxRatio = 1,
     *     maxRatioMessage = "L'image doit être carré, un ratio de 1:1",
     *     maxSize = "2m",
     *     maxSizeMessage = "l'image est trop grosse, kappa!, 2 mo Max !",
     * )
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PathPicture;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getPathPicture(): ?string
    {
        return $this->PathPicture;
    }

    public function setPathPicture(string $PathPicture): self
    {
        $this->PathPicture = $PathPicture;

        return $this;
    }

}
