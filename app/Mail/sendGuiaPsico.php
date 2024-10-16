<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendGuiaPsico extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $guia1;
    public $guia2;
    public $guia3;
    public $id;

    public function __construct($name, $guia1, $guia2, $guia3, $id)
    {
        $this->name = $name;
        $this->guia1 = $guia1;
        $this->guia2 = $guia2;
        $this->guia3 = $guia3;
        $this->id = $id;
    }

  
    public function build()
    {
        // return $this->view('email.psico')->from("Quien lo manda")->subject("Factor de Riesgo Psicosocial - RES")->attachFromStorage("Pasar nombnre de un archivo o ruta para mandarlo");

        return $this->view('email.psico')->subject("Factor de Riesgo Psicosocial - RES");
    }
}
