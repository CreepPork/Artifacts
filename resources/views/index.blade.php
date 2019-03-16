<ul>
    @if (count($uploads) > 0)
        @foreach ($uploads as $upload)
            <li><a href="{{ Storage::url($upload->path) }}">{{ $upload->original_filename }}</a> - uploaded {{ $upload->created_at->diffForHumans() }}</li>
        @endforeach
    @endif
</ul>
