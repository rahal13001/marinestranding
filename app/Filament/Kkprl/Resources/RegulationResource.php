<?php

namespace App\Filament\Kkprl\Resources;

use App\Filament\Kkprl\Resources\RegulationResource\Pages;
use App\Filament\Kkprl\Resources\RegulationResource\RelationManagers;
use App\Models\Kkprl\Regulation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegulationResource extends Resource
{
    protected static ?string $model = Regulation::class;

    protected static ?string $navigationIcon = 'heroicon-s-scale';
    protected static ?string $navigationGroup = 'Peta Tata Ruang Laut';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('regulation_number')
                    ->required()
                    ->label('Nomor Regulasi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('regulation_name')
                    ->required()
                    ->label('Nama Regulasi')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('regulation')
                    ->label('File Regulasi')
                    ->acceptedFileTypes(['application/pdf'])
                    ->visibility('public')
                    ->disk('public')
                    ->directory('regulations')
                    ->maxSize(20480)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('regulation_name')
                    ->searchable(),
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
            'index' => Pages\ListRegulations::route('/'),
            'create' => Pages\CreateRegulation::route('/create'),
            'view' => Pages\ViewRegulation::route('/{record}'),
            'edit' => Pages\EditRegulation::route('/{record}/edit'),
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
            return "Regulasi";
        }
        else
        {
            return "Regulation";
        }
    }
}
