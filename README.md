# OneSender Laravel

A laravel package to send notification to WhatsApp Channel.

## Install

1. Install require melalui composer:
```
composer remove pasya/onesender-laravel
```

2. Buat file configurasi `config/onesender.php`:

Isi file dengan kode berikut: 
```
<?php
return [
	'phone_column' 	=> 'phone',
	'base_api_url' 	=> 'http://onesender.my.id',
	'api_key' 		=> 'YOUR_API_KEY',
];
```

**Keterangan**:
- `phone_column`: nama kolom di tabel user yang digunakan untuk menyimpan nomor whatsapp.
- `base_api_url`: Isi dengan sub domain atau ip address. Contoh: `http://onesender.my.id`, `http://10.11.12.13:3000`.
- `api_key`: Kode token untuk akses.

3. Tambahkan service provider.
Buka file `config/app.php`. Lalu tambahkan baris berikut:

```

    'providers' => [
        ...        
        Pasya\OneSenderLaravel\OneSenderServiceProvider::class,

    ],


```


## Cara penggunaan

1. Buat notifikasi.
```
php artisan make:notification PesanWhatsApp
```

Selanjutnya buka file `app/Notifications/PesanWhatsApp.php`. 

Contoh sederhana seperti di bawah ini:

```
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PesanWhatsApp extends Notification
{
    use Queueable;

    public $content;
   
    public function __construct(array $payload)
    {
        $this->content = $payload['content'];
    }

    public function via($notifiable)
    {
        return ['onesender'];
    }
}

```



2. Test via `routes/web.php`.

```
use App\Models\User;
use App\Notifications\PesanWhatsApp;

Route::get('/test-wa', function(){

	$messageData = [
		'type' 		=> 'text',
		'content' 	=> 'Kirim pesan dengan OneSender',
    ];

	$user = User::first();

    $user->notify(new PesanWhatsApp($messageData));

    echo 'Pesan terkirim';
});

```
