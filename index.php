<?php
	// CONF
    $host = '$$$CONFIGURE$$$';
    $target_dir = 'uploads/';
    $thumbs_dir = 'uploads/';
    $thumbs_pre = 'tb.';
?>
<!DOCTYPE html>
<html>
<head>
<title>sup</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
  #upload {
      opacity: 0;
  }
  #upload-label {
      position: absolute;
      top: 50%;
      left: 1rem;
      transform: translateY(-50%);
  }
</style>
</head>
<body style="min-height: 100vh; background-color: #757f9a;background-image: linear-gradient(147deg, #757f9a 0%, #d7dde8 100%);">
    <header class="text-white text-center mt-5">
        <a href="<?php echo $host; ?>"><img src="https://bootstrapious.com/i/snippets/sn-img-upload/image.svg" alt="" width="150" class="mb-4"></a>
    </header>
    <div class="container">
    
    <?php    
    if (isset($_POST['submitok'])) {
    	echo '<div class="alert alert-success" role="alert">';
        $original_filename = basename($_FILES["fileToUpload"]["name"]);
        $ext = end(explode('.', $original_filename));

        $original_filename = md5($original_filename.'-'.date('YmdHis')).date('-Ymd').".$ext";
        $target_file = $target_dir . $original_filename;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (getimagesize($_FILES["fileToUpload"]["tmp_name"]) === false) {
            echo "Il file non è un'immagine.";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "&bull; <a href='{$host}uploads/{$original_filename}'>Immagine</a> caricata con successo ";

                $thumbnail_width = 100;
                $thumbnail_height = 100;
                $source_image = imagecreatefromjpeg($target_file);

                list($width, $height) = getimagesize($target_file);
                $aspect_ratio = $width / $height;
                if ($width > $height) {
                    $new_width = $thumbnail_width;
                    $new_height = $thumbnail_width / $aspect_ratio;
                } else {
                    $new_height = $thumbnail_height;
                    $new_width = $thumbnail_height * $aspect_ratio;
                }

                $thumbnail_image = imagecreatetruecolor($new_width, $new_height);

                imagecopyresampled($thumbnail_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                $thumbnail_filename = $thumbs_dir . $thumbs_pre . $original_filename;
                imagejpeg($thumbnail_image, $thumbnail_filename);

                echo "<br>&bull; <a href='{$host}{$thumbnail_filename}'>Miniatura</a> creata con successo";
                echo "<pre>[url={$host}uploads/{$original_filename}]\n[img]{$host}{$thumbnail_filename}[/img]\n[/url]</pre>";

                imagedestroy($source_image);
                imagedestroy($thumbnail_image);
            } else {
                echo "Si è verificato un errore durante il caricamento del file.";
            }
        }
      echo "</div>";
    }
    ?>

        <form id="uu" action="index.php" method="post" enctype="multipart/form-data">
            <div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
                <input id="upload" type="file" name="fileToUpload" onchange="javascript:document.getElementById('uu').submit()" class="form-control border-0">
                <label id="upload-label" for="upload" class="font-weight-light text-muted">Choose file</label>
                <div class="input-group-append">
                    <label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i class="fa fa-cloud-upload mr-2 text-muted"></i><small class="text-uppercase font-weight-bold text-muted">Choose file</small></label>
                </div>
            </div>
            <input name="submitok" value="1" type="hidden" />
        </form>
        
    </div>
</body>
</html>
