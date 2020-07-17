@extends('layouts.app')

@section('content')
    
  <div class="container">
    <h1 class="font-bold text-4xl m-auto w-2/4">Let's start something news!</h1>
    <form method="POST" 
      action="/projects" 
      class="w-2/4 m-auto mt-6 bg-gray-100 shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @include('projects.form', [
          'project' => new App\Project,
          'buttonText' => 'Create Project'
        ])
    </form>
  </div>

@endsection
