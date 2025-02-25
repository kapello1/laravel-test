<?php
// BookingManager Livewire Component
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingManager extends Component
{
    public $property_id;
    public $start_date;
    public $end_date;

    public function createBooking()
    {
        $this->validate([
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $this->property_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        session()->flash('message', 'Réservation créée avec succès.');
        $this->reset(['property_id', 'start_date', 'end_date']);
    }

    public function render()
    {
        return view('livewire.booking-manager', [
            'properties' => \App\Models\Property::all(),
        ]);
    }
}

// Configuration de Filament
namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use App\Models\Property;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('price_per_night')->numeric()->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price_per_night'),
            ]);
    }
}
