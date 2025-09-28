<?php

namespace App\Admin\Resources\CustomPages;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Admin\Resources\CustomPages\Pages\ListCustomPages;
use App\Admin\Resources\CustomPages\Pages\CreateCustomPages;
use App\Admin\Resources\CustomPages\Pages\EditCustomPages;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\CustomPages\Page;
use BackedEnum;
use UnitEnum;

class CustomPagesResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Content Management';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-m-newspaper';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    })
                    ->placeholder('Fill in the title for your page'),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('URL-friendly slug for the page, e.g. about-us'),

                DateTimePicker::make('expires_at')
                    ->label('Expires at')
                    ->nullable()
                    ->placeholder('Optional expiration date')
                    ->helperText('If set, the page will become unavailable after this date.'),

                Select::make('visibility')
                    ->label('Visibility')
                    ->options([
                        'everyone' => 'Everyone',
                        'guests' => 'Only people who are not logged in',
                        'logged-in' => 'Everyone who is logged in',
                        'customers' => 'Customers only',
                        'admins' => 'Admins only',
                    ])
                    ->default('everyone')
                    ->required()
                    ->helperText('Choose who can see your page'),

                Toggle::make('is_active')
                    ->label('Published')
                    ->default(true)
                    ->helperText('Toggle if the page is enabled'),

                Toggle::make('visible_in_menu')
                    ->label('Visible in navigationbar')
                    ->helperText('Toggle if the page is shown in the navigationbar'),

                MarkdownEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull()
                    ->placeholder('Enter the content of the page (supports markdown)'),

                Section::make('Metadata')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->placeholder('Custom title for SEO and embeds'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->placeholder('Short description of the page'),

                        ColorPicker::make('meta_color')
                            ->label('Meta Color')
                            ->placeholder('#ff6600'),

                        FileUpload::make('meta_image')
                            ->label('Meta Image')
                            ->image()
                            ->directory('meta-images'),

                        FileUpload::make('meta_favicon')
                            ->label('Meta Favicon')
                            ->image()
                            ->directory('meta-favicons'),
                    ]),
                Section::make('Advanced')
                    ->columns(1)
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('htmlcontent')
                            ->label('HTML Content')
                            ->placeholder('Paste or write HTML content here. This will overwrite ALL above settings, except visibility and navbar'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('slug')->label('Slug')->sortable(),
                TextColumn::make('visibility')
                    ->label('Visibility')
                    ->sortable()
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                IconColumn::make('is_active')->label('Active')->boolean()->sortable(),
                IconColumn::make('visible_in_menu')->label('On navigationbar')->boolean()->sortable(),
                TextColumn::make('updated_at')->label('Last updated')->dateTime()->sortable(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->getStateUsing(fn(Page $record) => $record->views()->count())
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomPages::route('/'),
            'create' => CreateCustomPages::route('/create'),
            'edit' => EditCustomPages::route('/{record}/edit'),
        ];
    }
}
