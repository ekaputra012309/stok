<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;

class TransaksiController extends Controller
{
    protected function generateInvoiceNumber($userId)
    {
        // Format the date
        $date = date('ymd');

        // Get the last transaction for the user on that date
        $lastTransaction = Transaksi::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();

        // Determine the next number
        $nextNumber = $lastTransaction ? (int) substr($lastTransaction->no_inv, -3) + 1 : 1;
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return "INV{$userId}-{$date}-{$formattedNumber}";
    }

    public function index()
    {
        $transaksi = Transaksi::with('user', 'details')
                    ->orderBy('created_at', 'desc')
                    ->get();
        $userId = auth()->user()->id;
        $no_inv = $this->generateInvoiceNumber($userId);
        $data = array(
            'title' => 'Transaksi | ',
            'datatransaksi' => $transaksi,
            'no_inv' => $no_inv,
        );
        $title = 'Delete Transaksi!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backend.transaksi.index', $data);
    }

    public function show($id)
    {
        $transaksiItem = transaksi::with('user', 'role')->findOrFail($id);
        return response()->json($transaksiItem);
    }

    public function create()
    {
        $userId = auth()->user()->id;
        $no_inv = $this->generateInvoiceNumber($userId);
        $data = array(
            'title' => 'Add Transaksi | ',
            'no_inv' => $no_inv,
        );
        return view('backend.transaksi.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'details' => 'required|array',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.harga' => 'required|numeric|min:0',
            'details.*.satuan' => 'required|string|max:255',
        ]);

        // Generate the no_inv
        $no_inv = $this->generateInvoiceNumber($request->user_id);

        // Create the transaction
        $transaksi = Transaksi::create([
            'no_inv' => $no_inv,
            'total' => collect($request->details)->sum(function ($detail) {
                return $detail['qty'] * $detail['harga'];
            }),
            'user_id' => $request->user_id,
        ]);

        // Create transaction details
        foreach ($request->details as $detail) {
            TransaksiDetail::create([
                'table_transaksi_id' => $transaksi->id,
                'no_inv' => $no_inv,
                'qty' => $detail['qty'],
                'harga' => $detail['harga'],
                'satuan' => $detail['satuan'],
                'user_id' => $request->user_id,
            ]);
        }
        Alert::success('Success', 'transaksi created successfully.');
        return redirect()->route('transaksi.index');
    }

    public function edit(transaksi $transaksi)
    {
        $user = User::where('id', '!=', 1)->get();
        $role = Role::where('kode_role', '!=', 'superadmin')->get();
        $data = array(
            'title' => 'Edit Transaksi | ',
            'transaksi' => $transaksi,
            'datauser' => $user,
            'datarole' => $role,
        );
        return view('backend.transaksi.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $transaksi = transaksi::findOrFail($id);
            $transaksi->update($request->all());
            Alert::success('Success', 'transaksi updated successfully.');

            return redirect()->route('transaksi.index');
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        }
    }

    public function destroy($id)
    {
        $transaksi = transaksi::findOrFail($id);
        $transaksi->delete();
        Alert::success('Success', 'transaksi deleted successfully.');

        return redirect()->route('transaksi.index');
    }
}
