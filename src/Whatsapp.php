<?php

namespace Gnumarquez;

use Brick\PhoneNumber\PhoneNumber;
use App\Models\WhatsappModel as Wa;


Class Whatsapp {

	public $telf = null;
	public $txt = null;	
	public $img = null;
	public $pdf = null;
	public $aud = null;
	public $mp4 = null;
	public $result = null;
	public $array = [];
	private $save;

	public function __construct($save = true) {
		$this->save = $save;
	}
	public function send(){
		if (empty($this->telf)) throw new \ErrorException('Falta número');
		if (empty($this->txt) && empty($this->img) && empty($this->pdf) && empty($this->aud) && empty($this->mp4)) throw new \ErrorException('Falta texto y/o documentos');
		$api = env('WHATSAPP_APIKEY');
		if (empty($api)) throw new \ErrorException('Falta configurar la variable WHATSAPP_APIKEY');

		if (strlen($this->telf)==8){
			//cr
			$this->telf = "506".$this->telf;
		}
		$nume = preg_replace("/^(?!\+)/","+",$this->telf);
		$number = PhoneNumber::parse($nume);

		if (empty($this->array)) {
			$data = array(
				'cod'=>$number->getCountryCode(),
				'pho'=>$number->getNationalNumber(),
				'txt'=>$this->txt,
				'img'=>$this->img,
				'aud'=>$this->aud,
				'mp4'=>$this->mp4,
				'pdf'=>$this->pdf
			);
		} else {
			$data = $this->array
		}
		$data['api'] = $api;
		

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://api.whatsapp506.biz/sendOne");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$this->result = curl_exec ($ch);
		curl_close ($ch);
		
		if (json_decode($this->result,true)['error'] == 0) {
			if ($this->save) {
				$wa = new Wa();
				$wa->telf = $this->telf;
				$wa->txt = $this->txt;
				$wa->img = $this->img;
				$wa->aud = $this->aud;			
				$wa->mp4 = $this->mp4;
				$wa->pdf = $this->pdf;
				$wa->save();
			}			
			return true;
		} else {
			$error = [
				0 => "Mensaje enviado",
				1 => "Api Key ausente",
				2 => "Código Telefónico Internacional ausente",
				3 => "Número Telefónico ausente",
				4 => "Texto, Imágen y PSD ausente",
				10 => "Api Key incorrecta",
				11 => "Sin saldo",
				21 => "Código Telefónico Internacional no numérico",
				31 => "Número Telefónico no numérico",
				41 => "Texto demasiado largo",
				51 => "Url de imágen no correcta",
				52 => "Imágen no jpeg",
				53 => "Tamaño imágen demasiado grande",
				61 => "Url de PDF no correcto",
				62 => "Documento no PDF",
				63 => "Documento demasiado grande"
			];
			\Log::error($this->telf." - ".$error[json_decode($this->result,true)['error']]);
			return false;
		}
	}

	public function receive($data){
		
		$this->telf = $data['telf'];

		foreach ($data as $key => $value) {
            if ($value == "none") {          
                $data[$key] = null;
            }
        }

		foreach (["img","aud","mp4","pdf"] as $i) {
			if (!empty($data[$i])) {
				$data[$i] = $this->attach($data[$i]);
			}
		}

		$wa = new Wa();
		$wa->telf = $data['telf'];
		$wa->txt = $data['txt'] ?? null;
		$wa->img = $data['img'] ?? null;
		$wa->aud = $data['aud'] ?? null;			
		$wa->mp4 = $data['mp4'] ?? null;
		$wa->pdf = $data['pdf'] ?? null;
		$wa->status = 1;
		$wa->save();
		
	}

	private  function attach($doc) {
		if (preg_match("/^http/",env("APP_URL"))) {
			$url = env("APP_URL");
		} else {
			if (env("APP_URL")=="localhost"){
				$url = "http://localhost";
			} else {
				$url = "https://".env("APP_URL");
			}
		}
		
		$options=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		); 
		$contents = file_get_contents($doc, false, stream_context_create($options));
		$ext = pathinfo($doc)['extension'];
		$name = "$this->telf/".bin2hex(random_bytes(20)).".$ext";
		\Storage::disk('public')->put($name,$contents);
		return "$url/storage/$name";
	}
}

