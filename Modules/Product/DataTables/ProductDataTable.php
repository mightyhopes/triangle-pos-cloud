<?php

namespace Modules\Product\DataTables;

use Modules\Product\Entities\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)->with('category')
            ->addColumn('action', function ($data) {
                return view('product::products.partials.actions', compact('data'));
            })
            ->addColumn('product_image', function ($data) {
                $media = $data->getFirstMedia('images');
                if ($media) {
                    $url = '/storage/' . $media->id . '/' . $media->file_name;
                    return '<img src="'.$url.'" border="0" width="50" class="img-thumbnail" align="center"/>';
                }
                return '<img src="/images/fallback_product_image.png" border="0" width="50" class="img-thumbnail" align="center"/>';
            })
            ->addColumn('product_price', function ($data) {
                return format_currency($data->product_price);
            })
            ->addColumn('product_cost', function ($data) {
                return format_currency($data->product_cost);
            })
            ->addColumn('product_quantity', function ($data) {
                return $data->product_quantity . ' ' . $data->product_unit;
            })
            ->rawColumns(['product_image', 'action']);
    }

    public function query(Product $model)
    {
        return $model->newQuery()->with('category');
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
                    ->orderBy(7)
                    ->buttons(
                        Button::make('excel')
                            ->text('<i class="bi bi-file-earmark-excel-fill"></i> ' . __('products.excel')),
                        Button::make('print')
                            ->text('<i class="bi bi-printer-fill"></i> ' . __('products.print')),
                        Button::make('reset')
                            ->text('<i class="bi bi-x-circle"></i> ' . __('products.reset')),
                        Button::make('reload')
                            ->text('<i class="bi bi-arrow-repeat"></i> ' . __('products.reload'))
                    );
    }

    protected function getColumns()
    {
        return [
            Column::computed('product_image')
                ->title(__('products.image'))
                ->className('text-center align-middle'),

            Column::make('category.category_name')
                ->title(__('products.category'))
                ->className('text-center align-middle'),

            Column::make('product_code')
                ->title(__('products.code'))
                ->className('text-center align-middle'),

            Column::make('product_name')
                ->title(__('products.name'))
                ->className('text-center align-middle'),

            Column::computed('product_cost')
                ->title(__('products.cost'))
                ->className('text-center align-middle'),

            Column::computed('product_price')
                ->title(__('products.price'))
                ->className('text-center align-middle'),

            Column::computed('product_quantity')
                ->title(__('products.quantity'))
                ->className('text-center align-middle'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
