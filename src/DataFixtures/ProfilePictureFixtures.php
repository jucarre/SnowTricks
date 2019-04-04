<?php

namespace App\DataFixtures;

use App\Entity\ProfilePicture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProfilePictureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($a = 1; $a <= 2; $a++) {
            $profilePicture = new ProfilePicture;

            $profilePicture->setPathPicture('bob-' .$a.'.jpeg');


            $this->addReference('profilePicture-' . $a, $profilePicture);
        }
    }
}
