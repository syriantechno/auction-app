<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; margin: 40px; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #1e40af; margin: 0; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0; color: #666; font-size: 12px; }
        .section-title { background: #f3f4f6; padding: 8px 15px; border-left: 5px solid #2563eb; font-weight: bold; margin: 20px 0 10px 0; text-transform: uppercase; font-size: 14px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { padding: 10px; border: 1px solid #e5e7eb; text-align: left; font-size: 12px; }
        .details-table th { background: #fafafa; width: 30%; font-weight: 600; }
        .footer { margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px; font-size: 10px; color: #999; text-align: center; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-excellent { background: #dcfce7; color: #166534; }
        .badge-good { background: #dbeafe; color: #1e40af; }
        .badge-fair { background: #fef9c3; color: #854d0e; }
        .badge-poor { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Vehicle Inspection Certificate</h1>
        <p>AUTOMAZAD Premium Auctions - Quality Assurance Division</p>
        <p>Report ID: #INSP-{{ $car->id }}-{{ date('Ymd') }} | Date: {{ date('F d, Y') }}</p>
    </div>

    <div class="section-title">Vehicle Identification</div>
    <table class="details-table">
        <tr>
            <th>Make / Model</th>
            <td>{{ $car->make }} {{ $car->model }}</td>
            <th>Year</th>
            <td>{{ $car->year }}</td>
        </tr>
        <tr>
            <th>VIN</th>
            <td>{{ $car->vin ?: 'N/A' }}</td>
            <th>Mileage</th>
            <td>{{ number_format($car->mileage) }} KM</td>
        </tr>
        <tr>
            <th>Ownership Type</th>
            <td>{{ ucfirst($car->ownership_type) }}</td>
            <th>Current Status</th>
            <td>{{ ucfirst($car->status) }}</td>
        </tr>
    </table>

    <div class="section-title">Expert Technical Evaluation</div>
    <table class="details-table">
        <tr>
            <th>Engine Condition</th>
            <td>
                <span class="badge badge-{{ $car->inspection_data['engine_condition'] ?? 'fair' }}">
                    {{ ucfirst($car->inspection_data['engine_condition'] ?? 'N/A') }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Transmission</th>
            <td>
                <span class="badge badge-{{ $car->inspection_data['transmission_condition'] ?? 'fair' }}">
                    {{ ucfirst($car->inspection_data['transmission_condition'] ?? 'N/A') }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Mechanical Notes</th>
            <td>{{ $car->inspection_data['mechanical_notes'] ?? 'No significant notes recorded.' }}</td>
        </tr>
    </table>

    <div class="section-title">Certification & Approval</div>
    <p style="font-size: 12px;">
        I, the undersigned inspector, hereby certify that the above-mentioned vehicle has been thoroughly inspected 
        according to the AUTOMAZAD 150-point inspection protocol. The findings represent the true state of the vehicle 
        at the time of inspection.
    </p>
    <div style="margin-top: 40px; border-bottom: 1px solid #333; width: 250px;"></div>
    <p style="font-size: 10px; font-weight: bold;">Certified Official Inspector Signature</p>

    <div class="footer">
        Automazad Auctions - 123 Luxury Road, Dubai Marina, UAE<br>
        This document is electronically generated and is valid without a physical signature when verified online.
    </div>
</body>
</html>

