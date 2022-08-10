<?php

namespace Gnumarquez;

use Brick\PhoneNumber\PhoneNumber;
use Gnumarquez\models\Whatsapp as Wa;


Class Whatsapp {

	public $telf = "";
	public $txt = "";	
	public $img = "";
	public $pdf = "";
	public $aud = "";
	public $mp4 = "";
	public $result = "";

	public function send(){
		if (empty($this->telf)) throw new \ErrorException('Falta número');
		if (empty($this->txt) && empty($this->img) && empty($this->pdf) && empty($this->aud) && empty($this->mp4)) throw new \ErrorException('Falta texto y/o documentos');

		$nume = preg_replace("/^(?!\+)/","+",$this->telf);
		$number = PhoneNumber::parse($nume);

		$api = env('WHATSAPP_APIKEY');

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

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://api.whatsapp506.biz/sendOne");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$this->result = curl_exec ($ch);
		curl_close ($ch);
		
		if (json_decode($this->result,true)['error'] == 0) {
			$wa = new Wa();
			$wa->telf = $this->telf;
			$wa->txt = $this->txt;
			$wa->img = $this->img;
			$wa->aud = $this->aud;			
			$wa->mp4 = $this->mp4;
			$wa->pdf = $this->pdf;
			$wa->status = 0;
			$wa->save();
			return true;
		} else {
			return false;
		}
	}
}

