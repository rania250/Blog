<?php 
namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UploadFile extends AbstractController{

    public function generate_name($length = 20){
        $code = "aze86rt3yu1iop9qsd8f7gh5jklm2w8xc6vbn";
        $result = "";

        while (strlen($result) < 20) {
            $result .= $code[rand(0, strlen($code)-1)];
        }

        return $result;
    }

    public function saveFile($file){
        $extension = $file->guessExtension();
        $filename = $this->generate_name(30).".".$extension;
        $file->move($this->getParameter('image_dir'), $filename);

        return '/_assets/images/articles/'.$filename;
    }
    public function updateFile($file, $old_file){
        $file_url = $this->saveFile($file);
        try {
            unlink($this->getParameter('static_dir').$old_file);
        } catch (\Throwable $th) {
        }
        return $file_url;
    }
}