<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\People\Entities\Customer;
use Modules\People\Entities\Supplier;
use Modules\Purchase\Entities\PurchasePayment;
use Modules\PurchasesReturn\Entities\PurchaseReturnPayment;
use Modules\Sale\Entities\SalePayment;
use Modules\SalesReturn\Entities\SaleReturnPayment;

class PaymentsReport extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $start_date;
    public $end_date;
    public $payment_type;
    public $payment_method;
    public $payment_results;

    protected $rules = [
        'start_date' => 'required|date|before:end_date',
        'end_date'   => 'required|date|after:start_date'
    ];
    
    protected $query;

    public function mount() {
        $this->start_date = today()->subDays(30)->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
        $this->payment_type = '';
        $this->payment_method = '';
        $this->query = null;
        $this->payment_results = collect();
    }

    public function render() {
        $this->getQuery();

        if ($this->query) {
            $this->payment_results = $this->query->orderBy('date', 'desc')
                ->when($this->start_date, function ($query) {
                    return $query->whereDate('date', '>=', $this->start_date);
                })
                ->when($this->end_date, function ($query) {
                    return $query->whereDate('date', '<=', $this->end_date);
                })
                ->when($this->payment_method, function ($query) {
                    return $query->where('payment_method', $this->payment_method);
                })
                ->paginate(10);
        }

        return view('livewire.reports.payments-report');
    }

    public function generateReport() {
        $this->validate();
        $this->resetPage();
    }

    public function updatedPaymentType($value) {
        $this->resetPage();
    }

    public function getQuery() {
        if ($this->payment_type == 'sale') {
            $this->query = SalePayment::query()->with('sale');
        } elseif ($this->payment_type == 'sale_return') {
            $this->query = SaleReturnPayment::query()->with('saleReturn');
        } elseif ($this->payment_type == 'purchase') {
            $this->query = PurchasePayment::query()->with('purchase');
        } elseif ($this->payment_type == 'purchase_return') {
            $this->query = PurchaseReturnPayment::query()->with('purchaseReturn');
        } else {
            $this->query = null;
            $this->payment_results = collect();
        }
    }
}
