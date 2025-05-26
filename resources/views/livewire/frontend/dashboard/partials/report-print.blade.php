@if ($lawyer_application_id)
<section>
    @if ($user->register_as == 'lc')
    <a href="{{ route('lower-court.prints.short-detail', ['id' => $lawyer_application_id, 'type' => 'lc']) }}"
        target="_blank" class="btn btn-primary btn-sm">
        <i class="fas fa-print mr-1"></i>Application Print</a>
    @endif

    @if ($user->register_as == 'gc_lc')
    <a href="{{ route('lower-court.prints.short-detail', ['id' => $lawyer_application_id, 'type' => 'gc_lc']) }}"
        target="_blank" class="btn btn-primary btn-sm">
        <i class="fas fa-print mr-1"></i>Application Print</a>
    @endif

    @if ($user->register_as == 'hc')
    <a href="{{ route('high-court.prints.short-detail', ['id' => $lawyer_application_id, 'type' => 'hc']) }}"
        target="_blank" class="btn btn-primary btn-sm">
        <i class="fas fa-print mr-1"></i>Application Print</a>
    @endif

    @if ($user->register_as == 'gc_hc')
    <a href="{{ route('high-court.prints.short-detail', ['id' => $lawyer_application_id, 'type' => 'gc_hc']) }}"
        target="_blank" class="btn btn-primary btn-sm">
        <i class="fas fa-print mr-1"></i>Application Print</a>
    @endif
</section>
@endif