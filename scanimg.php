<?php
    //遍历$file 获取所有图片，这里写一个函数 ，把图片全部存在myimgs数组
    error_reporting (E_ERROR);
    $myimgs = array();
    function getImg($dir){
        global $myimgs;
        $file = scandir($dir);
        // var_dump($file);
        for($i=0;$i<count($file);$i++){
            //var_dump(is_dir($dir."/".$file[$i]));
            if(is_dir($dir."/".$file[$i])){
                if($file[$i]!=".."&&$file[$i]!="."){
                    getImg($dir."/".$file[$i]);
                }		
            }else{
                //判断是否是图片
                $mimetype = exif_imagetype($dir.'/'.$file[$i]);
                if ($mimetype == IMAGETYPE_GIF || $mimetype == IMAGETYPE_JPEG || $mimetype == IMAGETYPE_PNG || $mimetype == IMAGETYPE_BMP)
                {
                    if(!imageCreateFromAny($dir.'/'.$file[$i])){
                        array_push($myimgs,$dir.'/'.$file[$i]);
                    }

                }			

            }
	    }
    }
    function imageCreateFromAny($filepath) { 
        $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
        $allowedTypes = array( 
            1,  // [] gif 
            2,  // [] jpg 
            3,  // [] png 
            6   // [] bmp 
        ); 
        if (!in_array($type, $allowedTypes)) { 
            return false; 
        } 
        switch ($type) { 
            case 1 : 
                $im = imageCreateFromGif($filepath); 
            break; 
            case 2 : 
                $im = imageCreateFromJpeg($filepath); 
            break; 
            case 3 : 
                $im = imageCreateFromPng($filepath); 
            break; 
            case 6 : 
                $im = imageCreateFromBmp($filepath); 
            break; 
        }    
        return $im;  
    }
    foreach($arrfiles as $value){
	    if(is_dir($value)){getImg($value);}
    }

    //var_dump($myimgs);
    $webname = $_SERVER['SERVER_NAME'];

    if(count($myimgs)){
        foreach($myimgs as $value){
            echo $webname.$value."<br/>";
        }
    }else{
        echo "没有找到损坏的图片";
    }
?>