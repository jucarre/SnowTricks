<?php

namespace App\DataFixtures;

use App\Entity\ProfilePicture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProfilePictureFixtures extends Fixture
{
    private $directoryProfilePictures;

    private $targetDirectory;

    public function __construct( $directoryProfilePictures, $targetDirectory)
    {
        $this->directoryProfilePictures = $directoryProfilePictures;
        $this->targetDirectory = $targetDirectory;
    }

    public function load(ObjectManager $manager)
    {
        for ($a = 1; $a <= 2; $a++) {
            $profilePicture = new ProfilePicture;

            copy($this->directoryProfilePictures.'/bob-'.$a.'.jpeg', $this->targetDirectory.'/bob-'.$a.'.jpeg');

            $profilePicture->setTargetDirectory($this->targetDirectory);

            $file = new UploadedFile($this->targetDirectory.'/bob-'.$a.'.jpeg', 'bob-'.$a, null, null, null, true);

            $profilePicture->setFile($file);


            $this->addReference('profilePicture-' . $a, $profilePicture);
        }
    }
}
