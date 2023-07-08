<?php

namespace App\Classes;

use Spatie\Valuestore\Valuestore;

class Settings extends Valuestore
{
    public static  $validation = [
        'name' => 'required|max:255',
        'first_address'=>'required',
        'contact_number'=>'required',
    ];

    public function store(){
        return json_decode(json_encode($this->all()));
    }


}
