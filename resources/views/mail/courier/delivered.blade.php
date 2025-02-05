<x-mail::message>
# Hello {{ $notifiable->name }}!

Le courrier {{ $courierUser->courier->courier_number }} est arrivé à destination.

<x-mail::panel>
## Détails courrier
- **Statut:** <span style="color: {{ $courierUser->status->getRank() === 5 ? 'green' : 'red' }}">{{ $courierUser->status->getLabel() }}</span>
- **Numéro courrier:** {{ $courierUser->courier->courier_number }}
- **Objet:** {{ $courierUser->courier->object }}
</x-mail::panel>

<x-mail::panel>
## Destinataire
- **Nom:** {{ $courierUser->recipient->name }}
- **Email:** {{ $courierUser->recipient->email }}
- **Téléphone:** {{ $courierUser->recipient->phone }}
- **Adresse:** {{ $courierUser->recipient->address }}
</x-mail::panel>

<x-mail::panel>
## Coursier
- **Nom:** {{ $courser->name }}
- **Email:** {{ $courser->email }}
- **Téléphone:** {{ $courser->phone }}
</x-mail::panel>

Cliquez sur le bouton **Voir courrier** ci-dessous pour y accéder rapidement!

<x-mail::button :url="$url">
Voir courrier
</x-mail::button>

Regards,<br>
{{ config('app.name') }}
</x-mail::message>
