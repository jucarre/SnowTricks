<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class ImageResizer
{

    /**
     * @param $image
     * @param $newPath
     * @return bool
     */
    public function cropAndResizer($image, $newPath)
    {
        $ImageDetails = $this->getImageDetails($image);
        $name = $ImageDetails->name;
        $height = $ImageDetails->height;
        $width = $ImageDetails->width;
        $fileExtention = $ImageDetails->extension;
        $ratio = $ImageDetails->ratio;
        $jpegQuality = 95;
        $ratio16x10 = 16 / 10;
        $thumb_width = 416;
        $thumb_height = 260;

        if ($thumb_width > $width && $thumb_height > $height && (round($ratio, 2) === round($ratio16x10, 2))) {
            copy($image, $newPath);
            return false;
        }

        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }

        $gd_image_dest = imagecreatetruecolor($thumb_width, $thumb_height);
        $gd_image_src = null;
        switch ($fileExtention) {
            case 'png' :
                $gd_image_src = imagecreatefrompng($image);
                imagealphablending($gd_image_dest, false);
                imagesavealpha($gd_image_dest, true);
                break;
            case 'jpeg':
            case 'jpg':
                $gd_image_src = imagecreatefromjpeg($image);
                break;
            case 'gif' :
                $gd_image_src = imagecreatefromgif($image);
                break;
            default:
                break;
        }

        // Resize and crop
        imagecopyresampled($gd_image_dest,
            $gd_image_src,
            0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
            0 - ($new_height - $thumb_height) / 2, // Center the image vertically
            0, 0,
            $new_width, $new_height,
            $width, $height);

        $filesystem = new Filesystem();
        $filesystem->mkdir($newPath, 0744);
        $newFileName = $newPath . $name . "." . $fileExtention;

        switch ($fileExtention) {
            case 'png' :
                imagepng($gd_image_dest, $newFileName);
                break;
            case 'jpeg' :
            case 'jpg' :
                imagejpeg($gd_image_dest, $newFileName, $jpegQuality);
                break;
            case 'gif' :
                imagegif($gd_image_dest, $newFileName);
                break;
            default:
                break;
        }

        imagedestroy($gd_image_dest);
        imagedestroy($gd_image_src);

        return true;

    }

    /**
     * Gets image details such as the extension, sizes and filename and returns them as a standard object.
     *
     * @param $imageWithPath
     * @return \stdClass
     */
    private function getImageDetails($imageWithPath)
    {
        $size = getimagesize($imageWithPath);

        $imgParts = explode("/", $imageWithPath);
        $lastPart = $imgParts[count($imgParts) - 1];

        if (stristr("?", $lastPart)) {
            $lastPart = substr($lastPart, 0, stripos("?", $lastPart));
        }
        if (stristr("#", $lastPart)) {
            $lastPart = substr($lastPart, 0, stripos("#", $lastPart));
        }

        $dotPos = stripos($lastPart, ".");
        $name = substr($lastPart, 0, $dotPos);
        $extension = substr($lastPart, $dotPos + 1);

        $Details = new \stdClass();
        $Details->height = $size[1];
        $Details->width = $size[0];
        $Details->ratio = $size[0] / $size[1];
        $Details->extension = $extension;
        $Details->name = $name;

        return $Details;
    }

}
