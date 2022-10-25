<div id="activity-logs">
    @forelse ($activities as $activity)
        <div class="card shadow-sm mb-3">
            <div class="card-body" id="activity-log-{{ $activity->id }}-h">
                <p class="card-text">
                    <i class="fas fa-history mr-1"></i> <a aria-controls="activity-log-{{ $activity->id }}" aria-expanded="true" class="text-body" data-toggle="collapse" href="#activity-log-{{ $activity->id }}">{{ $activity->description }}</a>
                </p>
            </div>
            <div class="collapse" aria-labelledby="activity-log-{{ $activity->id }}-h" data-parent="#activity-logs" id="activity-log-{{ $activity->id }}">
                @php
                    /** @var \Illuminate\Support\Collection $properties */
                    $properties = $activity->changes();
                @endphp
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                        @foreach ($properties['attributes'] ?? [] as $key => $value)
                            <tr>
                                <th class="bg-light">
                                    {{ str_replace('id', 'ID', ucfirst(implode(' ', explode('_', $key)))) }}:
                                </th>
                                <td class="w-100">
                                    @if (is_array($value))
                                        {{ __(':count Item(s)', ['count' => count($value)]) }}
                                    @elseif (is_bool($value))
                                        {{ $value ? 'Yes' : 'No' }}
                                    @elseif (is_string($value))
                                        @if (mb_strlen($value) > 20)
                                            {{ mb_substr($value, 0, 20) }}&hellip;
                                        @elseif (empty($value))
                                            <span class="text-muted">{{ __('Empty') }}</span>
                                        @else
                                            {{ $value }}
                                        @endif
                                    @elseif (is_null($value))
                                        <span class="text-muted">{{ __('Empty') }}</span>
                                    @else
                                        {{ $value }}
                                    @endif
                                    @if ($properties->has('old') && array_key_exists($key, $properties['old']))
                                        <del>
                                            @php
                                                $value = $properties['old'][$key];
                                            @endphp
                                            @if (is_array($value))
                                                {{ __(':count Item(s)', ['count' => count($value)]) }}
                                            @elseif (is_bool($value))
                                                {{ $value ? 'Yes' : 'No' }}
                                            @elseif (is_string($value))
                                                @if (mb_strlen($value) > 20)
                                                    {{ mb_substr($value, 0, 20) }}&hellip;
                                                @elseif (empty($value))
                                                    <span class="text-muted">{{ __('Empty') }}</span>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            @elseif (is_null($value))
                                                <span class="text-muted">{{ __('Empty') }}</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </del>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-right mb-0">
                    <span class="text-muted">{{ __('at') }}</span>
                    {{ $activity->created_at->format('d/m/Y H:i') }}
                    <span class="text-muted">{{ __('by') }}</span>
                    @if ($activity->causer)
                        <a href="{{ route('users.show', $activity->causer) }}">{{ $activity->causer->name }}</a>
                    @else
                        <span class="text-muted">{{ __('Unknown') }}</span>
                    @endif
                </p>
            </div>
        </div>
    @empty
        <div class="bg-light p-2">
            <p class="text-muted text-center mb-0">{{ __('No activity logged.') }}</p>
        </div>
    @endforelse
</div>
