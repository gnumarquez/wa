<?php

namespace Gnumarquez;

use Brick\PhoneNumber\PhoneNumber;
use Gnumarquez\DB;


Class Whatsapp {

	public $telf = null;
	public $cod = null;
	public $txt = null;	
	public $img = null;
	public $pdf = null;
	public $aud = null;
	public $mp4 = null;
	public $sender = null;
	public $result = null;
	public $error = null;
	public $apiKey = null;
	public $status = 0;
	public $btn = null;
	private $save;
	public $arr = null;

	public function __construct($save = true,$arr = null) {
		$this->save = $save;
		$this->arr = $arr;
	}
	public function send(){
		if (empty($this->telf)) throw new \ErrorException('Falta número');
		if (empty($this->txt) && empty($this->img) && empty($this->pdf) && empty($this->aud) && empty($this->mp4)) throw new \ErrorException('Falta texto y/o documentos');

		$api = is_null($this->apiKey) ? env('WHATSAPP_APIKEY'):$this->apiKey;
		
		if (empty($api)) throw new \ErrorException('Falta configurar la variable WHATSAPP_APIKEY');

		if (strlen($this->telf)==8){
			//cr
			$this->telf = "506".$this->telf;
		}
//test
		$this->telf = preg_replace("/^\+/","",$this->telf);

		$nume = preg_replace("/^(?!\+)/","+",$this->telf);
		$number = PhoneNumber::parse($nume);

		$http_code = 200;

		if ($this->status == "3") {
			//esto es para comentarios
			$this->result = json_encode(["error"=>0]);
		} else {
			$data = array(
				'api'=>$api,
				'cod'=>$number->getCountryCode(),
				'pho'=>$number->getNationalNumber(),
				'txt'=>$this->txt,
				'img'=>$this->img,
				'aud'=>$this->aud,
				'mp4'=>$this->mp4,
				'pdf'=>$this->pdf
			);
			if ($this->btn){
				$data['btn'] = $this->btn;
			}

			$url = "https://wapi.dsf.cr/sendwa";
			/*if ($this->btn){
				$data['btn'] = $this->btn;
				$url = "https://api.whatsapp506.biz/sendOneBtn";
			}*/

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$this->result = curl_exec ($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close ($ch);
		}
		
		
		if ($http_code == 200) {
			if ($this->save) {
				if (!$this->arr) {
					$wa = new \App\Models\WhatsappModel();
					$wa->telf = $this->telf;
					$wa->txt = $this->txt;
					$wa->img = $this->img;
					$wa->aud = $this->aud;			
					$wa->mp4 = $this->mp4;
					$wa->pdf = $this->pdf;
					$wa->status = $this->status;
					$wa->sender = $this->sender;
					$wa->save();
				} else {
					$db = new DB($this->arr["db"], $this->arr["user"], $this->arr["pass"], $this->arr["host"]);
					$db->run("insert into whatsapp(telf,txt,img,aud,mp4,pdf,status,sender) values (?,?,?,?,?,?,?,?)",[$this->telf,$this->txt,$this->img,$this->aud,$this->mp4,$this->pdf,$this->status,$this->sender]);
				}
				
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
			$this->error = $error[json_decode($this->result,true)['error']];
			error_log($this->telf." - ".$error[json_decode($this->result,true)['error']]);
			return false;
		}
	}

	public function receive($data,$url = null){
		
		$this->telf = $data['telf'];

		foreach ($data as $key => $value) {
            if ($value == "none") {          
                $data[$key] = null;
            }
        }

		foreach (["img","aud","mp4","pdf"] as $i) {
			if (!empty($data[$i])) {
				$data[$i] = $this->attach($data[$i],$url);
			}
		}

		if (!$this->arr) {
			$wa = new \App\Models\WhatsappModel();
			$wa->telf = $data['telf'];
			$wa->txt = $data['txt'] ?? null;
			$wa->img = $data['img'] ?? null;
			$wa->aud = $data['aud'] ?? null;			
			$wa->mp4 = $data['mp4'] ?? null;
			$wa->pdf = $data['pdf'] ?? null;
			$wa->status = 1;
			$wa->save();
		} else {
			$db = new DB($this->arr["db"], $this->arr["user"], $this->arr["pass"], $this->arr["host"]);
			$db->run("insert into whatsapp(telf,txt,img,aud,mp4,pdf,status) values (?,?,?,?,?,?,?)",[$data['telf'],$data['txt'],$data['img'],$data['aud'],$data['mp4'],$data['pdf'],1]);
		}
			
		
	}

	private  function attach($doc,$url) {
		if (!$url) {
			if (preg_match("/^http/",env("APP_URL"))) {
				$url = env("APP_URL");
			} else {
				if (env("APP_URL")=="localhost"){
					$url = "http://localhost";
				} else {
					$url = "https://".env("APP_URL");
				}
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

		$filename = "./storage/".$name;

		if(!is_dir(dirname($filename))) mkdir(dirname($filename).'/', 0777, TRUE);

		file_put_contents($filename, file_get_contents($doc));
		//copy($doc,$filename);

		//\Storage::disk('public')->put($name,$contents);

		return "/storage/$name";
	}
}

