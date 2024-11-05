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
    public $guia5;
    public $status;
    public $fechalimite;
    public $id;
    public $dias;

    public function __construct($name, $guia1, $guia2, $guia3, $guia5, $status, $fechalimite, $id, $dias)
    {
        $this->name = $name;
        $this->guia1 = $guia1;
        $this->guia2 = $guia2;
        $this->guia3 = $guia3;
        $this->guia5 = $guia5;
        $this->status = $status;
        $this->fechalimite = $fechalimite;
        $this->id = $id;
        $this->dias = $dias;
    }

  
    public function build()
    {
        // return $this->view('email.psico')->from("Quien lo manda")->subject("Factor de Riesgo Psicosocial - RES")->attachFromStorage("Pasar nombnre de un archivo o ruta para mandarlo");

        return $this->view('email.psico')->subject("Evaluación del Factor de Riesgo Psicosocial (NOM-035-STPS-2018)");
    }
}
