@extends('layouts.app')

@section('content')
    
  <div class="container">
    <h1 class="font-bold text-4xl m-auto w-2/4">Edit Project!!!</h1>
    <form method="POST" 
      action=" {{ $project->path() }} " 
      class="w-2/4 m-auto mt-6 bg-gray-100 shadow-md rounded px-8 pt-6 pb-8 mb-4">
      @method('PATCH')
      @include('projects.form', [
        'buttonText' => 'Update Project'
      ])
    </form>
  </div>

@endsection
