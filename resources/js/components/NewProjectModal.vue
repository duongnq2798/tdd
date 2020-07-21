<template>
  <modal name="new-project" classes="p-10 rounded-lg" height="auto">
    <h1 class="font-normal mb-10 text-center text-2xl">Let's start something new!</h1>

    <form @submit.prevent="submit">
      <div class="flex">
        <div class="flex-1 mr-4">
          <div class="mb-4">
            <label for="title" class="text-sm block mb-2">Title</label>
            <input
              type="text"
              id="title"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
              :class="form.errors.title ? 'border-red-300' : 'border-blue-400'"
              v-model="form.title"
            />
            <span
              class="text-xs text-italic text-error text-red-500"
              v-if="form.errors.title"
              v-text="errors.title[0]"
            ></span>
          </div>
          <div class="mb-4">
            <label for="description" class="text-sm block mb-2">Description</label>
            <textarea
              name="description"
              id="description"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
              :class="form.errors.description ? 'border-error' : 'border-muted-light'"
              rows="7"
              v-model="form.description"
            ></textarea>
            <span
              class="text-xs text-italic text-error text-red-500"
              v-if="form.errors.description"
              v-text="form.errors.description[0]"
            ></span>
          </div>
        </div>

        <div class="flex-1 ml-4">
          <div class="mb-4">
            <label class="text-sm block mb-2">Need Some Tasks</label>
            <input
              type="text"
              class="shadow appearance-none border rounded mb-2 w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
              placeholder="Task 1"
              v-for="(task, idx) in form.tasks"
              :key="idx"
              v-model="task.body"
            />
          </div>

          <button
            type="button"
            class="inline-flex items-center focus:outline-none"
            @click="addTask"
          >
            <img class="w-3 h-3" src="/img/plus.svg" alt="Add Task" />
            <span class="text-sm font-semibold ml-2">Add Task</span>
          </button>
        </div>
      </div>
      <footer class="flex justify-end">
        <button
          type="button"
          @click="$modal.hide('new-project')"
          class="bg-transparent hover:bg-blue-400 text-blue-400 font-semibold hover:text-white py-2 px-4 border border-blue-400 hover:border-transparent rounded focus:outline-none mr-3"
        >Cancle</button>
        <button
          class="button bg-blue-400 text-white py-2 px-4 rounded-md shadow-md font-medium focus:outline-none"
        >Create Project</button>
      </footer>
    </form>
  </modal>
</template>

<script>
import BirdboardForm from "./BirdboardForm";
export default {
  data() {
    return {
      form: new BirdboardForm({
        title: "",
        description: "",
        tasks: [{ body: "" }]
      })
    };
  },
  methods: {
    addTask() {
      this.form.tasks.push({ body: "" });
    },
    async submit() {
      if (!this.form.tasks[0].body) {
        delete this.form.originalData.tasks;
      }
      this.form
        .submit("/projects")
        .then(response => (location = response.data.message));
    }
  }
};
</script>