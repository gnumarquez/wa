# Libreria para enviar mensajes de whatsapp para laravel

Instalación
<pre>
composer install gnumarquez/wa
</pre>
El siguiente paso genera el job, modelo, y migración para almacenar whatsapp, es opcional solo si quieres guardar los whatsapp en base de datos
<pre>
php artisan vendor:publish --provider=Gnumarquez\WaServiceProvider
php artisan migrate
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
    
Por defecto se guardaran los mensajes automáticamente en la base de datos en la tabla whatsapp, si no quiers que se guarden mensajes en la base de datos debes instanciar la clase con el parametro <b>FALSE</b>
<pre>
$wa = new Whatsapp(false);<
</pre>