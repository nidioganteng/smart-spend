<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::latest()->paginate(10);
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'code'     => 'required|string|max:20|unique:vendors,code',
            'category' => 'required|string|max:100',
        ]);

        Vendor::create([
            'name'      => $request->name,
            'code'      => strtoupper($request->code),
            'category'  => $request->category,
            'is_active' => true,
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function show(Vendor $vendor)
    {
        return view('vendor.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'code'      => 'required|string|max:20|unique:vendors,code,' . $vendor->id,
            'category'  => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $vendor->update([
            'name'      => $request->name,
            'code'      => strtoupper($request->code),
            'category'  => $request->category,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus.');
    }

    public function generateQr(Vendor $vendor)
    {
        $expiresAt  = Carbon::now()->addHours(24);
        $payload    = implode('|', [$vendor->id, $vendor->code, $expiresAt->timestamp]);
        $secret     = config('app.key');
        $signature  = hash_hmac('sha256', $payload, $secret);
        $qrToken    = base64_encode($payload . '.' . $signature);

        $vendor->update([
            'qr_token'      => $qrToken,
            'qr_expires_at' => $expiresAt,
        ]);

        return redirect()->route('vendor.show', $vendor)->with('success', 'QR Code berhasil digenerate. Berlaku 24 jam.');
    }
}
