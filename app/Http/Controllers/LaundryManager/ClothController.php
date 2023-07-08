<?php

namespace App\Http\Controllers\LaundryManager;


use App\Http\Controllers\Controller;
use App\Models\Cloth;
use App\Models\ClothServiceMapper;
use App\Models\LaundryService;
use Illuminate\Http\Request;

class ClothController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $cloths = Cloth::all();
        $title = "List Cloth Type(s)";
        return view('cloths.index', compact('cloths', 'title'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $title = "New Cloth Type";
        $services = LaundryService::all();
        return view('cloths.create', compact('title', 'services'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cloth_name' => 'required',
            'cloth_description' => 'required',
        ]);

        $cloth = Cloth::create($validatedData);


        foreach ($request->get('prices') as $key=>$price){
            if(!empty($price)) {
                ClothServiceMapper::create(
                    [
                        'laundry_service_id' => $key,
                        'cloth_id' => $cloth->id,
                        'price' => $price
                    ]
                );
            }
        }

        return redirect()->route('cloths.index')->with('success', 'Cloth Type and Price created successfully.');
    }


    /**
     * @param Cloth $cloth
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $title = "Edit Cloth Type";
        $services = LaundryService::all();
        $cloth = Cloth::find($id);
        return view('cloths.edit', compact('cloth', 'title', 'services'));
    }

    /**
     * @param Request $request
     * @param Cloth $cloth
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cloth_name' => 'required',
            'cloth_description' => 'required',
        ]);

        $cloth = Cloth::find($id);

        $cloth->update($validatedData);

        foreach ($request->get('prices') as $key=>$price){

            ClothServiceMapper::updateorCreate(
                [
                    'laundry_service_id' => $key,
                    'cloth_id' => $cloth->id
                ],
                [
                    'laundry_service_id' => $key,
                    'cloth_id' => $cloth->id,
                    'price' => $price
                ]
            );

        }

        return redirect()->route('cloths.index')->with('success', 'Cloth Type and Price updated successfully.');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function prices()
    {
        $title = "Laundry Price(s)";
        $prices = ClothServiceMapper::with(['laundry_service', 'cloth'])->get();
        return view('cloths.list_price', compact('title', 'prices'));
    }

}
