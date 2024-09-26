<?php
require_once __DIR__ . '/../rest/Response.php';

$dir = __DIR__ . "/images";

try {

    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    if ($_FILES['upfile']['size'] > 1_000_000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (
        false === $ext = array_search(
            $finfo->file($_FILES['upfile']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
            ),
            true
        )
    ) {
        throw new RuntimeException('Invalid file format.');
    }

    $fileName = sprintf(
        "%s.%s",
        sha1_file($_FILES['upfile']['tmp_name']),
        $ext
    );
    if (
        !move_uploaded_file(
            $_FILES['upfile']['tmp_name'], $dir . "/$fileName")
    ) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    Response::echo(0, "File is uploaded successfully.", $fileName);
} catch (RuntimeException $e) {
    Response::echo(1, $e->getMessage(), null);
}

?>