# Laravel Livewire TODO アプリケーション

モダンなLaravelとLivewireを使用したリアルタイムTODOアプリケーションです。

## 特徴

✨ **モダンなLaravel実装**
- Laravel 11の最新構文を使用
- 属性ベースのバリデーション (`#[Validate]`)
- Computed properties (`#[Computed]`)
- Livewire v4による reactive UI

🚀 **リアルタイム機能**
- Livewireによるリアルタイムな UI 更新（ページリロード不要）
- AJAX ベースの相互作用
- 自動バリデーション表示

💾 **タスク管理機能**
- タスクの作成・編集・削除
- タスク完了状態の切り替え
- タスク説明（詳細）対応
- 完了日時の自動記録

🎨 **UI/UX**
- Tailwind CSS を使用したモダンなデザイン
- レスポンシブデザイン対応
- 暗黒モード対応
- 直感的なインターフェース

🔍 **フィルタリング機能**
- すべてのタスク表示
- 未完了タスクのみ表示
- 完了済みタスクのみ表示
- リアルタイムカウント表示

📄 **ページネーション**
- 1ページあたり 10 件表示
- Livewire ページネーション統合

## 技術スタック

| 技術 | バージョン | 説明 |
|------|-----------|------|
| **PHP** | 8.2+ | プログラミング言語 |
| **Laravel** | 11.x | バックエンドフレームワーク |
| **Livewire** | 4.x | リアルタイム UI フレームワーク |
| **Tailwind CSS** | 最新 | CSS フレームワーク |
| **SQLite** | - | データベース |

## インストール手順

### 1. 依存関係のインストール

```bash
composer install
```

### 2. データベース初期化

```bash
php artisan migrate
```

### 3. 開発サーバー起動

```bash
php artisan serve
```

ブラウザで `http://localhost:8000` にアクセスしてください。

## 使用方法

### タスク追加
1. タイトルを入力
2. （オプション）説明を入力
3.「タスクを追加」ボタンをクリック

### タスク完了状態の変更
- チェックボックスをクリックしてタスクの状態を変更
- 完了時に自動的に完了日時が記録される

### タスク削除
- 右側の削除アイコン（ゴミ箱）をクリック
- 確認ダイアログで削除を確定

### フィルタリング
- 「すべて」「未完了」「完了済み」ボタンでフィルタリング
- タスク数がリアルタイムで更新される

## プロジェクト構造

```
app/
├── Livewire/
│   └── TodoList.php           # Livewireコンポーネント
├── Models/
│   └── Todo.php               # Todoモデル
│
database/
├── migrations/
│   └── create_todos_table.php  # Todoテーブルマイグレーション
│
resources/
├── views/
│   ├── home.blade.php         # ホームページ
│   ├── layouts/
│   │   └── app.blade.php      # メインレイアウト
│   └── livewire/
│       └── todo-list.blade.php # TODOリストコンポーネントビュー
│
routes/
└── web.php                    # ルート定義
```

## ファイル詳細

### app/Models/Todo.php
```php
class Todo extends Model
{
    protected $fillable = ['title', 'description', 'completed', 'completed_at'];
    protected $casts = ['completed' => 'boolean', 'completed_at' => 'datetime'];
    
    // スコープ: 未完了のタスク
    public function scopePending($query) { ... }
    
    // スコープ: 完了済みのタスク
    public function scopeCompleted($query) { ... }
}
```

**特徴:**
- Mass Assignment 対応
- 型キャスト設定（boolean と datetime）
- カスタムスコープで簡単にクエリを分岐

### app/Livewire/TodoList.php
```php
class TodoList extends Component
{
    use WithPagination;
    
    #[Validate('required|string|max:255')]
    public string $title = '';
    
    public function addTodo(): void { ... }
    public function toggleTodo(Todo $todo): void { ... }
    public function deleteTodo(Todo $todo): void { ... }
    public function setFilter(string $filter): void { ... }
    
    #[\Livewire\Attributes\Computed]
    public function todos() { ... }
}
```

**特徴:**
- 属性ベースのバリデーション (`#[Validate]`)
- Computed Properties によるリアクティブなデータ
- WithPagination トレイトを使用したページネーション
- Public プロパティで自動的にリアクティブ

### resources/views/livewire/todo-list.blade.php

**特徴:**
- Tailwind CSS によるモダンなデザイン
- 動的スタイル適用（@class ディレクティブ）
- wire:click によるリアルタイムイベントハンドリング
- wire:model による双方向バインディング
- wire:confirm による削除確認

## モダンなLaravelの実装ポイント

### 1. 属性ベースのバリデーション
```php
#[Validate('required|string|max:255')]
public string $title = '';
```

### 2. Computed Properties
```php
#[\Livewire\Attributes\Computed]
public function todos()
{
    // キャッシュされるため効率的
}
```

### 3. Livewire Wire ディレクティブ
```blade
wire:click="toggleTodo({{ $todo->id }})"
wire:model="title"
wire:submit="addTodo"
```

### 4. モダンなBladeディレクティブ
```blade
@class(['bg-blue-500' => condition, 'text-white' => true])
@forelse ($todos as $todo) ... @empty ... @endforelse
```

### 5. Eloquent スコープ
```php
$query->pending()    // 未完了のタスク
$query->completed()  // 完了済みのタスク
```

## トラブルシューティング

### Livewireが反応しない場合
```bash
# キャッシュをクリア
php artisan cache:clear
php artisan config:clear

# 再度ミグレーション実行
php artisan migrate:fresh
```

### ページネーションが表示されない場合
```bash
# Livewireのパブリッシュ
php artisan livewire:publish
```

## 拡張可能な機能案

- 🔐 ユーザー認証対応（タスク所有者ごとの管理）
- 📌 タスク優先度設定
- 🏷️ カテゴリー・タグ機能
- 📅 期限設定と期限超過アラート
- 🔔 通知機能
- 👥 タスク共有機能
- 📊 進捗ダッシュボード

## ライセンス

MIT

## サポート

問題が発生した場合は、GitHub Issues で報告してください。
