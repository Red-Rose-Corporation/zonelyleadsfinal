{{-- Generic add/edit/delete block for one simple section. Expects: $user, $key, $def, $items --}}
@php
    $renderField = function (array $f, string $prefix, $value, bool $fromOld) {
        $name = $f['name'];
        $val  = $fromOld ? old($name) : $value;
        $id   = $prefix . '-' . $name;
        $html = '<div class="col-md-' . ($f['col'] ?? 12) . ' col-12">';
        if ($f['type'] === 'checkbox') {
            $html .= '<div class="form-check form-switch mt-md-4 pt-md-2">'
                   . '<input class="form-check-input" type="checkbox" role="switch" name="' . e($name) . '" id="' . e($id) . '" value="1"' . ($val ? ' checked' : '') . '>'
                   . '<label class="form-check-label" for="' . e($id) . '">' . e($f['label']) . '</label></div>';
        } else {
            $html .= '<label class="form-label fw-semibold" for="' . e($id) . '">' . e($f['label']) . '</label>';
            if ($f['type'] === 'select') {
                $html .= '<select class="form-select" name="' . e($name) . '" id="' . e($id) . '"' . (!empty($f['required']) ? ' required' : '') . '>';
                $html .= '<option value="">Select…</option>';
                foreach ($f['options'] as $ov => $ol) {
                    $html .= '<option value="' . e($ov) . '"' . ((string) $val === (string) $ov ? ' selected' : '') . '>' . e($ol) . '</option>';
                }
                $html .= '</select>';
            } elseif ($f['type'] === 'textarea') {
                $html .= '<textarea class="form-control" name="' . e($name) . '" id="' . e($id) . '" rows="' . ($f['rows'] ?? 3) . '"'
                       . ' maxlength="' . ($f['max'] ?? 1000) . '">' . e($val) . '</textarea>';
            } else {
                $html .= '<input type="text" class="form-control" name="' . e($name) . '" id="' . e($id) . '"'
                       . ' maxlength="' . ($f['max'] ?? 255) . '"' . (!empty($f['required']) ? ' required' : '')
                       . (isset($f['placeholder']) ? ' placeholder="' . e($f['placeholder']) . '"' : '')
                       . ' value="' . e($val) . '">';
            }
        }
        return $html . '</div>';
    };
    $newForm = 'item-' . $key . '-new';
@endphp

<div class="section-card mb-4" id="items-{{ $key }}">
    <div class="card-header {{ $def['header'] }} p-3">
        <h6 class="mb-0"><i class="fas {{ $def['icon'] }} me-2"></i>{{ $def['title'] }} ({{ $items->count() }})</h6>
    </div>
    <div class="card-body p-4">
        <p class="text-muted small">{{ $def['hint'] }}</p>

        @forelse($items as $row)
        @php
            $rowForm = 'item-' . $key . '-' . $row->id;
            $fromOld = old('_form') === $rowForm;
            [$primary, $secondary] = ($def['summary'])($row);
        @endphp
        <div class="border rounded-3 mb-3">
            <div class="p-3">
                <div class="fw-semibold">{{ $primary }}</div>
                @if($secondary !== '')<div class="small text-muted">{{ $secondary }}</div>@endif
            </div>
            <details class="border-top" {{ $fromOld ? 'open' : '' }}>
                <summary class="px-3 py-2 small fw-semibold text-primary" style="cursor:pointer">Edit</summary>
                <form method="POST" action="{{ route('admin.profiles.sections.items.update', [$user->id, $key, $row->id]) }}" class="p-3 pt-2">
                    @csrf @method('PUT')
                    <input type="hidden" name="_form" value="{{ $rowForm }}">
                    <div class="row g-3">
                        @foreach($def['fields'] as $f)
                            {!! $renderField($f, $rowForm, $row->{$f['name']}, $fromOld) !!}
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-success btn-sm mt-3"><i class="fas fa-save me-1"></i> Save</button>
                </form>
            </details>
            <div class="p-3 pt-0 border-top bg-light bg-opacity-50">
                <form method="POST" action="{{ route('admin.profiles.sections.items.destroy', [$user->id, $key, $row->id]) }}" class="mt-2"
                      onsubmit="return confirm('Delete this entry? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                </form>
            </div>
        </div>
        @empty
        <p class="text-muted mb-3">Nothing added yet.</p>
        @endforelse

        @php $newOld = old('_form') === $newForm; @endphp
        <details class="border rounded-3" {{ $newOld ? 'open' : '' }}>
            <summary class="px-3 py-2 fw-semibold text-success" style="cursor:pointer"><i class="fas fa-plus me-1"></i> Add {{ $def['item'] }}</summary>
            <form method="POST" action="{{ route('admin.profiles.sections.items.store', [$user->id, $key]) }}" class="p-3 pt-2">
                @csrf
                <input type="hidden" name="_form" value="{{ $newForm }}">
                <div class="row g-3">
                    @foreach($def['fields'] as $f)
                        {!! $renderField($f, $newForm, null, $newOld) !!}
                    @endforeach
                </div>
                <button type="submit" class="btn btn-success btn-sm mt-3"><i class="fas fa-plus me-1"></i> Add</button>
            </form>
        </details>
    </div>
</div>
