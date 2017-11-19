// visibility filters
var filters = {
  all: function (todos) {
    return todos
  },
  active: function (todos) {
    return todos.filter(function (todo) {
      return !todo.completed
    })
  },
  completed: function (todos) {
    return todos.filter(function (todo) {
      return todo.completed
    })
  }
}

// app Vue instance
Vue.use(VueResource);
var app = new Vue({
  // app initial state
  data: {
    todos: [],
    newTodo: '',
    editedTodo: null,
    visibility: 'all'
  },

  created: function () {
    this.getTodos()
  },

  // computed properties
  // http://vuejs.org/guide/computed.html
  computed: {
    filteredTodos: function () {
      return filters[this.visibility](this.todos)
    },
    remaining: function () {
      return filters.active(this.todos).length
    },
    allDone: {
      get: function () {
        return this.remaining === 0
      },
      set: function (value) {
        this.todos.forEach(function (todo) {
          todo.completed = value
        })
      }
    }
  },

  filters: {
    pluralize: function (n) {
      return n === 1 ? 'item' : 'items'
    }
  },

  // methods that implement data logic.
  // note there's no DOM manipulation here at all.
  methods: {
    getTodos: function () {
        var self = this;
        this.$http.get('/todos').then(
            response => self.todos = response.body,
            response => console.error(response.body),
        );
    },

    addTodo: function () {
      var value = this.newTodo && this.newTodo.trim()
      if (!value) {
        return
      }
      var todo = {
        title: value,
        completed: false,
      }

      var self = this;
      this.$http.post('/todos', todo).then(function (response)
          {
              todo = response.body;
              self.todos.push(todo);
          },
          response => console.error(response.body),
      );
      this.newTodo = ''
    },

    updateTodo: function (todo) {
        this.$http.put(`/todos?id=${todo.id}`, todo).then(
            response => todo = response.body,
            response => console.error(response.body),
        );
    },

    removeTodo: function (todo) {
        this.$http.delete(`/todos?id=${todo.id}`).then(
            response => this.todos.splice(this.todos.indexOf(todo), 1),
            response => console.error(response.body),
        );
    },

    editTodo: function (todo) {
      this.beforeEditCache = todo.title
      this.editedTodo = todo
    },

    doneEdit: function (todo) {
      if (!this.editedTodo) {
        return
      }
      this.editedTodo = null
      todo.title = todo.title.trim()
      if (!todo.title) {
        this.removeTodo(todo)
      } else {
        this.updateTodo(todo)
      }
    },

    cancelEdit: function (todo) {
      this.editedTodo = null
      todo.title = this.beforeEditCache
    },

    removeCompleted: function () {
      this.todos = filters.active(this.todos)
    }
  },

  // a custom directive to wait for the DOM to be updated
  // before focusing on the input field.
  // http://vuejs.org/guide/custom-directive.html
  directives: {
    'todo-focus': function (el, binding) {
      if (binding.value) {
        el.focus()
      }
    }
  }
})

// handle routing
function onHashChange () {
  var visibility = window.location.hash.replace(/#\/?/, '')
  if (filters[visibility]) {
    app.visibility = visibility
  } else {
    window.location.hash = ''
    app.visibility = 'all'
  }
}

window.addEventListener('hashchange', onHashChange)
onHashChange()

// mount
app.$mount('.todoapp')
