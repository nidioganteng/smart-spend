<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::latest()->paginate(10);
        return view('divisi.index', compact('divisis'));
    }

    public function create()
    {
        return view('divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:divisis,code',
        ]);

        Divisi::create([
            'name'      => $request->name,
            'code'      => strtoupper($request->code),
            'is_active' => true,
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function show(Divisi $divisi)
    {
        return view('divisi.show', compact('divisi'));
    }

    public function edit(Divisi $divisi)
    {
        return view('divisi.edit', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'code'      => 'required|string|max:20|unique:divisis,code,' . $divisi->id,
            'is_active' => 'boolean',
        ]);

        $divisi->update([
            'name'      => $request->name,
            'code'      => strtoupper($request->code),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }

    public function bindRfid(Request $request, Divisi $divisi)
    {
        $request->validate([
            'rfid_uid' => 'required|string|max:50|unique:divisis,rfid_uid,' . $divisi->id,
        ]);

        $divisi->update([
            'rfid_uid'      => strtoupper($request->rfid_uid),
            'rfid_bound_at' => Carbon::now(),
        ]);

        return redirect()->route('divisi.show', $divisi)->with('success', 'Kartu RFID berhasil di-bind ke divisi ini.');
    }

    public function unbindRfid(Divisi $divisi)
    {
        $divisi->update([
            'rfid_uid'      => null,
            'rfid_bound_at' => null,
        ]);

        return redirect()->route('divisi.show', $divisi)->with('success', 'Kartu RFID berhasil di-unbind.');
    }
}
