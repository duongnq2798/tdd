
  <ul class="text-xs list-reset">
      @foreach ($project->activity as $activity)
          <li class="{{ $loop->last ? '' : 'mb-1' }}">
            <div class="flex items-center bg-teal-500 text-white text-sm font-bold px-4  py-2 mt-2 mb-2" role="alert">
                @include ("projects.activity.{$activity->description}")
                <small class="ml-3">{{ $activity->created_at->diffForHumans(null, true) }}</small>
            </div>
              
          </li>
      @endforeach
  </ul>
