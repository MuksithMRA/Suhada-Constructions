<?php



if (isset($_FILES['file'])) {

    $path = getcwd();
    $path = str_replace('\\', '\\\\', $path);
    $docPath =  $path . "\\\\docs\\\\";

    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    $new_name = time() . '.' . $extension;

    move_uploaded_file($_FILES['file']['tmp_name'], $docPath . $new_name);

    echo json_encode(array("message" => "file uploaded", "status" => "success", "link" => "" . $docPath . $new_name , "file_name"=> $new_name));
} else {
    echo json_encode(array("message" => "file uploading failed !", "status" => "failed", "link" => ""));
}