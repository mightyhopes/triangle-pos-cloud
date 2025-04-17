<?php

namespace Modules\Quotation\DataTables;

use Modules\Quotation\Entities\Quotation;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QuotationsDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('total_amount', function ($data) {
                return format_currency($data->total_amount);
            })
            ->addColumn('status', function ($data) {
                return view('quotation::partials.status', compact('data'));
            })
            ->addColumn('action', function ($data) {
                return view('quotation::partials.actions', compact('data'));
            });
    }

    public function query(Quotation $model) {
        return $model->newQuery();
    }

    public function html() {
        return $this->builder()
            ->setTableId('sales-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(6)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('quotation.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('quotation.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('quotation.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('quotation.reload'))
            );
    }

    protected function getColumns() {
        return [
            Column::make('date')
                ->title(__('quotation.date'))
                ->className('text-center align-middle'),

            Column::make('reference')
                ->title(__('quotation.reference'))
                ->className('text-center align-middle'),

            Column::make('customer_name')
                ->title(__('quotation.customer'))
                ->className('text-center align-middle'),

            Column::computed('status')
                ->title(__('quotation.status'))
                ->className('text-center align-middle'),

            Column::computed('total_amount')
                ->title(__('quotation.total_amount'))
                ->className('text-center align-middle'),

            Column::computed('action')
                ->title(__('quotation.action'))
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Quotations_' . date('YmdHis');
    }
}
