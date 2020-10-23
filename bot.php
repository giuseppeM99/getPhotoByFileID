<?php
require_once "token.php";
require_once "GetPhotoByFileID.php";

$bot = new GetPhotoByFileID();
$bot->setToken(TOKEN);

$update = json_decode(file_get_contents("php://input"));

if ($update->message->photo) {
    $bot->sendMessage($update->message->chat->id,
        ENDPOINT . "?file_id=" . $update->message->photo[count($update->message->photo) - 1]->file_id);
} else {
    $bot->sendMessage($update->message->chat->id, "Mandami una foto");
}
