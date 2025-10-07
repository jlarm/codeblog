<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Post Details')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, ?string $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                    ->columnSpan(2),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(2),

                                Textarea::make('excerpt')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->columnSpan(1),

                                MarkdownEditor::make('content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'link',
                                        'heading',
                                        'bulletList',
                                        'orderedList',
                                        'codeBlock',
                                        'blockquote',
                                    ])
                                    ->helperText('Use markdown code fences with language: ```php\ncode here\n```'),
                            ]),
                        Section::make('Code Snippets')
                            ->description('Add code examples to your post')
                            ->schema([
                                Repeater::make('code_snippets')
                                    ->schema([
                                        TextInput::make('filename')
                                            ->label('Filename')
                                            ->placeholder('Example.php'),

                                        Select::make('language')
                                            ->label('Language')
                                            ->options([
                                                'php' => 'PHP',
                                                'javascript' => 'JavaScript',
                                                'typescript' => 'TypeScript',
                                                'python' => 'Python',
                                                'java' => 'Java',
                                                'csharp' => 'C#',
                                                'go' => 'Go',
                                                'rust' => 'Rust',
                                                'ruby' => 'Ruby',
                                                'html' => 'HTML',
                                                'css' => 'CSS',
                                                'sql' => 'SQL',
                                                'bash' => 'Bash',
                                                'json' => 'JSON',
                                                'yaml' => 'YAML',
                                                'markdown' => 'Markdown',
                                                'vue' => 'Vue',
                                            ])
                                            ->required()
                                            ->default('php')
                                            ->searchable(),

                                        Textarea::make('code')
                                            ->label('Code')
                                            ->required()
                                            ->rows(10)
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['filename'] ?? 'Code Snippet')
                                    ->addActionLabel('Add Code Snippet')
                                    ->reorderable()
                                    ->defaultItems(0),
                            ])
                            ->collapsible(),
                    ])->columnSpan(2),
                Group::make()
                    ->schema([
                        Section::make('Publishing')
                            ->schema([
                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug($state ?? ''))),

                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(table: 'categories', ignoreRecord: true),

                                        Textarea::make('description')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ])
                                    ->columnSpanFull(),

                                Toggle::make('is_published')
                                    ->label('Published'),

                                DateTimePicker::make('published_at')
                                    ->label('Publish Date'),
                            ])
                            ->columns(2),
                    ]),
            ])->columns(3);
    }
}
