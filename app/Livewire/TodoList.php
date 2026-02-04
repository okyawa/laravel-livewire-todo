<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:1000')]
    public string $description = '';

    public string $filter = 'all'; // all, pending, completed

    public function addTodo(): void
    {
        $validated = $this->validate();

        Todo::create($validated);

        $this->reset('title', 'description');
        $this->dispatch('todo-added');
    }

    public function toggleTodo(Todo $todo): void
    {
        $todo->update([
            'completed' => !$todo->completed,
            'completed_at' => $todo->completed ? null : now(),
        ]);

        $this->dispatch('todo-toggled');
    }

    public function deleteTodo(Todo $todo): void
    {
        $todo->delete();
        $this->dispatch('todo-deleted');
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    #[\Livewire\Attributes\Computed]
    public function todos()
    {
        $query = Todo::query();

        $filtered = match ($this->filter) {
            'pending' => $query->pending(),
            'completed' => $query->completed(),
            default => $query,
        };

        return $filtered->latest('id')->paginate(10);
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => $this->todos,
            'pendingCount' => Todo::pending()->count(),
            'completedCount' => Todo::completed()->count(),
        ]);
    }
}
