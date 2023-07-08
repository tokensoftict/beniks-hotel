<?php

namespace App\Http\Controllers\Settings;

use App\Classes\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceSettingsController extends Controller
{
    protected $settings;

    public function __construct(Settings $_settings){
        $this->settings = $_settings;
    }

    public function show(){
        $data['store']=  $this->settings->store();
        if(!isset( $data['store']->price_settings)){
           $this->settings->put('price_settings', [
               'selling_price' => true,
               'vip_selling_price' => false,
               'vvip_selling_price' => false,
               'executive_selling_price' => false,
           ]);
        }
        $data['title'] = "Price Settings";
        $data['price_settings'] = $this->settings->store()->price_settings;
        return view('settings.storesettings.pricesettings',$data);
    }


    public function update(Request  $request)
    {
        $set = [
            'selling_price' => true,
            'vip_selling_price' => false,
            'vvip_selling_price' => false,
            'executive_selling_price' => false,
        ];

        foreach ($set as $key=>$setting)
        {

            if($request->price_settings === NULL)
            {
                return redirect()->route('price_settings.view')->with('error','You must select at least one price to update');
            }

            if(in_array($key, $request->price_settings))
            {
                $set[$key] = true;
            }else{
                $set[$key] = false;
            }
        }

        $this->settings->put('price_settings', $set);

        return redirect()->route('price_settings.view')->with('success','Price Settings has been updated successful!');
    }
}
