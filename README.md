# getPhotoByFileID
A php script which works with **Telegram Bot API** to show a photo by its `file_id`.


Simply rename the file `token.php.example` in `token.php` and set your `TOKEN` and `ENDPOINT`.

Once uploaded on a web server, you can access photos from Telegram using the url

http://example.com/getPhotoByFileID/?file_id=FILEIDFROMTELEGRAM

To enable the bot, just set the webhook using the setWebhook method

https://api.telegram.org/botTOKENHERE/setwebhook?url=https://examplce.com/getPhotoByFileID/bot.php

**You MUST have HTTPS to set up the webhook**
