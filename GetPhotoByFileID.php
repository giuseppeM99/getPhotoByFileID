<?php

class GetPhotoByFileID
{
    private string $token, $myUrl;
    private bool           $gzip = false, $deflate = false;

    public function close(int $code, string $title, string $body = "")
    {
        http_response_code($code);
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        if ($body !== "") {
            echo "<html><body><h1>$title</h1><br><p>$body</p></body></html>";
            die;
        }
        echo "<html><body><h1>$title</h1></body></html>";
        die;
    }

    public function checkToken(string $token): bool
    {
        $tokenArr = explode(":", $token);
        if ((!is_numeric($tokenArr[0])) or (!(sizeof($tokenArr) === 2)) or (!(preg_match_all("/[a-zA-Z0-9_-]/",
                    $tokenArr[1], $matches, PREG_SET_ORDER, 0) === strlen($tokenArr[1])))) {
            return false;
        }
        return true;
    }

    public function setEncoding()
    {
        if (preg_match("|deflate|i", $_SERVER["HTTP_ACCEPT_ENCODING"])) {
            $this->deflate = true;
        } elseif (preg_match("|gzip|i", $_SERVER["HTTP_ACCEPT_ENCODING"])) {
            $this->gzip = true;
        }
    }

    public function displayImg(string $imgFile)
    {
        $imgInfo = getimagesizefromstring($imgFile);
        header("Content-type: {$imgInfo['mime']}");
        header("Cache-Control: max-age=31536000");
        if ($this->deflate) {
            error_log("DEFLATE");
            header("Content-Encoding: deflate");
            echo gzdeflate($imgFile);
        } elseif ($this->gzip) {
            error_log("GZIP");
            header("Content-Encoding: gzip");
            echo gzencode($imgFile);
        } else {
            echo $imgFile;
        }
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    private function Request(string $method, array $args = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $args,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_URL            => "https://api.telegram.org/bot{$this->token}/{$method}",
        ]);
        $resultCurl = curl_exec($curl);
        curl_close($curl);
        if ($resultCurl === false) {
            return false;
        }
        return json_decode($resultCurl);
    }

    public function sendMessage($chat_id, string $text)
    {
        return $this->Request('sendMessage', [
            'chat_id' => $chat_id,
            'text'    => $text
        ]);
    }

    public function getFile($file_id)
    {
        return $this->Request('getFile', [
            'file_id' => $file_id
        ]);
    }

    public function downloadFile($file_path)
    {
        return file_get_contents("http://api.telegram.org/file/bot{$this->token}/{$file_path}");
    }
}
