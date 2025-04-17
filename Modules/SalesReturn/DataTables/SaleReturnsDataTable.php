<?php

namespace Modules\SalesReturn\DataTables;

use Modules\SalesReturn\Entities\SaleReturn;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SaleReturnsDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('total_amount', function ($data) {
                return format_currency($data->total_amount);
            })
            ->addColumn('paid_amount', function ($data) {
                return format_currency($data->paid_amount);
            })
            ->addColumn('due_amount', function ($data) {
                return format_currency($data->due_amount);
            })
            ->addColumn('status', function ($data) {
                return view('salesreturn::partials.status', compact('data'));
            })
            ->addColumn('payment_status', function ($data) {
                return view('salesreturn::partials.payment-status', compact('data'));
            })
            ->addColumn('action', function ($data) {
                return view('salesreturn::partials.actions', compact('data'));
            });
    }

    public function query(SaleReturn $model) {
        return $model->newQuery();
    }

    public function html() {
        return $this->builder()
            ->setTableId('sale-returns-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(8)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('sale_return.excel')),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> ' . __('sale_return.print')),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> ' . __('sale_return.reset')),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> ' . __('sale_return.reload'))
            );
    }

    protected function getColumns() {
        return [
            Column::make('reference')
                ->title(__('sale_return.reference'))
                ->className('text-center align-middle'),

            Column::make('customer_name')
                ->title(__('sale_return.customer'))
                ->className('text-center align-middle'),

            Column::computed('status')
                ->title(__('sale_return.status'))
                ->className('text-center align-middle'),

            Column::computed('total_amount')
                ->title(__('sale_return.total_amount'))
                ->className('text-center align-middle'),

            Column::computed('paid_amount')
                ->title(__('sale_return.paid_amount'))
                ->className('text-center align-middle'),

            Column::computed('due_amount')
                ->title(__('sale_return.due_amount'))
                ->className('text-center align-middle'),

            Column::computed('payment_status')
                ->title(__('sale_return.payment_status'))
                ->className('text-center align-middle'),

            Column::computed('action')
                ->title(__('sale_return.action'))
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'SaleReturns_' . date('YmdHis');
    }
}
