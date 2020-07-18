<div class=" overflow-hidden shadow-lg pt-3 pl-3 rounded-lg " 
   style="height: 200px; overflow:hidden; background-color: #fbd46d">
    <h1 class="font-bold text-2xl mb-2 text-white"> 
      <a href=" {{$project->path()}} "> {{$project->title}} </a>
    </h1>
    <div class="text-white text-base"> {{ Str::limit($project->description)}} </div>
</div>
