<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f6f9; color: #333; }
        .admin-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .topbar { background-color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .btn-logout { display: flex; align-items: center; gap: 6px; color: #ef4444; text-decoration: none; font-size: 0.9rem; padding: 6px 12px; border: 1px solid #ef4444; border-radius: 6px; }
        .content { padding: 30px; flex: 1; }
    </style>
</head>
<body>
<div class="admin-layout">