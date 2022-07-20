<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CommonExport implements ShouldAutoSize, FromView
{
    protected $data, $request;

    public function __construct($data, $request, $view)
    {
        $this->data = $data;
        $this->request = $request;
        $this->view = $view;
    }

    public function view(): View
    {
        return view($this->view, ['data' => $this->data, 'request' => $this->request]);
    }
}
