<div class="border-indigo-100 mr-4 p-5 rounded-lg shadow-lg" style="height: 200px; overflow:hidden">
    <h1 class="font-bold text-blue-300 text-xl mb-6 -ml-5 border-l-4 border-blue-400 pl-4"> 
      <a href=" {{$project->path()}} "> {{$project->title}} </a>
    </h1>
    <div class=" mt-3"> {{ Str::limit($project->description)}} </div>
</div>
