# Libreria para enviar mensajes de whatsapp para laravel

Instalación
<pre>
composer install gnumarquez/wa
</pre>
El siguiente paso genera el job, modelo, y migración para almacenar whatsapp
<pre>
php artisan whatsapp:install
</pre>

Configuración

Configurar en tu .env la la apikey de whatsapp
<pre>
WHATSAPP_APIKEY=
</pre>
Uso

<pre>

use Gnumarquez\Whatsapp;

$wa = new Whatsapp();    
$wa->telf = "12345678";
$wa->txt = "Hola Mundo";
$wa->img = "https://url"; '<--- Opcional
$wa->aud = "https://url"; '<--- Opcional
$wa->mp4 = "https://url"; '<--- Opcional
$wa->pdf = "https://url"; '<--- Opcional
$wa->send();
</pre>
    
Por defecto se guardaran los mensajes automáticamente en la base de datos en la tabla whatsapp, si no quieres que se guarden mensajes en la base de datos debes instanciar la clase con el parametro <b>FALSE</b>
<pre>
$wa = new Whatsapp(false);<
</pre>

Puedes utilizar el Job para que el envío se realice en segundo plano
<pre>
use App\Jobs\Sendwa;

$array = [
    "telf"=>"12345678",
    "txt"=>"Mensaje"
];

Sendwa::dispatch($array);
</pre>