<?php

namespace App\Filament\Resources\Approvals\Pages;

use App\Filament\Resources\Approvals\ApprovalResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ViewApproval extends ViewRecord
{
    protected static string $resource = ApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('back')
                ->label('Back to List')
                ->tooltip('Back to list')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->url(static::getResource()::getUrl('index'))
                ->color('gray'),
            Action::make('approve')
                ->label('Approve')
                ->tooltip('Approve')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->requiresConfirmation()
                ->modalHeading('Approval Confirmation')
                ->modalDescription(fn($record) => "Are you sure you want to approve this document ({$record->document?->code})")
                ->modalSubmitAction(
                    fn($action) => $action
                        ->label('Approve')
                        ->icon(Heroicon::OutlinedCheckCircle)
                )
                ->modalCancelAction(
                    fn($action) => $action
                        ->label('Cancel')
                        ->icon(Heroicon::OutlinedXCircle)
                )
                ->action(function ($record) {
                    try {
                        $record->processApproval();
                        Notification::make()
                            ->title('Approval Success')
                            ->body("Approval processing for document {$record->document?->code} success")
                            ->success()
                            ->send();

                        $this->redirect($this->getResource()::getUrl('index'));
                    } catch (\Throwable $e) {
                        report($e);

                        Notification::make()
                            ->title('Approval Failed!')
                            ->body("Failed to processing your request right now, please contact your administrator, (Document No. : {$record->document?->code})")
                            ->danger()
                            ->persistent() // Trik v5: Notifikasi gak bakal ilang sampai user klik tombol 'X' (X close)
                            ->send();

                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->withProperties([
                                'document_id' => $record->document_id,
                                'document_code' => $record->document?->code,
                                'error_message' => $e->getMessage(),
                                'line' => $e->getLine(),
                                'file' => $e->getFile()
                            ])
                            ->log('failed_approval');
                    }
                })
                ->visible(fn() => $this->getRecord()->status === null),
            Action::make('reject')
                ->label('Reject')
                ->tooltip('Reject')
                ->icon(Heroicon::OutlinedXCircle)
                ->color(Color::Red)
                ->form([
                    Textarea::make('reason')
                        ->label('Reject reason')
                        ->required()
                        ->placeholder('Write the reason why you reject this document ...')
                        ->maxLength(255)
                ])
                ->modalSubmitAction(fn($action) => $action->label('Reject')->color('danger')->icon(Heroicon::OutlinedXCircle)->tooltip('Reject'))
                ->modalCancelAction(fn($action) => $action->label('Cancel')->tooltip('Cancel'))
                ->action(function (array $data) {
                    $record = $this->getRecord();

                    try {
                        $record->processReject($data['reason']);
                    } catch (\Throwable $e) {
                        report($e);
                        Notification::make()
                            ->title('Reject Process Failed!')
                            ->body("Failed to process reject request, please contact your administrator. (Doc: {$record->document?->code})")
                            ->danger()
                            ->persistent()
                            ->send();

                        activity()
                            ->causedBy(Auth::user())
                            ->performedOn($record)
                            ->withProperties([
                                'document_id' => $record->document_id,
                                'error_message' => $e->getMessage(),
                            ])
                            ->log('failed_rejection');
                    }
                })
                ->visible(fn() => $this->getRecord()->status === null)
        ];
    }
}
