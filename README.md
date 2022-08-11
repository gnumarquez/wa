# Libreria para enviar mensajes de whatsapp para laravel

Instalación
<pre>
composer install gnumarquez/wa
php artisan migrate <----- Opcional solo si deseas generar la tabla para almacenar los whatsapp
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
$wa->save();
</pre>
    
Por defecto se guardaran los mensajes automáticamente en la base de datos en la tabla whatsapp, si no quiers que se guarden mensajes en la base de datos debes instanciar la clase con el parametro <b>FALSE</b>
<pre>
$wa = new Whatsapp(false);<
</pre>
