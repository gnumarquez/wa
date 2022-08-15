<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Gnumarquez\Whatsapp;

class Sendwa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $data;

    public function __construct($request)
    {
        $this->data = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $wa = new Whatsapp();
        
        $wa->telf = $data['telf'];
        $wa->txt = $data['txt'] ?? null;
        $wa->img = $data['img'] ?? null;
        $wa->aud = $data['aud'] ?? null;			
        $wa->mp4 = $data['mp4'] ?? null;
        $wa->pdf = $data['pdf'] ?? null;

        $wa->send();
    }
}
