<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                /*TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),*/
            ])
            ->filters([
                Filter::make('verified')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->icon('heroicon-s-pencil')
                    ->color('info'),
                Action::make('Verify')
                    ->label('')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->action(function (User $user) {
                        $user->email_verified_at = now();
                        $user->save();
                    })
                    ->requiresConfirmation(),
                Action::make('Unverify')
                    ->label('')
                    ->icon('heroicon-s-stop-circle')
                    ->color('danger')
                    ->action(function (User $user) {
                        $user->email_verified_at = null;
                        $user->save();
                    })
                    ->requiresConfirmation()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('users')
                            ->fromTable()
                            ->ignoreFormatting(['email_verified_at']), // <-- clave
                    ]),
                ]),
            ]);
    }
}
