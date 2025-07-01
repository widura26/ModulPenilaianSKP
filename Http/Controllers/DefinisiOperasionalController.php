<?php

namespace Modules\Penilaian\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Penilaian\Entities\DefinisiOperasional;

class DefinisiOperasionalController extends Controller
{
    public function index()
    {
        // return view('penilaian::definisi-operasional.view-definisi-operasional');
        $dataDefinisi = DefinisiOperasional::orderBy('topik')->get();
        return view('penilaian::definisi-operasional.view-definisi-operasional', compact('dataDefinisi'));
    }

    public function create()
    {
        return view('penilaian::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'topik' => 'required|string|max:255',
            'sub_topik' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        DefinisiOperasional::create([
            'topik' => $request->topik,
            'sub_topik' => $request->sub_topik,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
        return view('penilaian::show');
    }

    public function edit($id)
    {
        return view('penilaian::edit');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'topik' => 'required|string|max:255',
            'sub_topik' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $item = DefinisiOperasional::findOrFail($id);
        $item->update($request->only('topik', 'sub_topik', 'deskripsi'));

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $item = DefinisiOperasional::findOrFail($id);
            $item->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }


    // public function destroy($id)
    // {
    //     $item = DefinisiOperasional::findOrFail($id);
    //     $item->delete();

    //     return redirect()->back()->with('success', 'Data berhasil dihapus');
    // }
}
