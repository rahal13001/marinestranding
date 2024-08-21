<?php

namespace App\Filament\Kkprl\Resources;

use App\Filament\Kkprl\Resources\KkprluseResource\Pages;
use App\Filament\Kkprl\Resources\KkprluseResource\RelationManagers;
use App\Models\Kkprl\Kkprluse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Dotswan\MapPicker\Fields\Map;

class KkprluseResource extends Resource
{
    protected static ?string $model = Kkprluse::class;
    protected static ?string $navigationGroup = 'Peta Pemanfaatan Ruang Laut';

    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('province_id')
                    ->label('Provinsi')
                    ->relationship('province', 'province')
                    ->required(),
                
                Forms\Components\Select::make('shp_type')
                    ->label('Tipe KKPRL')
                    ->options([
                        "LineString" => "Garis",
                        "Polygon" => "Polygon",
                        "Point" => "Titik",
                    ])
                    ->required(),
                Forms\Components\TextInput::make('subject_activity')
                    ->label('Bentuk Kegiatan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('subject_name')
                    ->label('Subjek Hukum')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('subject_status')
                    ->label('Status KKPRL')
                    ->options([
                        "Persetujuan" => "Persetujuan",
                        "Konfirmasi" => "Konfirmasi",
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_terbit')
                    ->label('Tgl. Terbit')
                    ->required(),
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
                                ->label('Lokasi')
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
                Forms\Components\ColorPicker::make('color')
                    ->label('Warna')
                    ->required(),
                Forms\Components\FileUpload::make('subject_shp')
                    ->label('GeoJson File')
                    ->visibility('public')
                    ->maxSize(50024)
                    ->disk('public')
                    ->directory('peta_kkprl')
                    ->required(),

                Forms\Components\TextInput::make('width')
                    ->label('Luas')
                    ->suffix('Ha'),

                Forms\Components\TextInput::make('length')
                    ->label('Panjang')
                    ->suffix('Km'),

            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('province.province')
                    ->label('Provinsi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_name')
                    ->label('Subjek Hukum')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tanggal_terbit')
                    ->label('Tgl. Terbit')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject_activity')
                    ->label('Bentuk Kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_status')
                    ->label('Status KKPRL')
                    ->searchable(),
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
            ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKkprluses::route('/'),
            'create' => Pages\CreateKkprluse::route('/create'),
            'view' => Pages\ViewKkprluse::route('/{record}'),
            'edit' => Pages\EditKkprluse::route('/{record}/edit'),
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
            return "Pemanfaatan Ruang Laut";
        }
        else
        {
            return "Marine Spatial Use";
        }
    }
}
