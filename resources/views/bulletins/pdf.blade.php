<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; }
        h1, h2, h3, p { margin: 0; }
        .wrap { padding: 24px; }
        .header { border-bottom: 1px solid #cbd5e1; padding-bottom: 16px; margin-bottom: 18px; }
        .grid { display: table; width: 100%; border-spacing: 8px; }
        .cell { display: table-cell; width: 25%; background: #f8fafc; padding: 12px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background: #f8fafc; }
        .summary { margin-top: 18px; padding: 12px; background: #0f172a; color: #fff; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="wrap">
        @include('bulletins._content', ['pdf' => true])
    </div>
</body>
</html>
