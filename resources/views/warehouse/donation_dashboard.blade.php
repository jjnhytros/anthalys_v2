@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gestione Donazioni e Prodotti in Scadenza</h1>

        <!-- Sezione Prodotti in Scadenza -->
        <section>
            <h2>Prodotti in Scadenza (Entro 24 giorni)</h2>
            <ul>
                @forelse ($expiringProducts as $product)
                    <li>
                        Prodotto: {{ $product->product_type }} | Quantità: {{ $product->quantity }} | Scadenza:
                        {{ $product->expiry_date }}
                    </li>
                @empty
                    <li>Nessun prodotto in scadenza a breve.</li>
                @endforelse
            </ul>
        </section>

        <!-- Sezione Prodotti in Attesa di Donazione -->
        <section>
            <h2>Prodotti Pronti per la Donazione</h2>
            <ul>
                @forelse ($pendingDonations as $product)
                    <li>
                        Prodotto: {{ $product->product_type }} | Quantità: {{ $product->quantity }} | Scadenza:
                        {{ $product->expiry_date }}
                    </li>
                @empty
                    <li>Nessun prodotto in attesa di donazione.</li>
                @endforelse
            </ul>
        </section>

        <!-- Sezione Prodotti Donati -->
        <section>
            <h2>Prodotti Donati</h2>
            <ul>
                @forelse ($donatedProducts as $donation)
                    <li>
                        Prodotto: {{ $donation->product->product_type }} | Quantità: {{ $donation->product->quantity }} |
                        Donato il: {{ $donation->donation_date }}
                    </li>
                @empty
                    <li>Nessuna donazione registrata.</li>
                @endforelse
            </ul>
            {{ $donatedProducts->links() }}
        </section>
    </div>
@endsection
