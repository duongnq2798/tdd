@extends('layouts.app')

@section('content')
  <header class="flex items-center mb-3 py-4">
    <div class="flex justify-between items-end w-full">
      <p class="font-semibold text-grey text-lg mb-3">
        <a class="text-gray-600" href="/projects">My Projects</a> / {{ $project->title }}
      </p>

      <div class="flex items-center">
        @foreach ($project->members as $member)
            <img
                src="{{ gravatar_url($member->email) }}"
                alt="{{ $member->name }}'s avatar"
                class="rounded-full w-8 mr-2">
        @endforeach

        <img
            src="{{ gravatar_url($project->owner->email) }}"
            alt="{{ $project->owner->name }}'s avatar"
            class="rounded-full w-8 mr-2">

            <a href="{{ $project->path().'/edit' }}" class="bg-pink-400 hover:bg-blue-200 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
              Edit Project
            </a>
    </div>

      
    </div>
  </header>

  <main>
    <div class="lg:flex">    
      
      <div class="lg:w-3/4 px-3">
        <div class="mb-6">
          <h2 class="font-semibold text-grey text-lg mb-3">Tasks</h2>
  
          @foreach ($project->tasks as $task)
            <div class="rounded-lg shadow p-4 mb-3 bg-white"> 
              <form method="POST" action="{{ $task->path() }}">
                @csrf
                @method('PATCH')

                <div class="flex">
                  <input name="body" value="{{ $task->body }}" class="outline-none w-full {{ $task->completed ? 'text-grey' : '' }}" >
                  <input name="completed" type="checkbox" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                </div>
              </form>
            </div>     
          @endforeach

          <div class="rounded-lg shadow py-4 pl-3 mb-3 bg-white">
            <form action="{{ $project->path() . '/tasks' }}" method="POST">
              @csrf
               <input placeholder="Add a new task..." class="w-full outline-none" name="body">
            </form>
          </div>
        </div>
  
        <div>
          <h2 class="font-semibold text-grey  text-lg mb-3">General Notes</h2>
          {{-- general notes --}}
          <form method="POST" action=" {{ $project->path() }} ">
            @csrf
            @method('PATCH')
            <textarea 
              name="notes"
              class=" w-full rounded-lg shadow py-3 pl-3 outline-none" 
              style="min-height: 200px" 
              placeholder="Note here..."
              >{{ $project->notes }}</textarea>
            <button type="submit" class="button bg-blue-300 shadow-lg py-3 px-8 mt-5 rounded-md mb-5">Save</button>
          </form>

          @include ('projects.errors')

        </div>
      </div>

      <div class="lg:w-1/4 px-3">
        @include('projects.card')

        @include ('projects.activity.card')

        @can ('manage', $project)      
            @include('projects.invite')
        @endcan

      </div>
    </div>
  </main>
  
@endsection

