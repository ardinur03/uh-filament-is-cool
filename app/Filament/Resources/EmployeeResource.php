<?php

namespace App\Filament\Resources;

use App\Enums\EmployeeStatus;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Department;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->prefixIcon('heroicon-o-user')
                    ->placeholder('John Doe')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->prefixIcon('heroicon-o-envelope')
                    ->placeholder('email@domain.com')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Group::make([
                    Forms\Components\Select::make('department_id')
                        ->relationship('department', 'name')
                        ->options(Department::query()->whereActive(true)->get()->pluck('name', 'id'))
                        ->label('Department Name')
                        ->editOptionForm(DepartmentResource::getFormFields())
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('position_id')
                        ->preload()
                        ->editOptionForm(PositionResource::getFormFields())
                        ->createOptionForm(PositionResource::getFormFields())
                        ->searchable()
                        ->label('Position Name')
                        ->relationship('position', 'name')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->placeholder('Employee Status')
                        ->native(false)
                        ->enum(EmployeeStatus::class)
                        ->options(EmployeeStatus::class)
                        ->required(),
                ])->columns(3)->columnSpan(3),
                Forms\Components\DatePicker::make('joined')
                    ->placeholder('MM-DD-YYYY')
                    ->prefixIcon('heroicon-o-calendar')
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('position:id,name');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('joined', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('department.name')
                    ->description(fn ($record) => $record->position->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Employee $record) => $record->email)
                    ->searchable(),
                Tables\Columns\TextColumn::make('joined')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->getColor()),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options(EmployeeStatus::class),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\SalariesRelationManager::class,
            RelationManagers\LeaveRequestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
