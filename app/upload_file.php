<?php


if (isset($_FILES['file'])) {

    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    $new_name = time() . '.' . $extension;

    move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/docs/' . $new_name);

    http_response_code(200);
    echo json_encode(array("message" => "file uploaded", "status" => 200, "link" => "" . $_SERVER['DOCUMENT_ROOT'] . '/docs/' . $new_name , "file_name"=> $new_name));
} else {
    http_response_code(404);
    echo json_encode(array("message" => "file uploading failed !", "status" => 404, "link" => ""));
}