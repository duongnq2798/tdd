@extends('layouts.app')

@section('content')
  <div class="flex items-center mb-4">
    <div class="flex justify-between items-center w-full">
      <h2 class="font-bold text-3xl">Projects</h2>
      <a class="button bg-blue-400 text-white py-2 px-6 rounded-md shadow-md font-medium" href="/projects/create">New Project</a>
    </div>
  </div>

  <main class="lg:flex lg:flex-wrap ">
    @forelse ($projects as $project)
      <div class=" lg:w-1/3 px-3 pb-8">
        @include('projects.card')
      </div>
      @empty
          <div class="button">No Project yet.</div>
    @endforelse
  </main>

@endsection
 
