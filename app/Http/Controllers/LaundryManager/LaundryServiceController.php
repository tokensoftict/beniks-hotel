<?php
namespace App\Http\Controllers\LaundryManager;

use App\Http\Controllers\Controller;
use App\Models\LaundryService;
use Illuminate\Http\Request;

class LaundryServiceController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $laundryServices = LaundryService::all();
        $title = "List Service(s)";
        return view('laundry_services.index', compact('laundryServices', 'title'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $title = "Create Service";
        return view('laundry_services.create', compact('title'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'laundry_service_name' => 'required|unique:laundry_services'
        ]);

        LaundryService::create([
            'laundry_service_name' => $request->laundry_service_name
        ]);

        return redirect()->route('services.index')->with('success', 'Laundry service created successfully.');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $laundryService = LaundryService::findOrFail($id);
        $title = "Edit Service";
        return view('laundry_services.edit', compact('laundryService', 'title'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'laundry_service_name' => 'required|unique:laundry_services,laundry_service_name,' . $id
        ]);

        $laundryService = LaundryService::findOrFail($id);
        $laundryService->update([
            'laundry_service_name' => $request->laundry_service_name
        ]);

        return redirect()->route('services.index')->with('success', 'Laundry service updated successfully.');
    }

}
