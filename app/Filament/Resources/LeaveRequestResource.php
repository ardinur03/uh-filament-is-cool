<?php

namespace App\Filament\Resources;

use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use App\Filament\Resources\EmployeeResource\RelationManagers\LeaveRequestsRelationManager;
use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormFields());
    }

    public static function getFormFields(): array
    {
        return [
            Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'name')
                ->searchable()
                ->hiddenOn(LeaveRequestsRelationManager::class)
                ->preload()
                ->required(),
            Forms\Components\Fieldset::make('Start Ending')->columns(2)->schema([
                Forms\Components\DatePicker::make('start_date')
                    ->native(false)
                    ->placeholder('Leave Start Date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->native(false)
                    ->placeholder('Leave End Date')
                    ->required(),
            ]),
            Forms\Components\Fieldset::make('Leave Details')->columns(2)->schema([
                Forms\Components\Select::make('type')
                    ->native(false)
                    ->enum(LeaveRequestType::class)
                    ->options(LeaveRequestType::class)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->enum(LeaveRequestStatus::class)
                    ->native(false)
                    ->options(LeaveRequestStatus::class)
                    ->required(),
                Forms\Components\RichEditor::make('reason')
                    ->columnSpanFull(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColums())
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
                ->hiddenOn(LeaveRequestsRelationManager::class)
                ->sortable(),
            Tables\Columns\TextColumn::make('start_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('end_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('type')
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn ($state) => $state->getColor())
                ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
