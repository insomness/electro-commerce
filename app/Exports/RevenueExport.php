<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class RevenueExport implements FromView
{

    private $_results;

    /**
     * Create a new exporter instance.
     *
     * @param array $results query result
     *
     * @return void
     */
    public function __construct($results)
    {
        $this->_results = $results;
    }

    public function view(): View
    {
        return view('admin.reports.exports.revenue_xlsx', [
            'revenues' => $this->_results
        ]);
    }
}
