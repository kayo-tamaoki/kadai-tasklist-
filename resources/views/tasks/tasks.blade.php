@if (count($taskts) > 0)
    <ul class="list-unstyled">
        @foreach ($tasks as $task)
            <li class="media mb-3">
                <div class="media-body">
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($task->content)) !!}</p>
                        <p class="mb-0">{!! nl2br(e($task->status)) !!}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@endif