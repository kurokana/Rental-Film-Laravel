<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    // Daftar promo
    public function index()
    {
        $promos = Promo::orderBy('created_at', 'desc')->paginate(20);
        
        return view('owner.promos.index', compact('promos'));
    }

    // Form tambah promo
    public function create()
    {
        return view('owner.promos.create');
    }

    // Simpan promo baru
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promos,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_transaction' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $promo = Promo::create($request->all());

        // Audit log
        AuditLog::log(
            'create',
            'Promo',
            $promo->id,
            "Promo {$promo->code} ditambahkan oleh " . auth()->user()->name,
            null,
            $promo->toArray()
        );

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    // Form edit promo
    public function edit(Promo $promo)
    {
        return view('owner.promos.edit', compact('promo'));
    }

    // Update promo
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promos,code,' . $promo->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_transaction' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $oldData = $promo->toArray();
        $promo->update($request->all());

        // Audit log
        AuditLog::log(
            'update',
            'Promo',
            $promo->id,
            "Promo {$promo->code} diupdate oleh " . auth()->user()->name,
            $oldData,
            $promo->toArray()
        );

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil diupdate.');
    }

    // Hapus promo
    public function destroy(Promo $promo)
    {
        // Check if promo is being used
        if ($promo->rentals()->exists()) {
            return back()->with('error', 'Promo tidak dapat dihapus karena sedang/sudah digunakan.');
        }

        $oldData = $promo->toArray();
        $promo->delete();

        // Audit log
        AuditLog::log(
            'delete',
            'Promo',
            $promo->id,
            "Promo {$promo->code} dihapus oleh " . auth()->user()->name,
            $oldData,
            null
        );

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    // Toggle status promo
    public function toggleStatus(Promo $promo)
    {
        $oldStatus = $promo->is_active;
        $promo->update(['is_active' => !$promo->is_active]);

        // Audit log
        AuditLog::log(
            'update',
            'Promo',
            $promo->id,
            "Status promo {$promo->code} diubah oleh " . auth()->user()->name,
            ['is_active' => $oldStatus],
            ['is_active' => $promo->is_active]
        );

        return back()->with('success', 'Status promo berhasil diubah.');
    }
}
