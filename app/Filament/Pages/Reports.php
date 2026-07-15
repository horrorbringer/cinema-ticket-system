<?php

namespace App\Filament\Pages;

use App\Exports\BookingsExport;
use App\Exports\RevenueExport;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class Reports extends Page
{
    protected static ?string $navigationLabel = 'Reports';
    protected static ?string $title = 'Report Exports';
    protected static ?string $slug = 'reports';

    protected string $view = 'filament.pages.reports';

    public static function getNavigationIcon(): string | \BackedEnum | null
    {
        return 'heroicon-o-document-arrow-down';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Report Type')
                    ->options([
                        'bookings' => 'Bookings Export',
                        'revenue' => 'Revenue Export',
                    ])
                    ->required(),
                Select::make('status')
                    ->label('Filter by Status')
                    ->options([
                        '' => 'All Statuses',
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->visible(fn ($get) => $get('report_type') === 'bookings'),
                DatePicker::make('from')
                    ->label('From Date'),
                DatePicker::make('to')
                    ->label('To Date'),
            ]);
    }

    public function export(): void
    {
        $data = $this->form->getState();

        $export = match ($data['report_type']) {
            'revenue' => new RevenueExport(
                from: $data['from'],
                to: $data['to'],
            ),
            default => new BookingsExport(
                status: $data['status'] ?: null,
                from: $data['from'],
                to: $data['to'],
            ),
        };

        $fileName = match ($data['report_type']) {
            'revenue' => 'revenue-' . now()->format('Ymd-His') . '.xlsx',
            default => 'bookings-' . now()->format('Ymd-His') . '.xlsx',
        };

        $tempPath = 'exports/' . $fileName;
        Excel::store($export, $tempPath, 'local');

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Report exported as {$fileName}",
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_paid_bookings')
                ->label('Quick Export — Paid Bookings')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(
                    new BookingsExport(status: 'confirmed'),
                    'paid-bookings.xlsx'
                )),
            Action::make('download_30day_revenue')
                ->label('Quick Export — Last 30 Days Revenue')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(
                    new RevenueExport(from: now()->subDays(30)->toDateString()),
                    'revenue-30days.xlsx'
                )),
            Action::make('download_all_bookings')
                ->label('Quick Export — All Bookings')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(
                    new BookingsExport,
                    'all-bookings.xlsx'
                )),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }
}
