<x-mail::message>
# Vehicle Ready for Handover

Dear Dealer,

Your vehicle purchase has been **confirmed and quality-checked**. Please review the details below and proceed with payment to complete the handover.

---

## Deal Summary

| Detail | Value |
|--------|-------|
| **Reference Code** | {{ $refCode }} |
| **Vehicle** | {{ $car->year ?? '' }} {{ $car->make ?? '' }} {{ $car->model ?? '' }} |
| **VIN** | {{ $car->vin ?? 'N/A' }} |
| **Purchase Amount** | ${{ $purchasePrice }} |

---

## Next Steps

1. **Make Payment** — Transfer the agreed amount to our account.
2. **Schedule Pickup** — Contact us to arrange vehicle delivery.
3. **Ownership Transfer** — Bring required documents for title transfer.

Please contact us within **48 hours** to arrange the final handover.

<x-mail::button :url="config('app.url')" color="success">
Contact Us to Arrange Handover
</x-mail::button>

---

*Reference: {{ $refCode }} | Motor Bazar Vehicle Trading*

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
