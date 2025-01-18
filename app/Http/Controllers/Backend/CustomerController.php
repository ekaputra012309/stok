<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Customer | ',
            'datacustomer' => Customer::all(),
        ];
        return view('backend.customer.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add Customer | ',
        ];
        return view('backend.customer.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Adjust as necessary
        ]);

        customer::create($request->all());
        Alert::success('Success', 'Customer created successfully.')->autoClose(2000);
        return redirect()->route('customer.index');
    }

    public function show(customer $customer)
    {
        $data = [
            'title' => 'View Customer | ',
            'customer' => $customer,
        ];
        return view('backend.customer.show', $data);
    }

    public function edit(customer $customer)
    {
        $data = [
            'title' => 'Edit Customer | ',
            'customer' => $customer,
        ];
        return view('backend.customer.edit', $data);
    }

    public function update(Request $request, customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $customer->update($request->all());
        Alert::success('Success', 'customer updated successfully.')->autoClose(2000);

        return redirect()->route('customer.index');
    }

    public function destroy(customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => 'customer deleted successfully.']);
        // Alert::success('Success', 'customer deleted successfully.');

        // return redirect()->route('customer.index');
    }    

    public function export()
    {
        $fileName = 'Customer-' . date('Ymd') . '.xlsx';
        return Excel::download(new CustomerExport, $fileName);
    }
}
