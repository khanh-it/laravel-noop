<?php

namespace App\Helpers;

use Intervention\Image\ImageManager;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class Upload {

    protected $configs;
    protected $imageManager;

    public function __construct( $configs = array() ) {

        $this->configs = array_merge( array(

            // kích thước hình theo folder
            "sizes" => array(
                "135_135"   => array(
                    "width"     => 120,
                    "height"    => 90,
                ),
                "500_500"   => array(
                    "width"     => 500,
                    "height"    => 375,
                ),
                "1024_768"   => array(
                    "width"     => 1024,
                    "height"    => 768,
                )
            ),

            // đường dẫn folder upload
            "uploadPath"    => config('filesystems.disks.accountUploads.root'),

            // url upload
            "uploadUrl"     => config('filesystems.disks.accountUploads.url'),

            // tên folder hình gốc
            "originFolder"  => "origin",

            // tên folder mặc định
            "defaultFolder" => "DEFAULT",

            //https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
            "mimes"         => array(
                "image/gif"             => ".gif",
                "image/x-icon"          => ".ico",
                "image/jpeg"            => ".jpg",
                "image/png"             => ".png",
                "image/svg+xml"         => ".svg",
                "image/tiff"            => ".tiff",
                "image/webp"            => ".webp"
            ),

            // config name
            "configDiskName"            => "accountUploads",

            // chuỗi kích thước
            "stringReplace"             => "{size}",

            // giữ kích thước không bị méo
            "aspectRatio"               => true
        ), $configs );

        $this->imageManager = new ImageManager(array('driver' => 'gd'));
    }

    /**
     * @todo Hàm upload hình
     * @param $file Illuminate\Http\UploadedFile | base64 url file
     * @return string url image
    */
    public function uploadImage( $file, $base_folder = "" ) {

        // nếu file là chuỗi base64
        if( is_string( $file ) ) {

            // chuyển base64 thành file
            $file = $this->convertBase64ToFile( $file );
        }

        // đường dẫn thư mục upload
        $uploadPath = $this->configs["uploadPath"];

        // đường dẫn web
        $uploadUrl = $this->configs["uploadUrl"];

        // tên file
        $fileName = str_random(10) . time() . $file->getClientOriginalName();

        // folder của account
        $base_folder = $base_folder ? $base_folder : $this->configs["defaultFolder"];

        // folder hình gốc
        $originFolder = $base_folder . "/" . $this->configs["originFolder"];

        // đường dẫn thư mục file gốc
        $originPath = $uploadPath . "/" . $originFolder;

        // đường dẫn file origin
        $filePath = $originPath . "/" . $fileName;

        // kích thước cần resize
        $sizes = $this->configs["sizes"];

        // save file vào origin
        $file->storeAs( $originFolder, $fileName, array(
            "disk"  => $this->configs["configDiskName"]
        ) );

        // instance image 
        $img = $this->imageManager->make( $filePath );
        
        $fixSize = $this->configs["stringReplace"];
        
        // duyệt qua cấu hình kích thước
        foreach( $sizes as $folder => $size ) {
            
            // tạo đường dẫn upload
            $folder = $uploadPath . "/" . $base_folder . "/" . $folder;

            // kiểm tra folder
            if( !file_exists( $folder ) ) {
                
                mkdir( $folder, 0777, true );
            }

            // resize hình
            $this->resize( $img, $size["width"], $size["height"], $this->configs["aspectRatio"] );

            //save
            $img->save("{$folder}/{$fileName}");
        }
        
        return "{$uploadUrl}/{$base_folder}/{$fixSize}/{$fileName}";
    }

    /**
     * @todo Hàm resize hình
     * @param image instane
     * @param width
     * @param height
     * @param ratio
    */
    private function resize( $img, $width, $height, $aspectRatio = false ) {

        if( !$aspectRatio ) {

            return $img->resize($width, $height);
        }

        if( $width > $height ) {

            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {

            $img->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
    }

    /**
    * @todo Hàm save base64 file
    * @param $base64_image_string: data:mime;base64,file
    * @param $fileName: tên file không bao gồm extension
    * @return UploadedFile | null
    */
    public function convertBase64ToFile($base64_image_string, $fileName = "") {
        
        // match data
        preg_match( "/^data:([a-zA-Z0-9]+\/[a-zA-Z0-9]+);base64,([a-zA-Z0-9+\/=\._]+)$/", $base64_image_string, $matches );

        // bỏ extension trong file name
        $fileName = preg_replace( '~(\.[a-zA-Z0-9]+)$~', "", $fileName );

        if( $matches && count($matches) == 3 ) {

            // data type
            $mime = $matches[1];

            // file
            $data = $matches[2];

            // tên file
            $fileName = $fileName ? $fileName : str_random(20) . time();

            // đuôi file
            $extension = $this->mimeTypeToExtension( $mime );
            $fileName = $fileName . $extension;

            // tạo file tạm
            $filePath = $this->temporary( $fileName );

            // ghi file
            $fileSize = file_put_contents( $filePath, base64_decode($data) );

            // tạo instance file
            $file = new SymfonyUploadedFile(
                $filePath,
                $fileName,
                $mime,
                $fileSize,
                0
            );
            
            $file = UploadedFile::createFromBase($file);

            return $file;
        }

        return null;
    }

    /**
    * @todo Hàm trả về đường dẫn thư mục file tạm
    * @return dir
    */
    private function getTmpDir() {
        
        return ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
    }

    /**
        * @todo Hàm tạo ra file tạm
        * @return temp file path
    */
    private function temporary( $name = "temp" ) {

        return tempnam($this->getTmpDir(), $name);
    }
    /**
     * @todo Hàm trả về đuôi file theo data type
     * @param $mime: string data type
     * @return string .jpg
    */
    public function mimeTypeToExtension( $mime ) {

        return $this->configs["mimes"][ $mime ];
    }

    /**
     * @todo Hàm chuyển từ extension sang data type
     * @param extension .jpg
     * @return string data type
    */
    public function extensionToMimeType( $extension ) {

        $extensions = array_flip($this->configs["mimes"]);
        return $extensions[ $extension ];
    }
}