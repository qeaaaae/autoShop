<?php
function upload_image($fileField)
{
    $allowedExtensions = array("image/jpg", "image/jpeg", "image/png");
    $maxFileSize = 2 * 2048 * 2048;

    $uploadedFile = $_FILES[$fileField];
    $filename = $uploadedFile['name'];
    $fileType = $uploadedFile['type'];
    $fileSize = $uploadedFile['size'];

    if (!in_array($fileType, $allowedExtensions)) {
        echo '<script>alert("Ошибка: Некорректрое расширение картинки.")</script>';
        return false;
    } elseif ($fileSize > $maxFileSize) {
        echo '<script>alert("Ошибка: Файл слишком большой. Максимальный размер - 5 МБ.")</script>';
        return false;
    } else {
        $destination = __DIR__ . '/images/' . $filename;
        move_uploaded_file($uploadedFile['tmp_name'], $destination);
        return true;
    }
}

function create_cypher($length = 6)
{
    $arr = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    );

    $res = '#';
    for ($i = 0; $i < $length; $i++) {
        $res .= $arr[random_int(0, count($arr) - 1)];
    }
    return $res;
}
