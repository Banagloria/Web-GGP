@php
    use App\Services\WorshipSchedulePartitionService;
    /** @var array<string, mixed> $cms */
    /** @var int $colIndex */
    $tableKind = $tableKind ?? (str_contains((string) ($sectionKey ?? ''), 'completed') ? 'completed' : 'upcoming');
    $iconClass = WorshipSchedulePartitionService::columnIconClass($cms, $tableKind, $colIndex);
    $extra = trim($extraClasses ?? '');
@endphp
<i class="{{ trim($iconClass.' '.$extra) }}" aria-hidden="true"></i>
