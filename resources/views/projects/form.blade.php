
  @csrf

  <div class="field mb-4">
    <label class="block text-gray-700 font-bold mb-2 text-2xl" for="title">Title</label>
    <div class="control">
      <input type="text" 
      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
      name="title" 
      placeholder="Title"
      required
      value=" {{ $project->title }}"
      >
    </div>
  </div>

  <div class="field mb-4">
    <label class="block text-gray-700 font-bold mb-2 text-2xl" for="title" >Description</label>
    <div class="control">
      <textarea 
        name="description" 
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
        placeholder="Write here..." 
        style="min-height: 200px">{{ $project->description }}</textarea>
    </div>
  </div>

  <div class="field mb-4">
    <div class="control">
      <button 
        type="submit" 
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"> 
        {{ $buttonText }} 
      </button>
      <a href=" {{ $project->path() }} " class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded">Cancle</a>
    </div>
  </div>

  @include ('projects.errors')