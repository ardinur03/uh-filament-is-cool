<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\RelationManagers\SalariesRelationManager;
use App\Filament\Resources\SalaryResource\Pages;
use App\Models\Salary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form->schema(self::getFormFields());
    }

    public static function getFormFields(): array
    {
        return [
            Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'name')
                ->hiddenOn(SalariesRelationManager::class)
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('amount')
                ->required()
                ->prefix('Rp')
                ->mask(RawJs::make('$money($input)')),
            Forms\Components\DatePicker::make('affected_date')
                ->native(false)
                ->default(now()->addMonth(6)->startOfMonth())
                ->required(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table->columns(self::getTableColums())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTableColums(): array
    {
        return [
            Tables\Columns\TextColumn::make('employee.name')
                ->numeric()
                ->hiddenOn(SalariesRelationManager::class)
                ->sortable(),
            Tables\Columns\TextColumn::make('amount')
                ->numeric()
                ->prefix('Rp ')
                ->sortable(),
            Tables\Columns\TextColumn::make('affected_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSalaries::route('/'),
        ];
    }
}
