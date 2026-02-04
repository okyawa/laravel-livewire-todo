<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- ヘッダー -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-8">
            <h1 class="text-3xl font-bold text-white mb-2">📝 TODO リスト</h1>
            <p class="text-blue-100">あなたのタスクを管理してください</p>
        </div>

        <!-- コンテンツ -->
        <div class="p-6">
            <!-- タスク追加フォーム -->
            <form wire:submit="addTodo" class="mb-8">
                <div class="space-y-4">
                    <!-- タイトル入力 -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            タイトル
                        </label>
                        <input
                            type="text"
                            id="title"
                            wire:model="title"
                            placeholder="新しいタスクを入力..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        />
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 説明入力 -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            説明（オプション）
                        </label>
                        <textarea
                            id="description"
                            wire:model="description"
                            placeholder="タスクの詳細を入力..."
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"
                        ></textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 追加ボタン -->
                    <button
                        type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                    >
                        ➕ タスクを追加
                    </button>
                </div>
            </form>

            <!-- フィルター -->
            <div class="mb-6">
                <div class="flex gap-2 flex-wrap">
                    <button
                        wire:click="setFilter('all')"
                        @class([
                            'px-4 py-2 rounded-lg font-medium transition',
                            'bg-blue-500 text-white' => $filter === 'all',
                            'bg-gray-200 text-gray-700 hover:bg-gray-300' => $filter !== 'all',
                        ])
                    >
                        すべて ({{ $todos->total() }})
                    </button>
                    <button
                        wire:click="setFilter('pending')"
                        @class([
                            'px-4 py-2 rounded-lg font-medium transition',
                            'bg-blue-500 text-white' => $filter === 'pending',
                            'bg-gray-200 text-gray-700 hover:bg-gray-300' => $filter !== 'pending',
                        ])
                    >
                        未完了 ({{ $pendingCount }})
                    </button>
                    <button
                        wire:click="setFilter('completed')"
                        @class([
                            'px-4 py-2 rounded-lg font-medium transition',
                            'bg-blue-500 text-white' => $filter === 'completed',
                            'bg-gray-200 text-gray-700 hover:bg-gray-300' => $filter !== 'completed',
                        ])
                    >
                        完了済み ({{ $completedCount }})
                    </button>
                </div>
            </div>

            <!-- タスクリスト -->
            <div class="space-y-3">
                @forelse ($todos as $todo)
                    <div
                        @class([
                            'flex items-start gap-4 p-4 border rounded-lg transition',
                            'bg-gray-50 border-gray-200' => $todo->completed,
                            'bg-white border-gray-300 hover:border-blue-400' => !$todo->completed,
                        ])
                    >
                        <!-- チェックボックス -->
                        <button
                            wire:click="toggleTodo({{ $todo->id }})"
                            class="flex-shrink-0 mt-1 focus:outline-none"
                        >
                            <div
                                @class([
                                    'w-6 h-6 rounded border-2 flex items-center justify-center transition',
                                    'bg-green-500 border-green-500' => $todo->completed,
                                    'border-gray-300 hover:border-blue-500' => !$todo->completed,
                                ])
                            >
                                @if ($todo->completed)
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                        </button>

                        <!-- タスク内容 -->
                        <div class="flex-grow min-w-0">
                            <h3
                                @class([
                                    'font-semibold text-gray-900 break-words',
                                    'line-through text-gray-500' => $todo->completed,
                                ])
                            >
                                {{ $todo->title }}
                            </h3>
                            @if ($todo->description)
                                <p
                                    @class([
                                        'text-sm text-gray-600 mt-1 break-words',
                                        'line-through text-gray-400' => $todo->completed,
                                    ])
                                >
                                    {{ $todo->description }}
                                </p>
                            @endif
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                <span>作成: {{ $todo->created_at->format('Y年m月d日 H:i') }}</span>
                                @if ($todo->completed_at)
                                    <span class="text-green-600">✓ 完了: {{ $todo->completed_at->format('Y年m月d日 H:i') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- 削除ボタン -->
                        <button
                            wire:click="deleteTodo({{ $todo->id }})"
                            wire:confirm="このタスクを削除しますか？"
                            class="flex-shrink-0 text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">タスクがありません</p>
                        <p class="text-gray-400 text-sm mt-1">
                            @if ($filter === 'pending')
                                すべてのタスクが完了しました！🎉
                            @elseif ($filter === 'completed')
                                完了済みのタスクはありません
                            @else
                                新しいタスクを追加してください
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            @if ($todos->hasPages())
                <div class="mt-6">
                    {{ $todos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
