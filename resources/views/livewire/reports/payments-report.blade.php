<div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form wire:submit="generateReport">
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('report.start_date') }} <span class="text-danger">*</span></label>
                                    <input wire:model="start_date" type="date" class="form-control" name="start_date">
                                    @error('start_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('report.end_date') }} <span class="text-danger">*</span></label>
                                    <input wire:model="end_date" type="date" class="form-control" name="end_date">
                                    @error('end_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('report.payment_type') }}</label>
                                    <select wire:model.live="payment_type" class="form-control" name="payment_type">
                                        <option value="">{{ __('report.all_payment_types') }}</option>
                                        <option value="sale">{{ __('report.sales') }}</option>
                                        <option value="sale_return">{{ __('report.sale_returns') }}</option>
                                        <option value="purchase">{{ __('report.purchases') }}</option>
                                        <option value="purchase_return">{{ __('report.purchase_returns') }}</option>
                                        <option value="expense">{{ __('expense.expenses') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('report.payment_method') }}</label>
                                    <select wire:model="payment_method" class="form-control" name="payment_method">
                                        <option value="">{{ __('report.all_payment_methods') }}</option>
                                        <option value="Cash">{{ __('Cash') }}</option>
                                        <option value="Credit Card">{{ __('Credit Card') }}</option>
                                        <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                        <option value="Cheque">{{ __('Cheque') }}</option>
                                        <option value="Other">{{ __('Other') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <span wire:target="generateReport" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i wire:target="generateReport" wire:loading.remove class="bi bi-shuffle"></i>
                                {{ __('report.filter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div>
                        <h6 class="text-center mb-4">{{ __('report.payments_report') }}</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr class="text-center">
                                    <th class="align-middle">{{ __('report.date') }}</th>
                                    <th class="align-middle">{{ __('report.reference') }}</th>
                                    <th class="align-middle">{{ __('report.payment_type') }}</th>
                                    <th class="align-middle">{{ __('report.payment_method') }}</th>
                                    <th class="align-middle">{{ __('report.amount') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(is_object($payment_results) && count($payment_results) > 0)
                                    @foreach($payment_results as $payment)
                                        <tr class="text-center">
                                            <td class="align-middle">{{ \Carbon\Carbon::parse($payment->date)->format('d M, Y') }}</td>
                                            <td class="align-middle">{{ $payment->reference }}</td>
                                            <td class="align-middle">
                                                @if($payment->payment_type == 'sale')
                                                    <span class="badge badge-success">{{ __('report.sales') }}</span>
                                                @elseif($payment->payment_type == 'sale_return')
                                                    <span class="badge badge-danger">{{ __('report.sale_returns') }}</span>
                                                @elseif($payment->payment_type == 'purchase')
                                                    <span class="badge badge-info">{{ __('report.purchases') }}</span>
                                                @elseif($payment->payment_type == 'purchase_return')
                                                    <span class="badge badge-warning">{{ __('report.purchase_returns') }}</span>
                                                @elseif($payment->payment_type == 'expense')
                                                    <span class="badge badge-dark">{{ __('expense.expenses') }}</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">{{ $payment->payment_method }}</td>
                                            <td class="align-middle">
                                                @if($payment->amount < 0)
                                                    <span class="text-danger">
                                                        {{ format_currency($payment->amount) }}
                                                    </span>
                                                @else
                                                    <span class="text-success">
                                                        {{ format_currency($payment->amount) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <span class="text-secondary">
                                                {{ __('report.no_data_available') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            
                            @if(is_object($payment_results) && method_exists($payment_results, 'links'))
                                <div class="mt-3">
                                    {{ $payment_results->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
