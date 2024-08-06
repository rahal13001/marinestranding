<?php

namespace App\Filament\Kkprl\Resources;

use App\Filament\Kkprl\Resources\KkprlmapResource\Pages;
use App\Filament\Kkprl\Resources\KkprlmapResource\RelationManagers;
use App\Models\Kkprl\Kkprlmap;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KkprlmapResource extends Resource
{
    protected static ?string $model = Kkprlmap::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('province_id')
                    ->label('Provinsi')
                    ->relationship('province', 'province')
                    ->required(),
                Forms\Components\ColorPicker::make('color')
                    ->required(),
                Forms\Components\Select::make('zone_id')
                    ->label('Zona')
                    ->relationship('zone', 'zone_name')
                    ->required(),
                Forms\Components\Select::make('regulation_id')
                    ->label('Regulasi')
                    ->relationship('regulation', 'regulation_name'),
                Forms\Components\FileUpload::make('shp')
                    ->label('geoJSON File')
                    ->visibility('public')
                    ->disk('public')
                    ->acceptedFileTypes(['aplication/json'])
                    ->directory('peta_zonasi')
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
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zone_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('regulation_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListKkprlmaps::route('/'),
            'create' => Pages\CreateKkprlmap::route('/create'),
            'view' => Pages\ViewKkprlmap::route('/{record}'),
            'edit' => Pages\EditKkprlmap::route('/{record}/edit'),
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
            return "Peta KKPRL";
        }
        else
        {
            return "Peta KKPRL";
        }
    }
}
