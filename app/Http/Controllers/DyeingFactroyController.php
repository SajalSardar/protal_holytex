<?php

namespace App\Http\Controllers;

use App\Models\DyeingFactroy;
use Illuminate\Http\Request;

class DyeingFactroyController extends Controller {

    public function showAll() {
        $dyeingFactory = DyeingFactroy::where('status', 'active')->get();
        if ($dyeingFactory) {
            return json_encode($dyeingFactory);
        } else {
            return 'Yarn not found!';
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $dyeingFactory = DyeingFactroy::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('dyeing_factory.index', compact('dyeingFactory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|unique:dyeing_factroys,name',
            'address' => 'required',
        ]);

        DyeingFactroy::create($request->all());
        toastr('Dyeing Factory Successfully Created!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DyeingFactroy $dyeingFactroy) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DyeingFactroy $dyeingFactroy) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DyeingFactroy $dyeingFactroy) {
        //
    }
}
