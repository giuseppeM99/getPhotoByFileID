<?php

require_once "GetPhotoByFileID.php";

$bot = new GetPhotoByFileID();

if (file_exists("token.php")) {
    require_once "token.php";
    $bot->setToken(TOKEN);
} elseif (!empty($_GET["api"])) {
    if($bot->checkToken($_GET["api"])){
        $bot->setToken($_GET["api"]);
    } else {
        $bot->close(403, "INVALID TOKEN");
    }
} else {
    $bot->close(403, "NO BOT TOKEN");
}

$bot->setEncoding();

if (!empty($_GET["file_id"])) {
    $file_id = $_GET["file_id"];
} else {
    $bot->close(400, "NO FILE ID");
}

$file = $bot->getFile($file_id);

if (!$file->ok) {
    $bot->close(502, "BOT API ERROR", $file->description);
}
if (!preg_match("#photos#i", $file->result->file_path)) {
    $bot->close(405, "NOT ALLOWED", "ONLY IMAGES FILE");
}

$imgFile = $bot->downloadFile($file->result->file_path);

$bot->displayImg($imgFile);
