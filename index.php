<?php
// Definindo o diretório base para uploads
$base_dir = "uploads/";
if (!file_exists($base_dir)) {
    mkdir($base_dir, 0777, true); // Cria a pasta se não existir
}

// Verifica se algum arquivo foi enviado
if ($_FILES && isset($_FILES["file"])) {
    // Obtem o tipo do arquivo com base no parâmetro 'type' ou usa 'image' como padrão
    $type = isset($_POST['type']) ? $_POST['type'] : 'image';

    // Define o diretório e tipos permitidos baseado no tipo de arquivo
    switch ($type) {
        case 'audio':
            $target_dir = $base_dir . "audios/";
            $allowedTypes = ['m4a'];
            break;
        case 'video': // Caso adicional para vídeos
            $target_dir = $base_dir . "videos/";
            $allowedTypes = ['mp4'];
            break;
        case 'image':
            $target_dir = $base_dir . "images/";
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            break;
        default:
            $target_dir = $base_dir;
            $allowedTypes = ['pdf']; // Default caso seja outro tipo ('pdf')
            break;
    }

    // Cria o diretório se não existir
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Constrói o caminho completo para o arquivo a ser salvo
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verifica se o arquivo corresponde aos tipos permitidos
    $isValidType = in_array($fileType, $allowedTypes);
    $check = ($type === 'image') ? getimagesize($_FILES["file"]["tmp_name"]) : true;

    if ($check !== false && $isValidType) {
        echo "File is a valid $type - " . ($type === 'image' ? $check["mime"] : "application/$fileType") . ".";
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not a valid $type or file type not allowed.";
    }
} else {
    echo "No file was uploaded.";
}
?>
