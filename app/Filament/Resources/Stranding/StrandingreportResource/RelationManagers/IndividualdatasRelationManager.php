<?php

namespace App\Filament\Resources\Stranding\StrandingreportResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Stranding\Code;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class IndividualdatasRelationManager extends RelationManager
{
    protected static string $relationship = 'individualdatas';
    protected static ?string $title = 'Data Individu';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sample_code')
                    ->required()
                    ->label('Kode Sampel'),
                Forms\Components\Select::make('code_id')
                    ->required()
                    ->label('Kode')
                    ->options(Code::all()->mapWithKeys(function ($code) {
                        return [$code->id => "{$code->code} - {$code->code_mean}"];
                    })),
                Forms\Components\Select::make('gender')
                    ->options([
                        'Jantan' => 'Jantan',
                        'Betina' => 'Betina',
                        'Tidak Diketahui' => 'Tidak Diketahui',
                    ]),
                Forms\Components\TextInput::make('total_length')
                    ->required()
                    ->numeric()
                    ->suffix('cm'),
                Forms\Components\Select::make('method_id')
                    ->required()
                    ->relationship('method', 'method'),
                TinyEditor::make('ind_desc')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                FileUpload::make('sample_doc1')
                    ->label('Dokumentasi Sampel 1')
                    ->image()
                    ->maxSize(2048),
                FileUpload::make('sample_doc2')
                    ->label('Dokumentasi Sampel 2')
                    ->image()
                    ->maxSize(2048),
                FileUpload::make('sample_doc3')
                    ->label('Dokumentasi Sampel 3')
                    ->image()
                    ->maxSize(2048),
            ])
            ;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('strandingreport_id')
            ->columns([
                Tables\Columns\TextColumn::make('sample_code')
                    ->label('Kode Sampel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code.code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_length')
                    ->label('Panjang Total')
                    ->searchable()
                    ->suffix(' cm'),
                Tables\Columns\TextColumn::make('method.method')
                    ->label('Metode')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Tambah Data Individu')
                    ->label('Tambah Data Individu')
                    ->modalWidth('7x1')
                    ->closeModalByClickingAway(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Lihat Data Individu')
                    ->modalWidth('7x1')
                    ->closeModalByClickingAway(false),
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Data Individu')
                    ->modalWidth('7x1')
                    ->closeModalByClickingAway(false),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));

            
    }

        public function isReadOnly(): bool
        {
            return false;
        }
}
