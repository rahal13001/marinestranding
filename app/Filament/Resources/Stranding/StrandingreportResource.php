<?php

namespace App\Filament\Resources\Stranding;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Stranding\Code;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\Stranding\Species;
use Dotswan\MapPicker\Fields\Map;
use App\Models\Stranding\Category;
use App\Models\Stranding\Province;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use App\Models\Stranding\Strandingreport;
use Dotswan\MapPicker\Infolists\MapEntry;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\VerticalAlignment;
use App\Models\Stranding\Group as SpeciesGroup;
use Illuminate\Contracts\View\View as ListView;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Group as ListGroup;
use Filament\Infolists\Components\Section as ListSection;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use App\Filament\Resources\Stranding\StrandingreportResource\Pages;
use App\Filament\Resources\Stranding\StrandingreportResource\RelationManagers;

class StrandingreportResource extends Resource
{
    
    protected static ?string $model = Strandingreport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $slug = 'laporan-biota-laut-terdampar';
    protected static ?string $title = 'Laporan Biota Laut Terdampar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Umum')
                    ->description('Informasi Umum Penanganan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->required()
                            ->label('Penyusun')
                            ->searchable()
                            ->preload()
                            ->searchPrompt('Cari penyusun...')
                            ->relationship('user', 'name'),
                        Forms\Components\Select::make('followers.user_id')
                            ->label('Pengikut')
                            ->searchable()
                            ->multiple()
                            ->preload()
                            ->searchPrompt('Cari pengikut...')
                            ->relationship('followers', 'name'),
                        Forms\Components\TextInput::make('informant_name')
                            ->label('Nama Pelapor')
                            ->placeholder('Nama pelapor kejadian')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('partner')
                            ->label('Mitra')
                            ->placeholder('Nama mitra yang terlibat')
                            ->maxLength(255),
                        Group::make()
                            ->relationship('map')
                            ->schema([
                                Forms\Components\DatePicker::make('information_date')
                                    ->label('Tanggal Informasi')
                                    ->required(),
                        ]),
                        Group::make()
                        ->relationship('map')
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                    ->label('Kejadian')
                                    ->required()
                                    ->options(Category::pluck('category', 'id')->toArray())
                                    ->searchable()
                                    ->searchPrompt('Cari kejadian...')
                                    ->preload(),
                        ])
                    ])->collapsible(),
              
               Section::make('Lokasi')
               ->description('Informasi Lokasi Kejadian')
               ->schema([
                    Group::make()
                        ->columns(2)
                        ->relationship('map')
                        ->schema([
                            Forms\Components\Select::make('province_id')
                                ->label('Provinsi')
                                ->required()
                                ->options(Province::pluck('province', 'id')->toArray())
                                ->searchable()
                                ->searchPrompt('Cari provinsi...')
                                ->preload(),
                            Forms\Components\TextInput::make('location')
                                ->required()
                                ->label('Lokasi')
                                ->placeholder('Nama lokasi kejadian')
                                ->maxLength(255),
            
                            Forms\Components\TextInput::make('latitude')
                                ->label('Latitude')
                                ->live()
                                ->numeric()
                                ->afterStateUpdated(function (Set $set, Get $get, ?float $state, $livewire): void {
                                    $longitude = $get('longitude');
                                    if ($state && $longitude) {
                                        $set('map_location', ['lat' => $state, 'lng' => $longitude]);
                                        $livewire->dispatch('refreshMap');
                                    }
                                }),
                    
                            Forms\Components\TextInput::make('longitude')
                                ->label('Longitude')
                                ->live()
                                ->numeric()
                                ->afterStateUpdated(function (Set $set, Get $get, ?float $state, $livewire): void {
                                    $latitude = $get('latitude');
                                    if ($latitude && $state) {
                                        $set('map_location', ['lat' => $latitude, 'lng' => $state]);
                                        $livewire->dispatch('refreshMap');
                                    }
                                }),

                                Map::make('map_location')
                                ->label('Location')
                                ->columnSpanFull()
                                ->default([
                                    'lat' => -0.8909726,
                                    'lng' => 131.3184784
                                ])
                                ->afterStateUpdated(function (Set $set, ?array $state): void {
                                    $set('latitude', $state['lat']);
                                    $set('longitude', $state['lng']);
                                })
                                ->afterStateHydrated(function ($record, Set $set, Get $get): void {
                                    // $set('location', ['lat' => $record->latitude, 'lng' => $record->longitude]);
                                    if ($record) {
                                        $latitude = $record->latitude;
                                        $longitude = $record->longitude;
                    
                                        if ($latitude && $longitude) {
                                                $set('map_location', ['lat' => $latitude, 'lng' => $longitude]);
                                        }
                                    }
                                })
                                ->extraStyles([
                                    'min-height: 50vh',
                                    'border-radius: 20px'
                                ])
                                ->liveLocation()
                                ->showMarker()
                                ->markerColor("#22c55eff")
                                ->showFullscreenControl()
                                ->showZoomControl()
                                ->draggable()
                                ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                                ->zoom(7)
                                ->detectRetina()
                                //not error just the copilot debuging doesn't have this method yet
                                ->showMyLocationButton()
                                ->extraTileControl([])
                                ->extraControl([
                                    'zoomDelta'           => 1,
                                    'zoomSnap'            => 2,
                                ]),
                    ]),
                   
                    
               ])->collapsible(),
               
              Section::make('Informasi Spesies')
                    ->description('Informasi Spesies yang Terdampar')
                    ->schema([
                        Group::make()
                            ->columns(2)
                            ->relationship('map')
                            ->schema([
                                Forms\Components\Select::make('group_id')
                                    ->label('Kelompok')
                                    ->required()
                                    ->options(SpeciesGroup::pluck('group_name', 'id')->toArray())
                                    ->required()
                                    ->searchable()
                                    ->searchPrompt('Cari kelompok...')
                                    ->preload(),
                                Forms\Components\Select::make('species_id')
                                    ->label('Spesies')
                                    ->required()
                                    ->options(Species::pluck('species', 'id')->toArray())
                                    ->searchable()
                                    ->searchPrompt('Cari spesies...')
                                    ->preload(),
                            ]),
                        Group::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('quantity_id')
                                    ->label('Kategori Berdasar Kuantitas')
                                    ->required()
                                    ->relationship('quantity', 'quantity')
                                    ->searchPrompt('Cari kategori...'),
                                Forms\Components\TextInput::make('count')
                                    ->required()
                                    ->label('Jumlah')
                                    ->required()
                                    ->placeholder('Jumlah biota terdampar')
                                    ->suffix('ekor')
                                    ->numeric(),
                            ])
                    ])->collapsible(),
                Section::make('Informasi Detail')
                    ->description('Informasi Detail Spesies yang Terdampar (Dapat diisi menyusul)')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Laporan')
                            ->placeholder('Laporan Penanganan Kejadian Biota Laut Terdampar ....')
                            ->maxLength(255),

                        Group::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\DatePicker::make('start_handling_date')
                                    ->label('Tanggal Mulai Penanganan'),
                                Forms\Components\DatePicker::make('end_handling_date')
                                    ->label('Tanggal Selesai Penanganan'),
                                
                            ]),
                        TinyEditor::make('report')
                            ->label('Detail Laporan'),
                        Group::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\FileUpload::make('documentation1')
                                    ->label('Dokumentasi 1')
                                    ->image()
                                    ->uploadingMessage('Mengunggah dokumentasi...')
                                    ->disk('public')
                                    ->directory('dokumentasi')
                                    ->openable()
                                    ->visibility('public')
                                    ->maxSize(2048),
                                Forms\Components\FileUpload::make('documentation2')
                                    ->label('Dokumentasi 2')
                                    ->image()
                                    ->uploadingMessage('Mengunggah dokumentasi...')
                                    ->disk('public')
                                    ->directory('dokumentasi')
                                    ->openable()
                                    ->visibility('public')
                                    ->maxSize(2048),
                                Forms\Components\FileUpload::make('documentation3')
                                    ->label('Dokumentasi 3')
                                    ->image()
                                    ->uploadingMessage('Mengunggah dokumentasi...')
                                    ->disk('public')
                                    ->directory('dokumentasi')
                                    ->openable()
                                    ->visibility('public')
                                    ->maxSize(2048),
                                Forms\Components\FileUpload::make('documentation4')
                                    ->label('Dokumentasi 4')
                                    ->image()
                                    ->uploadingMessage('Mengunggah dokumentasi...')
                                    ->disk('public')
                                    ->directory('dokumentasi')
                                    ->openable()
                                    ->visibility('public')
                                    ->maxSize(2048),
                                
                                Forms\Components\FileUpload::make('documentation5')
                                    ->label('Dokumentasi 5')
                                    ->image()
                                    ->uploadingMessage('Mengunggah dokumentasi...')
                                    ->disk('public')
                                    ->directory('dokumentasi')
                                    ->openable()
                                    ->visibility('public')
                                    ->maxSize(2048),

                                Forms\Components\FileUpload::make('st')
                                    ->label('Surat Tugas')
                                    ->nullable()
                                    ->uploadingMessage('Mengunggah dokumentasi...')
                                    ->disk('public')
                                    ->directory('st')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'image/*'])
                                    ->maxSize(5000)
                                    ->openable(),
            
                                Forms\Components\FileUpload::make('lainnya')
                                    ->label('Dokumentasi Lainnya')
                                    ->nullable()
                                    ->uploadingMessage('Mengunggah dokumentasi...')
                                    ->disk('public')
                                    ->directory('lainnya')
                                    ->visibility('public')
                                    ->openable()
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'image/*'])
                                    ->maxSize(10420)
                                    ->openable(),
                            ]),
                        
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('map.information_date')
                    ->date()
                    ->label('Tanggal Informasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penyusun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('map.category.category')
                    ->label('Kejadian')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('map.province.province')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('map.location')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('map.species.species')
                    ->label('Spesies')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('count')
                    ->numeric()
                    ->label('Jumlah')
                    ->suffix(' ekor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('informant_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kejadian')
                    ->relationship('map.category', 'category')
                    ->preload()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('province_id')
                    ->label('Provinsi')
                    ->relationship('map.province', 'province')
                    ->preload()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('species_id')
                    ->label('Spesies')
                    ->relationship('map.species', 'species')
                    ->preload()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('group_id')
                    ->label('Kelompok')
                    ->relationship('map.group', 'group_name')
                    ->preload()
                    ->searchable(),
            ])
            ->defaultSort('map.information_date', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
 
    public static function infolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->schema([
                ListSection::make()
                    ->heading('Informasi Umum')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Penyusun')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('followers.name')
                            ->label('Pengikut')
                            ->listWithLineBreaks()
                            ->weight(FontWeight::Bold)
                            ->bulleted(),
                        TextEntry::make('informant_name')
                            ->label('Nama Pelapor')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('partner')
                            ->label('Mitra')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('map.information_date')
                            ->label('Tanggal Informasi')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('map.category.category')
                            ->label('Kejadian')
                            ->weight(FontWeight::Bold)
                            ->color('primary'),
                    ])->collapsible(),

                ListSection::make()
                    ->heading('Lokasi')
                    ->schema([
                        ListGroup::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('map.province.province')
                                    ->label('Provinsi')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('map.location')
                                    ->label('Lokasi')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('map.latitude')
                                    ->label('Latitude')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('map.longitude')
                                    ->label('Longitude')
                                    ->weight(FontWeight::Bold),
                            ]),
                        MapEntry::make('location')
                            ->extraStyles([
                                'min-height: 50vh',
                                'border-radius: 50px'
                            ])
                            ->state(fn ($record) => ['lat' => $record?->map->latitude, 'lng' => $record?->map->longitude])
                            ->showMarker()
                            ->markerColor("#b91c1c")
                            ->showFullscreenControl()
                            ->draggable(false)
                            ->zoom(10)
                            //not error just the copilot debuging doesn't have this method yet
                            ->visible(fn ($record) => $record?->map->latitude && $record?->map->longitude),
                       
                    ])->collapsible(),

                ListSection::make()
                    ->heading('Informasi Spesies')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('map.group.group_name')
                            ->label('Kelompok')
                            ->color('primary')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('map.species.species')
                            ->label('Spesies')
                            ->color('primary')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('quantity.quantity')
                            ->label('Kategori Berdasar Kuantitas')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('count')
                            ->suffix(' ekor')
                            ->label('Jumlah')
                            ->weight(FontWeight::Bold),
                    ])->collapsible(),

                ListSection::make()
                    ->heading('Informasi Detail')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul Laporan')
                            ->visible(fn ($record) => $record->title)
                            ->weight(FontWeight::Bold),
                        ListGroup::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('start_handling_date')
                                    ->label('Tanggal Mulai Penanganan')
                                    ->visible(fn ($record) => $record->start_handling_date)
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('end_handling_date')
                                    ->label('Tanggal Selesai Penanganan')
                                    ->visible(fn ($record) => $record->end_handling_date)
                                    ->weight(FontWeight::Bold),
                            ]),
                        TextEntry::make('report')
                            ->label('Detail Laporan')
                            //listView for report, look the namespace if want to reuse it, because i use alias for this case
                                ->formatStateUsing(fn (string $state): ListView => view(
                                'infolists.components.report',
                                ['state' => $state],
                            ))
                            ->visible(fn ($record) => $record->report)
                            ->weight(FontWeight::Bold),
                        //listGroup for documentation, look the namespace if want to reuse it, because i use alias for this case
                        ListGroup::make()
                            ->columns(2)
                            ->schema([
                                ImageEntry::make('documentation1')
                                    ->label('Dokumentasi 1')
                                    ->url(asset( 'storage/'.$infolist->record->documentation1), '_blank')
                                    ->visible(fn ($record) => $record->documentation1),
                                ImageEntry::make('documentation2')
                                    ->label('Dokumentasi 2')
                                    ->url(asset( 'storage/'.$infolist->record->documentation2), '_blank')
                                    ->visible(fn ($record) => $record->documentation2),
                                ImageEntry::make('documentation3')
                                    ->label('Dokumentasi 3')
                                    ->url(asset( 'storage/'.$infolist->record->documentation3), '_blank')
                                    ->visible(fn ($record) => $record->documentation3),
                                ImageEntry::make('documentation4')
                                    ->label('Dokumentasi 4')
                                    ->url(asset( 'storage/'.$infolist->record->documentation4), '_blank')
                                    ->visible(fn ($record) => $record->documentation4),
                                ImageEntry::make('documentation5')
                                    ->label('Dokumentasi 5')
                                    ->url(asset( 'storage/'.$infolist->record->documentation5), '_blank')
                                    ->visible(fn ($record) => $record->documentation5),
                                IconEntry::make('st')
                                    ->label('Surat Tugas')
                                    ->icon('heroicon-o-eye')
                                    ->url(asset( 'storage/'.$infolist->record->st), '_blank')
                                    ->visible(fn ($record) => $record->st),
                                IconEntry::make('other')
                                    ->label('Dokumentasi Lainnya')
                                    ->icon('heroicon-o-eye')
                                    ->url(asset( 'storage/'.$infolist->record->st), '_blank')
                                    ->visible(fn ($record) => $record->other),
                            ]),
                    ])->collapsible(),
                    
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\IndividualdatasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStrandingreports::route('/'),
            'create' => Pages\CreateStrandingreport::route('/create'),
            'view' => Pages\ViewStrandingreport::route('/{record}'),
            'edit' => Pages\EditStrandingreport::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'id') {
            return "Laporan Keterdamparan";
        }
        else
        {
            return "Stranding Report";
        }
    }
}
