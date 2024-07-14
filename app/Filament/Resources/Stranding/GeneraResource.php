<?php

namespace App\Filament\Resources\Stranding;

use App\Filament\Resources\Stranding\GeneraResource\Pages;
use App\Filament\Resources\Stranding\GeneraResource\RelationManagers;
use App\Models\Stranding\Family;
use App\Models\Stranding\Genera;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneraResource extends Resource
{
    protected static ?string $model = Genera::class;

    protected static ?string $navigationIcon = 'phosphor-fish-bold';
    protected static ?string $navigationGroup = 'Taksonomi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('family_id')
                    ->required()
                    ->label('Family')
                    ->relationship('family', 'family')
                    ->options(Family::all()->pluck('family', 'id')->toArray())
                    ->searchPrompt('Search for a family...')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('genera')
                    ->required()
                    ->label('Genus')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('family_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genera')
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
            'index' => Pages\ListGeneras::route('/'),
            'create' => Pages\CreateGenera::route('/create'),
            'view' => Pages\ViewGenera::route('/{record}'),
            'edit' => Pages\EditGenera::route('/{record}/edit'),
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
            return "Genus";
        }
        else
        {
            return "Genera";
        }
    }
}
