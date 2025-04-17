<td data-label="Date">{{ $stock_adjustment->date }}</td>
<td data-label="Reference">{{ $stock_adjustment->reference }}</td>
<td data-label="Note">{{ $stock_adjustment->note }}</td>
<td data-label="Actions">
    <a href="{{ route('stock-adjustments.show', $stock_adjustment->id) }}" class="btn btn-info btn-sm">
        <i class="bi bi-eye"></i>
    </a>
    <a href="{{ route('stock-adjustments.edit', $stock_adjustment->id) }}" class="btn btn-primary btn-sm">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('stock-adjustments.destroy', $stock_adjustment->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</td> 