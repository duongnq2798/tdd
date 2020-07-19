<div class=" overflow-hidden shadow-lg pt-3 pl-3 rounded-lg relative" 
   style="height: 200px; overflow:hidden; background-color: #fbd46d">
    <h1 class="font-bold text-2xl mb-2 text-white"> 
      <a href=" {{$project->path()}} "> {{$project->title}} </a>
    </h1>
    <div class="text-white text-base mb-4"> {{ Str::limit($project->description)}} </div>

    <footer class="absolute bottom-0 right-0 mr-5 mb-4">
      <form method="POST" action=" {{ $project->path() }} " class="">
        @csrf
        @method('DELETE')
        <button class="bg-red-500 hover:bg-red-400 text-white text-xs border-none font-semibold py-2 px-4 rounded shadow" type="submit">
          Delete
        </button>
      </form>
    </footer>
</div>
