<div class=" overflow-hidden shadow-lg pt-3 pl-3 rounded-lg" 
          style="min-height: 160px;overflow:hidden; background-color: #fbd46d">
            <h3 class="font-bold text-2xl mb-2 text-white"> 
              Invite a User
            </h3>
              <form method="POST" action=" {{ $project->path() . '/invitations'}} " class="relative">
                @csrf
                <div class="mb-3 mr-2">
                  <input type="email" name="email" class="border border-green-100 rounded-full w-full py-2 px-3" placeholder="Email Address">
                </div>
                <button 
                class="bg-red-500 hover:bg-red-400 text-white text-xs 
                border-none font-semibold py-2 px-4 rounded shadow absolute right-0 mr-5" 
                type="submit">
                  Invite
                </button>
              </form>
        </div>