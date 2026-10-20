<?php

namespace App\Exports;

use App\Models\BarangTemplate;
use App\Models\BarangTemplateDetail;
use App\Models\CompanyProfile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BarangAssyExport implements FromView
{
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $barangTemplate = BarangTemplate::with('details.barang', 'user')->findOrFail($this->id);

        $companyProfile = CompanyProfile::first();
           

        return view('backend.barang_template.export_barang_template', [
            'barangTemplate' => $barangTemplate,
            'companyProfile' => $companyProfile,
        ]);
    }
}