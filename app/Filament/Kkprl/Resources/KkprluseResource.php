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

class KkprluseResource extends Resource
{
    protected static ?string $model = Kkprluse::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Forms\Components\Select::make('subject_status')
                    ->label('Status KKPRL')
                    ->options([
                        "Persetujuan" => "Persetujuan",
                        "Konfirmasi" => "Konfirmasi",
                    ])
                    ->required(),
                Forms\Components\TextInput::make('latitude')
                    ->label('Latitude')
                    ->required(),
                Forms\Components\TextInput::make('longitude')
                    ->label('Longitude')
                    ->required(),
                Forms\Components\FileUpload::make('subject_shp')
                    ->label('GeoJson File')
                    ->visibility('public')
                    ->disk('public')
                    ->directory('peta_kkprl')
                    ->required(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('province_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shp_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_activity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_status')
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
